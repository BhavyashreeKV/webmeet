<?php
function getCurrencyExchangeRates($from_curr="EUR",$to_curr="SEK")
{
    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,"https://api.exchangeratesapi.io/latest?base=$from_curr&symbols=$to_curr");
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
    $return  = json_decode($buffer,true);
    if(isset($return['error']))
    {
        return array();
    }

    return $return;
}

function convert_currency($convert_currency,$amount)
{
    $CI = &get_instance();
    $tbl_rate = $CI->db->where('currency',$convert_currency)->get('currency_rates')->row();
    if(!empty($tbl_rate))
    {
        $amount = $amount * $tbl_rate->rates;
    }

    return $amount;
}

