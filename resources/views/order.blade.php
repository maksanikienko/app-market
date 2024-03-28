@extends('layouts.base')

@section('title', 'Checkout')

@section('content')
        <h1>Confirm your order</h1>
        <div class="container">
            <div class="row justify-content-center">
                <p>Full order price: <b>{{ $order->getFullPrice() }}$</b></p>
                <form action=" {{ route('basket-confirm') }}" method="POST">
                    <div>
                        <p>Please provide your name and phone number so that our manager can contact you:</p>

                        <div class="container">
                            <div class="form-group">
                                <label for="name" class="control-label col-lg-offset-3 col-lg-2">Name: </label>
                                <div class="col-lg-4">
                                    <input type="text" name="name" id="name" value="" class="form-control">
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <label for="phone" class="control-label col-lg-offset-3 col-lg-2">Phone number: </label>
                                <div class="col-lg-4">
                                    <input type="text" name="phone" id="phone" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>
                        @csrf
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>
                </form>
            </div>
        </div>
@endsection
