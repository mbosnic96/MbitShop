@props(['data', 'columns', 'routePrefix', 'actions'])

<table class="min-w-full table-fixed">
    <thead>
        <tr class="bg-gray-200">
            @foreach($columns as $column)
                <th class="px-4 py-2 text-left">{{ ucwords(str_replace('_', ' ', $column)) }}</th>
            @endforeach
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
            <tr class="border-b">
                @foreach($columns as $column)
                    <td class="px-4 py-2 truncate max-w-[150px] overflow-hidden text-ellipsis"
                        title="{{ is_object($item->$column) ? $item->$column->name : $item->$column }}">
                        @if(is_object($item->$column))
                            {{ Str::limit($item->$column->name ?? 'N/A', 20, '...') }}
                        @else
                            {{ Str::limit($item->$column, 20, '...') }}
                        @endif
                    </td>
                @endforeach
                <td class="px-4 py-2 text-center">
                    @foreach($actions as $action)
                        @if($action['route'] === 'edit')
                            <button id="{{ $item->id }}"
                                class="open-modal px-2 py-2 text-white bg-yellow-500 rounded-full w-10 h-10 hover:bg-yellow-600"
                                data-modal-id="{{ $routePrefix }}-edit" data-id="{{ $item->id }}" data-all='@json($item)'>
                                <i class="fa fa-pencil"></i>
                            </button>





                        @else
                            <form action="{{ route($routePrefix . '.' . $action['route'], $item) }}"
                                method="{{ $action['route'] === 'destroy' ? 'POST' : 'GET' }}" style="display: inline;">

                                @csrf
                                @if($action['route'] === 'destroy')
                                    @method('DELETE')
                                @endif

                                <button type="submit"
                                    class="px-2 py-2 text-white bg-{{ $action['color'] }}-500 rounded-full w-10 h-10 hover:bg-{{ $action['color'] }}-600">
                                    {!! $action['label'] !!}
                                </button>
                            </form>
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- For debugging --}}
@if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4">
        <!-- Pagination Links -->
        {{ $data->links() }}
    </div>
@else
    <p>❌ No pagination available</p>
@endif