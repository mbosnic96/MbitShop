<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="\add-product" class="m-2 p-2 text-xl">Dodaj Proizvod</a>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-2">
                    @foreach($products as $product)
                    <p class="p-2">{{$product->name}} - {{$product->price}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>