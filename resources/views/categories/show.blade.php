<x-app-layout>


    <div class="container mx-auto p-4 flex">
        <!-- Sidebar -->
        <div class="w-1/4 bg-gray-800 text-white p-4">
            <h3 class="text-xl font-semibold mb-4">Filter Products</h3>

            <!-- Hidden Category Filter (Preselected by URL) -->
            <input type="hidden" id="selected-category" value="{{ $selectedCategoryId }}">

            <!-- Collapsible Brand Filter -->
            <div class="mb-4">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none" onclick="toggleSection('brand')">
                    Brand ▼
                </button>
                <div id="brand-section" class="hidden">
                    @foreach ($brands as $brand)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="brand_{{ $brand->id }}" class="filter-checkbox" data-filter="brand" value="{{ $brand->id }}">
                            <label for="brand_{{ $brand->id }}" class="ml-2">{{ $brand->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible Processor Filter -->
            <div class="mb-4">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none" onclick="toggleSection('processor')">
                    Processor ▼
                </button>
                <div id="processor-section" class="hidden">
                    <select class="filter-dropdown w-full" data-filter="processor">
                        <option value="">Select Processor</option>
                        @foreach ($processors as $processor)
                            <option value="{{ $processor }}">{{ $processor }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Collapsible RAM Filter -->
            <div class="mb-4">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none" onclick="toggleSection('ram')">
                    RAM Size ▼
                </button>
                <div id="ram-section" class="hidden">
                    @foreach ($ram_sizes as $ram_size)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="ram_{{ $ram_size }}" class="filter-checkbox" data-filter="ram" value="{{ $ram_size }}">
                            <label for="ram_{{ $ram_size }}" class="ml-2">{{ $ram_size }} GB</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible HDD Filter -->
            <div class="mb-4">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none" onclick="toggleSection('hdd')">
                    HDD Size ▼
                </button>
                <div id="hdd-section" class="hidden">
                    @foreach ($storages as $storage)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="hdd_{{ $storage }}" class="filter-checkbox" data-filter="hdd" value="{{ $storage }}">
                            <label for="hdd_{{ $storage }}" class="ml-2">{{ $storage }} GB</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Collapsible Graphics Card Filter -->
            <div class="mb-4">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none" onclick="toggleSection('graphics_card')">
                    Graphics Card ▼
                </button>
                <div id="graphics_card-section" class="hidden">
                    <select class="filter-dropdown w-full" data-filter="graphics_card">
                        <option value="">Select GPU</option>
                        @foreach ($graphics as $graphics_card)
                            <option value="{{ $graphics_card }}">{{ $graphics_card }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Collapsible Price Filter -->
            <div class="mb-4">
                <button class="w-full text-left font-medium text-lg mb-2 focus:outline-none" onclick="toggleSection('price')">
                    Price ▼
                </button>
                <div id="price-section" class="hidden">
                    <input type="range" id="price-range" min="0" max="5000" step="10" class="w-full" data-filter="price">
                    <div class="flex justify-between text-sm mt-2">
                        <span>0 KM</span>
                        <span id="price-value">5000 KM</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="w-3/4 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="product-list">
                <!-- Display products from the selected category initially -->
                @foreach ($products as $product)
                    <div class="bg-white p-8 rounded-md shadow-md">
                        <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                        @php
                    $images = json_decode($product->image, true) ?? [];
                    $firstImage = !empty($images) ? '/storage/' . $images[0] : '/storage/MbitShopLogo.png';
                @endphp
                   <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="product-image">
                        <p class="text-gray-800 font-semibold">{{ $product->price }} KM</p>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4">View Details</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Toggle collapsible sections
        function toggleSection(sectionId) {
            const section = document.getElementById(`${sectionId}-section`);
            section.classList.toggle('hidden');
        }

        // Fetch filtered products
        document.addEventListener("DOMContentLoaded", function () {
            const filterInputs = document.querySelectorAll('.filter-checkbox, .filter-dropdown, #price-range');
            filterInputs.forEach(input => {
                input.addEventListener('change', fetchFilteredProducts);
            });

            // Price Range Value Display
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');
            priceRange.addEventListener('input', function () {
                priceValue.textContent = `${priceRange.value} KM`;
                fetchFilteredProducts();
            });

            function getFilters() {
                const filters = {};
                const selectedCategoryId = document.getElementById('selected-category').value;

                // Always include the selected category ID in the filters
                filters["category"] = selectedCategoryId;

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

                // Price Range
                filters["price"] = `0-${priceRange.value}`;

                return filters;
            }

            function fetchFilteredProducts() {
                const filters = getFilters();

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
                const productList = document.getElementById('product-list');
                productList.innerHTML = '';

                if (products.length === 0) {
                    productList.innerHTML = '<p class="col-span-full text-center text-gray-600">No products found.</p>';
                    return;
                }

                products.forEach(product => {
                    let images = JSON.parse(product.image || '[]');
                    let firstImage = images.length ? `/storage/${images[0]}` : '/storage/MbitShopLogo.png';

                    let productHTML = `
                        <div class="bg-white p-8 rounded-md shadow-md">
                            <h2 class="text-xl font-semibold mb-2">${product.name}</h2>
                            <img src="${firstImage}" alt="${product.name}" class="product-image">
                            <p class="text-gray-800 font-semibold">${product.price || ''} KM</p>
                            <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4">View Details</button>
                        </div>
                    `;
                    productList.insertAdjacentHTML('beforeend', productHTML);
                });
            }
        });
    </script>

</x-app-layout>
