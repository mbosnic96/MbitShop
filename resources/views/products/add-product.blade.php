<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form  method="POST" action="{{route('store-product')}}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Product Name:</label>
                        <input name="name" type="text" id="name" class="form-input rounded-md shadow-sm" required>
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="brand" class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                        <select name="brand" id="brand" class="form-select block w-75 mt-1 border-gray-300 focus:border-indigo-300">
                            <option selected="true" disabled>Odaberite proizvođača</option>
                            @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                            @endforeach
                        </select>

                        <button type="button" @click="$dispatch('openAddBrandModal')" class="text-blue-500">Add New Brand</button>

                        <!-- Add Brand Modal -->
                        <x-jet-dialog-modal wire:model="addingBrand">
                            <x-slot name="title">Add New Brand</x-slot>
                            <x-slot name="content">
                                <livewire:add-brand />
                            </x-slot>
                            <x-slot name="footer">
                                <x-jet-secondary-button @click="$toggle('addingBrand')">Cancel</x-jet-secondary-button>
                            </x-slot>
                        </x-jet-dialog-modal>
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                        <input name="category" type="text" id="category" class="form-input rounded-md shadow-sm" required>
                        @error('category') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                        <textarea name="description" id="description" class="form-input rounded-md shadow-sm" autofocus></textarea>
                        @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                        <input name="price" type="number" id="price" class="form-input rounded-md shadow-sm" step="0.01" required autofocus>
                        @error('price') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="created_at" class="block text-gray-700 text-sm font-bold mb-2">Posted:</label>
                        <input name="created_at" type="date" id="created_at" class="form-input rounded-md shadow-sm" required autofocus>
                        @error('created_at') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="updated" class="block text-gray-700 text-sm font-bold mb-2">Updated:</label>
                        <input name="updated" type="date" id="created_at" class="form-input rounded-md shadow-sm" required autofocus>
                        @error('updated') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="stock_quantity" class="block text-gray-700 text-sm font-bold mb-2">Stock Quantity:</label>
                        <input name="stock_quantity" type="number" id="stock_quantity" class="form-input rounded-md shadow-sm" required autofocus>
                        @error('stock_quantity') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Product Image:</label>
                        <input name="image" type="file" id="image" class="form-input rounded-md shadow-sm" accept="image/*">
                        @error('image') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="bg-blue-500 px-4 py-2 rounded-md">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
