@extends('employee.layout.auth')
@section('content')
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="login-header text-center">
                <h2><b>EMPLOYEE LOGIN</b></h2>
            </div>
                   
            <component is='auth-validate' inline-template>
                <div class="login-box-body">
                    <h4 class="content-group">ERP LOGIN FORM</h4>
                    <form role="form" method="POST" action="{{ url('/employee/login') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} input-with-icon " >
                            <input id="email" type="email" :class="mailClasses" name="email" value="{{ old('email') }}" autofocus placeholder="Username (Email)" v-model="email">
                              <div class="form-control-feedback">
                                <i class="fa fa-user text-muted"></i>
                              </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} input-with-icon">
                            <input id="password" type="password" class="form-control" name="password" placeholder="Password" v-model='password'>
                            <div class="form-control-feedback">
                                <i class="fa fa-lock text-muted"></i>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <a class="btn btn-link" href="{{ url('/employee/password/reset') }}">
                                    Forgot Your Password?
                                </a>    
                            </div>
                            <!-- /.col -->
                        </div>
                       
                        <div class="row">     
                            <div class="col-xs-12">
                                  <button type="submit" class="btn btn-danger btn-block btn-material" :disabled="hideLoginSubmit">LOGIN</button>
                            </div>
                        </div>
                    </form>
                    <!-- /.social-auth-links -->
                    
                </div>
            </component>
            <!-- /.login-box-body -->
        </div>
@endsection
