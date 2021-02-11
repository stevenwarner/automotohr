<?php
require_once APPPATH . '/libraries/parsecsv.lib.php';
class Parse_csv {

    function ParseFile($file){
        $csv = new parseCSV();

        $csv->auto($file);

        $parsedData = array();
        $parsedData['titles'] = $csv->titles;
        $parsedData['data'] = $csv->data;

        return $parsedData;
    }


}
