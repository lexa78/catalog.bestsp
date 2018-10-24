@extends('main')
@section('left-side')
    <ul>
        @foreach($rootCategories as $rootCategory)
            <li><a href="{{ route('categoryProducts', ['category_id'=> $rootCategory->id]) }}">{{ $rootCategory->title }}</a></li>
            @if($rootCategory->Category->count() > 0)
                @include('products._treeChildMenu', ['categories' => $rootCategory->Category])
            @endif
        @endforeach
    </ul>
@stop
@section('content')
    <div style="width: 70%; margin: 10px auto;">
        <form action="{{ route('search') }}">
            <label for="search">Поиск</label>
            <input id="search" type="text" placeholder="текст из названия или описания" style="width: 70%" name="search_text">
            <input type="submit" value="Найти">
            {!! csrf_field() !!}
        </form>
    </div>
    @include('products._prod_list')
@stop