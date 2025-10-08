     <div class="vertical-menu">

         <div data-simplebar class="h-100">

             <!--- Sidemenu -->
             <div id="sidebar-menu">
                 <!-- Left Menu Start -->
                 <ul class="metismenu list-unstyled" id="side-menu">
                     <li class="menu-title" key="t-menu">Menu</li>

                     <li>
                         <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Dashboards</span>
                         </a>
                     </li>
                     <li class="menu-title" key="t-apps">Apps</li>

                     {{-- <li>
                         <a href="{{ route('admin.adddevices.index') }}" class="waves-effect">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Add Devices</span>
                         </a>
                     </li> --}}

                     <li>
                         <a href="{{ route('admin.faremetrix.index') }}" class="waves-effect">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Add Fare Metrix</span>
                         </a>
                     </li>


                     {{-- <li>
                         <a href="{{ route('admin.posuser.manageposuser') }}">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Add New Pos User</span>
                         </a>
                     </li> --}}

                
                        <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-home-circle"></i>
                                    <span key="t-dashboards">Reports</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('admin.reports.daily') }}" key="t-default">Daily Collection Reports</a></li>
                                    <li><a href="{{ route('admin.reports.vehicle') }}" key="t-crypto">Vehiclewise  Reports</a></li>
                                    <li><a href="dashboard-saas.html" key="t-saas">Userwise Reports</a></li>
                                    <li><a href="dashboard-blog.html" key="t-blog">Pass Reports</a></li>
                                </ul>
                            </li>
                 </ul>
             </div>
             <!-- Sidebar -->
         </div>
     </div>
