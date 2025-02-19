<x-app-layout>
    <div class="main-color-bg p-8 py-12 text-center absolute w-full left-0 top-[height-of-nav] z-0">

        <h1 class="text-white font-bold relative">
            <a href="{{ route('home') }}"><i class="fa fa-home"></i></a> <i class="fa fa-chevron-right"></i>
            {{ $category->name }}

        </h1>
    </div>

    <div class="flex p-6 py-12">

        <!-- Filter Sidebar -->
        <div class="w-1/6 bg-white p-6 py-12 rounded-lg shadow-md z-10">
           
            <div class="mb-4  border-b border-solid border-gray-300"> 
                <h3 class="text-xl font-semibold mb-4">Napredna pretraga</h3>

      
    
</div>


            <!-- Brand Filter (Buttons) -->
            <input type="hidden" id="selected-category" data-filter="category" value="{{ $selectedCategoryId }}">

            
            <div class="mb-4 border-b border-solid border-gray-300">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                    data-section="price">
                    <span>Cijena</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div id="price-section" class="hidden px-6">
                    <input type="range" id="price-range" min="0" max="5000" step="10" class="w-full"
                        data-filter="price">
                    <div class="flex justify-between text-sm mt-2">
                        <span>0 KM</span>
                        <span id="price-value">5000 KM</span>
                    </div>
                </div>
            </div>

            <!-- Collapsible Brand Filter -->
            <div class="mb-4 border-b border-solid border-gray-300">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                    data-section="brand">
                    <span>Brand</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div id="brand-section" class="hidden px-6">
                    @foreach ($brands as $brand)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="brand_{{ $brand->id }}" class="filter-checkbox" data-filter="brand"
                                value="{{ $brand->id }}">
                            <label for="brand_{{ $brand->id }}" class="ml-2">{{ $brand->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible Processor Filter -->
            <div class="mb-4 border-b border-solid border-gray-300">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                    data-section="processor">
                    <span>Procesor</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div id="processor-section" class="hidden px-6">
                    @foreach ($processors as $processor)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="processor_{{ $processor }}" class="filter-checkbox"
                                data-filter="processor" value="{{ $processor }}">
                            <label for="processor_{{ $processor }}" class="ml-2">{{ $processor }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible RAM Filter -->
            <div class="mb-4 border-b border-solid border-gray-300">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                    data-section="ram">
                    <span>RAM</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div id="ram-section" class="hidden px-6">
                    @foreach ($ram_sizes as $ram_size)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="ram_{{ $ram_size }}" class="filter-checkbox" data-filter="ram"
                                value="{{ $ram_size }}">
                            <label for="ram_{{ $ram_size }}" class="ml-2">{{ $ram_size }} GB</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible HDD Filter -->
            <div class="mb-4 border-b border-solid border-gray-300">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                    data-section="hdd">
                    <span>HDD/SDD</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div id="hdd-section" class="hidden px-6">
                    @foreach ($storages as $storage)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="hdd_{{ $storage }}" class="filter-checkbox" data-filter="hdd"
                                value="{{ $storage }}">
                            <label for="hdd_{{ $storage }}" class="ml-2">{{ $storage }} GB</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4 border-b border-solid border-gray-300">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                    data-section="screen_size">
                    <span>Veličina ekrana</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div id="screen_size-section" class="hidden px-6">
                    @foreach ($screenSizes as $size)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="screen_size_{{ $size }}" class="filter-checkbox"
                                data-filter="screen_size" value="{{ $size }}">
                            <label for="screen_size_{{ $size }}" class="ml-2">{{ $size }}"</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible Graphics Card Filter -->
            <div class="mb-4 border-b border-solid border-gray-300">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none flex justify-between"
                    data-section="graphics_card">
                    <span>Grafička karta</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div id="graphics_card-section" class="hidden px-6">
                    @foreach ($graphics as $graphics_card)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="graphics_card_{{ $graphics_card }}" class="filter-checkbox"
                                data-filter="graphics_card" value="{{ $graphics_card }}">
                            <label for="graphics_card_{{ $graphics_card }}" class="ml-2">{{ $graphics_card }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible Price Filter -->

        </div>

        <!-- Product List -->
        <div id="product-list" class="w-5/6 ml-6 z-10 py-12">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                @foreach ($products as $product)
                                <div class="bg-white p-4 rounded-md shadow-md box">
                                    <div>
                                        @php
                                            $images = json_decode($product->image, true);
                                            $firstImage = !empty($images) && isset($images[0]) ? asset('storage/' . $images[0]) : asset('storage/MbitShopLogo.png');
                                        @endphp

                                        <img src="{{ $firstImage }}" alt="{{  $product->name  }}" class="h-48 w-96 object-cover">


                                        <div>
                                            <h2 class="text-gray-800">{{ $product->name }}</h2>
                                            @if($product->brand->name)
                                                <p class="text-gray-600"> {{ $product->brand->name ?? '' }}</p>
                                            @endif
                                            @if($product->model)
                                                <p class="text-gray-600">{{ $product->model ?? ''}}</p>
                                            @endif

                                            <div class="flex flex-col mb-0">
                                                <p class="text-gray-800 font-semibold">{{ $product->price ?? '' }} KM</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-center mt-4">
                                            <!-- <button class="bg-blue-500 text-white px-4 py-2 rounded">Vidi detaljno</button> -->
                                        </div>
                                    </div>
                                </div>
                @endforeach
            </div>
            <div class="mt-4">
        <!-- Pagination Links -->
        {{ $products->links() }}
    </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const filterButtons = document.querySelectorAll('.filter-checkbox');
            const filterDropdowns = document.querySelectorAll('.filter-dropdown');
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');


            let activeFilters = {};


            const sectionButtons = document.querySelectorAll('[data-section]');
            sectionButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const sectionId = this.getAttribute('data-section');
                    const section = document.getElementById(`${sectionId}-section`);
                    section.classList.toggle('hidden');
                });
            });

            filterButtons.forEach(button => {
                button.addEventListener('change', function () {
                    const filter = this.getAttribute('data-filter');
                    const value = this.value;

                    if (!activeFilters[filter]) {
                        activeFilters[filter] = [];
                    }

                    const index = activeFilters[filter].indexOf(value);
                    if (index === -1) {
                        activeFilters[filter].push(value);
                    } else {
                        activeFilters[filter].splice(index, 1);
                    }

                    fetchFilteredProducts();
                });
            });

            // Handle dropdown changes
            filterDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', fetchFilteredProducts);
            });

            priceRange.addEventListener('input', function () {
                priceValue.textContent = `${this.value} KM`;
                activeFilters["price"] = ["0", this.value]; 
                fetchFilteredProducts();
            });


            function getFilters() {
                const filters = {};
                const selectedCategory = document.getElementById('selected-category').value;
                document.querySelectorAll('.filter-checkbox:checked').forEach(input => {
                    const filterName = input.getAttribute('data-filter');
                    const filterValue = input.value;
                    if (!filters[filterName]) filters[filterName] = [];
                    filters[filterName].push(filterValue);
                });
                document.querySelectorAll('.filter-dropdown').forEach(input => {
                    const filterName = input.getAttribute('data-filter');
                    const filterValue = input.value;
                    if (filterValue) {
                        filters[filterName] = filterValue;
                    }
                });
               


                const price = document.getElementById('price-range').value;
                if (price) {
                    filters["price"] = ["0",price];
                }

                if (selectedCategory) {
                    filters['category'] = selectedCategory;
                }
                console.log("Filters before sending:", filters);

                return filters;
            }



            function fetchFilteredProducts() {
                const filters = getFilters();
                console.log("Active Filters:", filters); // Debugging
                fetch('/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(filters)
                })
                    .then(response => response.json())
                    .then(products => {
                        updateProductList(products);
                    })
                    .catch(error => console.error('Fetch Error:', error));
            }


            function updateProductList(products) {
                const productList = document.querySelector('.grid');
                productList.innerHTML = ''; // Clear existing products

                if (products.length === 0) {
                    productList.innerHTML = '<p class="col-span-full text-center text-gray-600">Nema artikala.</p>';
                    return;
                }

                products.forEach(product => {
                    let images = JSON.parse(product.image || '[]');
                    let firstImage = images.length ? `/storage/${images[0]}` : '/storage/MbitShopLogo.png';
                    let brandName = product.brand ? product.brand.name : ''; // Ensure brand name is handled properly
                    let model = product.model ? product.model : '';
                    let productHTML = `
                        <div class="bg-white p-4 rounded-md shadow-md box">
                        <div>
                            <img src="${firstImage}" alt="${product.name}" class="h-48 w-96 object-cover">

                           

                            <div>
                            <p class="text-gray-600">${brandName}</p>
                            <p class="text-gray-800">${product.name}</p>
                            <p class="text-gray-600">${model}</p>

                               
                            <div class="flex flex-col mb-0"> 
                                <p class="text-gray-800 font-semibold">${product.price || ''} KM</p>
                            </div>
                            </div>
                            <div class="flex items-center justify-center mt-4">
                             <!--   <button class="bg-blue-500 text-white px-4 py-2 rounded">Vidi detaljno</button> -->
                            </div>
                            </div>
                        </div>
                    `;

                    productList.insertAdjacentHTML('beforeend', productHTML);
                });
            }
        });
    </script>
</x-app-layout>