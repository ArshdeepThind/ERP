@extends('employee.layout.master')
@section('content')
	<section class="content-header">
        <h1>
            Settings
            <small>Manage Settings</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Settings</a></li>
            <li class="active">General</li>
        </ol>
    </section>

  <section class="content" id="app"> 

        <div class="row">
            <div class="col-md-12">
              <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Settings</h3>
                    </div>
                @include('flash')
                <form class="form-horizontal" role="form" method="POST" 
                    action="{{ url('/employee/settings/') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    @foreach ($settings as $setting)
                    <div class="form-group{{ $errors->has($setting->label) ? ' has-error' : '' }}">
                        <label for="{{$setting->label}}" class="col-md-3 control-label">{{$setting->label}}</label>
                        <div class="col-md-7">
                            <input id="{{$setting->label}}" type="text" class="form-control" name="{{$setting->label}}" value="{{$setting->value}}" autofocus>
                            @if ($errors->has($setting->label))
                                <span class="help-block">
                                    <strong>{{ $errors->first($setting->label) }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                   
                    <div class="clear-fix"></div>
                    <div class="form-group">
                        <div class="col-md-7 col-md-offset-3">
                            <button type="submit" class="btn btn-primary">
                                Update Settings
                            </button>
                        </div>
                    </div>
                </form>
              </div>     
            </div>
        </div>
</section>
@endsection
