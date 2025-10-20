 <!-- wrap @s -->
 <div class="nk-wrap ">
     <!-- main header @s -->
     <div class="nk-header nk-header-fixed is-light">
         <div class="container-fluid">
             <div class="nk-header-wrap">
                 <div class="nk-menu-trigger d-xl-none ml-n1">
                     <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                             class="icon ni ni-menu"></em></a>
                 </div>
                 <div class="nk-header-brand d-xl-none">
                     <a href="html/index.html" class="logo-link">
                         <img class="logo-light logo-img logo-img-lg"
                             src="{{ asset('public/assets/images/FMDQlogo.svg') }}"
                             srcset="{{ asset('assets/images/FMDQlogo.svg') }}">
                         <img class="logo-dark logo-img logo-img-lg"
                             src="{{ asset('public/assets/images/FMDQlogo.svg') }}"
                             srcset="{{ asset('assets/images/FMDQlogo.svg') }}">
                     </a>
                 </div><!-- .nk-header-brand -->
                 <span style="margin: 65px 0px 0px 0px "></span>
                 <div class="nk-header-tools">
                     <ul class="nk-quick-nav">

                         <li class="dropdown user-dropdown">
                             <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                                 <div class="user-toggle">
                                     <div class="user-avatar sm">
                                         <em class="icon ni ni-user-alt"></em>
                                     </div>
                                     <div class="user-info d-none d-xl-block">

                                         <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                                     </div>
                                 </div>
                             </a>
                             <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                 <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                     <div class="user-card">
                                         <div class="user-avatar">
                                             <span>@php echo  substr(Auth::user()->name,0,1); @endphp </span>
                                         </div>
                                         <div class="user-info">
                                             <span class="lead-text">{{ Auth::user()->name }}</span>
                                             <span class="sub-text">{{ Auth::user()->email }}</span>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="dropdown-inner">
                                     <ul class="link-list">



                                         <li>


                                             <a class="dropdown-item " href="javascript:void();"
                                                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><em
                                                     class="icon ni ni-signout"></em><span>Logout</span></a>



                                             {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                 style="display: none;">
                                                 @csrf
                                             </form> --}}

                                             <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                 class="d-none">
                                                 @csrf
                                             </form>
                                     </ul>
                                 </div>

                             </div>
                         </li>
                     </ul>
                 </div>
             </div><!-- .nk-header-wrap -->
         </div><!-- .container-fliud -->
     </div>
