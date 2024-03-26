@extends('layouts.base')
@section('content')
            <h1>All Products</h1>

            <div class="row">
                @foreach($products as $product)
                    @include('layouts.card', compact('product'))
                @endforeach
            </div>
@endsection
