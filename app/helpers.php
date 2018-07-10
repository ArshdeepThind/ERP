<?php 
use Illuminate\Contracts\Encryption\DecryptException;

function flash($message, $level='info')
{
	session()->flash('flash_message',$message);
	session()->flash('flash_message_level',$level);
}

function mpr($d, $echo = TRUE)
{
   	if($echo)
   	{
   		echo '<pre>'.print_r($d, true).'</pre>';
   	}
   	else
   	{
   		return '<pre>'.print_r($d, true).'</pre>';
    }
}

function mprd($d){
   mpr($d);
   die;
}

function clean($string){
  $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
  return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function randomInteger($num=6){
  $value=1;
  $start=str_pad($value, $num, '0', STR_PAD_RIGHT);
  $end=($start*10) - 1;
  return mt_rand($start, $end);
}

function serializeEnc($array){
  return encrypt(serialize($array));
}

function serializeDec($array){
  return unserialize(decrypt($array));
}
function getFormatedDate($date=''){
    if($date==''){
      return null;
    }
    if(is_string($date)){
      $d = \Carbon\Carbon::parse($date);
      return $d->format('d-m-Y');
    }
    else{
      $className = get_class($date);
      switch ($className) {
        case 'Carbon\Carbon':
          return $date->format('d-m-Y');
          break;
        default:
          # code...
          break;
      }
    }

}