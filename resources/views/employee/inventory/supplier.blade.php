@extends('employee.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Suppliers
			<!--<small>Manage Products</small>-->
		</h1>
		<ol class="breadcrumb">
			<li><a href="/employee/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="/employee/products"><i class="fa fa-cart-plus"></i>Products</li>
			<li class="active"><i class="fa fa-group"></i><a href="#">Suppliers</a></li>
			<li><a href="/employee/orders"><i class="fa fa-shopping-bag"></i>Orders</a></li>
			
			
		</ol>
	</section>

	<section class="content" id="app">
		@include('flash')
        <supplier headline='supplier'></supplier>
	</section>
@endsection
