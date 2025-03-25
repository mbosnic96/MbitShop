<x-app-layout>
    <div class="flex h-screen" x-data="{ sidebarOpen: window.innerWidth >= 768 }" @resize.window="sidebarOpen = window.innerWidth >= 768">
        <!-- Mobile Sidebar Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="md:hidden fixed z-50 top-[80px] left-0 p-1.5 rounded-r-md bg-gray-800 text-white shadow-lg transition-all duration-300 hover:bg-gray-700"
                :class="{'left-64': sidebarOpen}">
            <span x-show="!sidebarOpen" class="text-xs font-bold">>></span>
            <span x-show="sidebarOpen" class="text-xs font-bold"><<</span>
        </button>

        <!-- Sidebar - Mobile Overlay -->
        <div x-show="sidebarOpen && window.innerWidth < 768" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden transition-opacity duration-300">
        </div>

        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto ml-0 transition-all duration-300">
            <div class="p-6">
    <!-- Tabs -->
    <div class="flex flex-col py-12">
            <div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-table-main :data="$users" :columns="['id', 'name','role']" routePrefix="users" :actions="[
        ['route' => 'destroy', 'label' => '<i class=\'fa fa-trash\'></i>', 'color' => 'red']
    ]" />
            </div>
        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 -->
</div> <!-- Closing tab-pane -->
</div>
</x-app-layout>