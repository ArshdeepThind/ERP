<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
  
 
  <ul class="sidebar-menu">
      
    
  
    <li class="header">ADMINISTRATION</li>

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
          <li class="{{ $current_route_name=='admin.permissions.index'?'active':'' }}">
            <a href="{{ url('/admin/permissions') }}"><i class="fa fa-circle-o"></i> Permissions</a>
          </li>
          <li class="{{ $current_route_name=='admin.myrolepermission'?'active':'' }}">
            <a href="{{ url('/admin/rolePermissions') }}"><i class="fa fa-circle-o"></i> Role Permissions</a>
          </li>
        </ul>
      </li>
      <li class="{{ $current_route_name=='admin.administrator.index'?'active':'' }}">
        <a href="{{ url('/admin/administrator') }}">
          <i class="fa  fa-user-secret"></i> <span>Admin Users</span>
          <span class="pull-right-container">
          </span>
        </a>
      </li>

      <li class="{{ $current_route_name=='admin.users.index'?'active':'' }}">
        <a href="{{ url('/admin/users') }}">
          <i class="fa  fa-user-secret"></i> <span>Employees</span>
          <span class="pull-right-container">
          </span>
        </a>
      </li>

  
  </ul>
  </section>
  <!-- /.sidebar -->
</aside>