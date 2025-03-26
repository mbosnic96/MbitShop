<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function dasboardIndex()
    {
        return view('cart.index');
    }
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id'); 
        // Pronalazak proizvoda
        $product = Product::find($productId); 

        
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Proizvod nije pronađen!'], 404);
        }
      
       
    $price = $product->price_with_discount;
        // Učitavanje korpe iz sesije
        $cart = session()->get('cart', []);
        
        // Ako proizvod već postoji, povećaj količinu
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // Dodaj novi proizvod u korpu
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $price,
                'quantity' => 1,
                'image' => !empty(json_decode($product->image)) ? asset('storage/' . json_decode($product->image)[0]) : asset('storage/MbitShopLogo.png'),
                'slug' => $product->slug,
            ];
        }
        
        // Čuvanje korpe u sesiji
        session()->put('cart', $cart);
        
        // Vraćanje JSON odgovora sa statusom
        return response()->json([
            'status' => 'success',
            'message' => 'Proizvod dodat u korpu!',
            'cart' => $cart
        ]);
    }
    
    
    public function showCart()
{
    try {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = session()->get('cart', []);

        $cartCount = array_reduce($cart, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);

        return response()->json([
            'cart' => $cart,
            'user' => $user,
            'cartCount' => $cartCount, 
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }

}

public function update(Request $request, $productId)
{
    $quantity = $request->input('quantity');

    $product = Product::findOrFail($productId);
    if ($quantity > $product->stock_quantity) {
        return response()->json([
            'message' => 'Not enough stock available.',
            'available_stock' => $product->stock_quantity,
        ], 400);
    }

    
    $cart = session()->get('cart', []);
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $quantity;
        session()->put('cart', $cart);
    }

    return response()->json([
        'cart' => $cart,
    ]);
}


public function remove($productId)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$productId])) {
        unset($cart[$productId]);
    }

    session()->put('cart', $cart);

    return response()->json([
        'cart' => $cart,
    ]);
}
    }
