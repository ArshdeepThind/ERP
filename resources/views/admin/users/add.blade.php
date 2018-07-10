@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
@endsection
@section('content')
    <section class="content-header">
        <h1>
            User Add
            <small>Manage Users</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/admin/users') }}">Users</a></li>
            <li class="active">Add User</li>
        </ol>
    </section>
    <section class="content" id="app">
    <component is='add-user' userids = 0 imgsrc = {{ $defaultImg }} inline-template> 
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add User</h3>
                    </div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/users') }}" enctype="multipart/form-data"  @submit.prevent='onSubmit(0)' @keydown = 'errors.clear($event.target.name)'>
                        <div class="box-body">
                        {{ csrf_field() }}
                        <div v-if='showAlert'>
                             <alert :type="alertType">@{{ alertText }}</alert>
                        </div>
                        <div class="form-group" v-bind:class="errors.has('firstname') ? ' has-error' : ''">
                                <label for="firstname" class="col-md-3 control-label">First Name</label>

                                <div class="col-md-7">
                                    <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" v-model="firstname">

                                    
                                        <span class="help-block" v-text="errors.get('firstname')"></span>
                                    
                                </div>
                        </div>
                            {{-- Last Name --}}
                        <div class="form-group" v-bind:class="errors.has('lastname') ? ' has-error' : ''">
                                <label for="lastname" class="col-md-3 control-label">Last Name</label>

                                <div class="col-md-7">
                                    <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" v-model="lastname">

                                    
                                      <span class="help-block" v-text="errors.get('lastname')"></span>
                                    
                                </div>
                        </div>
                        {{-- Email --}}
                        <div class="form-group" v-bind:class="errors.has('email') ? ' has-error' : ''">
                            <label for="email" class="col-md-3 control-label">Email</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" v-model="email">

                                    <span class="help-block" v-text="errors.get('email')"></span>
                            </div>
                        </div>
                        
                     
                        
                        <div class="form-group" v-bind:class="errors.has('phone') ? ' has-error' : ''">
                            <label for="phone" class="col-md-3 control-label">Phone</label>

                            <div class="col-md-7">

                                <input id="phone" name="phone" type="tel" class="form-control" v-model="phone"> 

                                <span class="help-block" v-text="errors.get('phone')"></span>
                               
                            </div>
                        </div>
                        {{-- PIC --}}
                        <div class="form-group" v-bind:class="errors.has('pic') ? ' has-error' : ''">
                            <label for="pic" class="col-md-3 control-label">Picture</label>

                            <div class="col-md-7">
                                    <!-- <img-fileinput imgsrc="{{ $defaultImg }}"></img-fileinput> -->
                                    <div class="img-input">
                                            <img :src="pic" class="img-responsive img-rounded">
                                            <input @change="onFileChange" type="file" name="pic" class="form-control" readonly>
                                    </div>
                                    
                                    <span class="help-block" v-text="errors.get('pic')"></span>
                              
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gender" class="col-md-3 control-label">Gender </label>
                            <div class="col-md-7">
                                <label>
                                    <input type="radio" value='Male' name="gender" class="minimal" checked v-model="gender">
                                    Male
                                </label>
                                <label>
                                    <input type="radio" value='Female' name="gender" class="minimal" v-model="gender">
                                    Female
                                </label>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="errors.has('dob') ? ' has-error' : ''">
                            <label for="dob" class="col-md-3 control-label">Date Of Birth </label>
                            <div class="col-md-7">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="dob" id="dob">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                    <span class="help-block" v-text="errors.get('dob')"></span>
                                </div>
                            </div>
                        </div>

                        <div class="clear-fix"></div>
                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                                <a type="button" href="{{ url('/admin/users') }}" class="btn btn-danger">
                                    Cancel
                                </a>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </component>
    </section>
@endsection
