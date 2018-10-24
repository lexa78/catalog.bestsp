<ul>
    @foreach($categories as $category)
        <li><a href="{{ route('categoryProducts', ['category_id'=> $category->id]) }}">{{ $category->title }}</a></li>
        @if($category->Category->count() > 0)
            @include('products._treeChildMenu', ['categories' => $category->Category])
        @endif
    @endforeach
</ul>