@extends('admin.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Permissions
			<small>Manage Permissions</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Permissions</li>
		</ol>
	</section>

	<section class="content" id="app">
	    <permissions headline='Permission'></permissions>
	</section>
@endsection
