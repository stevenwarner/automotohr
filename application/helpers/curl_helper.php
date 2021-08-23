<?php
//
if(!function_exists('SendRequest')){
    function SendRequest($url, $options = [], $plain = false, $withInfo = false){
        //
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
        ] + $options;
        //
        $curl = curl_init();
        //
        curl_setopt_array(
            $curl, 
            $options
        );
        //
        $response = curl_exec($curl);
        //
        $info = [];
        //
        if($withInfo){
            $info = curl_getinfo($curl);
        }
        //
        curl_close($curl);
        //
        if(!$plain){
            //
            $response = json_decode($response, true);
        }
        return [
            'Info' => $info,
            'Response' => $response
        ];
    }
}
