@extends('employee.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datepicker/datepicker3.css') }}">
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Employee Edit
            <small>Manage Employees</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/employee/employees') }}">Employees</a></li>
            <li class="active">Edit Employee</li>
        </ol>
    </section>
    <section class="content" id="app">
    <component is='add-employee' employeeids = {{$employeeId}} imgsrc = {{$picture }} inline-template> 
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Employee</h3>
                    </div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/employee/employees'.$employee->id) }}" enctype="multipart/form-data"  @submit.prevent='onSubmit(<?php echo $employee->id; ?>)' @keydown = 'errors.clear($event.target.name)'>
                        <div class="box-body">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div v-if='showAlert'>
                             <alert :type="alertType">@{{ alertText }}</alert>
                        </div>

                        <input type="hidden" v-text="{{ $employee->id }}" v-model = "id" name="id" id="id" />
                        <div class="form-group" v-bind:class="errors.has('first_name') ? ' has-error' : ''">
                                <label for="first_name" class="col-md-3 control-label">First Name<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $employee->first_name }}" v-model="first_name">

                                    
                                        <span class="help-block" v-text="errors.get('first_name')"></span>
                                    
                                </div>
                        </div>
                            
                        <div class="form-group" v-bind:class="errors.has('last_name') ? ' has-error' : ''">
                                <label for="last_name" class="col-md-3 control-label">Last Name<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $employee->last_name }}" v-model="last_name">

                                    
                                      <span class="help-block" v-text="errors.get('last_name')"></span>
                                    
                                </div>
                        </div>

                        <div class="form-group" v-bind:class="errors.has('email') ? ' has-error' : ''">
                            <label for="email" class="col-md-3 control-label">Email<span class="red">*</span></label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $employee->email }}" v-model="email">

                                    <span class="help-block" v-text="errors.get('email')"></span>
                            </div>
                        </div>
                        
                       <div class="form-group" v-show="edit" v-if="edit">
                            <div class="col-md-10">
                               <center> <input id="changepassword" name="changepassword" type="button" class="btn btn m-b-xs w-ms btn-danger" value="Change Password" @click="showpasswordfield()" /></center>
                            </div>
                        </div>

                         <div class="form-group" v-if="showme" v-show="showme" v-bind:class="errors.has('password') ? ' has-error' : ''">
                                <label for="password" class="col-md-3 control-label">Password<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="password" type="password" class="form-control" name="password" value="{{ $employee->password }}" v-model="password">
                                    <span class="help-block" v-text="errors.get('password')"></span>
                                </div>
                        </div>

                         <div class="form-group" v-show="showme" v-if="showme">
                            <div class="col-md-10">
                               <center> <input type="button" class="btn btn m-b-xs w-ms btn-danger" value="Hide Password Fields" @click="hidepasswordfield()" /></center>
                            </div>
                        </div>

                     
                        
                        <div class="form-group" v-bind:class="errors.has('phone_number') ? ' has-error' : ''">
                            <label for="phone_number" class="col-md-3 control-label">Phone_number<span class="red">*</span></label>

                            <div class="col-md-7">

                                <input id="phone_number" name="phone_number" type="tel" class="form-control" v-model="phone_number"> 

                                <span class="help-block" v-text="errors.get('phone_number')"></span>
                               
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
                                    <input type="radio" value='Male' name="gender" class="minimal" {{$employee->gender = 'Male'?'checked':''}} v-model="gender">
                                    Male
                                </label>
                                <label>
                                    <input type="radio" value='Female' name="gender" class="minimal" v-model="gender" {{$employee->gender = 'Female'?'checked':''}}>
                                    Female
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="marital_status" class="col-md-3 control-label">Marital Status </label>
                            <div class="col-md-7">
                                <label>
                                    <input type="radio" value='1' name="marital_status" class="minimal" {{$employee->marital_status = '1'?'checked':''}} v-model="marital_status">
                                    Yes
                                </label>
                                <label>
                                    <input type="radio" value='0' name="marital_status" class="minimal" {{$employee->marital_status = '0'?'checked':''}} v-model="marital_status">
                                    No
                                </label>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="errors.has('dob') ? ' has-error' : ''">
                            <label for="dob" class="col-md-3 control-label">Date Of Birth<span class="red">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="dob" id="dob" value="{{ $employee->date_of_birth }}">
                                    <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                    <span class="help-block" v-text="errors.get('dob')"></span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group" v-bind:class="errors.has('joining_date') ? ' has-error' : ''">
                            <label for="joining_date" class="col-md-3 control-label">Joining Date<span class="red">*</span></label>
                            <div class="col-md-7">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" name="joining_date" id="joining_date" value="{{ $employee->joining_date }}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                    <span class="help-block" v-text="errors.get('joining_date')"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="errors.has('address1') ? ' has-error' : ''">
                                <label for="address1" class="col-md-3 control-label">Address Line1<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="address1" type="text" class="form-control" name="address1" value="{{ $employee->address1 }}" v-model="address1">
                                    
                                      <span class="help-block" v-text="errors.get('address1')"></span>
                                    
                                </div>
                        </div>


                        <div class="form-group">
                                <label for="address2" class="col-md-3 control-label">Address Line2</label>

                                <div class="col-md-7">
                                    <input id="address2" type="text" class="form-control" name="address2" value="{{ $employee->address2 }}" v-model="address2">
                                </div>
                        </div>


                        <div class="form-group" v-bind:class="errors.has('zipcode') ? ' has-error' : ''">
                                <label for="zipcode" class="col-md-3 control-label">zipcode<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="zipcode" type="text" class="form-control" name="zipcode" value="{{ $employee->zipcode }}" v-model="zipcode">
                                    
                                      <span class="help-block" v-text="errors.get('zipcode')"></span>
                                    
                                </div>
                        </div>


                        <div class="form-group" v-bind:class="errors.has('city') ? ' has-error' : ''">
                                <label for="city" class="col-md-3 control-label">city<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="city" type="text" class="form-control" name="city" value="{{ $employee->city }}" v-model="city">
                                    
                                      <span class="help-block" v-text="errors.get('city')"></span>
                                    
                                </div>
                        </div>


                        <div class="form-group" v-bind:class="errors.has('state') ? ' has-error' : ''">
                                <label for="state" class="col-md-3 control-label">state<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="state" type="text" class="form-control" name="state" value="{{ $employee->state }}" v-model="state">
                                    
                                      <span class="help-block" v-text="errors.get('state')"></span>
                                    
                                </div>
                        </div>


                        <div class="form-group" v-bind:class="errors.has('country') ? ' has-error' : ''">
                                <label for="country" class="col-md-3 control-label">country<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="country" type="text" class="form-control" name="country" value="{{ $employee->country }}" v-model="country">
                                    
                                      <span class="help-block" v-text="errors.get('country')"></span>
                                    
                                </div>
                        </div>

                        <div class="form-group" v-bind:class="errors.has('current_salary') ? ' has-error' : ''">
                                <label for="current_salary" class="col-md-3 control-label">Current Salary<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="current_salary" type="text" class="form-control" name="current_salary" value="{{ $employee->current_salary }}" v-model="current_salary">
                                    
                                      <span class="help-block" v-text="errors.get('current_salary')"></span>
                                    
                                </div>
                        </div>

                        <div class="form-group" v-bind:class="errors.has('gross_salary') ? ' has-error' : ''">
                                <label for="gross_salary" class="col-md-3 control-label">Gross Salary<span class="red">*</span></label>

                                <div class="col-md-7">
                                    <input id="gross_salary" type="text" class="form-control" name="gross_salary" value="{{ $employee->gross_salary }}" v-model="gross_salary">
                                    
                                      <span class="help-block" v-text="errors.get('gross_salary')"></span>
                                    
                                </div>
                        </div>


                        <div class="form-group" v-bind:class="errors.has('loan_amount') ? ' has-error' : ''">
                                <label for="loan_amount" class="col-md-3 control-label">Loan Amount</label>

                                <div class="col-md-7">
                                    <input id="loan_amount" type="text" class="form-control" name="loan_amount" value="{{ $employee->loan_amount }}" v-model="loan_amount">
                                </div>
                        </div>


                         <div class="form-group" v-bind:class="errors.has('job') ? ' has-error' : ''">
                            <label for="job" class="col-md-3 control-label">Select Job</label>

                            <div class="col-md-3">
                               <select name="job" class="form-control" id="job" v-model="job" @change="clearjoberror">
                                    @foreach($jobs as $key => $job)
                                        @if($employee->jobs->toarray()[0]['job_id'] == $job->job_id)
                                            <option value="{{$job->job_id}}" selected>{{$job->job_title}}</option>
                                        @else
                                            <option value="{{$job->job_id}}">{{$job->job_title}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="help-block" v-text="errors.get('job')"></span>
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
