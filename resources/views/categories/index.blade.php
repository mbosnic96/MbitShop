<div class="tab-pane hidden" id="tab-categories">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('categories.add-category')
            @include('categories.edit-category')
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-table :data="$categories" :columns="['id', 'name', 'parent_id', 'position']" routePrefix="categories" :actions="[
        ['route' => 'edit', 'label' => '<i class=\'fa fa-pencil\'></i>', 'color' => 'yellow'],
        ['route' => 'destroy', 'label' => '<i class=\'fa fa-trash\'></i>', 'color' => 'red']
    ]" />
        </div>
        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 -->
</div> <!-- Closing tab-pane -->