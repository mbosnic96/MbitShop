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
                <!-- Tabs and Content -->
                <div class="flex flex-col py-6">
                    <!-- Livewire Modals -->
                     <div>
                    @include('products.add-product')</div>
                    @include('products.edit-product')

                    <!-- Table Container -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="container mx-auto p-6" 
                             x-data="tableData('/api/dashboard/products', ['id', 'name', 'model', 'stock_quantity', 'price', 'brand', 'category', 'processor', 'ram_size', 'storage'], 'products-edit', 'editProductForm')">
                            <x-table></x-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>