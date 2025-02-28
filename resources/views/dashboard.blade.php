<x-app-layout>
    
<div class="flex h-screen">
    @include('dashboard.sidebar')
    <div class="flex-1 p-6">
    <!-- Tabs -->
    <div class="flex flex-col">
            <div>
            @livewire('cart')

        </div> <!-- Closing max-w-7xl -->
    </div> <!-- Closing py-12 -->
</div> <!-- Closing tab-pane -->
</div>
</x-app-layout>