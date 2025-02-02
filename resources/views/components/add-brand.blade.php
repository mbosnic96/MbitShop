<div id="add-brand" class="modal">
    <form method="POST" action="{{ route('brands.store') }}">
        @csrf
        <div class="modal-content w-25">
            <span class="close" onclick="closeModal('add-brand')">&times;</span>
            <h2>Dodaj brend</h2>
            <div class="modal-body">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                    <input name="name" type="text" id="name" class="form-input rounded-md shadow-sm w-full" required>
                    @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('add-brand')">Zatvori</button>
                <button type="submit" class="bg-green-800 px-4 py-2 rounded-md text-light">Dodaj brend</button>
            </div>
        </div>
    </form>
</div>



<button onclick="openModal('add-brand')" class="mt-3 me-1">Dodaj brend</button>
