<div x-show="apiUrl === '/api/dashboard/orders'" class="mb-4 flex items-center justify-between">
    <!-- Status Checkboxes (Left) -->
    <div class="flex space-x-4">
        <label>
            <input type="checkbox" x-model="selectedStatus" value="na čekanju" x-on:change="applyFilter()"> Na čekanju
        </label>
        <label>
            <input type="checkbox" x-model="selectedStatus" value="u obradi" x-on:change="applyFilter()"> U obradi
        </label>
        <label>
            <input type="checkbox" x-model="selectedStatus" value="poslano" x-on:change="applyFilter()"> Poslano
        </label>
        <label>
            <input type="checkbox" x-model="selectedStatus" value="otkazano" x-on:change="applyFilter()"> Otkazano
        </label>
    </div>

    <!-- Search Input and Button (Right) -->
    <div class="flex space-x-2">
        <input
            type="text"
            x-model="searchTerm"
            placeholder="Broj narudžbe"
            class="px-2 py-1 border rounded"
        />
        <button
            @click="applyFilter()"
            class="px-4 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
            Traži
        </button>
    </div>
</div>

    <div x-show="filteredData.length > 0">
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
                <template x-for="item in filteredData" :key="item.id">
                    <tr class="odd:bg-white even:bg-gray-50">
                        <template x-for="column in columns" :key="column">
                            <td class="px-4 py-2 border-b">
                                <span x-text="typeof item[column] === 'object' ? item[column]?.name ?? '—' : item[column]"></span>
                            </td>
                        </template>
                        <td class="px-4 py-2 border-b text-center">
                        
                            <button @click="openModal(item)" title="Uredi" x-show="userRole === 'admin' && apiUrl !== '/api/dashboard/orders'"
                                class="open-modal px-2 py-2 text-white bg-yellow-500 rounded-full w-10 h-10 hover:bg-yellow-600">
                                <i class="fa fa-pencil"></i>
                            </button>
                                <button @click="deleteItem(item.id)" title="Briši" x-show="userRole === 'admin' && apiUrl !== '/api/dashboard/orders'"
                                    class="px-2 py-2 text-white bg-red-500 rounded-full w-10 h-10 hover:bg-red-600">
                                    <i class="fa fa-trash"></i>
                                </button>
                            <button x-show="userRole === 'admin' && apiUrl === '/api/dashboard/orders'" title="Prebaci narudžbu u obradu" @click="inProgress(item.id)"
                                class="px-2 py-2 text-white bg-blue-500 rounded-full w-10 h-10 hover:bg-blue-600">
                                <i class="fa fa-archive"></i>
                            </button>
                            <button x-show="userRole === 'admin' && apiUrl === '/api/dashboard/orders'" title="Označi kao poslano" @click="approveOrder(item.id)"
                                class="px-2 py-2 text-white bg-blue-500 rounded-full w-10 h-10 hover:bg-blue-600">
                                <i class="fa fa-truck"></i>
                            </button>
                            <button @click="canceledOrder(item.id)" title="Otkaži narudžbu" x-show="apiUrl === '/api/dashboard/orders' || apiUrl === '/api/dashboard/my-orders'"
                                    class="px-2 py-2 text-white bg-red-500 rounded-full w-10 h-10 hover:bg-red-600">
                                    <i class="fa fa-times"></i>
                                </button>
                            <button x-show="apiUrl === '/api/dashboard/orders' || apiUrl === '/api/dashboard/my-orders'" title="Preuzmi PDF narudžbe" @click="downloadPDF(item.id)"
                                class="px-2 py-2 text-white bg-green-500 rounded-full w-10 h-10 hover:bg-green-600">
                                <i class="fa fa-download"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- No data found -->
    <div x-show="filteredData.length === 0" class="text-center mt-6">
        <p class="text-lg text-gray-500">Nema podataka.</p>
    </div>

    <div class="mt-4 flex justify-center" x-show="data.length > 0">
    <button @click="fetchData(1)" :disabled="page <= 1" class="px-4 py-2 me-2 bg-blue-500 text-white rounded-l disabled:bg-blue-100">
    <i class="fa fa-angle-double-left"></i>
</button>

<button @click="fetchData(page - 1)" :disabled="page <= 1" class="px-4 py-2 bg-blue-500 text-white disabled:bg-blue-100">
    <i class="fa fa-chevron-left"></i>
</button>

<span class="px-4 py-2" x-text="`${page} of ${lastPage}`"></span>

<button @click="fetchData(page + 1)" :disabled="page >= lastPage" class="px-4 py-2 me-2 bg-blue-500 text-white disabled:bg-blue-100">
    <i class="fa fa-chevron-right"></i>
</button>

<button @click="fetchData(lastPage)" :disabled="page >= lastPage" class="px-4 py-2 bg-blue-500 text-white rounded-r disabled:bg-blue-100">
    <i class="fa fa-angle-double-right"></i>
</button>

</div>


<script>
function tableData(apiUrl, columns, modalId, formId) {
    return {
        columns: columns,
        data: [],  
        filteredData: [],  
        selectedStatus: [],
        searchTerm: '',  
        page: 1,
        lastPage: 1,
        modalId: modalId,
        formId: formId,
        apiUrl: apiUrl,
        userRole: null,  

        init() {
            
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam) {
                this.searchTerm = searchParam; 
                this.applyFilter(); 
            }

            this.fetchData(this.page); 
            this.userRole = document.querySelector('meta[name="user-role"]').getAttribute('content');  
            document.querySelector(`#${this.formId}`)?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitForm(e);
            });
            setInterval(() => {
                this.fetchData(this.page);
            }, 2000);
        },

        fetchData(page = 1) {
            this.page = page;
            
            const params = {
                page: page,
                status: this.selectedStatus,  
                search: this.searchTerm,  
            };

            axios.get(apiUrl, { params })
                .then(response => {
                    const result = response.data;
                    if (result && result.data && Array.isArray(result.data)) {
                        this.data = result.data;
                        this.filteredData = result.data;  
                        this.lastPage = result.last_page;
                    } else {
                        console.error('Unexpected API response structure:', result);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },

        applyFilter() {
            
            this.page = 1;  
            this.fetchData(this.page);
        },

     


            openModal(item) {
                axios.get(`${apiUrl}/${item.id}`)
                    .then(response => {
                        window.openModal(modalId, response.data);
                    })
                    .catch(() => {
                        Swal.fire('Greška!', 'Greška pri čitanju podataka. Pokušajte ponovo.', 'error');
                    });
            },

            deleteItem(id) {
                Swal.fire({
                    title: 'Jeste li sigurni?',
                    text: "Ova radnja se ne može poništiti!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`${apiUrl}/${id}`)
                            .then(() => {
                                this.fetchData(this.page);  // Re-fetch data after deletion
                                Swal.fire('Obrisano!', 'Stavka je obrisana.', 'success');
                            })
                            .catch(() => {
                                Swal.fire('Greška!', 'Došlo je do greške. Pokušajte ponovo.', 'error');
                            });
                    }
                });
            },

            downloadPDF(orderId) {
                axios.get(`${apiUrl}/${orderId}/pdf`, { responseType: 'blob' })
                    .then(response => {
                        const blob = new Blob([response.data], { type: 'application/pdf' });
                        const objectURL = window.URL.createObjectURL(blob);
                        const downloadLink = document.createElement('a');
                        downloadLink.href = objectURL;
                        downloadLink.download = `order_${orderId}.pdf`;
                        downloadLink.click();
                        window.URL.revokeObjectURL(objectURL);
                    })
                    .catch(() => {
                        Swal.fire('Greška!', 'Došlo je do problema pri generisanju PDF-a.', 'error');
                    });
            },

            inProgress(orderId) {
                Swal.fire({
                    title: 'Prebaciti narudžbu u obradu?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Da',
                    cancelButtonText: 'Ne'
                }).then((result) => {
                    if (result.isConfirmed) {
            axios.put(`${apiUrl}/${orderId}/u%20obradi`, { status: 'u obradi' })
                .then(response => {  // Fixed the incorrect response handler
                    if (response.status === 200 && response.data.message) {
                        Swal.fire('Odobreno!', response.data.message, 'success');
                        this.fetchData(this.page); // Refresh the data
                    } else {
                        Swal.fire({
                            title: 'Greška!',
                            text: response.data.message,
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    let errorMessage = 'Došlo je do greške. Pokušajte ponovo.';
                    
                    if (error.response && error.response.data) {
                        errorMessage = error.response.data.error || error.response.data.message || errorMessage;
                    }

                    Swal.fire('Greška!', errorMessage, 'error');
                });
        }
    });
},
            approveOrder(orderId) {
    Swal.fire({
        title: 'Jeste li sigurni?',
        text: "Ovim označavate narudžbu poslanom i obavještavate korisnika da je narudžba poslana!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Da',
        cancelButtonText: 'Ne'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.put(`${apiUrl}/${orderId}/poslano`, { status: 'poslano' })
                .then(response => {  // Fixed the incorrect response handler
                    if (response.status === 200 && response.data.message) {
                        Swal.fire('Odobreno!', response.data.message, 'success');
                        this.fetchData(this.page); // Refresh the data
                    } else {
                        Swal.fire({
                            title: 'Greška!',
                            text: response.data.message,
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    let errorMessage = 'Došlo je do greške. Pokušajte ponovo.';
                    
                    if (error.response && error.response.data) {
                        errorMessage = error.response.data.error || error.response.data.message || errorMessage;
                    }

                    Swal.fire('Greška!', errorMessage, 'error');
                });
        }
    });
},

        canceledOrder(orderId) {
    Swal.fire({
        title: 'Jeste li sigurni?',
        text: "Ovim označujete narudžbu kao otkazanu",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Da',
        cancelButtonText: 'Ne',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with the cancellation (make the API request)
            axios.put(`${apiUrl}/${orderId}/otkazano`, { status: 'otkazano' })
                .then(response => {
                    if (response.status === 200 && response.data.message) {
                        Swal.fire('Otkazano!', response.data.message, 'success');
                        this.fetchData(this.page); // Refresh the data
                    } else {
                        Swal.fire({
                            title: 'Greška!',
                            text: response.data.message,
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    let errorMessage = 'Došlo je do greške. Pokušajte ponovo.';

if (error.response && error.response.data) {
    errorMessage = error.response.data.error || error.response.data.message || errorMessage;
}

                    Swal.fire('Greška!', errorMessage, 'error');
                });
        }
    });
}


        };
    }
</script>
