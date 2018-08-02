@extends('employee.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Employees
			<small>Manage Employee Leaves Information</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Leaves</li>
		</ol>
	</section>

	<section class="content" id="app">
		@include('flash')
        <leaves headline='Leaves'></leaves>
	</section>
@endsection
