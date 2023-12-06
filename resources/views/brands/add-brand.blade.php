
        <div>
                <form  method="POST" action="{{route('store-brand')}}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Brand Name:</label>
                        <input name="name" type="text" id="name" class="form-input rounded-md shadow-sm" required>
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="bg-blue-500 px-4 py-2 rounded-md">Add Brand</button>
                </form>
          
    </div>

    @push('scripts')
    <script>
        Livewire.on('brandAdded', () => {
            @this.set('addingBrand', false);
        });

        Livewire.on('openAddBrandModal', () => {
            @this.set('addingBrand', true);
        });
    </script>
@endpush
