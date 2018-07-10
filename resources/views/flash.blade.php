@if(session()->has('flash_message'))
	<div class="animated bounce alert alert-dismissable alert-{{ session('flash_message_level') }}">
        <strong>{{ ucwords(session('flash_message_level')) }}!</strong>  {{ session('flash_message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden="true">&times;</button>
    </div>    
@endif