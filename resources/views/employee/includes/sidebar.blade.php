<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
  
 
  <ul class="sidebar-menu">
      
    
      <li>
        <a href="{{ url('/employee') }}">
          <i class="fa fa-th"></i> <span>DASHBOARD</span>
          <span class="pull-right-container">
          </span>
        </a>
      </li> 

   @if(auth()->user()->hasRole('hr'))
      <li class="sidebar-li {{ $current_route_name == 'employee.employees.index' ? 'active' : '' }}">
        <a href="{{ url('/employee/employees') }}">
          <i class="fa  fa-user"></i>
          <span>EMPLOYEES
          </span>
        </a>
      </li>

      <li class="sidebar-li {{ $current_route_name == 'employee.job.index' ? 'active' : '' }}">
        <a href="{{ url('/employee/job') }}">
          <i class="fa  fa-list-ul"></i>
          <span>JOBS
          </span>
        </a>
      </li>
  

      <li class="sidebar-li {{ $current_route_name == 'employee.chat' ? 'active' : '' }}">
        <a href="{{ url('employee/chat') }}">
           <i class="fa fa-wechat"></i>
          <span>CHATS
            <span class="label label-danger display-none" id="chat_badgeCount"></span>
          </span>
        </a>
      </li>
      
      <li class="sidebar-li {{ $current_route_name == 'employee.' ? 'active' : '' }}">
        <a href="{{ url('employee/leaves') }}">
           <i class="fa fa-gear"></i>
          <span>EMPLOYEE LEAVES
          </span>
        </a>
      </li>

      <li class="sidebar-li {{ $current_route_name == 'employee.unapprovedleaves' ? 'active' : '' }}">
        <a href="{{ url('employee/leaves/unapprovedleaves') }}">
           <i class="fa fa-gear"></i>
          <span>UNAPPROVED LEAVES
          </span>
        </a>
      </li>

      <li class="sidebar-li {{ $current_route_name == 'employee.payment.index' ? 'active' : '' }}">
        <a href="{{ url('employee/payment/') }}">
           <i class="fa fa-gear"></i>
          <span> EMPLOYEE SALARY
          </span>
        </a>
      </li>


      <li class="sidebar-li {{ $current_route_name == 'employee.attendence' ? 'active' : '' }}">
        <a href="{{ url('employee/attendence/') }}">
           <i class="fa fa-gear"></i>
          <span> EMPLOYEE ATTENDENCE
          </span>
        </a>
      </li>
      <li class="sidebar-li {{ $current_route_name == 'employee.settings.index' ? 'active' : '' }}">
        <a href="{{ url('employee/settings') }}">
           <i class="fa fa-gear"></i>
          <span>SETTINGS
          </span>
        </a>
      </li>
  @endif

  @if(auth()->user()->hasRole('inventory'))
      <li class="sidebar-li {{ $current_route_name == 'employee.products.index' ? 'active' : '' }}">
        <a href="{{ url('/employee/products') }}">
          <i class="fa  fa-cart-plus"></i>
          <span>PRODUCTS
          </span>
        </a>
      </li>

      <li class="sidebar-li {{ $current_route_name == 'employee.supplier.index' ? 'active' : '' }}">
        <a href="{{ url('/employee/supplier') }}">
          <i class="fa  fa-group"></i>
          <span>SUPPLIER
          </span>
        </a>
      </li>
  

      <li class="sidebar-li {{ $current_route_name == 'employee.orders.index' ? 'active' : '' }}">
        <a href="{{ url('employee/orders') }}">
           <i class="fa fa-shopping-bag"></i>
          <span>ORDERS
          </span>
        </a>
      </li>
      
  @endif
    
  </ul>
  </section>
  <!-- /.sidebar -->
</aside>