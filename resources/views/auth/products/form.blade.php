@extends('auth.layouts.base')

@isset($product)
    @section('title', 'Edit Product ' . $product->name)
@else
    @section('title', 'Create Product')
@endisset

@section('content')
    <div class="col-md-12">
        @isset($product)
            <h1>Edit Product <b>{{ $product->name }}</b></h1>
        @else
            <h1>Add New Product</h1>
        @endisset
        <form method="POST" enctype="multipart/form-data"
              @isset($product)
                  action="{{ route('products.update', $product) }}"
              @else
                  action="{{ route('products.store') }}"
            @endisset
        >
                @isset($product)
                    @method('PUT')
                @endisset
                @csrf
    <div class="row">
        <div class="col-md-6">

                    <div class="input-group">
                    <label for="code" class="col-form-label">Code: </label>
                        <!-- Display error msg from ProductRequest -->
                        @error('code')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" name="code" id="code"
                               value="@isset($product){{ $product->code }}@endisset">
                    </div>

                <br>

                <div class="input-group">
                    <label for="name" class=" col-form-label">Name: </label>
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                        <input type="text" class="form-control" name="name" id="name"
                               value="@isset($product){{ $product->name }}@endisset">
                </div>

                <br>

                <div class="input-group">
                    <label for="category_id" class="col-form-label">Category: </label>
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @isset($product)
                                            @if($product->category_id == $category->id)
                                                selected
                                    @endif
                                    @endisset
                                >{{ $category->name }}</option>
                            @endforeach
                        </select>
                </div>

                <br>

                <div class="input-group">
                    <label for="description" class="col-form-label">Description: </label>
                    @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
								<textarea name="description" id="description" cols="72"
                                          rows="7">@isset($product){{ $product->description }}@endisset</textarea>
                </div>

                <br>

                <div class="input-group ">
                    <label for="image" class=" col-form-label">Image: </label>
                    @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                        <label class="btn btn-default btn-file">
                            Upload <input type="file" style="display: none;" name="image" id="image">
                        </label>
                </div>

                <br>

                <div class="input-group ">
                    <label for="price" class=" col-form-label ">Price: </label>
                    @error('price')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                        <input type="text" class="form-control " name="price" id="price"
                               value="@isset($product){{ $product->price }}@endisset">
                </div>
        </div>
    </div>

                        <button class="btn btn-success ">Save</button>
        </form>
    </div>
@endsection
