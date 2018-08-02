@extends('employee.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Jobs
			<small>Manage Jobs</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Jobs</li>
		</ol>
	</section>

	<section class="content" id="app">
		@include('flash')
        <jobs headline='Job'></jobs>
	</section>
@endsection
