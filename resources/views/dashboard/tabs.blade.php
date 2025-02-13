<div class="flex-1 p-6">
    <!-- Tabs -->
    <div class="flex flex-col">

        <!-- Tab Content -->
        <div class="tab-content">
            <div class="tab-pane" id="tab-dashboard">
                <h2 class="text-2xl">Dashboard Content</h2>
            </div>
            @if (session()->has('message'))
        <div class="text-green-600" id="session-message">{{ session('message') }}</div>
    @endif
            @include('products.index')
            @include('brands.index')
            @include('categories.index')
            @include('users.index')
        </div> <!-- Closing tab-content -->
    </div> <!-- Closing flex flex-col -->
</div> <!-- Closing flex-1 p-6 -->

