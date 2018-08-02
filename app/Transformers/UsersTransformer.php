<?php

namespace App\Transformers;
class UsersTransformer extends  Transformer
{
	public function transform($item) {

		return [
			'receiverID'         => $item->receiverID,
			'receiverName'  => $item->receiverName
		];
	}

	public function roleName($roles){
		$inRoles=[];
		if(count($roles) > 0){
			foreach ($roles as $role) {
				$inRoles[]=$role['name'];
			}
		}
		return $inRoles;
	}
}