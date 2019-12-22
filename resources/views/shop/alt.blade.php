@extends('layouts.template')

@section('title', 'Shop')

@section('main')
    <h1>Shop - alternative listing</h1>
    @foreach($genres as $genre)
        <h2>{{ $genre->name}}</h2>
        <ul>
            @foreach($records as $record)
                @if ($record->genre_id == $genre->id)
                    <li>
                        <a href="/shop/{{$record->id}}" class="record click">{{ $record->artist }}
                            - {{ $record->title }}</a> | Price: € {{ $record->price }} | Stock: {{ $record->stock }}
                    </li>
                @endif
            @endforeach
        </ul>
    @endforeach
@endsection

@section('script_after')

@endsection
