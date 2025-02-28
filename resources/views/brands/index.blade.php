<x-app-layout>
    <div class="flex h-screen">
        @include('dashboard.sidebar')
        <div class="flex-1 p-6">
            <div class="flex flex-col py-12">
                <div>
                    @include('brands.add-brand')
                    @include('brands.edit-brand')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="container mx-auto p-6" x-data="tableData()">
                            <div x-show="data.length > 0">
                                <table class="min-w-full table-auto border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <template x-for="column in columns" :key="column">
                                                <th class="px-4 py-2 text-left border-b" x-text="column.charAt(0).toUpperCase() + column.slice(1)">
                                                </th>
                                            </template>
                                            <th class="px-4 py-2 text-left border-b">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="item in data" :key="item.id">
                                            <tr class="odd:bg-white even:bg-gray-50">
                                                <template x-for="column in columns" :key="column">
                                                    <td class="px-4 py-2 border-b" x-text="item[column]"></td>
                                                </template>
                                                <td class="px-4 py-2 border-b">
                                                    <button @click="editItem(item.id)" class="text-yellow-500 hover:text-yellow-700">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </button>
                                                    <button @click="deleteItem(item.id)" class="text-red-500 hover:text-red-700 ml-2">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="mt-4">
                                    <button 
                                        @click="fetchData(page - 1)" 
                                        :disabled="page <= 1" 
                                        class="p-2 bg-gray-300 rounded-l"
                                    >
                                        Previous
                                    </button>
                                    <span class="px-4 py-2" x-text="page"></span>
                                    <button 
                                        @click="fetchData(page + 1)" 
                                        :disabled="page >= lastPage"
                                        class="p-2 bg-gray-300 rounded-r"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>

                            <!-- No data found -->
                            <div x-show="data.length === 0" class="text-center mt-6">
                                <p class="text-lg text-gray-500">No data found</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function tableData() {
        return {
            columns: ['id', 'name'], // Customize the columns as needed
            data: [],
            page: 1,
            lastPage: 1,

            // Fetch the data immediately after Alpine initializes
            init() {
                this.fetchData(this.page); // Fetch data when component is initialized
            },

            fetchData(page = 1) {
                this.page = page;
                const url = `/api/dashboard/brands?page=${page}`;
                
                console.log('Making API request to:', url);

                axios.get(url)
                    .then(response => {
                        console.log('API Response:', response); // Log the whole response
                        
                        const result = response.data;
                        
                        // Check if 'data' is present in the response
                        if (result && result.data && Array.isArray(result.data)) {
                            console.log('Fetched Data:', result.data);
                            this.data = result.data; // Set data from the API response
                            this.lastPage = result.last_page; // Set pagination information
                        } else {
                            console.error('Unexpected API response structure:', result);
                        }

                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            },

            editItem(id) {
                console.log('Edit item with id:', id);
                // Implement the edit functionality here
            },

            deleteItem(id) {
    // SweetAlert confirmation before deleting
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/dashboard/brands/${id}`)
                .then(() => {
                    this.fetchData(this.page); // Refresh data after deletion
                    Swal.fire(
                        'Deleted!',
                        'The brand has been deleted.',
                        'success'
                    );
                })
                .catch(error => {
                    console.error('Error deleting item:', error);
                    Swal.fire(
                        'Error!',
                        'Something went wrong. Please try again.',
                        'error'
                    );
                });
        }
    });
}

        };
    }
</script>
