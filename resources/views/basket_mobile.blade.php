@extends('layouts.base')

@section('title', 'Cart')

@section('content')

<div class="mobile-cart">
    <div class="mobile-title"><div class="text-wrapper">Cart</div></div>
    <div class="frame">

        @foreach($order->products as $product)
        <div class="div">
            <div class="ui-image"><img class="replace" src="{{ Vite::asset('storage/app/public/' . $product->image) }}" /></div>
            <div class="frame-2">
                <div class="frame-3">
                    <div class="mobile-subtitle">
                        <p class="text-wrapper-2">{{ $product->name }}</p>
                        <div class="frame-4">
                            <div class="text">
                                <div class="text-wrapper-3">ID:{{ $product->code }}</div>
                                <div class="rectangle"></div>
                                <div class="in-stock-wrapper"><div class="in-stock">In stock</div></div>
                            </div>
                            <div class="text"><div class="text-wrapper-3">{{ $product->price }}</div></div>
                        </div>
                    </div>
                    <form action="{{ route('basket-remove', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="UI-button">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="24" rx="4" fill="#FF4848" fill-opacity="0.08"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.2426 7.75735C16.0474 7.56209 15.7308 7.56209 15.5355 7.75735L12 11.2929L8.46447 7.75735C8.2692 7.56209 7.95262 7.56209 7.75736 7.75735C7.5621 7.95261 7.5621 8.26919 7.75736 8.46446L11.2929 12L7.75736 15.5355C7.5621 15.7308 7.5621 16.0474 7.75736 16.2426C7.95262 16.4379 8.2692 16.4379 8.46447 16.2426L12 12.7071L15.5355 16.2426C15.7308 16.4379 16.0474 16.4379 16.2426 16.2426C16.4379 16.0474 16.4379 15.7308 16.2426 15.5355L12.7071 12L16.2426 8.46446C16.4379 8.26919 16.4379 7.95261 16.2426 7.75735Z" fill="#FF4848"/>
                                </svg>
                        </button>
                    </form>
                </div>


                <div class="frame-5">
                    <div class="UI-counter">
                        <form action="{{ route('basket-add', $product) }}" method="POST">
                            <button type="submit" class="UI-button">
                                <div class="div-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" rx="4" fill="#D9D9D9" fill-opacity="0.24"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6C11.7239 6 11.5 6.22386 11.5 6.5V11.5H6.5C6.22386 11.5 6 11.7239 6 12C6 12.2761 6.22386 12.5 6.5 12.5H11.5V17.5C11.5 17.7761 11.7239 18 12 18C12.2761 18 12.5 17.7761 12.5 17.5V12.5H17.5C17.7761 12.5 18 12.2761 18 12C18 11.7239 17.7761 11.5 17.5 11.5H12.5V6.5C12.5 6.22386 12.2761 6 12 6Z" fill="black"/>
                                    </svg>
                                </div>
                            </button>
                            @csrf
                        </form>
                        <div class="counter"><div class="text-wrapper-4">{{ $product->pivot->count }}</div></div>
                        <form action="{{ route('basket-remove', $product) }}" method="POST">
                            <button type="submit" class="UI-button">
                                <div class="div-3"><div class="rectangle-2"></div></div>
                            </button>
                            @csrf
                        </form>
                    </div>
                    <div class="text-wrapper-5">${{ $product->getPriceForCount() }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>



    <div class="frame-7">
        <div class="frame-8">
            <div class="fonts-title"><div class="text-wrapper-6">Total</div></div>
            <div class="frame-9">
                <div class="frame-10">
                    <div class="text-wrapper-7">Subtotal</div>
                    <div class="text-wrapper-8">{{ $order->getFullPrice() }}</div>
                </div>
                <div class="frame-10">
                    <div class="text-wrapper-7">Estimated Shipping</div>
                    <div class="text-wrapper-8">Free</div>
                </div>
            </div>
            <div class="rectangle-3"></div>
            <div class="frame-10">
                <div class="text-wrapper-9">Order Total</div>
                <div class="text-wrapper-10">${{ $order->getFullPrice() }}</div>
            </div>
            <div class="bottom">
                <div class="frame-11"><div class="text-wrapper-11">
                        <a class="check-out-btn" href="{{ route('basket-place') }}">Check out</a>
                    </div></div>
                <div class="text-wrapper-12">
                    <a class="btn text-wrapper-14" href="{{ route('index') }}">Continue Shopping</a>
                </div>
            </div>
        </div>
        <div class="frame-9">
            <div class="text-wrapper-13">Payment methods</div>
            <img class="image" src="{{ Vite::asset('storage/app/public/payments_methods.png' ) }}" />
        </div>
    </div>
</div>
@endsection
