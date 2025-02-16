<x-app-layout>
    
<div class="flex h-screen">
    @include('dashboard.sidebar')
    <div class="flex-1 p-6">
    <!-- Tabs -->
    <div class="flex flex-col py-12">
            <div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-table :data="$users" :columns="['id', 'name','role']" routePrefix="users" :actions="[
        ['route' => 'destroy', 'label' => '<i class=\'fa fa-trash\'></i>', 'color' => 'red']
    ]" />
            </div>
        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 -->
</div> <!-- Closing tab-pane -->
</div>
</x-app-layout>