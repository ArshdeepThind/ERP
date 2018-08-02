@extends('employee.layout.auth')

<!-- Main Content -->
@section('content')
        
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="login-header text-center">
                <h2><b>RESET PASSWORD</b></h2>
            </div>
                   
            
            <div class="login-box-body">
                <h4 class="content-group">Enter Email to Reset Password</h4>
                <form role="form" method="POST" action="{{ url('/employee/password/email') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} input-with-icon " >
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus placeholder="Username (Email)" v-model="email">
                          <div class="form-control-feedback">
                            <i class="fa fa-envelope text-muted"></i>
                          </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="row">                            
                        <div class="col-xs-6"></div>
                    <div class="col-xs-6">
                                <a class="btn btn-link pull-right" href="{{ url('/employee') }}">
                                    Back to login
                                </a> 
                    </div>
                    </div>

                    <div class="row">     
                        <div class="col-xs-12">
                              <button type="submit" class="btn btn-danger btn-block btn-material">Send Password Reset Link</button>
                        </div>
                    </div>
                </form>
                <!-- /.social-auth-links -->
                
            </div>
      
            <!-- /.login-box-body -->
        </div>
@endsection
