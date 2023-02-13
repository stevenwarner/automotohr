<?php

	/**
	 * 
	 */
	class Scorm_lib
	{

	    /**
	     * 
	     */
	    private $scorm;

	    /**
	     * 
	     */
	    private $scorm_array;
	    private $scorm_items;
	    private $scorm_versions;
	    private $resources;
	    private $organization;
	    private $primary_objectives;
	    private $single_item;
	    private $multiple_items;
	    private $nested_items;

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
	        $this->resources = [];
	        $this->scorm_array = [];
	        $this->scorm_items = [];
	        $this->organization = [];
	        $this->primary_objectives = [];
	        //
	        $this->scorm_versions = [
	        	"20043rd",
	        	"20044th",
	        	"12"
	        ];
	        //
	        $this->single_item = [
	        	"advancedruntime",
	        	"basicruntime"
	        ];
	        //
	        $this->multiple_items = [
	        	"randomtest", 
	        	"multioscosinglefile", 
	        	"minimumcalls",
	        	"posttestrollup4thEd",
	        	"forcedsequential",
	        	"posttestrollup"
	        ];
	        //
	        $this->nested_items = [
	        	"preorposttestrollup",
	        	"simpleremediation"
	        ];
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
	        //
	        $this->processScormBasicInfo($SA['manifest']);
	        //
	        if (
	        	in_array($this->scorm_array['version'], $this->scorm_versions)
	        ) {
	        	//
	        	// Check Scorm has single item
	        	if (
	        		in_array($this->scorm_array['type'], $this->single_item)
	        	) {
	        		//
	        		$this->getSingleScormItem($this->organization);
	        	}
	        	//
	        	// Check Scorm has multiple items
	        	if (
	        		in_array($this->scorm_array['type'], $this->multiple_items)
	        	) {
	        		$this->getMultipleScormItems($this->organization);
	        	}
	        	//
	        	// Check Scorm has nested items
	        	if (
	        		in_array($this->scorm_array['type'], $this->nested_items)
	        	) {
	        		$this->getNestedScormItems($this->organization);
	        	}
	        	//
	        	if(!empty($this->scorm_items)) {
    				$this->scorm_array['items'] = $this->manageScromSequencing($this->scorm_items, $this->resources);
    				//
    				if ($this->scorm_array['type'] == "simpleremediation"  && $this->scorm_array['version'] == "20043rd" ) {
			    		$this->getPairItems($this->scorm_items);
			    	} else {
			    		$this->scorm_array['sequencing'] = $this->scorm_array['items'];
			    	}
			    	//
	        	}
	        	//
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

	    /**
	     * 
	     */
	    private function processScormBasicInfo ($manifest) {
	    	//
	        $scromBasicInfo = explode('.', $manifest['@attributes']['identifier']);
	        $this->organization = $manifest['organizations']['organization'];
	        $this->resources = $manifest['resources']['resource'];
	        //
	        // Check and Get the course title
	        if(isset($this->organization['title']) && !empty($this->organization['title'])) {
	        	$this->scorm_array['title'] = $this->organization['title'];
	        }
	        //
	        // Get the version, name and type
	        $this->scorm_array['name'] = $manifest['metadata']['schema'];
	        // $this->scorm_array['version'] = $manifest['metadata']['schemaversion'];
	        $this->scorm_array['type'] = $scromBasicInfo[4];
	        $this->scorm_array['version'] = $scromBasicInfo[5];
	        //
	        // Assign sequencing and objectives
	        $this->scorm_array['startPoint'] = 0;
	        $this->scorm_array['storage'] = 0;
	        $this->scorm_array['items'] = [];
	        $this->scorm_array['sequencing'] = [];
	        $this->scorm_array['objectives'] = [];
	        //
	        if ($this->scorm_array['type'] == "simpleremediation"  && $this->scorm_array['version'] == "20043rd" ) {
	    		$this->scorm_array['mapInfo'] = [];
	    	}
	    }

	    /**
	     * 
	     */
	    private function getSingleScormItem ($organization) 
	    {
	        //
			if(isset($organization['item']) && !empty($organization['item'])) {
				//
				$this->scorm_items[0] = $organization['item'];
				//
				if (isset($organization['item']['imsss:sequencing']['imsss:objectives']['imsss:objective'])) {
					$this->manageScromObjective($organization['item']['imsss:sequencing']['imsss:objectives'] , "objective");
				}
				
    		}
			//
			return true;
	    }

	    /**
	     * 
	     */
	    private function getMultipleScormItems ($organization) 
	    {
	        //
			if(isset($organization['item']) && !empty($organization['item'])) {
        		foreach ($organization['item'] as $items) {
        			
        			if(isset($items['item']) && !empty($items['item'])) {
	        			foreach ($items['item'] as $item) {
	        				array_push($this->scorm_items, $item);
	        			}
        			} else {
        				array_push($this->scorm_items, $items);
        			}

        			if(isset($items['imsss:sequencing']) && !empty($items['imsss:sequencing'])) {
        				$this->manageScromObjective($items['imsss:sequencing']["imsss:objectives"]);
        			}
        		}
    		}
			//
			return true;
	    }

	    /**
	     * 
	     */
	    private function getNestedScormItems ($organization) 
	    {
	        //
			if(isset($organization['item']['item']) && !empty($organization['item']['item'])) {
        		foreach ($organization['item']['item'] as $items) {
        			if(isset($items['item']) && !empty($items['item'])) {
	        			foreach ($items['item'] as $item) {
	        				array_push($this->scorm_items, $item);
	        			}
        			} else {
        				array_push($this->scorm_items, $items);
        			}

        			if(isset($items['imsss:sequencing']) && !empty($items['imsss:sequencing'])) {
        				$this->manageScromObjective($items['imsss:sequencing']["imsss:objectives"]);
        			}
        		}
    		}
			//
			return true;
	    }

	    /**
	     * 
	     */
	    private function manageScromObjective ($objectives, $type = "primaryObjective") 
	    {
	        //
	        if ($type == "primaryObjective") {
	        	if (isset($objectives["imsss:primaryObjective"])) {
					if (!empty($objectives["imsss:primaryObjective"])) {
						array_push($this->primary_objectives, $objectives["imsss:primaryObjective"]);
					} else {
						if (isset($objectives["imsss:objective"])) {
							if (!empty($objectives["imsss:objective"])) {
								array_push($this->primary_objectives, $objectives["imsss:objective"]);
							} 
						}
					}
				}
	        } else if ($type == "objective") {
	        	if (isset($objectives["imsss:objective"])) {
					if (!empty($objectives["imsss:objective"])) {
						$this->primary_objectives = $objectives["imsss:objective"];
					} 
				}
	        }
			//
			return true;
	    }

	    /**
	     * 
	     */
	    private function manageScromSequencing ($items, $resources) 
	    {
	        $flag = 1;
	        $result = array();
	        //
	        if (!empty($this->primary_objectives)) {
	        	$flag = 0;
	        }
	        //
	        foreach($items as $index => $item){
				//
				if ($flag == 1) {
					if (isset($item["imsss:sequencing"]["imsss:objectives"])) {
						$this->manageScromObjective($item['imsss:sequencing']["imsss:objectives"]);
					}
				}	
				//
				$response = $this->processScormItem($item, $resources);
				//
				$result[$index] = $response;
            }
            //
            $this->getCourseObjectives($this->primary_objectives);
            //
	        return $result;
	    }

	    /**
	     * 
	     */
	    private function processScormItem ($item, $resources) 
	    {
	        $result = array(
	        	"title" => '',
	        	"parameter" => '',
	        	"slides" => 1
	        );
	        //
			$result['title'] = isset($item['title']) && !empty($item['title']) ? $item['title'] : '';
			//
			$this->checkStorageActive($item);
			//
			if (
				isset($item['@attributes']) && 
				$item['@attributes']['identifierref'] == "assessment_resource" && 
				isset($item['@attributes']['parameters'])
			) {
				$result['parameter'] =  explode("=", $item['@attributes']['parameters'])[1];
			} else {
				//
				$response = $this->processScormResources ($item['@attributes']['identifierref'], $resources); 	
				//
				$result['slides'] = $response['pageCount'];
				$result['parameter'] = $response['reference'];
			}
			//
			return $result;
	    } 

	    /**
	     * 
	     */
	    private function checkStorageActive ($item) 
	    {
	    	if ($item["adlcp:data"]["adlcp:map"]["@attributes"]["targetID"]) {
				//
				$targetID = explode(".", $item["adlcp:data"]["adlcp:map"]["@attributes"]["targetID"])[5];
				//
				if ($targetID = "notesStorage") {
					$this->scorm_array['storage']++;
				}
			}
			//
			return true;
		}	

	    /**
	     * 
	     */
	    private function processScormResources ($identifier, $resources) 
	    {
	        $resourceInfo = array();
	        //
			if (isset($resources['@attributes'])) {
    			$resourceInfo = $this->getResourceInfo($identifier, $resources);
    		} else {
				foreach ($resources as $resource) { 
					if ($resource['@attributes']['identifier'] == $identifier) {
						$resourceInfo = $this->getResourceInfo($identifier, $resource);
					}
				}
			}
			//
			return $resourceInfo;
	    } 


	    /**
	     * 
	     */
	    private function getResourceInfo ($identifier, $resource) 
	    {
	        $result = array(
	        	"pageCount" => 0,
	        	"reference" => ''
	        );
	        //
	        if ($resource['@attributes']['identifier'] == $identifier) {
	        	//
				$href = $resource['@attributes']['href'];
				$result['reference'] = explode("=", $href)[1];
				//
				if (isset($resource['file']) && !empty($resource['file'])) {
					//
					$pageCount = 0;
					//
					foreach ($resource['file'] as $file) {
						if (str_replace('.html', '', $file["@attributes"]["href"]) != $file["@attributes"]["href"]) {
							// 
							if(!preg_match('/launchpage.html/', $file["@attributes"]["href"])) {	
								$pageCount++;	
							}
            			}
					}
					//
					$result['pageCount'] = $pageCount == 0 && $identifier == "assessment_resource" ? 1 : $pageCount;
				} 
			}
			//
			return $result;
	    }    

	    /**
	     * 
	     */
	    private function getCourseObjectives ($objectives) 
	    {
	        //
			if (!empty($objectives)) {
				//
				foreach ($objectives as $objective) {
					if (!empty($objective["@attributes"]["objectiveID"]) && !in_array($objective["@attributes"]["objectiveID"], $this->scorm_array['objectives'])) {
						//
						array_push($this->scorm_array['objectives'], $objective["@attributes"]["objectiveID"]);
					}
				}
			} 
			//
			return true;
	    }

	    /**
	     * 
	     */
	    private function getPairItems ($items) 
	    {
	  
			if (!empty($items)) {
				foreach ($items as $key => $item) {
		    		$title = $item["title"];
		    		$targetObjective = '';
		    		//
		    		if (isset($item["imsss:sequencing"]["imsss:objectives"]["imsss:primaryObjective"]["imsss:mapInfo"]["@attributes"]["targetObjectiveID"])) {
		    			$targetObjective = $item["imsss:sequencing"]["imsss:objectives"]["imsss:primaryObjective"]["imsss:mapInfo"]["@attributes"]["targetObjectiveID"];
		    		}
		    		//
		    		if (!empty($targetObjective)) {
		    			$mapInfo = explode('.', $targetObjective);
		    			$this->scorm_array["mapInfo"][$mapInfo[6]][$title] = $key;
		    		}
		    	}
			}
			//
			if (!empty($this->scorm_array["mapInfo"]) && !empty($this->scorm_array["items"])) {
				foreach ($this->scorm_array["mapInfo"]  as  $mapInfo) {
					foreach ($mapInfo as $index) {
						array_push($this->scorm_array["sequencing"], $this->scorm_array["items"][$index]);
					}
				}
			}
			//
			return true;
	    }

	}
?>	