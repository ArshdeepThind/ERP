@extends('admin.layout.master')

@section('content')

@php


if(old('mode')!='')
{
    $mode = old('mode');
}
else{
    $mode = 'pf';
}


if (session()->has('mode'))
{
    $mode = session('mode');
}

@endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ ucwords(auth()->user()->name) }} Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User profile</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content" id="app">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="{{ $mode=='pf'?'active':'' }}"><a href="#admin-profile" data-toggle="tab">Profile</a></li>
                        <li class="{{ $mode=='cp'?'active':'' }}"><a href="#admin-changepassword" data-toggle="tab">Change Password</a></li>
                    </ul>
                    <div class="tab-content">
                        @include('flash')
                        {{-- Admin Profile --}}
                        <div class="{{ $mode=='pf'?'active':'' }} tab-pane" id="admin-profile">
                            <form class="form-horizontal" role="form" method="POST" 
                                action="{{ url('/admin/profile/'.auth()->user()->id) }}" enctype="multipart/form-data">
                                {{method_field('PUT')}}
                                {{ csrf_field() }}

                                <input type="hidden" name="mode" value="pf">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-3 control-label">Name</label>
                                    <div class="col-md-7">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" autofocus>

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-3 control-label">Email</label>

                                    <div class="col-md-7">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" autofocus disabled>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('pic') ? ' has-error' : '' }}">
                                    <label for="pic" class="col-md-3 control-label">Picture</label>

                                    <div class="col-md-7">
                                    
                                        <img-fileinput imgsrc="{{ $adminPic }}"></img-fileinput>
                                        @if ($errors->has('pic'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('pic') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clear-fix"></div>
                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- Change Password --}}
                        <div class="{{ $mode=='cp'?'active':'' }} tab-pane" id="admin-changepassword">
                            <form class="form-horizontal" role="form" method="POST" 
                                action="{{ url('/admin/changepassword/'.auth()->user()->id) }}" enctype="multipart/form-data">
                                {{method_field('PUT')}}
                                {{ csrf_field() }}

                                 <input type="hidden" name="mode" value="cp">
                                <div class="form-group{{ $errors->has('oldpassword') ? ' has-error' : '' }}">
                                    <label for="oldpassword" class="col-md-3 control-label">Old Password</label>
                                    <div class="col-md-7">
                                        <input id="oldpassword" type="password" class="form-control" name="oldpassword" value="" autofocus>
                                        @if ($errors->has('oldpassword'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('oldpassword') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-3 control-label">New Password</label>
                                    <div class="col-md-7">
                                        <input id="password" type="password" class="form-control" name="password" value="" autofocus>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password_confirmation" class="col-md-3 control-label">Confirm Password</label>
                                    <div class="col-md-7">
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" autofocus>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="clear-fix"></div>
                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary">
                                            Change Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
