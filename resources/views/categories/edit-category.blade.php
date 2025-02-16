<div id="categories-edit" class="modal">
    <form method="POST" action="{{ route('categories.update', ':id') }}" id="editCategoryForm">
        @csrf

        <div class="modal-content w-25">
            <span class="close close-modal" data-modal="categories-edit">&times;</span>
            <h2>Izmjeni kategoriju</h2>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="mb-4">
                    <label for="edit-name" class="block text-gray-700 text-sm font-bold mb-2">Kategorija:</label>
                    <input name="name" type="text" id="name" class="form-input rounded-md shadow-sm w-full" required>
                </div>

                <div class="mb-4">
                        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Pripadajuća kategorija:
                            (prazno ako nema)</label>
                        <select name="parent_id" id="parent_id"
                            class="form-select inline-block mt-1 border-gray-300 focus:border-indigo-300 w-full">
                            <option selected value="">Odaberite kategoriju (nema)</option>
                            @foreach($categories as $category)
                            @if($category->parent_id === null)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('category') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                    <label for="position" class="block text-gray-700 text-sm font-bold mb-2">Pozicija u navigaciji:</label>
                    <small>Default 0, prva pozicija</small>
                    <input name="position" type="text" id="position" class="form-input rounded-md shadow-sm w-full">
                    @error('position') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-modal="categories-edit" class="px-4 py-2 bg-gray-600 text-white rounded-md close-modal">Zatvori</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Sačuvaj</button>
            </div>
        </div>
    </form>
</div>
