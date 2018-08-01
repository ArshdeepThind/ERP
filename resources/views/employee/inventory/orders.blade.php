@extends('employee.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Orders
			<!--<small>Manage Products</small>-->
		</h1>
		<ol class="breadcrumb">
			<li><a href="/employee/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
			<li ><a href="/employee/products"><i class="fa fa-cart-plus"></i>Products</li>
			<li><a href="/employee/supplier"><i class="fa fa-group"></i>Suppliers</a></li>
            <li class="active"><a href="#"><i class="fa fa-shopping-bag"></i>Orders</a></li>
		</ol>
	</section>

	<section class="content" id="app">
	<!-- <administrator headline='products'></administrator> -->
	@include('flash')
        <orders headline='order'></orders>
	</section>
@endsection
