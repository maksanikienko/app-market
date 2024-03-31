@extends('layouts.base')
@section('content')

            <h1>{{$product->name}}</h1>
            <p>
                <a href="{{route('category', $product->category->code)}} " class="btn btn-default "
                   role="button">{{$product->category->name}}</a>
            </p>

            <img src=" {{ Vite::asset('storage/app/public/' . $product->image) }} ">
            <h3 class="text-muted">{{$product->description}}</h3>
            <p>Price: <b> {{$product->price}}$</b></p>
            <form action="{{ route('basket-add', $product) }}" method="POST">
                <button type="submit" class="btn btn-success" role="button">Add to cart</button>
                @csrf
            </form>
@endsection
