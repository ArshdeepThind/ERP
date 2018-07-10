@extends('admin.layout.master')
@section('content')
	<section class="content-header">
		<h1>
			Pages
			<small>Manage Pages</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Pages</li>
		</ol>
	</section>

	<section class="content" id="app">
		@include('flash')
        <csm-pages headline='Pages'></csm-pages>
	</section>
@endsection
