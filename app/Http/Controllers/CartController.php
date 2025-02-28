<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id'); // Dobijanje ID-a iz JSON zahteva
        
        // Pronalazak proizvoda
        $product = Product::find($productId); // Koristimo find umesto findOrFail da bismo kontrolisali greške
        
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Proizvod nije pronađen!'], 404);
        }
        
        // Učitavanje korpe iz sesije
        $cart = session()->get('cart', []);
        
        // Ako proizvod već postoji, povećaj količinu
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // Dodaj novi proizvod u korpu
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
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
    $cart = session()->get('cart', []);
    $user = auth()->user(); // Get the logged-in user

    return response()->json([
        'cart' => $cart,
        'user' => $user,
    ]);
}

public function update(Request $request, $productId)
{
    $quantity = $request->input('quantity');

    // Fetch product from the database
    $product = Product::findOrFail($productId);

    // Check if the requested quantity is available
    if ($quantity > $product->stock_quantity) {
        return response()->json([
            'message' => 'Not enough stock available.',
            'available_stock' => $product->stock_quantity,
        ], 400);
    }

    // Update the quantity in the session cart (or your database if applicable)
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