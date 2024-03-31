@extends('auth.layouts.base')

@section('title', 'Orders')

@section('content')
    <div class="col-md-12">
        <h1>Orders</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Name
                </th>
                <th>
                    Phone
                </th>
                <th>
                    Order date
                </th>
                <th>
                    Total
                </th>
                <th>
                    Actions
                </th>
            </tr>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id}}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->created_at->format('H:i d/m/Y') }}</td>
                    <td>{{ $order->getFullPrice() }} $</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a class="btn btn-success" type="button"

                               @admin
                               href="{{ route('orders.show', $order) }}"
                               @else
                               href=" {{route('user.orders.show', $order)}}"
                                @endadmin

                            >Open</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
