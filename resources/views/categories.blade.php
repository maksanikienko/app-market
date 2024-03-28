@extends('layouts.base')
@section('content')


        @foreach($categories as $category)
            <a href="{{route('category', $category->code)}}">
                <div class="panel">
                        <img src=" {{ Vite::asset('public/storage/' . $category->image) }} ">
                        <h2>{{$category->name}}</h2>
                    <p>
                        {{$category->description}}
                    </p>
                </div>
            </a>

        @endforeach

@endsection
