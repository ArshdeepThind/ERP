@extends('admin.layout.master')
@section('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
	<section class="content-header">
		<h1>
			Roles
			<small>Manage Roles</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Roles</li>
		</ol>
	</section>

	<section class="content" id="app">
	    <roles headline='Role'></roles>
	</section>
@endsection
