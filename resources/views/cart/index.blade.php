    <div class="min-h-screen flex flex-col" x-data="cartData">
        <div class="flex-grow p-6">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Košarica</h2>
            <div class="flex">
                <!-- Cart Items (Left Side) -->
                <div class="w-3/4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <ul class="divide-y divide-gray-200 h-[70vh] overflow-y-auto">
                        <template x-for="(item, productId) in cart" :key="productId">
                            <li class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                <div class="flex-1 flex items-start space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <img :src="item.image" alt="Product Image" class="w-16 h-16 object-cover">
                                    </div>
                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <a :href="'product/' + item.slug" class="block">
                                            <h3 class="text-lg font-medium text-gray-900" x-text="item.name"></h3>
                                            <p class="text-lg font-semibold text-gray-900 mt-2" x-text="'KM ' + item.price"></p>
                                        </a>
                                    </div>
                                </div>
                                <!-- Quantity and Actions -->
                                <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                                    <input 
                                        type="number" 
                                        x-model.number="item.quantity" 
                                        @change="updateQuantity(productId)"
                                        :max="item.stock_quantity"
                                        min="1" 
                                        class="w-20 px-3 py-2 border border-gray-300 rounded-md text-center focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                    <button 
                                        @click="removeFromCart(productId)"
                                        class="text-red-600 hover:text-red-900 transition duration-300"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Order Summary (Right Side) -->
                <div class="w-1/4 bg-white shadow-md sm:rounded-lg p-6 ml-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Detalji narudžbe</h3>
                        <div class="space-y-4">
                            <!-- Subtotal -->
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cijena</span>
                                <span class="text-gray-900 font-medium" x-text="'KM' + subtotal.toFixed(2)"></span>
                            </div>

                            <!-- Shipping -->
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dostava</span>
                                <span class="text-gray-900 font-medium" x-text="shippingText"></span>
                            </div>

                            <!-- Total -->
                            <div class="flex justify-between border-t pt-4">
                                <span class="text-xl font-semibold text-gray-900">Ukupno</span>
                                <span class="text-xl font-semibold text-gray-900" x-text="'KM' + total.toFixed(2)"></span>
                            </div>

                            <!-- Deliver To -->
                            <div class="border-t pt-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Dostava:</h4>
                                <div class="text-gray-600">
                                    <p x-text="user.name"></p>
                                    <p x-text="user.country"></p>
                                    <p x-text="user.city"></p>
                                    <p x-text="user.address"></p>
                                    <p x-text="user.phone_number"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons at the Bottom of the w-1/4 Div -->
                    <div class="mt-6">
                        <button 
                            @click="checkout"
                            :disabled="cartCount <= 0 || !isUserInfoValid"
                            :class="{ 'opacity-50 cursor-not-allowed': cartCount <= 0, 'hover:bg-indigo-700': cartCount > 0 }"
                            class="w-full px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md transition duration-300"
                        >
                            Završi narudžbu!
                        </button>
                        <a href="/" class="w-full mt-4 px-6 py-3 bg-white text-indigo-600 border border-indigo-600 font-semibold rounded-md hover:bg-indigo-50 transition duration-300 inline-block text-center">
                            Nastavi kupovinu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartData', () => ({
                cart: [],
                shipping: 12.00,
                user: {},
                cartCount: 0,

                async init() {
                    await this.fetchCart();
                },

                async fetchCart() {
                    try {
                        const response = await fetch(`/api/cart`, {
                            method: 'GET',
                            headers: { 'Content-Type': 'application/json' },
                            credentials: 'include'
                        });

                        if (!response.ok) throw new Error('Failed to fetch cart data');

                        const data = await response.json();
                        this.cart = data.cart;
                        this.user = data.user;
                        this.cartCount = Object.values(this.cart).reduce((acc, item) => acc + item.quantity, 0);
                    } catch (error) {
                        console.error('Error loading cart:', error);
                    }
                },

                get subtotal() {
                    return Object.values(this.cart).reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                get total() {
                    return this.subtotal + (this.subtotal > 99 ? 0 : this.shipping);
                },

                get shippingText() {
                    return this.subtotal > 99 ? "Besplatno" : "KM" + this.shipping.toFixed(2);
                },

                async updateQuantity(productId) {
    const product = this.cart[productId];
    if (!product) return;

    // Klijentska validacija
    if (product.quantity > product.stock_quantity) {
        product.quantity = product.stock_quantity;
        toastr.warning(`Na stanju je samo ${product.stock_quantity} komada.`);
        return;
    }

    const response = await fetch(`/api/cart/update/${productId}`, {
        method: 'PUT',
        body: JSON.stringify({ quantity: product.quantity }),
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });

    const data = await response.json(); 

    if (response.ok) {
        this.cart = data.cart;
        this.cartCount = Object.values(this.cart).reduce((acc, item) => acc + item.quantity, 0);
    } else {
        if (data.message && data.available_stock !== undefined) {
            product.quantity = data.available_stock; 
            toastr.warning(`${data.message} Dostupno: ${data.available_stock} kom.`);
        } else {
            toastr.error('Greška pri ažuriranju količine.');
        }
    }
},


async removeFromCart(productId) {
    const result = await Swal.fire({
        title: 'Jeste li sigurni?',
        text: 'Ovim brišete artikl iz košarice.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Da, obriši!',
        cancelButtonText: 'Cancel',
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(`/api/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) throw new Error('Greška pri brisanju');

            const data = await response.json();
            this.cart = data.cart;
            this.cartCount = data.cartCount;

            Swal.fire({
                title: 'Obrisano!',
                text: 'Artikl obrisan iz košarice.',
                icon: 'success',
                confirmButtonText: 'OK',
            });
        } catch (error) {
            console.error('Error removing item:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Greška pri brisanju, pokušajte ponovo!',
                icon: 'error',
                confirmButtonText: 'OK',
            });
        }
    }
},
get isUserInfoValid() {
    return this.user.name && this.user.country && this.user.city && this.user.address && this.user.phone_number;
},

                async checkout() {
                    const result = await Swal.fire({
                        title: 'Jeste li sigurni?',
                        text: 'Želite završiti narudžbu? Provjerite podatke za dostavu!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Da, završi narudžbu!',
                        cancelButtonText: 'Odustani'
                    });

                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/api/checkout`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                credentials: 'include'
                            });

                            const data = await response.json();
                            if (response.ok) {
                                Swal.fire('Narudžba potvrđena!', 'Uspješno ste završili narudžbu.', 'success');
                                this.cart = [];
                                this.cartCount = 0;
                            } else {
                                Swal.fire('Greška!', data.error, 'error');
                            }
                        } catch (error) {
                            Swal.fire('Greška!', 'Došlo je do problema pri obradi narudžbe.', 'error');
                        }
                    }
                }
            }));
        });
    </script>