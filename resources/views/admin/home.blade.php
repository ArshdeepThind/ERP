@extends('admin.layout.master')

@section('content')
<div id="app">
   <section class="content">
	    <div class="row">
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>

	            <div class="info-box-content">
	              <span class="info-box-text">Users</span>
	               <span class="info-box-number"><?php echo $usercount;?></span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	       
	    
	    </div>
    </section>
</div>
@endsection
