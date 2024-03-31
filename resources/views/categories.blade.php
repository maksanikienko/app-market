@extends('layouts.base')
@section('content')


        @foreach($categories as $category)
            <a href="{{route('category', $category->code)}}">
                <div class="panel-categories">
                        <img src=" {{ Vite::asset('storage/app/public/' . $category->image) }} ">
                        <h2>{{$category->name}}</h2>
                    <p class="text-muted">
                        {{$category->description}}
                    </p>
                </div>
            </a>

        @endforeach

@endsection
