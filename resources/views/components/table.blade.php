<!-- resources/views/components/table.blade.php -->
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
                <td class="px-4 py-2 truncate max-w-[150px] overflow-hidden text-ellipsis" title="{{ is_object($item->$column) ? $item->$column->name : $item->$column }}">
    @if(is_object($item->$column))
        {{ Str::limit($item->$column->name ?? 'N/A', 20, '...') }} 
    @else
        {{ Str::limit($item->$column, 20, '...') }}
    @endif
</td>


                                    @endforeach
                                    <td class="px-4 py-2">
                                        @foreach($actions as $action)
                                            <form action="{{ route(  $routePrefix.'.'.$action['route'], $item) }}" method="GET" style="display: inline;">
                                            @csrf
                        @if($action['route'] === 'destroy')
                            @method('POST')
                        @endif                                              
                            <button type="submit" class="px-2 py-2 text-white bg-{{ $action['color'] }}-500 rounded-full w-10 h-10 hover:bg-{{ $action['color'] }}-600">
                            {!! $action['label'] !!}
                            </button>
                        </form>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
