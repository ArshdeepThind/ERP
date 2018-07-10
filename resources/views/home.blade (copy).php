@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Customers</div>
        
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/customer/') }}" enctype="multipart/form-data" @submit.prevent='onSubmit' @keydown = 'errors.clear($event.target.name)'>
                        {{ csrf_field() }}

                        <div v-if='showAlert'>
                             <alert :type="alertType" v-text = "alertText"></alert>
                        </div>


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="name" class="form-control" name="name" value="{{ old('name') }}"  autofocus v-model="name">

                               <span class="help is-danger" v-text="errors.get('name')">
                                </span>
                            </div>

                             
                        </div>

                           <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"  v-model="email" autofocus>

                                <span class="help is-danger" v-text="errors.get('email')">
                                        
                                    </span>
                            </div>

                             
                        </div>



                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Vendors</label>

                            <div class="col-md-6">
                                <select multiple="multiple" name="vendors[]" id="vendors" v-model="vendors" @change="clearerror">
                                    @foreach($vendor as $key => $avendor)
                                            <option value="{{$avendor->id}}">{{$avendor->name}}</option>
                                    @endforeach
                                </select>

                               
                                    <span class="help is-danger" v-text="errors.get('vendors')">
                                        
                                    </span>
                               

                            </div>
                        </div>
                      
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" :disabled="errors.any()">
                                    Create Customer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
