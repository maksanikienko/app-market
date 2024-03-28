<a href="{{ route('product', [$product->category->code, $product->code, $product]) }}">
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
            <img src="{{ Vite::asset('public/storage/' . $product->image) }}">

            <div class="caption">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->price }} $</p>
                <p>
                <form action="{{ route('basket-add', $product) }}" method="POST">
                    <button type="submit" class="btn btn-primary" role="button">Add to cart</button>
                    @csrf
                </form>
                </p>
            </div>
        </div>
    </div>
</a>


