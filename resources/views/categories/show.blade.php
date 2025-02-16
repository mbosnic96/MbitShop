<x-app-layout>

<div class="flex p-6">
 
<div class="w-1/4 bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-4">Filter</h3>

    <!-- Brand Filter (Checkboxes) -->
    <div class="mb-4">
        <h4 class="font-medium text-lg mb-2">Brand</h4>
        @foreach ($brands as $brand)
            <div class="flex items-center mb-2">
                <input type="checkbox" id="brand_{{ $brand->id }}" class="filter-checkbox" data-filter="brand" value="{{ $brand->id }}">
                <label for="brand_{{ $brand->id }}" class="ml-2">{{ $brand->name }}</label>
            </div>
        @endforeach         
    </div>
    <div class="mb-4">
                    <h4 class="font-medium text-lg mb-2">Category</h4>
                    <select class="filter-dropdown" data-filter="category">
                    <option value="">Select Kategoriju</option>
                        @foreach ($allCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
    <!-- Processor Filter (Checkboxes) -->
    <div class="mb-4">
        <h4 class="font-medium text-lg mb-2">Processor</h4>
        @foreach ($processors as $processor)
            <div class="flex items-center mb-2">
                <input type="checkbox" id="processor_{{ $processor }}" class="filter-checkbox" data-filter="processor" value="{{ $processor }}">
                <label for="processor_{{ $processor }}" class="ml-2">{{ $processor }}</label>
            </div>
        @endforeach
    </div>

    <!-- Screen Size Filter (Checkboxes) -->
    <div class="mb-4">
        <h4 class="font-medium text-lg mb-2">Screen Size</h4>
        <div class="flex items-center mb-2">
            <input type="checkbox" id="screen_size_13" class="filter-checkbox" data-filter="screen_size" value="13">
            <label for="screen_size_13" class="ml-2">13"</label>
        </div>
        <div class="flex items-center mb-2">
            <input type="checkbox" id="screen_size_15" class="filter-checkbox" data-filter="screen_size" value="15">
            <label for="screen_size_15" class="ml-2">15"</label>
        </div>
        <div class="flex items-center mb-2">
            <input type="checkbox" id="screen_size_17" class="filter-checkbox" data-filter="screen_size" value="17">
            <label for="screen_size_17" class="ml-2">17"</label>
        </div>
    </div>

    <!-- RAM Filter (Checkboxes) -->
    <div class="mb-4">
        <h4 class="font-medium text-lg mb-2">RAM Size</h4>
        @foreach ($ram_sizes as $ram_size)
            <div class="flex items-center mb-2">
                <input type="checkbox" id="ram_{{ $ram_size }}" class="filter-checkbox" data-filter="ram" value="{{ $ram_size }}">
                <label for="ram_{{ $ram_size }}" class="ml-2">{{ $ram_size }} GB</label>
            </div>
        @endforeach
    </div>

    <!-- Graphics Filter (Checkboxes) -->
    <div class="mb-4">
        <h4 class="font-medium text-lg mb-2">Graphics Card</h4>
        @foreach ($graphics as $graphics_card)
            <div class="flex items-center mb-2">
                <input type="checkbox" id="gpu_{{ $graphics_card }}" class="filter-checkbox" data-filter="graphics_card" value="{{ $graphics_card }}">
                <label for="gpu_{{ $graphics_card }}" class="ml-2">{{ $graphics_card }}</label>
            </div>
        @endforeach
    </div>
</div>

<div id="product-list" class="w-3/4 ml-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="product-list">
        @foreach ($products as $product)
            <div class="bg-white p-8 rounded-md shadow-md box">
                <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                @php
                    $images = json_decode($product->image, true);
                    $firstImage = !empty($images) ? asset('storage/' . $images[0]) : asset('storage/MbitShopLogo.png');
                @endphp

                <img src="{{ $firstImage }}" alt="{{  $product->name  }}" class="product-image">

                <div class="flex flex-col mb-0 text-animated"> 
                    <p class="text-gray-800 font-semibold">{{ $product->price ?? '' }} KM</p>
                </div>
                <div class="flex flex-col items-center space-x-2 box-hidden">
                    <div>
                        @if($product->brand && $product->brand->name)
                            <p class="text-gray-600">Brand: {{ $product->brand->name }}</p>
                        @endif

                        @if($product->model)
                            <p class="text-gray-600">Model: {{ $product->model }}</p>
                        @endif

                        @if($product->processor)
                            <p class="text-gray-600">Procesor (Model/GHz): {{ $product->processor }}</p>
                        @endif

                        @if($product->ram_size)
                            <p class="text-gray-600">RAM (GB): {{ $product->ram_size }}</p>
                        @endif

                        @if($product->storage)
                            <p class="text-gray-600">Memorija (GB): {{ $product->storage }}</p>
                        @endif

                        @if($product->graphics_card)
                            <p class="text-gray-600">Grafička kartica: {{ $product->graphics_card }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded">Vidi detaljno</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle Filter Changes
        const filterInputs = document.querySelectorAll('.filter-checkbox, .filter-dropdown');
        filterInputs.forEach(input => {
            input.addEventListener('change', fetchFilteredProducts);
        });

        // Function to collect all filter values
        function getFilters() {
            const filters = {};
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

            return filters;
        }

        // Fetch and update products dynamically
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
            const productList = document.querySelector('.grid'); // Adjust to target your grid container
            productList.innerHTML = ''; // Clear existing products

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

                        <div class="flex flex-col mb-0"> 
                            <p class="text-gray-800 font-semibold">${product.price || ''} KM</p>
                        </div>

                        <div>
                            ${(product.brand && product.brand.name) || product.model ? 
                                `<p class="text-gray-600">
                                    ${product.brand && product.brand.name ? product.brand.name : ''}
                                    ${product.brand && product.brand.name && product.model ? ' | ' : ''}
                                    ${product.model ? product.model : ''}
                                </p>` : ''}

                            ${product.ram_size || product.storage ? 
                                `<p class="text-gray-600">
                                    ${product.ram_size ? product.ram_size : ''}
                                    ${product.ram_size && product.storage ? ' | ' : ''}
                                    ${product.storage ? product.storage : ''}
                                </p>` : ''}

                        </div>
                        <div class="flex items-center justify-center mt-4">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded">Vidi detaljno</button>
                        </div>
                    </div>
                `;

                productList.insertAdjacentHTML('beforeend', productHTML);
            });
        }
    });
</script>

</x-app-layout>
