<div class="tab-pane hidden" id="tab-brands">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('brands.add-brand')
            @include('brands.edit-brand')
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-table :data="$brands" :columns="['id', 'name']" routePrefix="brand" :actions="[
        ['route' => 'edit', 'label' => '<i class=\'fa fa-pencil\'></i>', 'color' => 'yellow'],
        ['route' => 'destroy', 'label' => '<i class=\'fa fa-trash\'></i>', 'color' => 'red']
    ]" />
            </div>
        </div> 
    </div>
</div>