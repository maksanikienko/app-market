<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('index') }}">Home Page</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li  class="active" ><a href="{{ route('index') }}">All products</a></li>
                <li><a href="{{ route('categories') }}">Categories</a></li>
                </li>
                <li ><a href="{{ route('basket') }}">Cart</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ route('login') }}">Admin Panel</a></li>
                @endguest

                @auth
                    <li><a>{{Auth::user()->name}}</a></li>
                    <li><a href="{{ route('home') }}">Admin Panel</a></li>
                    <li><a href="{{ route('get-logout') }}">Logout</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
