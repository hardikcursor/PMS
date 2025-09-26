     <div class="vertical-menu">

         <div data-simplebar class="h-100">

             <!--- Sidemenu -->
             <div id="sidebar-menu">
                 <!-- Left Menu Start -->
                 <ul class="metismenu list-unstyled" id="side-menu">
                     <li class="menu-title" key="t-menu">Menu</li>

                     <li>
                         <a href="{{ route('superadmin.dashboard') }}" class="waves-effect">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Dashboards</span>
                         </a>
                     </li>


                     <li class="menu-title" key="t-apps">Apps</li>



                     <li>
                         <a href="javascript: void(0);" class="has-arrow waves-effect">
                             <i class="bx bx-envelope"></i>
                             <span key="t-email">Company</span>
                         </a>
                         <ul class="sub-menu" aria-expanded="false">
                             <li><a href="{{ route('superadmin.company.create') }}" key="t-inbox">Create Company</a>
                             </li>
                             <li><a href="{{ route('superadmin.company.manage') }}" key="t-read-email">Manage
                                     Company</a></li>
                             <li>
                                 <a href="{{ route('superadmin.company.activate') }}">
                                     <span key="t-email-templates">Active Companies</span>
                                 </a>
                             </li>

                                    <li>
                                 <a href="{{ route('superadmin.company.inactive') }}">
                                     <span key="t-email-templates">Inactive Companies</span>
                                 </a>
                             </li>
                         </ul>
                     </li>
                     <li>
                         <a href="{{ route('superadmin.adddevices.index') }}" class="waves-effect">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Add Devices</span>
                         </a>
                     </li>

                     <li>
                         <a href="{{ route('superadmin.faremetrix.index') }}" class="waves-effect">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Add Fare Metrix</span>
                         </a>
                     </li>

                     <li>
                         <a href="{{ route('superadmin.posuser.manageposuser') }}">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Add New Pos User</span>
                         </a>
                     </li>

                         <li>
                         <a href="{{ route('superadmin.subscription.manage') }}" class="waves-effect">
                             <i class="bx bx-home-circle"></i>
                             <span key="t-dashboards">Add License</span>
                         </a>
                     </li>







                 </ul>
             </div>
             <!-- Sidebar -->
         </div>
     </div>
