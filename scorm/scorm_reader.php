<?php

/**
 * 
 */
class SCORMReader
{

    /**
     * 
     */
    private $scorm;

    /**
     * 
     */
    private $scorm_array;

    /**
     * 
     */
    function __construct()
    {
        //
        $this->scorm = new DomDocument;
        $this->scorm->preserveWhiteSpace = FALSE;
        $this->scorm->formatOutput = true;
        //
        $this->scorm_array = [];
    }


    /**
     * 
     */
    function LoadFile($file)
    {
        //
        $this->scorm->load($file);
        //
        $this->Parse();
        //
        return $this;
    }
    
    
    /**
     * 
     */
    function GetIndex($index = '')
    {
        //
        return isset($this->scorm_array[$index]) ? $this->scorm_array[$index] : $this->scorm_array;
    }


    /**
     * 
     */
    private function Parse()
    {
        //
        $SA = $this->xml_to_array($this->scorm);
        // Check and Get the version
        $this->scorm_array['name'] = $SA['manifest']['metadata']['schema'];
        $this->scorm_array['version'] = $SA['manifest']['metadata']['schemaversion'];
        //
        $this->scorm_array['primaryObjective'] = [];
        $this->scorm_array['objective'] = [];
        $this->scorm_array['deliveryControls'] = [];
        $this->scorm_array['sequencing'] = [];
        $this->scorm_array['resources'] = [];
        // Set primary objective
        if(isset($SA['manifest']['organizations']['organization']['item']['sequencing']['objectives']['primaryObjective'])){
            //
            if(!empty($SA['manifest']['organizations']['organization']['item']['sequencing']['objectives']['primaryObjective']['@attributes'])){
                //
                foreach($SA['manifest']['organizations']['organization']['item']['sequencing']['objectives']['primaryObjective']['@attributes'] as $index => $attr){
                    //
                    $this->scorm_array['primaryObjective'][$index] = $attr;
                }
            }
        }
        // Set Objectives
        if(isset($SA['manifest']['organizations']['organization']['item']['imsss:sequencing']['imsss:objectives']['imsss:objective'])){
            //
            foreach($SA['manifest']['organizations']['organization']['item']['imsss:sequencing']['imsss:objectives']['imsss:objective'] as $objective){
                //
                if(!empty($objective['@attributes'])){
                    //
                    $this->scorm_array['objective'][] = $objective['@attributes']['objectiveID'];
                }
            }
        }
        // Set Delivery Controls
        if(isset($SA['manifest']['organizations']['organization']['item']['sequencing']['deliveryControls']) && !empty($SA['manifest']['organizations']['organization']['item']['sequencing']['deliveryControls']['@attributes'])){
            //
            foreach($SA['manifest']['organizations']['organization']['item']['sequencing']['deliveryControls']['@attributes'] as $index => $attr){
                //
                $this->scorm_array['deliveryControls'][$index] = $attr;
            }
        }
        // Set Sequencing Mode
        if(isset($SA['manifest']['organizations']['organization']['sequencing'])){
            //
            foreach($SA['manifest']['organizations']['organization']['sequencing'] as $index => $sequence){
                //
                $this->scorm_array['sequencing'][$index] = $sequence;
                //
                unset($this->scorm_array['sequencing'][$index]['@attributes']);
                //
                if(!empty($sequence['@attributes'])){
                    foreach($sequence['@attributes'] as $index1 => $v){
                        //
                        $this->scorm_array['sequencing'][$index][$index1] = $v;
                    }
                }
            }
        }
        // Set Resources
        if(isset($SA['manifest']['resources']['resource'])){
            //
            if(!empty($SA['manifest']['resources']['resource']['@attributes'])){
                foreach($SA['manifest']['resources']['resource']['@attributes'] as $index => $attr){
                    $this->scorm_array['resources'][$index] = $attr;
                }
            }
            //
            $this->scorm_array['resources']['files'] = [];
            //
            if(isset($SA['manifest']['resources']['resource']['file'])){
                foreach($SA['manifest']['resources']['resource']['file'] as $index => $attr){
                    //
                    if(!empty($attr['@attributes'])){
                        //
                        $t = [];
                        //
                        foreach($attr['@attributes'] as $index1 => $v){
                            $t[$index1] = $v;
                        }
                        //
                        $this->scorm_array['resources']['files'][] = $t;
                    }
                }
            }
        }
        //
        return $this;
    }


    /**
     * 
     */
    private function xml_to_array($root) 
    {
        $result = array();
    
        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result['@attributes'][$attr->name] = $attr->value;
            }
        }
    
        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }
            }
            $groups = array();
            foreach ($children as $child) {
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = $this->xml_to_array($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = $this->xml_to_array($child);
                }
            }
        }
    
        return $result;
    }

}