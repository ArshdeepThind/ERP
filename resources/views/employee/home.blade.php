@extends('employee.layout.master')

@section('content')

<div id="app">
  <section class="content">


   @if(auth()->user()->hasRole('hr'))
   	  <div class="row">
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $employeecount;?></h3>

              <p>Employees</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="employees/" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $jobscount;?></h3>

              <p>Jobs</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="job/" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $departmentcount; ?></h3>

              <p>Departments</p>
            </div>
            <div class="icon">
              <i class="fa fa-gear"></i>
            </div>
            <a href="job/" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
    	
      <div class="row">
      	<div class="col-md-6">
                <!-- USERS LIST -->
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Latest Members</h3>

                    <div class="box-tools pull-right dashboard-box-tools">
                      <span class="label label-danger"><?php echo $latestmembercount; ?> New Members</span>
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body no-padding">
                    <ul class="users-list clearfix">
                    	@foreach ($latestmember as $key => $value)
                      <li>
                        @if($value->pic)
                        <img src="{{ asset('storage/employee/').'/'.$value->id.'/'.$value->pic }}" width="50px" />
                        @else
                        <img src="{{ asset('storage/avatar.png') }}" width="50px" />
                        @endif
                        <a class="users-list-name" href="#">{{$value->first_name}} {{$value->last_name}}</a>
                        <span class="users-list-date">{{ Carbon\Carbon::parse($value->joining_date)->format('d, F') }}</span>
                      </li>
                      @endforeach
                    </ul>
                    <!-- /.users-list -->
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer text-center">
                    <a href="employees/" class="uppercase">View All Employees</a>
                  </div>
                  <!-- /.box-footer -->
                </div>
                <!--/.box -->
          </div>

          <div class="col-md-6">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Opening</h3>

                <div class="box-tools pull-right dashboard-box-tools">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <ul class="products-list product-list-in-box">
                	@foreach ($opening as $key => $value)
                  <li class="item">
                    <div class="product-info">
                      <span class="product-title">{{$value->job_title}}</span>
                        <span class="label label-warning pull-right opening-class">{{$value->no_of_vacancies}}</span>
                      	<span class="product-description">
                          	{{$value->name}}
                          </span>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
              <!-- /.box-body -->
              <div class="box-footer text-center">
                <a href="job/" class="uppercase">View All Jobs</a>
              </div>
              <!-- /.box-footer -->
            </div>
          </div>
      </div>

      <div class="row">
      	<div class="col-md-6">
                <!-- USERS LIST -->
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Upcoming Birthday</h3>

                    <div class="box-tools pull-right dashboard-box-tools">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body no-padding">
                    <ul class="users-list clearfix">
                      @foreach ($birthdays as $key => $value)
                      <li>
                        @if($value->pic)
                        <img src="{{ asset('storage/employee/').'/'.$value->id.'/'.$value->pic }}" width="50px" />
                        @else
                        <img src="{{ asset('storage/avatar.png') }}" width="50px" />
                        @endif
                        <a class="users-list-name" href="#">{{$value->first_name}} {{$value->last_name}}</a>
                        <span class="users-list-date">{{ Carbon\Carbon::parse($value->date_of_birth)->format('d, F') }}</span>
                      </li>
                      @endforeach
                    </ul>
                    <!-- /.users-list -->
                  </div>
                  <!-- /.box-footer -->
                </div>
                <!--/.box -->
        </div>

        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Work anniversary</h3>

                  <div class="box-tools pull-right dashboard-box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                   
                    @foreach ($work_anniversary as $key => $value)
                    <li>
                      @if($value->pic)
                      <img src="{{ asset('storage/employee/').'/'.$value->id.'/'.$value->pic }}" width="50px" />
                      @else
                      <img src="{{ asset('storage/avatar.png') }}" width="50px" />
                      @endif
                      <a class="users-list-name" href="#">{{$value->first_name}} {{$value->last_name}}</a>
                      <span class="users-list-date">{{ $value->joining_date }}</span>
                    </li>
                    @endforeach 
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-footer -->
              </div>
            <div class="box-footer text-center">
              <a href="employees/" class="uppercase">View All Employees</a>
            </div>
            <!-- /.box-footer -->
        </div>
      </div>
   @endif

    @if(auth()->user()->hasRole('inventory'))
      <div class="row">
        <!-- ./col -->
        <div class="col-lg-6 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
               <h3><?php echo $a[0];?></h3>
              <p>Products</p>
            </div>
            <div class="icon">
              <i class="fa fa-cart-plus"></i>
            </div>
            <a href="products/" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-md-2"></div>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $a[1];?></h3>
              <p>Suppliers</p>
            </div>
            <div class="icon">
              <i class="fa fa-group"></i>
            </div>
            <a href="supplier/" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <div class="row">
      <div class="col-md-12">
              <!-- USERS LIST -->
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Best Selling Products</h3>
                  <div class="box-tools pull-right dashboard-box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                    </div>

                    <table id="example" class="table table-hover table-bordered">
                       <thead>
                       <tr>
                          <th>Product Category</th>
                          <th>Product Name</th>
                
                        </tr>
                      </thead>
                      <tbody>

                        @foreach($a[2] as $key=> $value)
                            <tr>
                                <td>{{ $value->product_category }}</td>
                                <td>{{ $value->product_name }}</td>
                            </tr>
                        @endforeach

                      </tbody>
                    </table>

                </div>

                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="products/" class="uppercase">View All Products</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
      </div>
      </div>
    @endif

  </section>
</div>
@endsection
