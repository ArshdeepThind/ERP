@extends('employee.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Products
			<!--<small>Manage Products</small>-->
		</h1>
		<ol class="breadcrumb">
			<li><a href="/employee/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active"><a href="#"><i class="fa fa-cart-plus"></i>Products</li>
			<li><a href="/employee/supplier"><i class="fa fa-group"></i>Suppliers</a></li>
			<li><a href="/employee/orders"><i class="fa fa-shopping-bag"></i>Orders</a></li>
		</ol>
	</section>

	<section class="content" id="app">
	<!-- <administrator headline='products'></administrator> -->
		@include('flash')
        <products headline='products'></products>
	</section>
@endsection
