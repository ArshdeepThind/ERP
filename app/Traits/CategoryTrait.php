<?php 
namespace App\Traits;
use App\Apicategory;

trait CategoryTrait{
	
	public function fetchSliderOptions($selected=''){
		$results=Apicategory::where('status',ACTIVE_STATUS)->get();
		$selectBox='<select class="form-control" name="category_id">';
		if(count($results)>0){
			$selectBox.='<option value="">Select Category</option>';
			foreach ($results as $result) {
				if($selected==$result->id){
					$setSelectedAttr='selected';
				}
				else{
					$setSelectedAttr='';	
				}
	        	$selectBox.='<option value="'.$result->id.'" '.$setSelectedAttr.'>'.$result->title.'</option>';
			}
		}
		else{
			$selectBox.='<option value="">No Category Available</option>';
		}
		$selectBox.='</select>';

		return $selectBox;
	}
}