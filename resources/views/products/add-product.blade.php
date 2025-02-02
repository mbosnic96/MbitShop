<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>

        <div class="mt-3">
        <h4 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Toolbar') }}
        </h4>

            <x-add-brand></x-add-brand>
            <x-add-category></x-add-category>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form  method="POST" action="{{route('products.store')}}"  class="flex flex-col" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Naziv: (obavezno)</label>
                        <input name="name" type="text" id="name" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full" required>
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="brand" class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                        <select name="brand" id="brand" class="form-select inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                            <option selected="true" disabled>Odaberite proizvođača</option>
                            @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Kategorija: (obavezno)</label>
                        <select name="category" id="category" class="form-select inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                            <option selected="true" disabled>Odaberite kategoriju</option>
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Opis:</label>
                        <textarea name="description" id="description" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full"></textarea>
                        @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="model" class="block text-gray-700 text-sm font-bold mb-2">Model:</label>
                        <input name="model" type="text" id="model" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('model') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="processor" class="block text-gray-700 text-sm font-bold mb-2">Procesor:</label>
                        <input name="processor" type="text" id="processor" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('processor') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="ram_size" class="block text-gray-700 text-sm font-bold mb-2">RAM:</label>
                        <input name="ram_size" type="text" id="ram_size" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('ram_size') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="storage" class="block text-gray-700 text-sm font-bold mb-2">Memorija:</label>
                        <input name="storage" type="text" id="storage" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('storage') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="graphics_card" class="block text-gray-700 text-sm font-bold mb-2">Grafička karta:</label>
                        <input name="graphics_card" type="text" id="graphics_card" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('graphics_card') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="operating_system" class="block text-gray-700 text-sm font-bold mb-2">OS:</label>
                        <input name="operating_system" type="text" id="operating_system" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('operating_system') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Cijena: (obavezno)</label>
                        <input name="price" type="number" id="price" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full" step="0.01" required >
                        @error('price') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="stock_quantity" class="block text-gray-700 text-sm font-bold mb-2">Na stanju: (obavezno)</label>
                        <input name="stock_quantity" type="number" id="stock_quantity" class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full" required >
                        @error('stock_quantity') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
    <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Slike: (moguće više)</label>
    <input type="file" name="images[]" id="images" multiple>

    @error('images') <span class="text-red-500">{{ $message }}</span>@enderror
</div>


</div>
<button type="submit" class="bg-blue-500 px-4 py-2 rounded-md self-end">Add Product</button>
                    
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
