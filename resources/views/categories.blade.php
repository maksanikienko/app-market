@extends('layouts.base')
@section('content')


        @foreach($categories as $category)
            <div class="panel">
                <a href="{{route('category', $category->code)}}">
                    <img src=" {{ Vite::asset('public/storage/' . $category->image) }} ">
                    <h2>{{$category->name}}</h2>
                </a>
                <p>
                    {{$category->description}}
                </p>
            </div>
        @endforeach

@endsection
