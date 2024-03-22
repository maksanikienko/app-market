@extends('base')

@section('title', 'Категория ' . $category->name)

@section('content')
    <div class="starter-template">
        <h1>{{$category->name}}</h1>
            <p>{{ $category->description }}</p>
            <p>Total: {{$category->products->count()}}</p>

            <div class="row">
                @foreach($category->products as $product)
                    @include('card', compact('product'))
                @endforeach
            </div>
    </div>
@endsection
