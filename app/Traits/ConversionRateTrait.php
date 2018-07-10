<?php 

namespace App\Traits;
use App\Currency;
use App\Conversion;

trait ConversionRateTrait{
	public function conversionRateslist(){
        $convesion = \DB::table('conversion')
        ->join('currency as ct', 'ct.id', '=', 'conversion.to_id')
        ->join('currency as cc', 'cc.id', '=', 'conversion.currency_id')
        ->select('conversion.rate','ct.name as to_name','cc.name as currency_name',
            'ct.label as to_label','cc.label as currency_label')
        ->orderBy("cc.name", "asc")
        ->get();
        if(!$convesion->count()){
            return [];
        }
        return $convesion;
    }
    /**
     * [checkConversion description]
     * @param  [STRING] $fromCountryoffline [description]
     * @param  [STRING] $toCountryoffline   [description]
     * @return [ARR]                     [description]
     */
    public function checkConversion($fromCountryoffline, $toCountryoffline){
        $fromCurruncy = Currency::getByName($fromCountryoffline);
        if(! $fromCurruncy){
            flash(trans("messages.CURR_NOT_EXISTS",["name"=>$fromCountryoffline]),'danger');
            return false;
        }
        $toCurruncy = Currency::getByName($toCountryoffline);
        if(! $toCurruncy){
            flash(trans("messages.CURR_NOT_EXISTS",["name"=>$toCountryoffline]),'danger');
            return false;
        }
        $rates = Conversion::getRates($fromCurruncy->id, $toCurruncy->id);
        if(!$rates){
            flash(trans("messages.CONVERSION_NOT_AVAIALBLE",["from"=>$fromCountryoffline, "to"=>$toCountryoffline]),'danger');
            return false;
        }
        $from_curruncy_id = $fromCurruncy->id;
        $to_curruncy_id   = $toCurruncy->id;
        $conversion_rate  = $rates->rate;
        return [
            "from_curruncy_id" =>$from_curruncy_id,
            "to_curruncy_id"   =>$to_curruncy_id,
            "conversion_rate"  =>$conversion_rate,
        ];
    }
}