<x-app-layout>
    
<div class="flex">
    @include('dashboard.sidebar')
    <div class="flex-1 p-6">
    <!-- Tabs -->
    <div class="flex flex-col py-6">
            <div>
                <!-- Livewire Modal -->
                @include('products.add-product')
                @include('products.edit-product')
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="container mx-auto p-6" x-data="tableData('/api/dashboard/products', ['id', 'name', 'model', 'stock_quantity', 'price', 'brand', 'category', 'processor', 'ram_size', 'storage'], 'products-edit', 'editProductForm')">
                            <x-table></x-table>
                        </div>
              
            </div>
            </div>
        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 --></div>
</div> <!-- Closing tab-pane -->
</x-app-layout>