<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!--  <a href="\add-brand" class="m-2 p-2 text-xl">Dodaj brend</a> -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-2">
                    @foreach($categories as $category)
                    <p class="p-2">{{$category->name}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>