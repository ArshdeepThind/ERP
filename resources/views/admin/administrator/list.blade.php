@extends('admin.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Admin Users
			<small>Manage Administrators</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Administrators</li>
		</ol>
	</section>

	<section class="content" id="app">
        <administrator headline='admin'></administrator>
	</section>
@endsection
