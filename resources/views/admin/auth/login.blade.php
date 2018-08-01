@extends('admin.layout.auth')
@section('content')
    <div class="login-box">
        <!-- /.login-logo -->
        
        <component is='auth-validate' inline-template>
            <div class="login-box-body">
                <div class="login-logo">
                     <a href="#"><img src="{{ asset('/images/logo.png') }}" style="height: 100px"></a>
                </div>
                <p class="login-box-msg">Sign in to start your session</p>
                {{-- <p class="login-box-msg">Pinpoint the perfect pour</p> --}}
                <form role="form" method="POST" action="{{ url('/admin/login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} input-with-icon" >
                        <input id="email" type="email" :class="mailClasses" name="email" value="{{ old('email') }}" autofocus v-model="email">
                        
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} input-with-icon">
                        <input id="password" type="password" class="form-control" name="password" v-model='password'>
                        
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                            <a class="btn btn-link" href="{{ url('/admin/password/reset') }}">
                                Forgot Your Password?
                            </a>    
                        </div>
                        <!-- /.col -->
                    </div>
                   
                    <div class="row">     
                        <div class="col-xs-12">
                              <button type="submit" class="btn btn-danger btn-block btn-material" :disabled="hideLoginSubmit">Login</button>
                        </div>
                    </div>
                </form>
                <!-- /.social-auth-links -->
                
            </div>
        </component>
        <!-- /.login-box-body -->
    </div>
@endsection
