<x-app-layout>
    
<div class="flex h-screen">
    @include('dashboard.sidebar')
    <div class="flex-1 p-6">
    <!-- Tabs -->
    <div class="flex flex-col py-12">
            <div>
            @include('categories.add-category')
            @include('categories.edit-category')
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-table :data="$categories" :columns="['id', 'name','slug', 'parent_id', 'position']" routePrefix="categories" :actions="[
                      ['route' => 'show', 'label' => '<i class=\'fa fa-eye\'></i>', 'color' => 'blue'],
        ['route' => 'edit', 'label' => '<i class=\'fa fa-pencil\'></i>', 'color' => 'yellow'],
        ['route' => 'destroy', 'label' => '<i class=\'fa fa-trash\'></i>', 'color' => 'red']
    ]" />
        </div>
        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 -->
</div> <!-- Closing tab-pane -->
</div>
</x-app-layout>