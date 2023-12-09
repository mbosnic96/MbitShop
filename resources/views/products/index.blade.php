<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="\add-product" class="m-2 p-2 text-xl">Dodaj Proizvod</a>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
                    @foreach($products as $product)
                    <div class="flex space-x-6">
                        <div class="p-2 flex-1">
                            <p class="p-2">{{$product->name}} - {{$product->price}} - {{$product->image}}</p>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-end p-3">
                            <form action="{{route('delete-product')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$product->id}}">
                                <div class="p-2">
                                    <button class="ml-4 inline-flex items-center bg-red-700 border border-transparent font-semi-bold px-4 py-2 rounded-ml text-white">
                                        {{__('Obrisi')}}
                                    </button>
                                </div>
                            </form>

                            <form action="{{route('edit-product')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$product->id}}">
                                <div class="p-2">
                                    <button class="ml-4 inline-flex items-center bg-green-700 border border-transparent font-semi-bold px-4 py-2 rounded-ml text-white">
                                        {{__('Uredi')}}
                                    </button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                
            </div>
        </div>
    </div>
</x-app-layout>