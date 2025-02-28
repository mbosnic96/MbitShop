<!-- Add Brand Modal -->
<div id="add-brand" class="modal">
    <form method="POST" action="{{ route('brands.store') }}">
        @csrf
        <div class="modal-content w-25">
            <span class="close" data-modal="add">&times;</span>
            <h2>Dodaj brand</h2>
            <div class="modal-body">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                    <input name="name" type="text" id="name" class="form-input rounded-md shadow-sm w-full" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-modal="add-brand" class="px-4 py-2 bg-gray-600 text-white rounded-md close-modal">Zatvori</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Dodaj brend</button>
            </div>
        </div>
    </form>
</div>

<!-- Button to open Add Brand Modal -->
<button class="my-2 px-4 py-2 bg-green-500 text-white rounded open-modal" data-modal-id="add-brand">Dodaj brend</button>
