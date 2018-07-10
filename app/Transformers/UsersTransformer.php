<?php

namespace App\Transformers;
class UsersTransformer extends  Transformer
{
	public function transform($item) {

		return [
			'id'         => $item->id,
			'firstname'  => $item->firstname,
			'lastname'   => $item->lastname,
			"email"      => $item->email,
			"phone"      => $item->phone,
			"wallet"     => $item->wallet,
			"credit"     => $item->credit,
			"total"		 => ($item->wallet+$item->credit),
			"gender"     => $item->gender,
			"dob"      	 => $item->dob,
			"is_verified"=> $item->is_verified,
			'status'     => $item->status,
			'region_code' => $item->region_code,
			'created_at' => $item->created_at->format('Y-m-d H:i:s'),
		];
	}

}