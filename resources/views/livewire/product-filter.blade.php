<div class="w-full" x-data="{ priceOpen: true, brandOpen: false, processorOpen: false, ramOpen: false, storageOpen: false, screenSizeOpen: false, graphicsOpen: false }">
    <div class="main-color-bg p-8 py-12 text-center absolute w-full left-0 top-[height-of-nav] z-0">
        <h1 class="text-white font-bold relative">
            <a href="{{ route('home') }}"><i class="fa fa-home"></i></a> <i class="fa fa-chevron-right"></i>
            {{ $category->name }}
        </h1>
    </div>

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
                        wire:model.live.debounce.500ms="maxPrice" 
                        min="0" 
                        max="5000" 
                        step="10" 
                        class="w-full">
                    <div class="flex justify-between text-sm mt-2">
                        <span>0 KM</span>
                        <span>{{ $maxPrice }} KM</span>
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
                    @foreach ($brands as $brand)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" 
                                id="brand_{{ $brand->id }}" 
                                wire:model.live="selectedBrands"
                                value="{{ $brand->id }}">
                            <label for="brand_{{ $brand->id }}" class="ml-2">{{ $brand->name }}</label>
                        </div>
                    @endforeach
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
                    @foreach ($processors as $processor)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" 
                                id="processor_{{ $processor }}" 
                                wire:model.live="selectedProcessors"
                                value="{{ $processor }}">
                            <label for="processor_{{ $processor }}" class="ml-2">{{ $processor }}</label>
                        </div>
                    @endforeach
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
                    @foreach ($ramSizes as $ramSize)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" 
                                id="ram_{{ $ramSize }}" 
                                wire:model.live="selectedRam"
                                value="{{ $ramSize }}">
                            <label for="ram_{{ $ramSize }}" class="ml-2">{{ $ramSize }} GB</label>
                        </div>
                    @endforeach
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
                    @foreach ($storages as $storage)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" 
                                id="storage_{{ $storage }}" 
                                wire:model.live="selectedStorage"
                                value="{{ $storage }}">
                            <label for="storage_{{ $storage }}" class="ml-2">{{ $storage }} GB</label>
                        </div>
                    @endforeach
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
                    @foreach ($screenSizes as $size)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" 
                                id="screen_size_{{ $size }}" 
                                wire:model.live="selectedScreenSizes"
                                value="{{ $size }}">
                            <label for="screen_size_{{ $size }}" class="ml-2">{{ $size }}"</label>
                        </div>
                    @endforeach
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
                    @foreach ($graphicsCards as $graphicsCard)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" 
                                id="graphics_card_{{ $graphicsCard }}" 
                                wire:model.live="selectedGraphics"
                                value="{{ $graphicsCard }}">
                            <label for="graphics_card_{{ $graphicsCard }}" class="ml-2">{{ $graphicsCard }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="w-5/6 ml-6 z-10 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white p-4 rounded-md shadow-md box">
                        <div>
                            @php
                                $images = json_decode($product->image, true);
                                $firstImage = !empty($images) && isset($images[0]) ? asset('storage/' . $images[0]) : asset('storage/MbitShopLogo.png');
                            @endphp

                            <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="h-48 w-96 object-cover">

                            <div>
                                <h2 class="text-gray-800">{{ $product->name }}</h2>
                                @if($product->brand->name)
                                    <p class="text-gray-600"> {{ $product->brand->name ?? '' }}</p>
                                @endif
                                @if($product->model)
                                    <p class="text-gray-600">{{ $product->model ?? ''}}</p>
                                @endif

                                <div class="flex items-center justify-between">
    <p class="text-gray-800 font-semibold">{{ $product->price ?? '' }} KM</p>
    
    <button wire:click="addToCart({{ $product->id }})" class="text-blue-500 hover:text-blue-700">
        <i class="fa fa-cart-plus text-2xl"></i>
    </button>
</div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>