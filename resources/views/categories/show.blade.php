<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl sm:px-6 lg:px-4 mx-auto">
            <div class="flex space-x-6">
            @foreach ($category->products as $product)
                <div class="bg-white p-6 rounded-md shadow-md flex-1">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-40 object-contain mb-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-700 mb-4">{{ $product->description }}</p>
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-gray-800 font-semibold">${{ $product->price }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            @php
                                $brandName = \App\Models\Brand::find($product->brand)->name ?? null;
                            @endphp
                            <p class="text-gray-600">Brand: {{ $brandName }}</p>
                            <p class="text-gray-600">Model: {{ $product->model }}</p>
                            <p class="text-gray-600">Processor: {{ $product->processor }}</p>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-600">RAM Size: {{ $product->ram_size }}</p>
                            <p class="text-gray-600">Storage: {{ $product->storage }}</p>
                            <p class="text-gray-600">Graphics Card: {{ $product->graphics_card }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
</div>
    </div>
</x-app-layout>
