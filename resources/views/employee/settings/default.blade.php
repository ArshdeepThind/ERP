@extends('clinic.layout.auth')
@section('content')
	<section class="content" id="app">
	      <div class="col-sm-12">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Settings</a></li>
            <li class="breadcrumb-item active">General</li>
          </ol>
        </div>

          <div class="col-12">
              <div class="dashboard-box">
                  <div class="row bs-example-support-detail">
                      <div class="col-12">
                        <span class="inner-box-support">DEFAULT SETTINGS</span>
                        <p>Default settings are the user’s preferred choices for usual technical details. These will be selected by default in your cases, but you will be able to change them as not all cases can be handled always in the same manner. Please make sure you review each case when you do it.</p>
                      </div>
                  </div>
              </div>
          </div>
          <settingdefault></settingdefault>  
         

	       
        
     </section>
@endsection
