@extends('main')
@section('content')
    <h1>{{ $categoryTitle }}</h1>
    @if( ! count($products))
        <h3>В этой категории товаров нет</h3>
    @else
        @include('products._prod_list')
    @endif
    <a href="{{ route('main') }}">На главную</a>
@stop