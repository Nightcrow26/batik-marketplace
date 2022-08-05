<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Batik Ciprat Langitan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="{{ asset('shopper') }}/fonts/icomoon/style.css">
    
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('shopper') }}/css/owl.theme.default.min.css">


    <link rel="stylesheet" href="{{ asset('shopper') }}/css/aos.css">

    <link rel="stylesheet" href="{{ asset('shopper') }}/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
        @yield('css')
  </head>
  <body>
      <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <ul>
              @if (Route::has('login'))
                    @auth
                        
                          <div class="dropdown ">
                            <a class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style= "color:white">
                            <span class="icon icon-person" style= "color:white"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                                <a class="dropdown-item" href="{{ route('user.alamat') }}">Setting Alamat</a>
                                <a class="dropdown-item" href="#">
                                
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                  onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                  <i class="mdi mdi-logout mr-2 text-primary"></i> Logout 
                              </a>

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>
                            </div>&nbsp&nbsp&nbsp&nbsp&nbsp
                          <?php
                            $user_id = \Auth::user()->id;
                            $total_keranjang = \DB::table('keranjang')
                            ->select(DB::raw('count(id) as jumlah'))
                            ->where('user_id',$user_id)
                            ->first();
                          ?>
                            <a href="{{ route('user.keranjang') }}" class="site-cart">
                            <span class="icon icon-add_shopping_cart" style= "color:white"></span>
                            
                            <span style= "color:white" class="count">{{ $total_keranjang->jumlah }}</span>
                            </a>&nbsp&nbsp&nbsp&nbsp&nbsp
                        <?php
                            $user_id = \Auth::user()->id;
                            $total_order = \DB::table('order')
                            ->select(DB::raw('count(id) as jumlah'))
                            ->where('user_id',$user_id)
                            ->where('status_order_id','!=',5)
                            ->where('status_order_id','!=',6)
                            ->first();
                          ?>
                        <a href="{{ route('user.order') }}" class="site-cart">
                            <span class="icon icon-shopping_cart" style= "color:white"></span>
                            <span style= "color:white" class="count">{{ $total_order->jumlah }} </span>
                            </a>
                    @else 
                    <div class="dropdown">
                            <a class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style= "color:white">
                            <span class="icon icon-person" style= "color:white"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                                @if (Route::has('register'))
                                  <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                                @endif
                            </div>
                            </div>
                    @endauth
                </div>
            @endif
            <li class="d-inline-block d-md-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
            </div>
            </ul>
      <div></div>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item {{ Request::path() === '/' ? '' : '' }}">
            <a class="nav-link" href="{{ route('home') }}" style= "color:white"><strong>Beranda</strong></a>
          </li>
          <li class="nav-item {{ Request::path() === 'produk' ? '' : '' }}">
            <a class="nav-link" href="{{ route('user.produk') }}" style= "color:white"><strong>Produk</strong></a>
          </li>
          <li class="nav-item {{ Request::path() === '/' ? '' : '' }}">
            <a class="nav-link" href="{{ url('tentang') }}" style= "color:white"><strong>About Us</strong></a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Profil</a>
          </li> -->
          <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-right">
              <form action="{{ route('user.produk.cari') }}" method="get" class="site-block-top-search" >
                @csrf
                <span class="icon icon-search2"></span>
                <input type="text" class="form-control border-0" name="cari" placeholder="Cari">
              </form>
          </div>
        </ul>
      </div>
    </div>
  </nav>
  <br><br>    
  <!--navigation-->

    @yield('content')
    
    <footer>
		<div class="footer-bottom">
			<div class="container">
				<div class="row d-flex">
					<p class="col-lg-12 footer-text text-center">
						<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            <font color="black"> Copyright &copy;<script>document.write(new Date().getFullYear());</script> <font color="black"><strong>Batik Ciprat Langitan Simbatan</strong>
        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
				</div>
			</div>
		</div>
	</footer>
	<!--================ End footer Area  =================-->
          
        </div>
      </div>
    </footer>
  </div>

  <script src="{{ asset('shopper') }}/js/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
  <script src="{{ asset('shopper') }}/js/jquery-ui.js"></script>
  <script src="{{ asset('shopper') }}/js/popper.min.js"></script>
  <script src="{{ asset('shopper') }}/js/bootstrap.min.js"></script>
  <script src="{{ asset('shopper') }}/js/owl.carousel.min.js"></script>
  <script src="{{ asset('shopper') }}/js/jquery.magnific-popup.min.js"></script>
  <script src="{{ asset('shopper') }}/js/aos.js"></script>

  <script src="{{ asset('shopper') }}/js/main.js"></script>
    @yield('js')
  </body>
</html>