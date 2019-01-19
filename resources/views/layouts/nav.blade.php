<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Cotontchad</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <!-- Fontastic Custom icon font-->
        <link rel="stylesheet" href="{{asset('css/fontastic.css')}}">
        <!-- Google fonts - Roboto -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
        <!-- jQuery Circle-->
        <link rel="stylesheet" href="{{asset('css/grasp_mobile_progress_circle-1.0.0.min.css')}}">
        <!-- Custom Scrollbar-->
        <link rel="stylesheet" href="{{asset('css/jquery.mCustomScrollbar.css')}}">
        <!-- theme stylesheet-->
        <link rel="stylesheet" href="{{asset('css/style.default.css')}}" id="theme-stylesheet">
        <!-- Custom stylesheet - for your changes-->
        <link rel="stylesheet" href="{{asset('css/custom.css')}}">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css" integrity="sha384-v2Tw72dyUXeU3y4aM2Y0tBJQkGfplr39mxZqlTBDUZAb9BGoC40+rdFCG0m10lXk" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" integrity="sha384-q3jl8XQu1OpdLgGFvNRnPdj5VIlCvgsDQTQB6owSOHWlAurxul7f+JpUOVdAiJ5P" crossorigin="anonymous">

        <!-- Favicon-->
        <link rel="shortcut icon" href="img/logo.png">
        <script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>


    </head>
    <body>
    <div id="app">
       @guest
         <div class="content-bg">
          @yield('content')
         </div>
        @else
        <audio id="Audio" >
            <source src="{{asset('sound/sound.mp3')}}"/>
        </audio>

            <!-- chat Navbar -->
        <nav class="chat-navbar">
           <chat :userid="{{Auth::user()->id}}"></chat>
        </nav>

        <div class="content">
            <header class="header-seperation">
                <nav class="navbar">
                    <div class="container-fluid">
                        <div class="navbar-holder d-flex align-items-center justify-content-between">
                            <div class="navbar-header">
                                <a id="toggle-btn-sep" href="#" class="menu-btn">
                                    <img src="img/barswhite.png">
                                </a>
                            </div>
                            <a href="index.html" class="title-site">
                                <span>COTON</span>
                                <strong class="text-primary">TCHAD</strong>
                            </a>
                            <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">

                                <li class="nav-item">
                                    <a id="toggle-btn-sep-right" href="#"><img src="img/msgwhite.png" style="width: 25px;"></a>
                                    <span class="icon-notify-msg" id="msg-icon"></span>

                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <header class="header">
                <nav class="navbar">
                    <div class="container-fluid">
                        <div class="navbar-holder d-flex align-items-center justify-content-between">
                            <div class="navbar-header">
                                <div class="navbar-bleft d-md-inline-block">
                                    <a href="index.html" class="navbar-brand">
                                        <div class="brand-text  d-none d-md-inline-block "><span>COTON</span><strong class="text-primary">TCHAD</strong>
                                        </div>
                                    </a>
                                </div>
                                <div class="navbar-bright d-md-inline-block">
                                    <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                                        <li class="nav-item dropdown">
                                            <a id="toggle-btn" href="#" class="menu-btn"><img src="img/bars.png"></a>
                                        </li>

                                        <li class="nav-item dropdown">
                                            <a href="">
                                                <img src="img/refresh.png">
                                            </a>

                                        </li>

                                        <li class="nav-item dropdown">
                                            <div class="navbar-search">
                                                <img src="img/search.png" class="img-search">
                                                <input type="text" class="inp-search" placeholder="Search..">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">


                                <li class="nav-item dropdown"><a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link link-user">
                                        <img src="{{asset('images/'.Auth::user()->image)}}" alt="person" class="img-user rounded-circle">
                                        <span class="nav-icon-online"></span></a>
                                    <ul  class="dropdown-menu mysetting-menu">
                                        <div class="arrow"></div>
                                        <li><a rel="nofollow" href="/profil" class="dropdown-item">My Account</a></li>
                                        <li><a rel="nofollow" href="#" class="dropdown-item">My Inbox</a></li>
                                        <li class="divider"></li>
                                        <li><a rel="nofollow" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                               class="dropdown-item"><i class="fa fa-power-off"></i>Log Out</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <span class="h-seperate"></span>
                                    <a id="toggle-btn-right" href="#">
                                        <img src="img/msg.png" style="width:25px">
                                        <span class="icon-notify-msg" id="icon-msg"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Side Navbar -->
            <nav class="side-navbar">
                <div class="side-navbar-wrapper">
                    <!-- Sidebar Header    -->
                    <div class="sidenav-header d-flex align-items-center justify-content-center">
                        <!-- User Info-->
                        <div class="sidenav-header-inner row">
                            <div class="col-xs-4 col-img">
                                <img src="{{asset('images/'.Auth::user()->image)}}" alt="person" class="img-fluid rounded-circle">
                                <span class="availability-bubble online"></span>
                            </div>
                            <div class="col-xs-8 col-content">
                                <h2 class="h5">{{ Auth::user()->nom }}</h2>
                                <span>{{Auth::user()->role}}</span>
                                <p class="profil-icon">
                                    <a href="/profil"><i class="fa fa-cog"></i></a>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i></a>
                                </p>
                            </div>
                        </div>
                        <!-- Small Brand information, appears on minimized sidebar-->
                        <div class="sidenav-header-logo"><a href="index.html" class="brand-small text-center"> <strong>B</strong><strong class="text-primary">D</strong></a></div>
                    </div>
                    <!-- Sidebar Navigation Menus-->
                    <div class="main-menu">
                        <h5 class="sidenav-heading">Main</h5>
                        <ul id="side-main-menu" class="side-menu list-unstyled">
                            <li><a href="/"><img src="img/accueil.png" class="img-listnav"><img src="img/accueilwhite.png" class="img-shrink">Accueil</a></li>
                            @if(Auth::user()->role == "Administrateur")

                                <li><a href="/users"><img src="img/guser.png" class="img-listnav"><img src="img/gwhite.png" class="img-shrink">Gestion utilisateur</a></li>
                                <li><a href="/saisons"><img src="img/saison.png" class="img-listnav"><img src="img/saisonwhite.png" class="img-shrink">Gestion saison</a></li>
                                <li><a href="/articles"><img src="img/product.png" class="img-listnav"><img src="img/productwhite.png" class="img-shrink">Gestion article</a></li>
                                <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"><img src="img/location.png" class="img-listnav"><img src="img/locationwhite.png" class="img-shrink">Localisation</a>
                                    <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                                        <li><a href="/prefectures">Prefecture</a></li>
                                        <li><a href="/sousprefectures">Sousprefecture</a></li>
                                        <li><a href="/cantons">Canton</a></li>
                                        <li><a href="/cgis">Cgi</a></li>
                                        <li><a href="/avs">Av</a></li>

                                    </ul>
                                </li>
                            @elseif(Auth::user()->role == "Cozoc")
                                <li><a href="/articles"><img src="img/product.png" class="img-listnav"><img src="img/productwhite.png" class="img-shrink">Consulter articles</a></li>
                                <li><a href="/besoins"><img src="img/besoin.png" class="img-listnav"><img src="img/besoinwhite.png" class="img-shrink">Gestion besoin</a></li>
                            @elseif(Auth::user()->role == "Gestionnaire de pont Bascule")
                                <li><a href="/tickets"><img src="img/ticket.png" class="img-listnav"><img src="img/ticketwhite.png" class="img-shrink">Gestion Tickets</a></li>
                            @elseif(Auth::user()->role == "Responsable CGI")
                                <li><a href="/besoins"><img src="img/besoin.png" class="img-listnav"><img src="img/besoinwhite.png" class="img-shrink">Consulter besoin
                                        <notify></notify>
                                    </a></li>
                                <li><a href="/bordereau"><img src="img/slip.png" class="img-listnav"><img src="img/slipwhite.png" class="img-shrink">Gestion des bordereaux</a></li>
                                <li><a href="/factures"><img src="img/bill.png" class="img-listnav"><img src="img/billwhite.png" class="img-shrink">Factures livraison</a></li>
                            @elseif(Auth::user()->role == "Responsable Financier")
                                <li><a href="/factures"><img src="img/bill.png" class="img-listnav"><img src="img/billwhite.png" class="img-shrink">Factures livraison</a></li>
                                <li><a href="/reglement"><img src="img/regle.png" class="img-listnav"><img src="img/reglewhite.png" class="img-shrink">Gestion de réglement</a></li>
                                <li><a href="/global"><img src="img/slip.png" class="img-listnav"><img src="img/slipwhite.png" class="img-shrink">Factures global</a></li>

                            @endif
                        </ul>
                    </div>

                </div>
            </nav>


            <div class="page">


                @yield('content')

                <footer class="main-footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Your company &copy; 2018-2019</p>
                            </div>
                            <div class="col-sm-6 text-right">
                                <p>Design by
                                    <a href="https://www.facebook.com/yassine.e.lahbib" class="external">Lahbib Yassine</a>
                                    &
                                    <a href="https://www.facebook.com/israr.kboubi" class="external"> Israr Kboubi</a>
                                </p>
                                <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions and it helps me to run Bootstrapious. Thank you for understanding :)-->
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

       @endguest

    </div>

    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"> </script>

    <script src="{{asset('js/grasp_mobile_progress_circle-1.0.0.min.js')}}"></script>
    <script src="{{asset('js/jquery.cookie.js')}}"> </script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <!-- Main File-->
    <script src="{{asset('js/front.js')}}"></script>
    </body>
</html>
