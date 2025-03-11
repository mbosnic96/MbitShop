<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Mail\OrderApprovedMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Events\OrderCanceled;
use App\Events\OrderPlaced;


class OrderController extends Controller
{
    public function index(Request $request)
    {
    
      
        // Get the status filter from the request (e.g., ?status=na%20čekanju)
        $statusFilters = $request->query('status', []);
    
        // If status is a string (single value), convert it to an array
        if (!is_array($statusFilters)) {
            $statusFilters = [$statusFilters];
        }
    
        // Get the search term from the request (e.g., ?search=123)
        $searchTerm = $request->query('search', '');
    
        // Start building the query for all orders
        $ordersQuery = Order::with('items.product');
    
        // Apply status filter if any status is provided
        if (!empty($statusFilters)) {
            $ordersQuery->whereIn('status', $statusFilters);
        }
    
        // Apply search filter if a search term is provided
        if (!empty($searchTerm)) {
            $ordersQuery->where('order_number', 'like', '%' . $searchTerm . '%');
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
                'shipping_address' => "{$user->address}, {$user->city}, {$user->country}",
            ]);


            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            session()->forget('cart'); // Clear cart after checkout

            DB::commit();

            broadcast(new OrderPlaced($order));

            // Notify admin (via event, email, or dashboard alert)
            Notification::route('mail', config('mail.from.address'))->notify(new NewOrderNotification($order));

            return response()->json(['success' => true, 'message' => 'Order placed successfully!', 'order' => $order]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function myOrders(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    // Get the status filter from the request (e.g., ?status=na%20čekanju)
    $statusFilters = $request->query('status', []);

    // If status is a string (single value), convert it to an array
    if (!is_array($statusFilters)) {
        $statusFilters = [$statusFilters];
    }

    // Get the search term from the request (e.g., ?search=123)
    $searchTerm = $request->query('search', '');

    // Start building the query
    $ordersQuery = Order::where('user_id', $user->id)
        ->with('items.product');

    // Apply status filter if any status is provided
    if (!empty($statusFilters)) {
        $ordersQuery->whereIn('status', $statusFilters);
    }

    // Apply search filter if a search term is provided
    if (!empty($searchTerm)) {
        $ordersQuery->where('order_number', 'like', '%' . $searchTerm . '%');
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

    public function updateStatus(Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);
    $order->status = $request->status;
    $order->save();

    return response()->json(['success' => true, 'message' => 'Status narudžbe je ažuriran.']);
}


public function approveOrder($orderId)
{
    // Fetch the order with relationships
    $order = Order::with(['user', 'items.product'])->findOrFail($orderId);

    // Calculate subtotal, shipping, and total_price (do not save to database)
    $subtotal = $order->items->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });

    $shipping = $subtotal > 99 ? 0 : 12.00;
    $totalPrice = $subtotal + $shipping;

    // Update the order status (only save the status)
    $order->status = 'poslano';
    $order->save();

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

    public function cancelOrder($orderId)
    {
       $user = auth()->user();
        $userName = $user->name;
        $order = Order::with(['user', 'items.product'])->findOrFail($orderId);
     
        if ($user->role === 'admin') {
            $order->status = 'otkazano';
            $order->save();
            return response()->json(['message' => 'Narudžba je otkazana.']);
        }
    
        if ($user->role === 'customer' && $order->status !== 'poslano') {
            $order->status = 'otkazano';
            $order->save();
        }
        \Log::info('Broadcasting OrderCanceled Event', ['order_id' => $order->id]);
            // Emitovanje eventa kako bi admin dobio notifikaciju
            broadcast(new OrderCanceled('Hello from Laravel!'));
    
            return response()->json(['message' => 'Narudžba je otkazana.']);
     //   }
    
   //     return response()->json(['error' => 'Nemate dozvolu za otkazivanje ove narudžbe.'], 403);
    }


}
