<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
  
 
  <ul class="sidebar-menu">
      
    
    <li>
      <a href="{{ url('/admin') }}">
        <i class="fa fa-th"></i> <span>Dashboard</span>
        <span class="pull-right-container">
        </span>
      </a>
    </li>
   

    <li class="header">ADMINISTRATION</li>

     @if(auth()->user()->can('developerOnly') || auth()->user()->can('role'))
      <li class="treeview {{ in_array($current_route_name,['admin.roles.index','admin.permissions.index','admin.myrolepermission'])?'active':'' }}">
        <a href="javascript:void(0);">
          <i class="fa fa-shield"></i> <span>Role Manager</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          <li class="{{ $current_route_name=='admin.roles.index'?'active':'' }}">
            <a href="{{ url('/admin/roles') }}"><i class="fa fa-circle-o"></i> Roles</a>
          </li>
          @can('developerOnly')
          <li class="{{ $current_route_name=='admin.permissions.index'?'active':'' }}">
            <a href="{{ url('/admin/permissions') }}"><i class="fa fa-circle-o"></i> Permissions</a>
          </li>
          @endcan
          <li class="{{ $current_route_name=='admin.myrolepermission'?'active':'' }}">
            <a href="{{ url('/admin/rolePermissions') }}"><i class="fa fa-circle-o"></i> Role Permissions</a>
          </li>
        </ul>
      </li>
      @endif
      {{-- ADMIN USERS --}}

      @if(auth()->user()->can('developerOnly') || auth()->user()->can('admin.list'))
        <li class="{{ $current_route_name=='admin.administrator.index'?'active':'' }}">
          <a href="{{ url('/admin/administrator') }}">
            <i class="fa  fa-user-secret"></i> <span>Admin Users</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
      @endif
  
      
      @if(auth()->user()->can('developerOnly') || auth()->user()->can('pages'))
        <li class="treeview {{ in_array($current_route_name,['admin.pages.index','admin.pages.create'])?'active':'' }}">
          <a href="javascript:void(0);">
            <i class="fa  fa-file-text"></i> <span>Pages</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

          <ul class="treeview-menu">
            <li class="{{ $current_route_name=='admin.pages.index'?'active':'' }}">
              <a href="{{ url('/admin/pages') }}"><i class="fa fa-circle-o fa-list"></i> List</a>
            </li>
            <li class="{{ $current_route_name=='admin.pages.create'?'active':'' }}">
              <a href="{{ url('/admin/pages/create') }}"><i class="fa fa-circle-o fa-plus-square-o"></i> Add New</a>
            </li>
          </ul>
        </li>
      @endif


    
  </ul>
  </section>
  <!-- /.sidebar -->
</aside>