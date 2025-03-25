<x-app-layout>
    <div class="w-full" x-data="productFilter()">
        <!-- Header -->
        <div class="main-color-bg p-4 md:p-8 md:py-12 text-center relative w-full z-0">
            <h1 class="text-white font-bold text-sm md:text-base">
                <a href="/" class="hidden sm:inline"><i class="fa fa-home"></i></a> 
                <i class="fa fa-chevron-right mx-1 sm:mx-2"></i>
                <span x-text="category.name" class="truncate max-w-[200px] inline-block"></span>
            </h1>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col md:flex-row p-4 md:p-6 md:py-12">
            <!-- Mobile Filter Toggle -->
            <button @click="mobileFiltersOpen = !mobileFiltersOpen" 
                    class="md:hidden flex items-center justify-center w-full bg-gray-200 p-3 mb-4 rounded-lg">
                <i class="fa fa-filter mr-2"></i> Filter
                <i class="fa ml-2" :class="mobileFiltersOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
            </button>

            <!-- Filter Sidebar -->
            <div class="w-full md:w-1/4 lg:w-1/6 bg-white p-4 md:p-6 rounded-lg shadow-md z-10 mb-4 md:mb-0"
                 :class="{'hidden md:block': !mobileFiltersOpen, 'block': mobileFiltersOpen}">
                <div class="mb-4 border-b border-solid border-gray-300">
                    <h3 class="text-lg md:text-xl font-semibold mb-4">Napredna pretraga</h3>
                </div>

                <!-- Price Filter -->
                <div class="mb-4 border-b border-solid border-gray-300 pb-2">
                    <button class="w-full text-left font-medium text-base md:text-lg mb-2 focus:outline-none flex justify-between items-center"
                        @click="priceOpen = !priceOpen">
                        <span>Cijena</span>
                        <i class="fa transition-transform duration-200" :class="priceOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
                    </button>
                    <div x-show="priceOpen" x-collapse class="px-2 md:px-4">
                        <input type="range" 
                            x-model="maxPrice" 
                            min="0" 
                            max="5000" 
                            step="10" 
                            class="w-full"
                            @change="fetchProducts()">
                        <div class="flex justify-between text-xs md:text-sm mt-2">
                            <span>0 KM</span>
                            <span x-text="maxPrice + ' KM'"></span>
                        </div>
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="mb-4 border-b border-solid border-gray-300 pb-2">
                    <button class="w-full text-left font-medium text-base md:text-lg mb-2 focus:outline-none flex justify-between items-center"
                        @click="brandOpen = !brandOpen">
                        <span>Brand</span>
                        <i class="fa transition-transform duration-200" :class="brandOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
                    </button>
                    <div x-show="brandOpen" x-collapse class="px-2 md:px-4">
                        <template x-for="brand in filterOptions.brands" :key="brand.id">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'brand_' + brand.id" 
                                    x-model="selectedBrands"
                                    :value="brand.id"
                                    @change="fetchProducts()"
                                    class="h-4 w-4">
                                <label :for="'brand_' + brand.id" class="ml-2 text-sm md:text-base" x-text="brand.name"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Processor Filter -->
                <div class="mb-4 border-b border-solid border-gray-300 pb-2">
                    <button class="w-full text-left font-medium text-base md:text-lg mb-2 focus:outline-none flex justify-between items-center"
                        @click="processorOpen = !processorOpen">
                        <span>Procesor</span>
                        <i class="fa transition-transform duration-200" :class="processorOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
                    </button>
                    <div x-show="processorOpen" x-collapse class="px-2 md:px-4">
                        <template x-for="processor in filterOptions.processors" :key="processor">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'processor_' + processor" 
                                    x-model="selectedProcessors"
                                    :value="processor"
                                    @change="fetchProducts()"
                                    class="h-4 w-4">
                                <label :for="'processor_' + processor" class="ml-2 text-sm md:text-base" x-text="processor"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- RAM Filter -->
                <div class="mb-4 border-b border-solid border-gray-300 pb-2">
                    <button class="w-full text-left font-medium text-base md:text-lg mb-2 focus:outline-none flex justify-between items-center"
                        @click="ramOpen = !ramOpen">
                        <span>RAM</span>
                        <i class="fa transition-transform duration-200" :class="ramOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
                    </button>
                    <div x-show="ramOpen" x-collapse class="px-2 md:px-4">
                        <template x-for="ram in filterOptions.ram_sizes" :key="ram">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'ram_' + ram" 
                                    x-model="selectedRam"
                                    :value="ram"
                                    @change="fetchProducts()"
                                    class="h-4 w-4">
                                <label :for="'ram_' + ram" class="ml-2 text-sm md:text-base" x-text="ram + ' GB'"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Storage Filter -->
                <div class="mb-4 border-b border-solid border-gray-300 pb-2">
                    <button class="w-full text-left font-medium text-base md:text-lg mb-2 focus:outline-none flex justify-between items-center"
                        @click="storageOpen = !storageOpen">
                        <span>HDD/SDD</span>
                        <i class="fa transition-transform duration-200" :class="storageOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
                    </button>
                    <div x-show="storageOpen" x-collapse class="px-2 md:px-4">
                        <template x-for="storage in filterOptions.storages" :key="storage">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'storage_' + storage" 
                                    x-model="selectedStorage"
                                    :value="storage"
                                    @change="fetchProducts()"
                                    class="h-4 w-4">
                                <label :for="'storage_' + storage" class="ml-2 text-sm md:text-base" x-text="storage + ' GB'"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Screen Size Filter -->
                <div class="mb-4 border-b border-solid border-gray-300 pb-2">
                    <button class="w-full text-left font-medium text-base md:text-lg mb-2 focus:outline-none flex justify-between items-center"
                        @click="screenSizeOpen = !screenSizeOpen">
                        <span>Veličina ekrana</span>
                        <i class="fa transition-transform duration-200" :class="screenSizeOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
                    </button>
                    <div x-show="screenSizeOpen" x-collapse class="px-2 md:px-4">
                        <template x-for="size in filterOptions.screen_sizes" :key="size">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'screen_size_' + size" 
                                    x-model="selectedScreenSizes"
                                    :value="size"
                                    @change="fetchProducts()"
                                    class="h-4 w-4">
                                <label :for="'screen_size_' + size" class="ml-2 text-sm md:text-base" x-text="size + '\"'"></label>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Graphics Card Filter -->
                <div class="mb-4 border-b border-solid border-gray-300 pb-2">
                    <button class="w-full text-left font-medium text-base md:text-lg mb-2 focus:outline-none flex justify-between items-center"
                        @click="graphicsOpen = !graphicsOpen">
                        <span>Grafička karta</span>
                        <i class="fa transition-transform duration-200" :class="graphicsOpen ? 'fa-angle-up' : 'fa-angle-down'"></i>
                    </button>
                    <div x-show="graphicsOpen" x-collapse class="px-2 md:px-4">
                        <template x-for="graphics in filterOptions.graphics_cards" :key="graphics">
                            <div class="flex items-center mb-2">
                                <input type="checkbox" 
                                    :id="'graphics_card_' + graphics" 
                                    x-model="selectedGraphics"
                                    :value="graphics"
                                    @change="fetchProducts()"
                                    class="h-4 w-4">
                                <label :for="'graphics_card_' + graphics" class="ml-2 text-sm md:text-base" x-text="graphics"></label>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="w-full md:w-3/4 lg:w-5/6 md:ml-4 lg:ml-6 z-10 py-4 md:py-12">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-6">
                    <template x-for="product in products.data" :key="product.id">
                        <div class="bg-white rounded-md shadow-sm md:shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            <div class="relative">
                                <img :src="product.image && JSON.parse(product.image).length > 0 ? '../storage/' + JSON.parse(product.image)[0] : '../storage/MbitShopLogo.png'" 
                                     :alt="product.name" 
                                     class="h-32 md:h-48 w-full object-contain p-2">
                                <div x-show="product.discount > 0" class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    <span x-text="product.discount + '%'"></span>
                                </div>
                            </div>
                            <div class="p-2 md:p-4">
                                <h2 class="text-gray-800 text-sm md:text-base font-medium truncate" x-text="product.name"></h2>
                                <p class="text-gray-600 text-xs md:text-sm" x-text="product.brand?.name"></p>
                                <div class="mt-2">
                                    <template x-if="product.discount > 0">
                                        <p class="text-gray-400 text-xs line-through" x-text="product.price + ' KM'"></p>
                                    </template>
                                    <p :class="{'text-red-500': product.discount > 0, 'text-gray-800': product.discount <= 0}" 
                                       class="text-sm md:text-base font-semibold"
                                       x-text="(product.discount > 0 ? (product.price - (product.price * product.discount / 100)).toFixed(2) : product.price) + ' KM'">
                                    </p>
                                </div>
                                <div class="flex mt-3">
                                    <a :href="'/product/' + (product.slug || '')" 
                                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-2 py-1 md:px-4 md:py-2 rounded-l-full text-xs md:text-sm transition-colors duration-200">
                                        Vidi
                                    </a>
                                    <button @click="addToCart(product.id)" 
                                            class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-2 py-1 md:px-4 md:py-2 rounded-r-full text-xs md:text-sm transition-colors duration-200">
                                        + <i class="fa fa-shopping-cart ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6 overflow-x-auto">
                    <div class="flex justify-center space-x-1 min-w-max">
                        <template x-for="link in products.links">
                            <a :href="link.url" 
                               class="px-3 py-1 md:px-4 md:py-2 text-sm rounded transition-colors duration-200"
                               :class="{
                                   'bg-blue-500 text-white': link.active,
                                   'bg-gray-200 hover:bg-gray-300': !link.active && link.url,
                                   'text-gray-400 cursor-not-allowed': !link.url
                               }"
                               x-html="link.label"
                               @click.prevent="link.url ? fetchProducts(link.url) : null"></a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function productFilter() {
            return {
                mobileFiltersOpen: false,
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
                priceOpen: true,
                brandOpen: false,
                processorOpen: false,
                ramOpen: false,
                storageOpen: false,
                screenSizeOpen: false,
                graphicsOpen: false,

                init() {
                    this.fetchCategoryAndFilters();
                    this.fetchProducts();
                    
                    // Close all accordions on mobile by default
                    if (window.innerWidth < 768) {
                        this.priceOpen = false;
                        this.brandOpen = false;
                        this.processorOpen = false;
                        this.ramOpen = false;
                        this.storageOpen = false;
                        this.screenSizeOpen = false;
                        this.graphicsOpen = false;
                    }
                },

                async fetchCategoryAndFilters() {
                    const slug = window.location.pathname.split('/').pop();
                    try {
                        const response = await fetch(`/api/products/filter/${slug}`);
                        const data = await response.json();
                        this.category = data.category?.name || '';
                        this.filterOptions = data.filter_options || {
                            brands: [],
                            processors: [],
                            ram_sizes: [],
                            storages: [],
                            screen_sizes: [],
                            graphics_cards: [],
                        };
                    } catch (error) {
                        console.error('Error fetching filters:', error);
                    }
                },

                async fetchProducts(url = null) {
                    const slug = window.location.pathname.split('/').pop();
                    const apiUrl = url || `/api/products/filter/${slug}?${this.buildQueryParams()}`;
                    
                    try {
                        const response = await fetch(apiUrl);
                        const data = await response.json();
                        this.products = {
                            data: data.products?.data || [],
                            links: data.products?.links || []
                        };
                    } catch (error) {
                        console.error('Error fetching products:', error);
                        toastr.error('Error loading products', 'Error');
                    }
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
                    if (!productId) return;

                    try {
                        const response = await fetch('/api/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ product_id: productId })
                        });

                        const responseData = await response.json();

                        if (response.ok) {
                            toastr.success(responseData.message || 'Dodano u košaricu', 'Success');
                        } else {
                            toastr.error(responseData?.message || 'Nepoznata greška', 'Error');
                        }
                    } catch (error) {
                        console.error('Error adding to cart:', error);
                        toastr.error('Error adding product to cart', 'Error');
                    }
                }
            };
        }
    </script>
</x-app-layout>

<x-footer></x-footer>