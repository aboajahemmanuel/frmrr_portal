
            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                     @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('sub_admin') )
                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            {{-- <li class="menu-title">Main</li> --}}

                            <li>
                                <a href="{{route('dashboard')}}" class="waves-effect">
                                    <i class="ti-home"></i><span class="badge rounded-pill bg-primary float-end"></span>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{url('roles')}}" class="waves-effect">
                                    <i class="ti-calendar"></i>
                                    <span>Roles</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{url('permissions')}}" class="waves-effect">
                                    <i class="ti-email"></i>
                                    <span>Permissions</span>
                                </a>
                               
                            </li>


                            <li>
                                <a href="{{url('users')}}" class="waves-effect">
                                    <i class="fas fa-users"></i>
                                    <span class="badge rounded-pill bg-success float-end"></span>
                                    <span>Users</span>
                                </a>
                                
                            </li>


                            <li>
                                <a href="{{url('groups')}}" class="waves-effect">
                                    <i class="fas fa-users"></i>
                                    <span class="badge rounded-pill bg-success float-end"></span>
                                    <span>Groups</span>
                                </a>
                                
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ti-email"></i>
                                    <span>Category</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{url('categories')}}">Catrgories</a></li>
                                    <li><a href="{{url('subcategories')}}">Sub Categories</a></li>
                                   
                                </ul>
                            </li>

                            <li>
                                <a href="{{url('entities')}}" class="waves-effect">
                                    <i class="ti-receipt"></i>
                                    <span class="badge rounded-pill bg-success float-end"></span>
                                    <span>Entities</span>
                                </a>
                               
                            </li>


                            <li>
                                <a href="{{url('regulations')}}" class="waves-effect">
                                    <i class="ti-pie-chart"></i>
                                    <span>Documents</span>
                                </a>
                               
                            </li>


                            <li>
                                <a href="{{url('transactions')}}" class="waves-effect">
                                    <i class="ti-money"></i>
                                    <span>Transactions</span>
                                </a>
                               
                            </li>


                          

                          

                        </ul>
                    </div>
                    @endif



                      @if(Auth::user()->hasRole('document_owner') || Auth::user()->hasRole('admin_user') )
                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            {{-- <li class="menu-title">Main</li> --}}

                            <li>
                                <a href="{{route('dashboard')}}" class="waves-effect">
                                    <i class="ti-home"></i><span class="badge rounded-pill bg-primary float-end"></span>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            


                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ti-email"></i>
                                    <span>Category</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{url('categories')}}">Catrgories</a></li>
                                    <li><a href="{{url('subcategories')}}">Sub Categories</a></li>
                                   
                                </ul>
                            </li>

                            <li>
                                <a href="{{url('entities')}}" class="waves-effect">
                                    <i class="ti-receipt"></i>
                                    <span class="badge rounded-pill bg-success float-end"></span>
                                    <span>Entities</span>
                                </a>
                               
                            </li>


                            <li>
                                <a href="{{url('regulations')}}" class="waves-effect">
                                    <i class="ti-pie-chart"></i>
                                    <span>Documents</span>
                                </a>
                               
                            </li>


                            <li>
                                <a href="{{url('transactions')}}" class="waves-effect">
                                    <i class="ti-money"></i>
                                    <span>Transactions</span>
                                </a>
                               
                            </li>


                          

                          

                        </ul>
                    </div>
                    @endif
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->