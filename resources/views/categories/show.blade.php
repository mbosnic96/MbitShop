<x-app-layout>
    <div class="py-12 sm:m-6">
        <div class="container mx-auto">
            <div class="grid 2xl:grid-cols-4 xl:grid-cols-4 lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-x-6 gap-y-12 w-full mt-6">
            @foreach ($category->products as $product)
                <div class="bg-white p-8 rounded-md shadow-md box">
                    <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                    <img src="{{ $product->image ?? asset('../storage/MbitShopLogo.png') }}" alt="{{ $product->name }}" class="w-full object-contain mb-4 mx-auto">
                    
                   
                    <div class="flex flex-col mb-4 text-animated"> 
                        <p class="text-gray-800 font-semibold">${{ $product->price }}</p>
                    </div>
                    <div class="flex flex-col items-center space-x-4 box-hidden">
                        <div>
                            @php
                                $brandName = \App\Models\Brand::find($product->brand)->name ?? null;
                                $categoryName = \App\Models\Category::find($product->category)->name ?? null;
                            @endphp
                            <p class="text-gray-600">Brand: {{ $brandName }}</p>
                            <p class="text-gray-600">Model: {{ $product->model }}</p>
                            <p class="text-gray-600">Procesor (Model/GHz): {{ $product->processor }}</p>
                            @if($categoryName != 'Mobiteli')
                                <p class="text-gray-600">RAM (GB): {{ $product->ram_size }}</p>
                            @endif
                            <p class="text-gray-600">Memorija (GB): {{ $product->storage }}</p>
                            <p class="text-gray-600">Grafička kartica: {{ $product->graphics_card }}</p>
                        </div>
                        <div>
                        </div>
                        <div class="flex items-center justify-center mt-4">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded">Buy Now</button>
                    </div>
                    </div>
                    
                </div>
            @endforeach
        </div>
</div>
    </div>
</x-app-layout>
