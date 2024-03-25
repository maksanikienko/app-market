@extends('layouts.base')

@section('title', 'Корзина')

@section('content')
        <h1>Cart</h1>
        <p>Place an order</p>
        <div class="panel">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Count</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                </thead>

                <tbody>
                @foreach($order->products as $product)
                    <tr>
                        <td>
                            <a href="{{ route('product', [$product->category->code, $product->code]) }}">
                                <img height="56px" src="{{ Vite::asset('public/storage/' . $product->image) }} ">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td><span class="badge">{{ $product->pivot->count }}</span>
                            <div class="btn-group form-inline">

                                <form action="{{ route('basket-remove', $product) }}" method="POST">
                                    <button type="submit" class="btn btn-danger"
                                            href=""><span
                                            class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                                    @csrf
                                </form>

                                <form action="{{ route('basket-add', $product) }}" method="POST">
                                    <button type="submit" class="btn btn-success"
                                            href=""><span
                                            class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                    @csrf
                                </form>
                            </div>
                        </td>
                        <td>{{ $product->price }} руб.</td>
                        <td>{{ $product->getPriceForCount() }} руб.</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3">Total:</td>
                    <td>{{ $order->getFullPrice() }} $</td>
                </tr>
                </tbody>
            </table>
            <br>
            <div class="btn-group pull-right" role="group">
                <a type="button" class="btn btn-success" href=" {{ route('basket-place') }}">Submit</a>
            </div>
        </div>
@endsection
