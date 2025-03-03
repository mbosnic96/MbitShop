<x-app-layout>
    <div class="flex h-screen">
        @include('dashboard.sidebar')
        <div class="flex-1 p-6">
            <div class="flex flex-col py-12">
                <div>
                    @include('brands.add-brand')
                    @include('brands.edit-brand') <!-- Ensure this modal is included -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="container mx-auto p-6" x-data="tableData('/api/dashboard/brands', ['id', 'name'], 'editBrandModal', 'editBrandForm')">
                            <x-table></x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
