<?php  
$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,'http://52.77.117.85:8005/api/searchdrivers');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
$query = curl_exec($curl_handle);
curl_close($curl_handle);
echo $query;
?>