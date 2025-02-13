<div class="tab-pane hidden" id="tab-products">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                <!-- Livewire Modal -->
                @include('products.add-product')
                @include('products.edit-product')
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-table :data="$products" :columns="['id', 'name', 'model', 'stock_quantity', 'price', 'brand', 'category', 'processor', 'ram_size', 'storage']" routePrefix="products" :actions="[
        ['route' => 'create', 'label' => '<i class=\'fa fa-eye\'></i>', 'color' => 'blue'],
        ['route' => 'edit', 'label' => '<i class=\'fa fa-pencil\'></i>', 'color' => 'yellow'],
        ['route' => 'destroy', 'label' => '<i class=\'fa fa-trash\'></i>', 'color' => 'red']
    ]" />
            </div>
        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 -->
</div> <!-- Closing tab-pane -->