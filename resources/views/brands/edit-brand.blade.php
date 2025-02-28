<div id="brand-edit" class="modal">
    <form method="POST" action="{{ route('brand.update', ':id') }}" id="editBrandForm">
        @csrf

        <div class="modal-content w-25">
            <span class="close close-modal" data-modal="brand-edit">&times;</span>
            <h2>Izmjeni brand</h2>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="mb-4">
                    <label for="edit-name" class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                    <input name="name" type="text" id="edit-name" class="form-input rounded-md shadow-sm w-full" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-modal="brand-edit" class="px-4 py-2 bg-gray-600 text-white rounded-md close-modal">Zatvori</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Sačuvaj</button>
            </div>
        </div>
    </form>
</div>
