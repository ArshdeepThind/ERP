@extends('admin.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Users
			<small>Manage Users</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Users</li>
		</ol>
	</section>

	<section class="content" id="app">
		@include('flash')
        <users headline='User'></users>
	</section>
@endsection
