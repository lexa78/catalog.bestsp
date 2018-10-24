@extends('main')
@section('content')
    @if( ! count($products))
        <h1>Товаров не найдено</h1>
    @else
        @include('products._prod_list')
    @endif
    <a href="{{ route('main') }}">На главную</a>
@stop