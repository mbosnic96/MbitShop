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


                        <div class="container mx-auto p-6"
                            x-data="tableData('/api/dashboard/categories', ['id', 'name','slug', 'parent_id', 'position'], 'categories-edit', 'editCategoryForm')">
                            <x-table></x-table>
                        </div>
                    </div> <!-- Closing max-w-7xl -->
                </div> <!-- Closing py-12 -->
            </div> <!-- Closing tab-pane -->
        </div>
</x-app-layout>