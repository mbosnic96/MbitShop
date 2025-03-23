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
    public function index(Request $request)
    {
        $user = auth()->user();
    
        // Get the status filter from the request (e.g., ?status=na%20čekanju)
        $statusFilters = $request->query('status', []);
    
        // If status is a string (single value), convert it to an array
        if (!is_array($statusFilters)) {
            $statusFilters = [$statusFilters];
        }
    
        // Get the search term from the request (e.g., ?search=123)
        $searchTerm = $request->query('search', '');
    
        // Start building the query
        $ordersQuery = Order::with('items.product');
    
        // Apply status filter if any status is provided
        if (!empty($statusFilters)) {
            $ordersQuery->whereIn('status', $statusFilters);
        }
    
        // Apply search filter if a search term is provided
        if (!empty($searchTerm)) {
            $ordersQuery->where('order_number', 'like', '%' . $searchTerm . '%');
        }
    
        // If the user is not an admin, filter orders to only show their own
        if ($user->role !== 'admin') {
            $ordersQuery->where('user_id', $user->id);
        }
    
        // Sort orders by status, prioritizing 'na čekanju'
        $ordersQuery->orderByRaw("
            CASE
                WHEN status = 'na čekanju' THEN 1
                WHEN status = 'u obradi' THEN 2
                WHEN status = 'otkazano' THEN 3
                ELSE 4
            END
        ");
    
        // Paginate the results
        $orders = $ordersQuery->paginate(10);
    
        return response()->json([
            'data' => $orders->items(),
            'last_page' => $orders->lastPage(),
            'current_page' => $orders->currentPage(),
            'total' => $orders->total(),
        ]);
    }
    
    
    public function dasboardIndex()
    {
        return view('orders.index');

    }
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

            // Calculate shipping (free if subtotal > 100, else 12)
            $shipping = $subtotal > 99 ? 0 : 12;

            // Calculate total price
            $totalPrice = $subtotal + $shipping;

            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => strtoupper(uniqid('MBit-')),
                'subtotal' => $subtotal, // Add subtotal to the order
                'shipping' => $shipping, // Add shipping cost to the order
                'total_price' => $totalPrice, // Add total price to the order
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

            /**
             * bug here
             */
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderNotification($order));
            }

           
            return response()->json(['success' => true, 'message' => 'Order placed successfully!', 'order' => $order]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkStatus(Request $request, $orderId, $status)
    {
        $user = auth()->user();
        // Fetch the order
        $order = Order::findOrFail($orderId);

        // Validate the requested status change
        switch ($status) {
            case 'u obradi':
                if (in_array($order->status, ['otkazano', 'poslano'])) {
                    return response()->json(['error' => 'Narudžba otkazana ili poslana, nemoguće vratiti status.'], 400);
                }
                break;

            case 'poslano':
                if (in_array($order->status, ['otkazano', 'na čekanju', 'u obradi'])) {
                    return response()->json(['error' => 'Ne možete označiti kao poslano. Provjerite status narudžbe'], 400);
                }
                break;

            case 'otkazano':
                // Prevent canceling an order if its status is already 'poslano'
                if ($user->role === 'customer' && $order->status === 'poslano' || $order->status === 'otkazano') {
                    return response()->json(['error' => 'Ne možete otkazati narudžbu.'], 400);
                }
    
                break;

            default:
                return response()->json(['error' => 'Nevažeći status'], 400);
        }

        // If all checks pass, update the status
        $order->status = $status;
        $order->save();

        // Call the appropriate function based on status
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

    // Calculate subtotal, shipping, and total_price (do not save to database)
    $subtotal = $order->items->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    $shipping = $subtotal > 99 ? 0 : 12.00;
    $totalPrice = $subtotal + $shipping;

    // Pass the calculated values to the PDF view
    $pdf = PDF::loadView('orders.pdf', [
        'order' => $order,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total_price' => $totalPrice,
    ]);

    // Ensure the invoices directory exists
    $invoiceDirectory = storage_path('invoices');
    if (!file_exists($invoiceDirectory)) {
        mkdir($invoiceDirectory, 0755, true); // Create the directory with proper permissions
    }

    // Save the PDF
    $pdfPath = storage_path("invoices/order_{$order->order_number}.pdf");
    $pdf->save($pdfPath);

    // Send Email to User
    Mail::to($order->user->email)->send(new OrderApprovedMail($order, $pdfPath));

    return response()->json(['success' => true, 'message' => 'Order approved and invoice sent.']);
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

        // Return the generated PDF as a response
        return $pdf->download("order_{$orderId}.pdf");
    }

    public function cancelOrder(Order $order, $user)
    {
        
        // Handle admin cancellation
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['error' => 'Nemate dozvolu za otkazivanje ove narudžbe.'], 403);
        }
        $order->status = 'otkazano';
        $order->save();
    
            // Notify customer
            $order->user->notify(new OrderCancelledNotification($order));
    
            // Notify admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderCancelledNotification($order));
            }
    
            return response()->json(['message' => 'Narudžba je otkazana.']);
    
}
    

}
