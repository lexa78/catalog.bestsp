@foreach($products as $product)
    <div style="width: 75%; border: 1px solid black; margin: 10px auto;">
        <div><a href="{{$product->url}}" target="_blank">{{$product->title}}</a></div>
        <div><a href="{{$product->url}}" target="_blank"><img src="{{$product->image}}"></a></div>
        <div>{{$product->description}}</div>
        <div><b>Цена: </b>{{$product->price}}</div>
        <div><b>В наличии: </b>{{$product->amount}}</div>
    </div>
@endforeach