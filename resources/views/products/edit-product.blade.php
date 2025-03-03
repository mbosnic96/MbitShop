<!-- Edit Product Modal -->
<div id="products-edit" class="modal">
    <form method="POST" action="/api/dashboard/products/:id" enctype="multipart/form-data"  id="editProductForm">
        @csrf
        <div class="modal-content w-50">
            <span class="close close-modal" data-modal="products-edit">&times;</span>
            <h2>Izmjeni proizvod</h2>
            <div class="modal-body">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Naziv: (obavezno)</label>
                    <input name="name" type="text" id="name"
                        class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full" required>
                    @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Brand Field -->
                    <div class="mb-4">
                        <label for="brand" class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                        <select name="brand" id="brand"
    class="form-select inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
    <option disabled>Odaberite proizvođača</option>
    @foreach($brands as $brand)
        <option value="{{ $brand->id }}" {{ old('brand', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
            {{ $brand->name }}
        </option>
    @endforeach
</select>

                        @error('brand') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <!-- Category Field -->
                    <div>
                        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Kategorija:
                            (obavezno)</label>
                        <div class="custom-dropdown">
                            <div class="dropdown-header">
                                <span>{{ $selectedCategory->name ?? 'Odaberite kategoriju' }}</span>
                                <i class="fa fa-angle-down"></i>
                            </div>
                            <div class="dropdown-content hidden">
                                @foreach($categories as $category)
                                    @if($category->parent_id === null)
                                        <div class="parent-category">
                                            <div class="parent-header toggle">
                                                <span>{{ $category->name }}</span>
                                                <i class="fa fa-angle-down toggle-icon"></i>
                                            </div>
                                            <div class="child-categories hidden">
                                                @foreach($categories as $child)
                                                    @if($child->parent_id === $category->id)
                                                        <div class="child-category" data-value="{{ $child->id }}">
                                                            {{ $child->name }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="category" value="">
                    </div>

                </div>

                <!-- Description Field -->
                <div>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Opis:</label>
                    <textarea name="description" id="description"
                        class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full"></textarea>
                    @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <!-- Model Field -->
                    <div>
                        <label for="model" class="block text-gray-700 text-sm font-bold mb-2">Model:</label>
                        <input name="model" type="text" id="model"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('model') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <!-- Processor Field -->
                    <div class="mb-4">
                        <label for="processor" class="block text-gray-700 text-sm font-bold mb-2">Procesor:</label>
                        <input name="processor" type="text" id="processor"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('processor') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <!-- RAM Field -->
                    <div class="mb-4">
                        <label for="ram_size" class="block text-gray-700 text-sm font-bold mb-2">RAM:</label>
                        <input name="ram_size" type="text" id="ram_size"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('ram_size') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <!-- Storage Field -->
                    <div class="mb-4">
                        <label for="storage" class="block text-gray-700 text-sm font-bold mb-2">Memorija:</label>
                        <input name="storage" type="text" id="storage"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('storage') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <!-- Graphics Card Field -->
                    <div class="mb-4">
                        <label for="graphics_card" class="block text-gray-700 text-sm font-bold mb-2">Grafička
                            karta:</label>
                        <input name="graphics_card" type="text" id="graphics_card"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('graphics_card') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <!-- Operating System Field -->
                    <div class="mb-4">
                        <label for="operating_system" class="block text-gray-700 text-sm font-bold mb-2">OS:</label>
                        <input name="operating_system" type="text" id="operating_system"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                        @error('operating_system') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Price Field -->
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Cijena:
                            (obavezno)</label>
                        <input name="price" type="number" id="price"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full"
                            step="0.01" required>
                        @error('price') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <!-- Stock Quantity Field -->
                    <div class="mb-4">
                        <label for="stock_quantity" class="block text-gray-700 text-sm font-bold mb-2">Na stanju:
                            (obavezno)</label>
                        <input name="stock_quantity" type="number" id="stock_quantity"
                            class="form-input inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full"
                            required>
                        @error('stock_quantity') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>

                <!-- Images Field (optional) -->
                <div class="mb-4">
                    <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Slike: (moguće
                        više)</label>
                    <input type="file" name="images[]" id="images" multiple>

                    <div class="flex gap-4" id="uploaded-images">
                        <!-- Images will be dynamically inserted here using JavaScript -->
                    </div>
                    @error('images') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-modal="products-edit"
                    class="px-4 py-2 bg-gray-600 text-white rounded-md close-modal">Zatvori</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Sačuvaj</button>
            </div>
        </div>
    </form>
</div>