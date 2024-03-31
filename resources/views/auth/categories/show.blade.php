@extends('auth.layouts.base')

@section('title', 'Category ' . $category->name)

@section('content')
    <div class="col-md-12">
        <h1>Category Appliance</h1>
        <table class="table">
            <tbody>
            <tr>
                <th>
                    Field
                </th>
                <th>
                    Value
                </th>
            </tr>
            <tr>
                <td>ID</td>
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <td>Code</td>
                <td>{{ $category->code }}</td>
            </tr>
            <tr>
                <td>Name</td>
                <td>{{ $category->name }}</td>
            </tr>
            <tr>
                <td>Description</td>
                <td>{{ $category->description }}</td>
            </tr>
            <tr>
                <td>Image</td>
                <td>
                    <img src=" {{ Vite::asset('storage/app/public/'. $category->image) }} " height="240px">
                </td>
            </tr>
            <tr>
                <td>Total Products:</td>
                <td>{{ $category->products->count() }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
