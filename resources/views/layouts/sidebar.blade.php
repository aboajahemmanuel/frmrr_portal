 <!-- sidebar @s -->
 <div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
     <div class="nk-sidebar-element nk-sidebar-head">
         <div class="nk-sidebar-brand">
             <a href="{{ url('dashboard') }}" class="logo-link nk-sidebar-logo">
                 <img class="logo-light logo-img logo-img-lg" src="{{ asset('public/admin/images/FMDQlogo.svg') }}"
                     srcset="{{ asset('public/admin//images/FMDQlogo.svg') }}">
                 <img class="logo-dark logo-img logo-img-lg" src="{{ asset('public/admin/images/FMDQlogo.svg') }}"
                     srcset="{{ asset('public/admin/images/FMDQlogo.svg') }}">

             </a>
         </div>
         {{-- <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div> --}}
     </div><!-- .nk-sidebar-element -->
     <div class="nk-sidebar-element" style="background-color: #22346c">
         <div class="nk-sidebar-content">
             <div class="nk-sidebar-menu" data-simplebar>



                 <ul class="nk-menu">
                     <li class="nk-menu-heading">

                     </li><!-- .nk-menu-item -->
                     <li class="nk-menu-item">
                         <a href="{{ route('dashboard') }}" class="nk-menu-link">
                             <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-bag"></em></span>
                             <span class="nk-menu-text" style="color: white">Dashboard</span>
                         </a>
                     </li><!-- .nk-menu-item -->

                     @can('user-list')
                         <li class="nk-menu-item has-sub">
                             <a href="#" class="nk-menu-link nk-menu-toggle">
                                 <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-task"></em></span>
                                 <span class="nk-menu-text" style="color: white">Users</span>
                             </a>
                             <ul class="nk-menu-sub">
                                 <li class="nk-menu-item">
                                     <a href="{{ url('admin_users') }}" class="nk-menu-link" style="color: white"><span
                                             class="nk-menu-text">Internal Users</span></a>
                                     <a href="{{ url('eternal_users') }}" class="nk-menu-link" style="color: white"><span
                                             class="nk-menu-text">External Users</span></a>
                                 </li>

                             </ul><!-- .nk-menu-sub -->
                         </li><!-- .nk-menu-item -->
                     @endcan


                     {{-- 
                     @can('role-list')
                         <li class="nk-menu-item has-sub">
                             <a href="#" class="nk-menu-link nk-menu-toggle">
                                 <span class="nk-menu-icon" style="color: white"><em
                                         class="icon ni ni-share-alt"></em></span>
                                 <span class="nk-menu-text" style="color: white">Roles And Permissions</span>
                             </a>
                             <ul class="nk-menu-sub">
                                 <li class="nk-menu-item">
                                     <a href="{{ url('roles') }}" class="nk-menu-link" style="color: white"><span
                                             class="nk-menu-text">Roles</span></a>
                                 </li>
                                 <li class="nk-menu-item">
                                     <a href="{{ url('permissions') }}" class="nk-menu-link" style="color: white"><span
                                             class="nk-menu-text">Permissions</span></a>
                                 </li>

                             </ul>
                         </li>
                     @endcan --}}



                     @can('role-list')
                         <li class="nk-menu-item">
                             <a href="{{ url('roles') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon" style="color: white"><em
                                         class="icon ni ni-share-alt"></em></span>
                                 <span class="nk-menu-text" style="color: white">Roles And Permission
                             </a>
                         </li><!-- .nk-menu-item -->
                     @endcan

                     @can('group-list')
                         <li class="nk-menu-item">
                             <a href="{{ url('groups') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon" style="color: white"><em
                                         class="icon ni ni-book-read"></em></span>
                                 <span class="nk-menu-text" style="color: white">Group Management
                             </a>
                         </li><!-- .nk-menu-item -->
                     @endcan

                     @can('category-list')
                         <li class="nk-menu-item has-sub">
                             <a href="#" class="nk-menu-link nk-menu-toggle">
                                 <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-task"></em></span>
                                 <span class="nk-menu-text" style="color: white">Category Management</span>
                             </a>
                             <ul class="nk-menu-sub">
                                 <li class="nk-menu-item">
                                     <a href="{{ url('categories') }}" class="nk-menu-link" style="color: white"><span
                                             class="nk-menu-text">Categories</span></a>
                                     <a href="{{ url('subcategories') }}" class="nk-menu-link" style="color: white"><span
                                             class="nk-menu-text">Sub Categories</span></a>
                                 </li>

                             </ul><!-- .nk-menu-sub -->
                         </li><!-- .nk-menu-item -->
                     @endcan

                     @can('entity-list')
                         <li class="nk-menu-item">
                             <a href="{{ url('entities') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon" style="color: white"><em
                                         class="icon ni ni-list-thumb-alt"></em></span>
                                 <span class="nk-menu-text" style="color: white">Entity
                             </a>
                         </li><!-- .nk-menu-item -->
                     @endcan

                     @can('post-list')
                         <li class="nk-menu-item">
                             <a href="{{ url('news') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-qr"></em></span>
                                 <span class="nk-menu-text" style="color: white">News Alert
                             </a>
                         </li><!-- .nk-menu-item -->
                     @endcan


                     @can('regulation-list')
                         <li class="nk-menu-item">
                             <a href="{{ url('regulations') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-article"></em></span>
                                 <span class="nk-menu-text" style="color: white">Documents
                             </a>
                         </li>
                     @endcan

                     @can('transaction-list')
                         <li class="nk-menu-item has-sub">
                             <a href="#" class="nk-menu-link nk-menu-toggle">
                                 <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-task"></em></span>
                                 <span class="nk-menu-text" style="color: white">Subscriptions</span>
                             </a>
                             <ul class="nk-menu-sub">
                                 <li class="nk-menu-item">
                                     <a href="{{ url('subcription_plan') }}" class="nk-menu-link"
                                         style="color: white"><span class="nk-menu-text">Subcription Plan</span></a>
                                     <a href="{{ url('subscribers') }}" class="nk-menu-link" style="color: white"><span
                                             class="nk-menu-text">Subscribers</span></a>
                                 </li>

                             </ul><!-- .nk-menu-sub -->
                         </li><!-- .nk-menu-item -->
                     @endcan



                     @if (Auth::user()->hasRole('Super_Administrator_Authoriser') || Auth::user()->hasRole('Super_Administrator_Inputter'))
                         <li class="nk-menu-item has-sub">
                             <a href="{{ url('logActivity') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon"><em class="icon ni ni-coins"></em></span>
                                 <span class="nk-menu-text" style="color: white">Audit logs</span>
                             </a>

                         </li>
                     @endif

                     <!-- Disclaimer History Link -->
                     <li class="nk-menu-item">
                         <a href="{{ route('admin.disclaimers.index') }}" class="nk-menu-link">
                             <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-file-docs"></em></span>
                             <span class="nk-menu-text" style="color: white">Disclaimer History
                         </a>
                     </li><!-- .nk-menu-item -->


                      <!-- Disclaimer History Link -->
                     <li class="nk-menu-item">
                         <a href="{{ route('home') }}" target="blank_" class="nk-menu-link">
                             <span class="nk-menu-icon" style="color: white"><em class="icon ni ni-file-docs"></em></span>
                             <span class="nk-menu-text" style="color: white">View Frontend
                         </a>
                     </li><!-- .nk-menu-item -->

                 </ul>

                 <!-- .nk-menu -->
             </div><!-- .nk-sidebar-menu -->
         </div><!-- .nk-sidebar-content -->
     </div><!-- .nk-sidebar-element -->
 </div>
 <!-- sidebar @e -->