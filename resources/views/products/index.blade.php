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
                <x-table :data="$products" :columns="['id', 'name', 'model', 'stock_quantity', 'price', 'brand', 'category', 'processor', 'ram_size', 'storage']" routePrefix="products" :actions="[
        ['route' => 'show', 'label' => '<i class=\'fa fa-eye\'></i>', 'color' => 'blue'],
        ['route' => 'edit', 'label' => '<i class=\'fa fa-pencil\'></i>', 'color' => 'yellow'],
        ['route' => 'destroy', 'label' => '<i class=\'fa fa-trash\'></i>', 'color' => 'red']
    ]" />
            </div>
        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 --></div>
</div> <!-- Closing tab-pane -->
</x-app-layout>