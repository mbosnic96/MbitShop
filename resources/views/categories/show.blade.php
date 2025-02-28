    <x-app-layout>
    <div class="w-full" x-data="productFilter()">
        <!-- Header -->
        <div class="main-color-bg p-8 py-12 text-center absolute w-full left-0 top-[height-of-nav] z-0">
            <h1 class="text-white font-bold relative">
                <a href="/"><i class="fa fa-home"></i></a> <i class="fa fa-chevron-right"></i>
                <span x-text="category.name"></span>
            </h1>
        </div>

        <!-- Main Content -->
        <div class="flex p-6 py-12">
            <!-- Filter Sidebar -->
            <div class="w-1/6 bg-white p-6 py-12 rounded-lg shadow-md z-10">
                <div class="mb-4 border-b border-solid border-gray-300">
                    <h3 class="text-xl font-semibold mb-4">Napredna pretraga</h3>
                </div>

                <!-- Price Filter -->
                <div class="mb-4 border-b border-solid border-gray-300">
                    <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                        @click="priceOpen = !priceOpen">
                        <span>Cijena</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="priceOpen" class="px-6">
                        <input type="range" 
                            x-model="maxPrice" 
                            min="0" 
                            max="5000" 
                            step="10" 
                            class="w-full"
                            @change="fetchProducts()">
                        <div class="flex justify-between text-sm mt-2">
                            <span>0 KM</span>
                            <span x-text="maxPrice + ' KM'"></span>
                        </div>
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="mb-4 border-b border-solid border-gray-300">
                    <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                        @click="brandOpen = !brandOpen">
                        <span>Brand</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="brandOpen" class="px-6">
                        <template x-for="brand in filterOptions.brands" :key="brand.id">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'brand_' + brand.id" 
                                    x-model="selectedBrands"
                                    :value="brand.id"
                                    @change="fetchProducts()">
                                <label :for="'brand_' + brand.id" class="ml-2" x-text="brand.name"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Processor Filter -->
                <div class="mb-4 border-b border-solid border-gray-300">
                    <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                        @click="processorOpen = !processorOpen">
                        <span>Procesor</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="processorOpen" class="px-6">
                        <template x-for="processor in filterOptions.processors" :key="processor">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'processor_' + processor" 
                                    x-model="selectedProcessors"
                                    :value="processor"
                                    @change="fetchProducts()">
                                <label :for="'processor_' + processor" class="ml-2" x-text="processor"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- RAM Filter -->
                <div class="mb-4 border-b border-solid border-gray-300">
                    <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                        @click="ramOpen = !ramOpen">
                        <span>RAM</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="ramOpen" class="px-6">
                        <template x-for="ram in filterOptions.ram_sizes" :key="ram">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'ram_' + ram" 
                                    x-model="selectedRam"
                                    :value="ram"
                                    @change="fetchProducts()">
                                <label :for="'ram_' + ram" class="ml-2" x-text="ram + ' GB'"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Storage Filter -->
                <div class="mb-4 border-b border-solid border-gray-300">
                    <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                        @click="storageOpen = !storageOpen">
                        <span>HDD/SDD</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="storageOpen" class="px-6">
                        <template x-for="storage in filterOptions.storages" :key="storage">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'storage_' + storage" 
                                    x-model="selectedStorage"
                                    :value="storage"
                                    @change="fetchProducts()">
                                <label :for="'storage_' + storage" class="ml-2" x-text="storage + ' GB'"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Screen Size Filter -->
                <div class="mb-4 border-b border-solid border-gray-300">
                    <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                        @click="screenSizeOpen = !screenSizeOpen">
                        <span>Veličina ekrana</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="screenSizeOpen" class="px-6">
                        <template x-for="size in filterOptions.screen_sizes" :key="size">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'screen_size_' + size" 
                                    x-model="selectedScreenSizes"
                                    :value="size"
                                    @change="fetchProducts()">
                                <label :for="'screen_size_' + size" class="ml-2" x-text="size + '\"'"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Graphics Card Filter -->
                <div class="mb-4 border-b border-solid border-gray-300">
                    <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                        @click="graphicsOpen = !graphicsOpen">
                        <span>Grafička karta</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="graphicsOpen" class="px-6">
                        <template x-for="graphics in filterOptions.graphics_cards" :key="graphics">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'graphics_card_' + graphics" 
                                    x-model="selectedGraphics"
                                    :value="graphics"
                                    @change="fetchProducts()">
                                <label :for="'graphics_card_' + graphics" class="ml-2" x-text="graphics"></label>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="w-5/6 ml-6 z-10 py-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    <template x-for="product in products.data" :key="product.id">
                        <div class="bg-white p-4 rounded-md shadow-md box">
                            <div>
                                <img :src="product.image" :alt="product.name" class="h-48 w-96 object-cover">
                                <div>
                                    <h2 class="text-gray-800" x-text="product.name"></h2>
                                    <p class="text-gray-600" x-text="product.brand?.name"></p>
                                    <p class="text-gray-600" x-text="product.model"></p>
                                    <div class="flex flex-col mb-0">
    <div class="flex justify-between items-center">
        <p class="text-gray-800 font-semibold" x-text="product.price + ' KM'"></p>
        <button @click="addToCart(product.id)" 
    class="flex items-center bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 focus:outline-none">
    <i class="fa fa-shopping-cart mr-2"></i>
    Kupi odmah!
</button>

    </div>
</div>

                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="mt-4">
                    <!-- Pagination -->
                    <template x-if="products.links">
                        <div class="flex justify-center">
                            <template x-for="link in products.links">
                                <a :href="link.url" 
                                   class="px-4 py-2 mx-1 rounded"
                                   :class="{ 'bg-blue-500 text-white': link.active }"
                                   x-html="link.label"
                                   @click.prevent="fetchProducts(link.url)"></a>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function productFilter() {
            return {
                category: {},
                filterOptions: {
                    brands: [],
                    processors: [],
                    ram_sizes: [],
                    storages: [],
                    screen_sizes: [],
                    graphics_cards: [],
                },
                products: {
                    data: [],
                    links: [],
                },
                selectedBrands: [],
                selectedProcessors: [],
                selectedRam: [],
                selectedStorage: [],
                selectedScreenSizes: [],
                selectedGraphics: [],
                maxPrice: 5000,
                priceOpen: false,
                brandOpen: false,
                processorOpen: false,
                ramOpen: false,
                storageOpen: false,
                screenSizeOpen: false,
                graphicsOpen: false,

                init() {
                    this.fetchCategoryAndFilters();
                    this.fetchProducts();
                },

                async fetchCategoryAndFilters() {
                    const slug = window.location.pathname.split('/').pop();
                    const response = await fetch(`/api/products/filter/${slug}`);
                    const data = await response.json();
                    this.category = data.category.name;
                    this.filterOptions = data.filter_options;
                },

                async fetchProducts(url = null) {
                    const slug = window.location.pathname.split('/').pop();
                    const apiUrl = url || `/api/products/filter/${slug}?${this.buildQueryParams()}`;
                    const response = await fetch(apiUrl);
                    const data = await response.json();
                    this.products = data.products;
                },

                buildQueryParams() {
                    const params = new URLSearchParams();
                    if (this.selectedBrands.length) params.append('selected_brands', this.selectedBrands.join(','));
                    if (this.selectedProcessors.length) params.append('selected_processors', this.selectedProcessors.join(','));
                    if (this.selectedRam.length) params.append('selected_ram', this.selectedRam.join(','));
                    if (this.selectedStorage.length) params.append('selected_storage', this.selectedStorage.join(','));
                    if (this.selectedScreenSizes.length) params.append('selected_screen_sizes', this.selectedScreenSizes.join(','));
                    if (this.selectedGraphics.length) params.append('selected_graphics', this.selectedGraphics.join(','));
                    params.append('max_price', this.maxPrice);
                    return params.toString();
                },

                async addToCart(productId) {
  

    const response = await fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    });

    const responseData = await response.json().catch(() => null);

   

    if (response.ok) {
        // Use Toastr for success notification
        toastr.success(responseData.message, 'Dodano u košaricu');
    } else {
        // Use Toastr for error notification
        toastr.error(responseData?.message || 'Nepoznata greška', 'Error');
    }
}



              
            };
        }
    </script>
</x-app-layout>