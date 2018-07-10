@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
@endsection
@section('content')
    <section class="content-header">
        <h1>
            User Edit
            <small>Manage Users</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/admin/users') }}">Users</a></li>
            <li class="active">Edit User</li>
        </ol>
    </section>
    <section class="content" id="app">

    <component is='add-user' userids={{$userId}}  imgsrc = {{$picture}} inline-template> 
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit User</h3>
                    </div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/users/'.$user->id) }}" enctype="multipart/form-data" @submit.prevent='onSubmit(<?php echo $user->id; ?>)' @keydown = 'errors.clear($event.target.name)'>
                        <div class="box-body">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div v-if='showAlert'>
                             <alert :type="alertType">@{{ alertText }}</alert>
                        </div>
                        <input type="hidden" v-text="{{ $user->id }}" v-model = "id" name="id" id="id" />
                        <div class="form-group" v-bind:class="errors.has('firstname') ? ' has-error' : ''">
                                <label for="firstname" class="col-md-3 control-label">First Name</label>

                                <div class="col-md-7">
                                    <input id="firstname" type="text" class="form-control" name="firstname" value="{{ $user->firstname }}" v-model="firstname">

                                    
                                        <span class="help-block" v-text="errors.get('firstname')"></span>
                                    
                                </div>
                        </div>
                            {{-- Last Name --}}
                        <div class="form-group" v-bind:class="errors.has('lastname') ? ' has-error' : ''">
                                <label for="lastname" class="col-md-3 control-label">Last Name</label>

                                <div class="col-md-7">
                                    <input id="lastname" type="text" class="form-control" name="lastname" value="{{ $user->lastname }}" v-model="lastname">

                                    
                                      <span class="help-block" v-text="errors.get('lastname')"></span>
                                    
                                </div>
                        </div>
                        {{-- Email --}}
                        <div class="form-group" v-bind:class="errors.has('email') ? ' has-error' : ''">
                            <label for="email" class="col-md-3 control-label">Email</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" v-model="email">

                                    <span class="help-block" v-text="errors.get('email')"></span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="errors.has('phone') ? ' has-error' : ''">
                            <label for="phone" class="col-md-3 control-label">Phone</label>

                            <div class="col-md-7">

                                <input id="phone" name="phone" type="tel" class="form-control" value="{{ $user->phone }}" v-model="phone"> 

                                    <span class="help-block" v-text="errors.get('phone')"></span>
                               
                            </div>
                        </div>
                        {{-- PIC --}}
                        <div class="form-group" v-bind:class="errors.has('pic') ? ' has-error' : ''">
                            <label for="pic" class="col-md-3 control-label">Picture</label>

                            <div class="col-md-7">
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
                                    <input type="radio" value='Male' name="gender" class="minimal" v-model="gender" {{$user->gender = 'Male'?'checked':''}}>
                                    Male
                                </label>
                                <label>
                                    <input type="radio" value='Female' name="gender" class="minimal" v-model="gender" {{$user->gender = 'Female'?'checked':''}}>
                                    Female
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dob" class="col-md-3 control-label">Date Of Birth </label>
                            <div class="col-md-7">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" value="{{ $user->dob }}" name="dob" id="dob">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
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
