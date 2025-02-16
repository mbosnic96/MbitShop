<x-app-layout>
    
<div class="flex h-screen">
    @include('dashboard.sidebar')
    <div class="flex-1 p-6">
    <!-- Tabs -->
    <div class="flex flex-col py-12">
            <div>
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
</div>
</x-app-layout>