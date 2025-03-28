<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\OrderCancelledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Notifications\OrderNotification;
use App\Mail\OrderApprovedMail;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    //return orders for user or all for admin
    public function index(Request $request)
    {
        $user = auth()->user();
        //filter i search
        $statusFilters = $request->query('status', []);
    
        if (!is_array($statusFilters)) {
            $statusFilters = [$statusFilters];
        }
    
        $searchTerm = $request->query('search', '');
        $ordersQuery = Order::with('items.product');
        if (!empty($statusFilters)) {
            $ordersQuery->whereIn('status', $statusFilters);
        }
    
        if (!empty($searchTerm)) {
            $ordersQuery->where('order_number', 'like', '%' . $searchTerm . '%');
        }
    
        
        if ($user->role !== 'admin') {
            $ordersQuery->where('user_id', $user->id);
        }
    
        //raw query i sort
        $ordersQuery->orderByRaw("
        CASE
            WHEN status = 'na čekanju' THEN 1
            WHEN status = 'u obradi' THEN 2
            WHEN status = 'poslano' THEN 3
            ELSE 4
        END
    ")->orderBy('id', 'desc');
    
    
        $orders = $ordersQuery->paginate(10);
    
        return response()->json([
            'data' => $orders->items(),
            'last_page' => $orders->lastPage(),
            'current_page' => $orders->currentPage(),
            'total' => $orders->total(),
        ]);
    }
    
    //vraća view
    public function dasboardIndex()
    {
        return view('orders.index');

    }
    //checkout narudzbi
    public function checkout(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        DB::beginTransaction();
        try {
            $subtotal = array_reduce($cart, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);

            // Calculate shipping
            $shipping = $subtotal > 99 ? 0 : 12;

            // Calculate total price
            $totalPrice = $subtotal + $shipping;

            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => strtoupper(uniqid('MBit-')),
                'subtotal' => $subtotal, 
                'shipping' => $shipping, 
                'total_price' => $totalPrice, 
                'shipping_address' => "{$user->name},{$user->phone_number}, {$user->address}, {$user->city}, {$user->country}",
            ]);


            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            
                $product = Product::find($productId);
            
                if (!$product) {
                    throw new \Exception("Proizvod sa ID {$productId} ne postoji.");
                }
            
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Nema dovoljno zaliha za proizvod: {$product->name}");
                }
            
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }
            

            session()->forget('cart'); // Clear cart after checkout

            DB::commit();

            //notifikacija adminu da ima narudžbu i mail
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderNotification($order));
            }

           
            return response()->json(['success' => true, 'message' => 'Uspješno naručeno!', 'order' => $order]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //provjera statusa, poziva funkcije za obradu i postavlja zastite
    public function checkStatus(Request $request, $orderId, $status)
    {
        $user = auth()->user();
        $order = Order::findOrFail($orderId);
        switch ($status) {
            case 'u obradi':
                if (in_array($order->status, ['otkazano', 'poslano'])) {
                    return response()->json(['error' => 'Narudžba otkazana ili poslana, nemoguće vratiti status.'], 400);
                }
                break;

            case 'poslano':
                if (in_array($order->status, ['otkazano', 'na čekanju'])) {
                    return response()->json(['error' => 'Ne možete označiti kao poslano. Provjerite status narudžbe'], 400);
                }
                break;

            case 'otkazano':
                if ($user->role === 'customer' && $order->status === 'poslano' || $order->status === 'otkazano') {
                    return response()->json(['error' => 'Ne možete otkazati narudžbu.'], 400);
                }
    
                break;

            default:
                return response()->json(['error' => 'Nevažeći status'], 400);
        }

        $order->status = $status;
        $order->save();

        // poziva funkciju zavisno od statusa
        switch ($status) {
            case 'u obradi':
                return $this->inProgress($order);
            case 'poslano':
                return $this->approveOrder($order);
            case 'otkazano':
                return $this->cancelOrder($order, $user);
            default:
                return response()->json(['error' => 'Invalid status'], 400);
        }
    }

  
    public function inProgress(Order $order)
{
    return response()->json(['success' => true, 'message' => 'Narudžba je u obradi.']);
}


public function approveOrder(Order $order)
{
    $subtotal = $order->items->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    $shipping = $subtotal > 99 ? 0 : 12.00;
    $totalPrice = $subtotal + $shipping;

    // šalje podatke pdfu
    $pdf = PDF::loadView('orders.pdf', [
        'order' => $order,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total_price' => $totalPrice,
    ]);

    $invoiceDirectory = storage_path('invoices');
    if (!file_exists($invoiceDirectory)) {
        mkdir($invoiceDirectory, 0755, true); // Create the directory with proper permissions
    }

    // Save PDF
    $pdfPath = storage_path("invoices/order_{$order->order_number}.pdf");
    $pdf->save($pdfPath);
    Mail::to($order->user->email)->send(new OrderApprovedMail($order, $pdfPath));

    return response()->json(['success' => true, 'message' => 'Narudžba odobrena!']);
}
    public function downloadPDF($orderId)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($orderId);
        // Calculate subtotal
        $subtotal = $order->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    
        $shipping = $subtotal > 99 ? 0 : 12.00;
        $totalPrice = $subtotal + $shipping;
    

        $pdf = PDF::loadView('orders.pdf', [
            'order' => $order,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total_price' => $totalPrice,
        ]);

        return $pdf->download("order_{$orderId}.pdf");
    }

    public function cancelOrder(Order $order, $user)
    {
        
        // admin može otkazati naružbu iako je poslana
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['error' => 'Nemate dozvolu za otkazivanje ove narudžbe.'], 403);
        }
        $order->status = 'otkazano';
        $order->save();
    
            $order->user->notify(new OrderCancelledNotification($order));
    
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderCancelledNotification($order));
            }
    
            return response()->json(['message' => 'Narudžba je otkazana.']);
    
}

public function getOrderStats()
{
    // koristi se u dashbordu za početni panel
    $currentMonthOrders = Order::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->get();
    
    $currentMonthCount = $currentMonthOrders->count();
    $currentMonthRevenue = $currentMonthOrders->sum('total_price');

    
    $previousMonthOrders = Order::whereYear('created_at', now()->subMonth()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->get();
        
    $previousMonthCount = $previousMonthOrders->count();
    $previousMonthRevenue = $previousMonthOrders->sum('total_price');

    
    $countPercentageChange = $this->calculatePercentageChange($previousMonthCount, $currentMonthCount);
    $revenuePercentageChange = $this->calculatePercentageChange($previousMonthRevenue, $currentMonthRevenue);

    return response()->json([
        'current_month' => [
            'count' => $currentMonthCount,
            'revenue' => $currentMonthRevenue
        ],
        'previous_month' => [
            'count' => $previousMonthCount,
            'revenue' => $previousMonthRevenue
        ],
        'percentage_changes' => [
            'count' => $countPercentageChange,
            'revenue' => $revenuePercentageChange
        ]
    ]);
}

private function calculatePercentageChange($previous, $current)
{
    if ($previous > 0) {
        return round((($current - $previous) / $previous) * 100, 2);
    } elseif ($current > 0) {
        return 100;
    }
    return 0;
}
    

}
