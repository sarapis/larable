
<div class="left_col scroll-view">
  <div class="navbar nav_title" style="border: 0;">
    <a href="{{url('dashboard')}}" class="site_title"><span>{{$layout->site_name}}</span></a>
  </div>

  <div class="clearfix"></div>

  <br />

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <ul class="nav side-menu">
        <li><a href="/" target="_blank"><i class="fa fa-desktop blue"></i> View Site</a></li>
      </ul>
    </div>
    <div class="menu_section">
      <h3>Main</h3>
      <ul class="nav side-menu">
        <li><a><i class="fa fa-windows"></i> Pages <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="/home_edit">Home</a></li>
            <li><a href="/about_edit">About</a></li>
            <li><a href="/login_register_edit">Login/Register</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-table"></i> Settings <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="/layout_edit">Appearance</a></li>        
            <li><a href="/map">Map</a></li>
            <li><a href="/sections">Sections</a></li> 
          </ul>
        </li>
        <li><a><i class="fa fa-gears"></i> Tools <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="/meta_filter">Meta Filter</a></li>
            <li><a href="/messagesSetting">Campaigns</a></li>
            <li><a href="/analytics">Analytics</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-line-chart"></i> Taxonomies <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <!-- <li><a href="/service_taxonomy">Service Taxonomy</a></li>
            <li><a href="/tb_alt_taxonomy">Service Alt Taxonomy</a></li> -->
            <li><a href="/religions">Religions</a></li>
            <li><a href="/languages">Languages</a></li>
            <li><a href="/organizationTypes">Organization Type</a></li>
            <li><a href="/ContactTypes">Contact Type</a></li>
            <li><a href="/FacilityTypes">Facility Type</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-database"></i> Datasync <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="/import">Import</a></li>
            <li><a href="/export">Export</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-envelope"></i> Contact Form <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="/contact_form">Contact Form</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="/tb_services">Services</a></li>
            <li><a href="/tb_locations">Locations</a></li>
            <li><a href="/tb_organizations">Organizations</a></li>
            <li><a href="/tb_contact">Contact</a></li>
            <li><a href="/tb_phones">Phones</a></li>
            <li><a href="/tb_address">Address</a></li>
            <li><a href="/tb_schedule">Schedule</a></li>
            <li><a href="/tb_taxonomy">Taxonomy</a></li>
            @if($source_data->active ==1 )
            <li><a href="/tb_details">Details</a></li>
            @endif
            @if($source_data->active ==0 )
            <li><a href="/tb_languages">Languages</a></li>
            <li><a href="/tb_accessibility">Accessibility</a></li>
            @endif
            <li><a href="/tb_service_areas">Service area</a></li>
          </ul>
        </li>            
      </ul>
    </div>
    <div class="menu_section">
      <h3>System</h3>
      <ul class="nav side-menu">
      @if (Sentinel::getUser()->hasAnyAccess(['user.*']))
        <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="{{route('user.index')}}">All users</a></li>
            <li><a href="{{route('user.create')}}">New user</a></li>
          </ul>
        </li>
      @endif
      @if (Sentinel::getUser()->hasAnyAccess(['role.*']))
        <li><a><i class="fa fa-cog"></i> Roles <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="{{route('role.index')}}">All Roles</a></li>
            <li><a href="{{route('role.create')}}">New Role</a></li>
          </ul>
        </li>
      @endif
      <li><a><i class="fa fa-list"></i> Log Viewer <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="/log-viewer"> Dashboard</a></li>
          <li><a href="/log-viewer/logs"> Logs</a></li>
        </ul>
      </li>
      <li><a href="/logout"><i class="fa fa-sign-out red"></i> Logout</a></li>
      </ul>
    </div>

  </div>
  
</div>
