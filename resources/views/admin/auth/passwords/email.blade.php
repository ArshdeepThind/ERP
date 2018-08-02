@extends('admin.layout.auth')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">


            <div class="login-box" id="app">
                
                <div class="login-box-body">
                    <div class="login-logo">
                         <a href="#"><img src="{{ asset('/images/logo.png') }}" style="height: 100px"></a>
                    </div>
                    <p class="login-box-msg">Enter Email to Reset Password</p>            
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form role="form" method="POST" action="{{ url('/admin/password/email') }}">
                            {{ csrf_field() }}
                            
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} input-with-icon" >
                                <input id="email" type="email" class="mailClasses" placeholder="Email" name="email" value="{{ old('email') }}" autofocus>
                                <span class="glyphicon glyphicon-envelope input-span-icon"></span>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="row">     
                                <div class="col-xs-6">
                                </div>

                                <div class="col-xs-6">
                                    <a class="btn btn-link pull-right" href="{{ url('/admin') }}">
                                        Back to login
                                    </a> 
                                </div>
                            </div>

                           
                             <div class="row">     
                                <div class="col-xs-12">
                                      <button type="submit" class="btn btn-primary btn-block btn-material">Send Password Reset Link</button>
                                </div>

                               
                            </div>


                           <!--  <div class="form-group">
                               <div class="col-md-6 col-md-offset-4">
                                   <button type="submit" class="btn btn-primary">
                                       Send Password Reset Link
                                   </button>
                               </div>
                           </div> -->
                        </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
