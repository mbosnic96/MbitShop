<x-app-layout>
    <div class="flex">
        @include('dashboard.sidebar')
        <div class="flex-1 p-6">
            <!-- Tabs -->
            <div class="flex flex-col py-6">

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="container mx-auto p-6" x-data="tableData('/api/dashboard/orders', ['id', 'order_number', 'total_price', 'status', 'shipping_address'])">
                        <x-table></x-table>
                    </div>
                </div>
            </div> <!-- Closing max-w-7xl -->
        </div> <!-- Closing py-12 -->
    </div>
</x-app-layout>
