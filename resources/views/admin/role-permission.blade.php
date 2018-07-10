@extends('admin.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Role Permissions
			<small>Manage Role's Permission</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Role's Permissions</li>
		</ol>
	</section>
	<section class="content" id="app">
	    <assign-permission></assign-permission>
	</section>
@endsection
