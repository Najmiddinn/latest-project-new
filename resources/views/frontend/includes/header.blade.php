<header>
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="header-logo">
                        <a href="{{route('site.index')}}" class="logo" style="font-size: 24px; color: #fff;">
                            <img src="{{asset('/assets-frontend/img/logo_xoja_buxoriy.png')}}" alt="" class="logo-img"> <span>Xojabuxoriy Kutubxona</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-3 clearfix">
                    <div class="header-ctn">
                        <div class="menu-toggle">
                            <a href="#">
                                <i class="fa fa-bars"></i>
                                <span>Menu</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="header-search">
                        <form action="{{route('site.search')}}" method="GET" class="my-form">
                            <input class="input" placeholder="Search here" name="q" required>
                            <button class="search-btn" style="border-radius:0">Izlash</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="header-search">
                        <div>
                            {{-- <span style="color: #fff"> {{ Auth::user()->name }} </span> --}}
                           <div class="btn btn-success pull-right" style="text-align: right;font-size:18px"> 
                                <a href="{{ route('logout') }}" 
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();" class="" style="color: #fff">
                                <i class="fas fa-sign-out-alt mr-2"></i> 
                                {{ __('Logout') }}
                            </a>
                            <span class="float-right text-muted text-sm"> 
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            </span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<nav id="navigation">
    <div class="container">
        <div id="responsive-nav">
            <ul class="main-nav nav navbar-nav">
                @if (!empty($menu))
                    @foreach ($menu as $item)
                        <li class="nav-item"><a href="{{route('site.index')}}{{$item->url}}">{{$item->name}}</a></li> 
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</nav>