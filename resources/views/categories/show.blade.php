<x-app-layout>
    <div class="py-12 sm:m-6">
        <div class="container mx-auto">
            <div class="grid 2xl:grid-cols-4 xl:grid-cols-4 lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-x-6 gap-y-12 w-full mt-6">
            @foreach ($category->products as $product)
                <div class="bg-white p-8 rounded-md shadow-md box">
                    <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                    @php
    $images = json_decode($product->image, true);
    $firstImage = !empty($images) ? asset('storage/' . $images[0]) : asset('storage/MbitShopLogo.png');
@endphp

<img src="{{ $firstImage }}" alt="{{  $product->name  }}" class="product-image">




                    
                   
                    <div class="flex flex-col mb-0 text-animated"> 
                        <p class="text-gray-800 font-semibold">{{ $product->price }} KM</p>
                    </div>
                    <div class="flex flex-col items-center space-x-2 box-hidden">
                        <div>
                            <p class="text-gray-600">Brand: {{ $product->brand->name  }}</p>
                            <p class="text-gray-600">Model: {{ $product->model }}</p>
                            <p class="text-gray-600">Procesor (Model/GHz): {{ $product->processor }}</p>
                            <p class="text-gray-600">RAM (GB): {{ $product->ram_size }}</p>
                            <p class="text-gray-600">Memorija (GB): {{ $product->storage }}</p>
                            <p class="text-gray-600">Grafička kartica: {{ $product->graphics_card }}</p>
                        </div>
                        <div>
                        </div>
                        <div class="flex items-center justify-center mt-4">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded">Vidi detaljno</button>
                    </div>
                    </div>
                    
                </div>
            @endforeach
        </div>
</div>
    </div>
</x-app-layout>
