@extends('employee.layout.master')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />

<style>
	.video_positioner {
		width: 50%;
		height: 50%;
		position: absolute;
		left: 0;
		top: 0;
	}

	.video_icon {
		position: absolute;
		right: -25px;
		bottom: -25px;
		width: 50px;
		height: 50px;
		background: url(http://www.slatecube.com/images/play-btn.png);
		background-size: 50px 50px;
		background-repeat: no-repeat;
	}
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
	var flag = 1;
	$('#angletoggle').click(function(e) {
		if(flag == 0) {
			$('#main').removeClass('col-xl-8');
			$('#main').addClass('col-xl-6');

			$('#sidebar').removeClass('d-none');
			$('#sidebar').addClass('d-xl-block');

			$('#angletoggle').removeClass("active");
			flag = 1;
		} else {
			$('#main').removeClass('col-xl-6');
			$('#main').addClass('col-xl-8');

			$('#sidebar').addClass('d-none');
			$('#sidebar').removeClass('d-xl-block');

			$('#angletoggle').addClass("active");
			flag = 0;
		}
	});

	$().fancybox({
		selector : '[data-fancybox="chat_gallery"]',
		loop	: true
	});
});
</script>

@endsection

@section('content')

	<section class="content" id="app">
		<div class="col-sm-12">

		</div>

		<chat employee_name='{{$employee_name}}' employee_id='{{$employee_id}}' v-bind="chat_data_parent"></chat>
	</section>

@endsection
