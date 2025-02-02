<div id="add-category" class="modal">
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <div class="modal-content w-25">
            <span class="close" onclick="closeModal('add-category')">&times;</span>
            <h2>Dodaj kategoriju</h2>
            <div class="modal-body">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Naziv kategorije:</label>
                    <input name="name" type="text" id="name" class="form-input rounded-md shadow-sm w-full" required>
                    @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('add-category')">Zatvori</button>
                <button type="submit" class="bg-green-800 px-4 py-2 rounded-md text-light">Dodaj kategoriju</button>
            </div>
        </div>
    </form>
</div>



<button onclick="openModal('add-category')" class="mt-3 me-1">Dodaj Kategoriju</button>
