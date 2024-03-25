@extends('auth.layouts.base')

@isset($category)
    @section('title', 'Edit Category ' . $category->name)
@else
    @section('title', 'Create Category')
@endisset

@section('content')
    <div class="col-md-12">
        @isset($category)
            <h1>Edit Category <b>{{ $category->name }}</b></h1>
        @else
            <h1>Add New Category</h1>
        @endisset

        <form method="POST" enctype="multipart/form-data"
              @isset($category)
                  action="{{ route('categories.update', $category) }}"
              @else
                  action="{{ route('categories.store') }}"
            @endisset
        >
                @isset($category)
                    @method('PUT')
                @endisset
                @csrf

    <div class="row">
        <div class="col-md-6">

            <div class="input-group">
                <label for="code" class="col-form-label">Code: </label>
                <input type="text" class="form-control" name="code" id="code"
                        value="@isset($category){{ $category->code }}@endisset">
            </div>

        <br>

            <div class="input-group ">
                <label for="name" class="col-form-label">Name: </label>
                <input type="text" class="form-control" name="name" id="name"
                        value="@isset($category){{ $category->name }}@endisset">
            </div>

        <br>

            <div class="input-group ">
                <label for="description" class="col-form-label">Description: </label>
                <textarea name="description" id="description" cols="72"
                          rows="7">@isset($category){{ $category->description }}@endisset</textarea>
            </div>

        <br>

            <div class="input-group ">
                <label for="image" class="col-form-label">Image: </label>
                <label class="btn btn-default btn-file">
                    Download <input type="file" style="display: none;" name="image" id="image">
                </label>
            </div>

        </div>



    </div>
                    <button class="btn btn-success">Save</button>

        </form>
    </div>
@endsection
