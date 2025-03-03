<div x-show="data.length > 0">
    <table class="table min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <template x-for="column in columns" :key="column">
                    <th class="px-4 py-2 text-left border-b" x-text="column.charAt(0).toUpperCase() + column.slice(1)">
                    </th>
                </template>
                <th class="px-4 py-2 text-left border-b text-center">Akcije</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="item in data" :key="item . id">
                <tr class="odd:bg-white even:bg-gray-50">
                    <template x-for="column in columns" :key="column">
                    <td class="px-4 py-2 border-b">
                        <span x-text="typeof item[column] === 'object' ? item[column]?.name ?? '—' : item[column]"></span>
                    </td>
                    </template>
                    <td class="px-4 py-2 border-b text-center">
                        <button @click="openModal(item)"
                            class="open-modal px-2 py-2 text-white bg-yellow-500 rounded-full w-10 h-10 hover:bg-yellow-600">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button @click="deleteItem(item.id)"
                            class="px-2 py-2 text-white bg-red-500 rounded-full w-10 h-10 hover:bg-red-600">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        <!-- First Page Button -->
        <button @click="fetchData(1)" :disabled="page <= 1"
            class="px-4 py-2 me-2 bg-blue-500 text-white rounded-l disabled:bg-blue-100">
            <i class="fa fa-angle-double-left"></i>
        </button>

        <!-- Previous Page Button -->
        <button @click="fetchData(page - 1)" :disabled="page <= 1"
            class="px-4 py-2 bg-blue-500 text-white disabled:bg-blue-100">
            <i class="fa fa-chevron-left"></i>
        </button>

        <!-- Current Page and Total Pages -->
        <span class="px-4 py-2" x-text="`${page} of ${lastPage}`"></span>

        <!-- Next Page Button -->
        <button @click="fetchData(page + 1)" :disabled="page >= lastPage"
            class="px-4 py-2 me-2 bg-blue-500 text-white disabled:bg-blue-100">
            <i class="fa fa-chevron-right"></i>
        </button>

        <!-- Last Page Button -->
        <button @click="fetchData(lastPage)" :disabled="page >= lastPage"
            class="px-4 py-2 bg-blue-500 text-white rounded-r disabled:bg-blue-100">
            <i class="fa fa-angle-double-right"></i>
        </button>
    </div>
</div>

<!-- No data found -->
<div x-show="data.length === 0" class="text-center mt-6">
    <p class="text-lg text-gray-500">Nema podataka.</p>
</div>

<script>
    function tableData(apiUrl, columns, modalId, formId) {
        return {
            columns: columns,
            data: [],
            page: 1,
            lastPage: 1,
            modalId: modalId,
            formId: formId,


            init() {
                this.fetchData(this.page);
                document.querySelector(`#${this.formId}`)?.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.submitForm(e);
                });
            },

            fetchData(page = 1) {
                this.page = page;
                const url = `${apiUrl}?page=${page}`;


                axios.get(url)
                    .then(response => {

                        const result = response.data;


                        if (result && result.data && Array.isArray(result.data)) {
                            this.data = result.data; // Set data from the API response
                            this.lastPage = result.last_page; // Set pagination information
                        } else {
                            console.error('Unexpected API response structure:', result);
                        }

                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
                    setInterval(() => {
                this.fetchCart(); // Refresh cart data every 5 seconds
            }, 5000);

            },
            openModal(item) {


                const url = `${apiUrl}/${item.id}`;


                axios.get(url)
                    .then(response => {


                        const fullItemData = response.data;

                        // Open the modal
                        window.openModal(modalId, fullItemData);

                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Greška!',
                            text: 'Greška pri čitanju podataka. Pokušajte ponovo.',
                            icon: 'error',
                        });
                    });
            },
            deleteItem(id) {
                // SweetAlert confirmation before deleting
                Swal.fire({
                    title: 'Jeste li sigurni?',
                    text: "Ova se ne može poništiti!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`${apiUrl}/${id}`)
                            .then(() => {
                                this.fetchData(this.page); // Refresh data after deletion
                                Swal.fire(
                                    'Obrisano!',
                                    'Stavka je obrisana.',
                                    'success'
                                );
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Greška!',
                                    'Greška. Pokušajte ponovo.',
                                    'error'
                                );
                            });
                    }
                });
            },
            

        };
    }
</script>