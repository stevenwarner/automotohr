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
	        	"2004 3rd Edition",
	        	"20043rd",
	        	"2004 4th Edition",
	        	"20044th",
	        	"1.2",
	        	"12"
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
	        	$this->getScormItems($this->organization);
	        	//
	        	if(!empty($this->scorm_items)) {
    				$this->scorm_array['items'] = $this->manageScromSequencing($this->scorm_items, $this->resources);
    				$this->getPairItems($this->scorm_items);
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
	        $metaData = array();
	        //
	        if (isset($manifest['metadata']) && !empty($manifest['metadata'])) {
	        	$metaData = $manifest['metadata'];
	        } else if (isset($manifest['organizations']['organization']['metadata'])) {
	        	$metaData = $manifest['organizations']['organization']['metadata'];
	        }
	        //
	        // Get the version, name and type
	        $this->scorm_array['name'] = $metaData['schema'];
	        //
	        if ($metaData['schemaversion'] == "1.2") {
	        	$this->scorm_array['version'] = "12";
	        } else if ($metaData['schemaversion'] == "2004 3rd Edition") {
	        	$this->scorm_array['version'] = "20043rd";
	        } else if ($metaData['schemaversion'] == "2004 4th Edition") {
	        	$this->scorm_array['version'] = "20044th";
	        } else {
	        	$this->scorm_array['version'] = $manifest['metadata']['schemaversion'];
	        }
	        //
	        // Assign sequencing and objectives
	        $this->scorm_array['startPoint'] = 0;
	        $this->scorm_array['storage'] = 0;
	        $this->scorm_array['lastLocation'] = 0;
	        $this->scorm_array['lastChapter'] = 0;
	        $this->scorm_array['launchFile'] = '';
	        $this->scorm_array['suspend_data'] = '';
	        $this->scorm_array['paramKey'] = '';
	        $this->scorm_array['items'] = [];
	        $this->scorm_array['sequencing'] = [];
	        $this->scorm_array['objectives'] = [];
	        $this->scorm_array['mapInfo'] = [];
	    }

	    private function getScormItems ($organization) {
	    	//
			if (isset($organization['item']['item']) && !empty($organization['item']['item'])) {
        		foreach ($organization['item']['item'] as $items) {
        			if(isset($items['item']) && !empty($items['item'])) {
	        			foreach ($items['item'] as $item) {
	        				array_push($this->scorm_items, $item);
	        			}
        			} else {
        				array_push($this->scorm_items, $items);
        			}
        			//
	        		if (isset($items['imsss:sequencing']) && !empty($items['imsss:sequencing'])) {
		        		$this->manageScromObjective($items['imsss:sequencing']["imsss:objectives"]);
		        	}
        		}
    		} else if (isset($organization['item']) && !empty($organization['item'])) {
    			if (isset($organization['item']['title'])) {
    				$this->scorm_items[0] = $organization['item'];
    				//
    				if (isset($organization['item']['imsss:sequencing']['imsss:objectives']['imsss:objective'])) {
						$this->manageScromObjective($organization['item']['imsss:sequencing']['imsss:objectives'] , "objective");
					}
    			} else {
    				foreach ($organization['item'] as $items) {
	        			
	        			if(isset($items['item']) && !empty($items['item'])) {
		        			foreach ($items['item'] as $item) {
		        				array_push($this->scorm_items, $item);
		        			}
	        			} else {
	        				array_push($this->scorm_items, $items);
	        			}
	        			//
	        			if (isset($items['imsss:sequencing']) && !empty($items['imsss:sequencing'])) {
			        		$this->manageScromObjective($items['imsss:sequencing']["imsss:objectives"]);
			        	}
	        		}
    			}
    		}
			//
			return true;
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
	        	"paramValue" => '',
	        	"slides" => 1,
	        	"location" => ''
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
				$result['paramValue'] =  explode("=", $item['@attributes']['parameters'])[1];
				$response = $this->processScormResources ($item['@attributes']['identifierref'], $resources); 
				$result['location'] = $response['href'];
			} else {
				//
				if (isset($item['@attributes']['identifierref'])) {
					$response = $this->processScormResources ($item['@attributes']['identifierref'], $resources); 	
					//
					$result['slides'] = $response['pageCount'];
					$result['paramValue'] = $response['paramValue'];
					$result['location'] = $response['firstLocation'];
					$result['href'] = $response['href'];
				}
			}
			//
			return $result;
	    } 

	    /**
	     * 
	     */
	    private function checkStorageActive ($item) 
	    {
	    	if (isset($item["adlcp:data"]["adlcp:map"]["@attributes"]["targetID"])) {
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
	        	"paramValue" => '',
	        	"firstLocation" => '',
	        	"href" => ''
	        );
	        //s
	        if ($resource['@attributes']['identifier'] == $identifier) {
	        	//
	        	if (isset($resource['@attributes']['href'])) {
	        		$href = explode("?", $resource['@attributes']['href']);
	        		//
					$launchFile = $href[0];
	        		//
	        		if (empty($this->scorm_array['launchFile'])) {
	        			$this->scorm_array['launchFile'] = $launchFile;
	        		}
	        		//
	        		if (empty($this->scorm_array['paramKey'])) {
	        			$this->scorm_array['paramKey'] = explode("=", $href[1])[0];
	        		}
	        		//
					$result['paramValue'] = explode("=", $href[1])[1];
					$result['href'] = $launchFile;
					//
					if (isset($resource['file']) && !empty($resource['file'])) {
						//
						$pageCount = 0;
						$firstLocation = '';
						//
						if (isset($resource['file']['@attributes'])) {
							if (str_replace('.html', '', $resource['file']['@attributes']["href"]) != $resource['file']['@attributes']["href"]) {
								if (empty($firstLocation)) {
									if (str_replace('/', '', $resource['file']['@attributes']["href"]) != $resource['file']['@attributes']["href"]) {
										$location = explode(".", $resource['file']['@attributes']["href"])[0];
										$tokens = explode('/', $location);
										$firstLocation = trim(end($tokens));
									} else 	{
										$firstLocation = explode(".", $resource['file']['@attributes']["href"])[0];
									}
								}
								// 
								if(!preg_match('/$launchFile/', $resource['file']['@attributes']["href"])) {	
									$pageCount++;	
								}
	            			}
						} else {
							foreach ($resource['file'] as $file) {								//
								if (str_replace('.html', '', $file["@attributes"]["href"]) != $file["@attributes"]["href"]) {
									if (empty($firstLocation)) {
										if (str_replace('/', '', $file["@attributes"]["href"]) != $file["@attributes"]["href"]) {
											$location = explode(".", $file["@attributes"]["href"])[0];
											$tokens = explode('/', $location);
											$firstLocation = trim(end($tokens));
										} else 	{
											$firstLocation = explode(".", $file["@attributes"]["href"])[0];
										}
									}
									// 
									if(!preg_match('/$launchFile/', $file["@attributes"]["href"])) {	
										$pageCount++;	
									}
		            			}
							}
						}
						//
						$result['firstLocation'] = $firstLocation;
						//
						$result['pageCount'] = $pageCount == 0 && $identifier == "assessment_resource" ? 1 : $pageCount;
					} 
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
	    	$mapItemsCount = 0;
	    	//
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
		    			$mapItemsCount++;
		    		}
		    	}
			}
			//
			if (!empty($this->scorm_array["mapInfo"]) && !empty($this->scorm_array["items"])) {
				if ($mapItemsCount == count($items)) {
					foreach ($this->scorm_array["mapInfo"]  as  $mapInfo) {
						foreach ($mapInfo as $index) {
							array_push($this->scorm_array["sequencing"], $this->scorm_array["items"][$index]);
						}
					}
				} else {
					$this->scorm_array['sequencing'] = $this->scorm_array['items'];
				}
			} else {
				$this->scorm_array['sequencing'] = $this->scorm_array['items'];
			}
			//
			return true;
	    }

	}
?>	