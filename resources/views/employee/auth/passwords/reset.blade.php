@extends('employee.layout.auth')
@section('content')
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="login-header text-center">
                <h2><b>RESET PASSWORD</b></h2>
            </div>
                   
            <component is='auth-validate' inline-template>
                <div class="login-box-body">
                    <h4 class="content-group">PASSWORD RESET FORM</h4>
                    <form role="form" method="POST" action="{{ url('/employee/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <div class="col-md-12">
                                <input id="email" placeholder="Email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }} input-with-icon">

                            <div class="col-md-12">
                                <input id="password" placeholder="Password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    <div class="row form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} input-with-icon">
                            <div class="col-md-12">
                                <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                    </div>

                       
                        <div class="row">     
                            <div class="col-xs-12">
                                  <button type="submit" class="btn btn-danger btn-block btn-material">RESET PASSWORD</button>
                            </div>
                        </div>
                    </form>
                    <!-- /.social-auth-links -->
                    
                </div>
            </component>
            <!-- /.login-box-body -->
        </div>
@endsection
