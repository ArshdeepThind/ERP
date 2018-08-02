@extends('employee.layout.master')
@section('js')
<script type="text/javascript">
	document.getElementById("btnPrint").onclick = function () {
    	printElement(document.getElementById("printThis"));
	}

	function printElement(elem) {
	    var domClone = elem.cloneNode(true);
	    
	    var $printSection = document.getElementById("printSection");
	    
	    if (!$printSection) {
	        var $printSection = document.createElement("div");
	        $printSection.id = "printSection";
	        document.body.appendChild($printSection);
	    }
	    
	    $printSection.innerHTML = "";
	    $printSection.appendChild(domClone);
	    window.print();
	}
</script>
@endsection
@section('content')

	<section class="content-header">
		<h1>
			Payment History
			<small>Employee History</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Payment History</li>
		</ol>
	</section>

	<section class="content" id="app">
		@include('flash')
        <payment-history employee_id='{{$employee_id}}' professional_tax='{{$professional_tax}}' esi='{{$esi}}' headline='Payment History'></payment-history>
	</section>
@endsection
