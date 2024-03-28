<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('index') }}">Home Page</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li  class="active" ><a href="{{ route('index') }}">All items</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                </li>
                <li ><a href="{{ route('basket') }}">Cart</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ route('login') }}">Admin</a></li>
                @endguest

                @auth
                    <li><a>Hello {{Auth::user()->name}}</a></li>
                    <li><a href="{{ route('home') }}">Admin</a></li>
                    <li><a href="{{ route('get-logout') }}">Logout</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
