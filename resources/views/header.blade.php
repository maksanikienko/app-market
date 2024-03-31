<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('index') }}">Home</a>
        </div>
        <div class="collapse navbar-collapse " id="navbar-collapse">
            <ul class="nav navbar-nav ">
                <li class="active"><a href="{{ route('index') }}">Items</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                <li ><a href="{{ route('basket') }}">Cart</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>

                @endguest

                @auth
                        @admin
                        <li><a href="{{ route('home') }}">Admin</a></li>
                    @else
                        <li><a>Hello {{Auth::user()->name}}</a></li>
                        <li><a href="{{ route('user.orders.index') }}">My Orders</a></li>
                        @endadmin
                    <li><a href="{{ route('get-logout') }}">Logout</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
