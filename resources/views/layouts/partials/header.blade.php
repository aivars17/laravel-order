@inject('cartService', "App\Services\CartService")
<!-- Top Bar -->
<div class="row">

 <div class="top-bar">
    <ul>
        <li><i class="fa fa-angle-right"></i>  <a href="{{ route('logout') }}">Logout</a></li>
        <li><i class="fa fa-user"></i>
            <a href="{{ route('profile') }}">{{ Auth::user()->name }}</a>
        </li>
    </ul>
    <ul>
       <li><i class="fa fa-phone"></i>  <a href="tel:{{$phone}}">{{$phone}}</a></li>
       <li><i class="fa fa-envelope"></i>  <a href="mailto:{{$email}}">{{$email}}</a></li>
   </ul>
</div>
</div>
<!-- Header -->
<div class="row">
    <div class="logo pl-2">
       <a href="{{ route('home') }}"><img src="{{asset('images/logo2.png')}}"></a>
   </div>
   <div class="cart-menu-mobile">
       <span class="cart-menu-icon-mobile">
          <a href="{{ route('order.index') }}"><i class="fa fa-cart-arrow-down"></i></a>
      </span>
  </div>
    <div class="nav d-flex justify-content-end ">
        <nav class="navbar navbar-expand-lg navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @php $name = Route::currentRouteName(); @endphp
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link btn {{ ($name == 'home' ? 'btn-outline-danger' : 'btn-danger') }}" href="{{ route('home') }}">Home <span
                                    class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger" href="{{route('order.orders')}}">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger" href="{{ route('profile') }}">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn {{ ($name == 'pages.contacts' ? 'btn-outline-danger' : 'btn-danger') }}" href="{{ route('pages.contacts') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="cart-menu">
        <a href="{{ route('order.index') }}">
            <span class="cart-menu-inside">
                <span class="cart-menu-icon">
                    <i class="fa fa-cart-arrow-down"></i>
                </span>
                <span class="cart-menu-price totalQuantityTop">Items: {{ $cartService->getUserOrderTotalQuantity()}} / </span>
                <span class="cart-menu-price totalPriceTop">  € {{ number_format($cartService->getUserOrderTotalPrice(), 2, '.', '') }}</span>
    </a>
</div>
</div>
<!-- Slider -->
<div id="search" class="row justify-content-center">
    <div class="slider">

        @foreach($offers as $offer)
            <div>
                <a href="{{ route('special.show', $offer->id) }}"><img
                            src="{{asset('storage/image/'.$offer->filename)}}"/></a>
            </div>
        @endforeach
        <div>
            <img src="{{asset('images/slides/4.jpg')}}"/>
        </div>
    </div>
    <div class="slider-arrows-left left"><i class="fa fa-arrow-circle-left"></i></div>
    <div class="slider-arrows-right right"><i class="fa fa-arrow-circle-right right"></i></div>
</div>
<!-- Search -->
<div class="row">
    <div class="col-12 d-flex justify-content-center pt-3 pb-3 search-bar-back">
        <form class="form-inline justify-content-center" action="{{ route('home.sort') }}" method="GET">
            @if (isset($query))
            <input name="query" id="productsSearch" class="form-control mr-sm-2 search-inputas" type="search"
            placeholder="Search" aria-label="Search" value="{{ old('name', $query) }}">
            @else
            <input name="query" id="productsSearch" class="form-control mr-sm-2 search-inputas" type="search"
            placeholder="Search" aria-label="Search" value="{{ old('name') }}">
            @endif
            <button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</div>