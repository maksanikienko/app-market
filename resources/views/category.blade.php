@extends('layouts.base')

@section('title', 'Категория ' . $category->name)

@section('content')
        <h1>{{$category->name}}</h1>
        <p>{{ $category->description }}</p>
        <p>Total: {{$category->products->count()}} items</p>

        <div class="row">
            @foreach($category->products as $product)
                @include('layouts.card', compact('product'))
            @endforeach
        </div>
@endsection
