@extends('layouts.template')

@section('main')
<h1>Records</h1>

<ul>
    @foreach ($records as $record)
        <li>{!! $record !!}</li>
    @endforeach
</ul>

@endsection
