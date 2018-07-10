@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="login-box" id="app">
                <div class="login-logo">
                    <a href="#"><img src="{{ asset('/images/logo.png') }}" style="height: 150px"></a>
                </div>

                <div class="login-box-body">
                       <label>Now this link is expired. If you want to reset again, please generate new request. <br/>Thank you.</label>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
