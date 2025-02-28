<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
class Cart extends Component
{
    public $cart = [];

    // Mount function to load cart items from session
    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    // Add item to cart
  

    // Remove item from cart
    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->cart = $cart;

         //   $this->dispatchBrowserEvent('cart-updated', ['message' => 'Product removed from cart']);
        }
    }

    // Update cart item quantity
    public function updateQuantity($productId, $quantity)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            // Update the quantity if valid
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $this->cart = $cart;

        //    $this->dispatchBrowserEvent('cart-updated', ['message' => 'Cart updated']);
        }
    }

    // Get the total amount of the cart
    public function getTotalAmount()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Render the cart view
    public function render()
    {
        return view('livewire.cart');
    }
}