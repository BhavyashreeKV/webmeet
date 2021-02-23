<?php 
/* -----------------------------------------------
    This is a geohelper for fetching the geocoding from the different 
    apis and diffrent solutions eg. Google, Mapbox, Location Iq
-------------------------------------------------- */

/* Google Geocode API 
    This function is restricted to give the Latitude and Longitude for the 
    entered address. Print the response to check much more options like location,
    geomentry, address formats, etc.
*/
function get_lat_lng($address)
{
        $return  = array('latitude'=>'','longitude'=>'');
        $address = str_replace(" ", "+", $address);
    $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyCforhwQ0BRHgJNZ89z34sT_ptU_I3NYeU";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
    $responseJson = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($responseJson);
    if ($response->status == 'OK') {
        $latitude = $response->results[0]->geometry->location->lat;
        $longitude = $response->results[0]->geometry->location->lng;
        $return['latitude']=$latitude;
        $return['longitude']=$longitude;
        return $return;
    } else {
        return  $return;
        // return var_dump($response);
    }    
}


/* Location IQ Geocode */
function location_IQ($address)
{
    /*
    Refence Link : https://locationiq.com/docs
    The Search API allows converting addresses, such as a street address,
    into geographic coordinates (latitude and longitude), Here the type is mentioned to POI - Point of Intrest */
    $address = str_replace(" ", "+", $address);
    $key = config_item('location_IQ_key');
    $curl = curl_init('https://api.locationiq.com/v1/autocomplete.php?key='.$key.'&q='.urlencode($address).'&format=json&type=poi');
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER    =>  true,
        CURLOPT_FOLLOWLOCATION    =>  true,
        CURLOPT_MAXREDIRS         =>  10,
        CURLOPT_TIMEOUT           =>  30,
        CURLOPT_CUSTOMREQUEST     =>  'GET',
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        
        return 'cURL Error #:' . $err;
    } else {
        return json_decode($response,true);
    }
}

/* Mapbox Geocode */
function mapboxGeocode($address)
    {
        /*
        The Search API allows converting addresses, such as a street address,
        into geographic coordinates (latitude and longitude)*/
        // $access_key = 'pk.eyJ1IjoiZGV2MjAyMCIsImEiOiJjanpsOHlsc28wMGhjM29wNGt6c2Y0bTY2In0.lPcUXnOKhMRMMwmlKdCrrg';
        $access_key = config_item('geocode_access_token');
        $curl = curl_init('https://api.mapbox.com/geocoding/v5/mapbox.places/'.urlencode($address).'.json?access_token='.$access_key);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER    =>  true,
            CURLOPT_FOLLOWLOCATION    =>  true,
            CURLOPT_MAXREDIRS         =>  10,
            CURLOPT_TIMEOUT           =>  30,
            CURLOPT_CUSTOMREQUEST     =>  'GET',
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
           
            return 'cURL Error #:' . $err;
        } else {
           
            return json_decode($response,true);
        }
    }
