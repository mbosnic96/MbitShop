<div class="min-h-screen flex flex-col">
    <div class="flex-grow p-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Your Cart</h2>
        <div class="flex">
            @if(count($cart) > 0)
                <!-- Cart Items (Left Side) -->
                <div class="w-3/4 bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul class="divide-y divide-gray-200 h-[70vh] overflow-y-auto">
                        @foreach($cart as $productId => $item)
                            <li class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                <div class="flex-1 flex items-start space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                      img
                                    </div>
                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item['name'] }}</h3>
                                        <p class="text-lg font-semibold text-gray-900 mt-2">${{ number_format($item['price'], 2) }}</p>
                                    </div>
                                </div>
                                <!-- Quantity and Actions -->
                                <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                                    <input 
                                        type="number" 
                                        wire:model="cart.{{ $productId }}.quantity" 
                                        wire:change="updateQuantity({{ $productId }}, $event.target.value)" 
                                        min="1" 
                                        value="{{ $item['quantity'] }}"
                                        class="w-20 px-3 py-2 border border-gray-300 rounded-md text-center focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                    <button 
                                        wire:click="removeFromCart({{ $productId }})"
                                        class="text-red-600 hover:text-red-900 transition duration-300"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Order Summary (Right Side) -->
                <div class="w-1/4 bg-white shadow sm:rounded-lg p-6 ml-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h3>
                        <div class="space-y-4">
                            <!-- Subtotal -->
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="text-gray-900 font-medium">${{ number_format($this->getTotalAmount(), 2) }}</span>
                            </div>

                            <!-- Shipping -->
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-gray-900 font-medium">
                                    @php
                                        $shipping = $this->getTotalAmount() > 100 ? 0 : 12;
                                    @endphp
                                    ${{ number_format($shipping, 2) }}
                                </span>
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between border-t pt-4">
                                <span class="text-xl font-semibold text-gray-900">Total</span>
                                <span class="text-xl font-semibold text-gray-900">
                                    ${{ number_format($this->getTotalAmount() + $shipping, 2) }}
                                </span>
                            </div>

                            <!-- Deliver To -->
                            <div class="border-t pt-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Deliver To:</h4>
                                <div class="text-gray-600">
                                    <p>{{ auth()->user()->name }}</p>
                                    <p>{{ auth()->user()->country }}, {{ auth()->user()->city }}, {{ auth()->user()->state }}</p>
                                    <p>{{ auth()->user()->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons at the Bottom of the w-1/4 Div -->
                    <div class="mt-6">
                        <button 
                            class="w-full px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition duration-300"
                            wire:click="checkout"
                        >
                            Proceed to Checkout
                        </button>
                        <button 
                            class="w-full mt-4 px-6 py-3 bg-white text-indigo-600 border border-indigo-600 font-semibold rounded-md hover:bg-indigo-50 transition duration-300"
                        >
                            Continue Shopping
                        </button>
                    </div>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="bg-white shadow sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500 text-lg">Your cart is empty.</p>
                    <a href="/" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-300">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>