<?php

/**
 * SCORM reader
 *
 * SCORM parser supports 1.2, 2004 3ed edition, and 2004 4th edition
 * Single SCO
 * Multiple SCO
 * Minimum Run-Time calls
 * Basic Run-Time calls
 * Advanced Run-Time calls
 * Forced Sequential Order
 * Post Test Rollup
 * Post Test Rollup
 *
 * @version 1.0
 * @author  AutomotoHR <www.automotohr.com>
 */
class parser
{
    /**
     * holds the xml content
     * @var string
     */
    private $content;
    /**
     * holds the parsed XML
     * @var array
     */
    private $parsedData;
    /**
     * holds the SCORM array
     * @var array
     */
    private $parsedArray;
    /**
     * holds the SCORM version alias
     * @var array
     */
    private $versionAlias;

    /**
     * Constructor
     */
    public function __construct()
    {
        // flush the data
        $this->content = '';
        $this->parsedData = [];
        $this->parsedArray = [];
        // set default data
        $this->parsedArray['version'] = '';
        $this->parsedArray['type'] = '';
        $this->parsedArray['callsLMS'] = false;
        $this->parsedArray['about'] = [];
        $this->parsedArray['launchPage'] = '';
        // set SCO's (chapters)
        $this->parsedArray['sco'] = [];
        $this->parsedArray['organizations'] = [];
        $this->parsedArray['resources'] = [];
        // set default versions aliases
        $this->versionAlias = [
            '1.2' => '1.2',
            'CAM 1.3' => '2004 3rd edition'
        ];
    }

    /**
     * Parse the data
     */
    public function parse()
    {
        //
        header('Content-Type: application/json');
        // convert XML to array
        $this->xmlToArray();
        // set basic information
        $this->setVersionAndType();
        // set resources
        $this->setResources();
        // check and set resource dependencies
        $this->setResourceDependencies();
        // set organizations
        $this->setOrganizations();
        // remove index
        $this->parsedArray['resources'] = array_values($this->parsedArray['resources']);
        $this->parsedArray['sco'] = array_values($this->parsedArray['sco']);
        // set launch page
        $this->parsedArray['launchPage'] = $this->parsedArray['resources'][0]['props']['href'];
        // set proper type
        $this->setCourseType();

        return json_encode($this->parsedArray);
    }

    /**
     * set the XML content
     *
     * @param string $xmlContent
     * @return
     */
    public function setContent(string $xmlContent)
    {
        $xmlContent = $this->cleanContent($xmlContent);
        // set the data
        $this->content = preg_replace_callback('/\badlcp:(.*)\b/im', function ($match) {
            return 'adlcp_' . strtolower($match[1]);
        }, $xmlContent);
        // make sure to replace all "imsss:..." with "imsss_..."
        $this->content = preg_replace('/imsss:([a-z])/im', "imsss_$1", $this->content);
        // header('Content-Type: application/xml');
        // echo $this->content;
        // die;
        // return this for chaining
        return $this;
    }

    private function cleanContent($xmlString)
    {

        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress errors for now
        $dom->loadXML($xmlString);

        // Function to remove namespace prefixes
        function removeNamespaces($dom)
        {
            // Loop through all nodes
            foreach ($dom->getElementsByTagName('*') as $node) {
                // If the node has a namespace prefix
                if ($node->prefix) {
                    // Create a new element without the namespace
                    $newNode = $dom->createElement(str_replace($node->prefix . ':', '', $node->nodeName));

                    // Copy attributes
                    foreach ($node->attributes as $attr) {
                        $newNode->setAttribute($attr->nodeName, $attr->nodeValue);
                    }

                    // Copy child nodes
                    while ($node->firstChild) {
                        $newNode->appendChild($dom->importNode($node->firstChild, true));
                    }

                    // Replace the old node with the new node
                    $node->parentNode->replaceChild($newNode, $node);
                }
            }
        }

        // Remove namespaces
        removeNamespaces($dom);

        // Remove the namespace declarations from the root element
        $root = $dom->documentElement;
        $root->removeAttribute('xmlns');
        $root->removeAttribute('xmlns:adlcp');
        $root->removeAttribute('xmlns:xsi');

        $cleanedXml = $dom->saveXML();
       
        return $cleanedXml;
    }

    /**
     * convert XML to array
     *
     * @return
     */
    private function xmlToArray()
    {
        // set the parsed data
        $this->parsedData = json_decode(json_encode((array)simplexml_load_string($this->content, "SimpleXMLElement", LIBXML_NOCDATA)), true);
        // return this for chaining
        return $this;
    }

    /**
     * set version and type
     *
     * @return
     */
    private function setVersionAndType()
    {
        // set the SCORM version
        $this->parsedArray['version'] = $this->versionAlias[$this->parsedData['metadata']['schemaversion']] ?? $this->parsedData['metadata']['schemaversion'];
        // set the SCORM type
        $this->parsedArray['type'] = count($this->parsedData['resources']) <= 1 ? 'single_sco' : 'multiple_sco';
        // return this for chaining
        return $this;
    }

    /**
     * set organizations
     *
     * @return
     */
    private function setOrganizations()
    {
        // set default organizations
        $this->parsedArray['organizations'] = [
            'default' => [],
            'others' => [],
        ];
        // set organizations
        $organizationsArray = [];
        // check if multiple organizations exists
        if (!isset($this->parsedData['organizations'][0])) {
            $organizationsArray[] = $this->parsedData['organizations'];
        } else {
            $organizationsArray = $this->parsedData['organizations'];
        }

        // loop through organizations
        foreach ($organizationsArray as $i0 => $v0) {
            // check if it's default
            if (isset($v0['@attributes']['default'])) {
                $this->parsedArray['organizations']['default']['identifier'] = $v0['@attributes']['default'];
            }
            // set organization
            $this->setSingleOrganization($v0['organization']);
        }
        // return this for chaining
        return $this;
    }

    /**
     * set single organization
     *
     * @param array $organization
     * @return
     */
    private function setSingleOrganization(array $organization)
    {
        // set default multiple organizations
        $multipleOrganizations = [];
        // check for array
        if (!isset($organization[0])) {
            $multipleOrganizations[] = $organization;
        } else {
            $multipleOrganizations = $organization;
        }
        // loop through organizations
        foreach ($multipleOrganizations as $i0 => $v0) {
            // _e($v0, true, true);
            // set organization array
            $organizationArray = [];
            $organizationArray['title'] = $v0['title'];
            $organizationArray['props'] = $v0['@attributes'];
            $organizationArray['items'] = [];
            $organizationArray['sequencing'] = [];
            // if sequencing is set
            if ($v0['imsss_sequencing']) {
                $this->setSequency(
                    $v0['imsss_sequencing'],
                    $organizationArray['sequencing']
                );
            }
            // ste the item array
            $this->setItems($v0['item'], $organizationArray['items']);
            // check if identifier matches the default
            if ($organizationArray['props']['identifier'] === $this->parsedArray['organizations']['default']['identifier']) {
                $this->parsedArray['organizations']['default'] = $organizationArray;
                $this->parsedArray['organizations']['default']['identifier'] = $organizationArray['props']['identifier'];
            } else {
                // pushes to default
                $this->parsedArray['organizations']['others'][] = $organizationArray;
            }
        }
        return $this;
    }

    /**
     * set organization items
     *
     * @param array     $itemArray
     * @param reference $attachArray
     * @return
     */
    private function setItems(array $itemArray, array &$attachArray)
    {
        // set default multiple items
        $multipleItems = [];
        // check for array
        if (!isset($itemArray[0])) {
            $multipleItems[] = $itemArray;
        } else {
            $multipleItems = $itemArray;
        }
        // loop through items
        foreach ($multipleItems as $v0) {
            // set identifier
            $identifier = $v0['@attributes']['identifier'];
            // set chapter
            $this->parsedArray['sco'][$identifier] = [];
            $this->parsedArray['sco'][$identifier]['title'] = $v0['title'];
            $this->parsedArray['sco'][$identifier]['items'] = [];
            $this->parsedArray['sco'][$identifier]['props'] = $v0['@attributes'];
            // check if multiple items
            if (isset($v0['item'])) {
                // set item array
                $itemArray = [];
                $itemArray['title'] = $v0['title'];
                $itemArray['props'] = $v0['@attributes'];
                $itemArray['item'] = [];
                //
                $this->setMultipleItemsWithinItems(
                    $v0['item'],
                    $itemArray['item']
                );
                $this->parsedArray['sco'][$identifier]['items'] = $itemArray['item'];
                //
                $attachArray[$identifier] = $itemArray;
            } else {
                // set item array
                $itemArray = [];
                // set identifier
                $identifierRef = $v0['@attributes']['identifierref'];
                $itemArray['title'] = $v0['title'];
                $itemArray['launchPage'] = '';
                $itemArray['parameters'] = $v0['@attributes']['parameters'] ?? '';
                $itemArray['props'] = $v0['@attributes'];
                $itemArray['sequence'] = [];
                $itemArray['resource'] = $this->parsedArray['resources'][$identifierRef] ?? [];
                // if resource was found
                if ($itemArray['resource']['props']['href']) {
                    $itemArray['launchPage'] = $itemArray['resource']['props']['href'];
                }
                // if sequencing is set
                if ($v0['imsss_sequencing']) {
                    $this->setSequency(
                        $v0['imsss_sequencing'],
                        $itemArray['sequence']
                    );
                }
                //
                $this->parsedArray['sco'][$identifier] = $itemArray;
                //
                $attachArray[$identifierRef] = $itemArray;
            }
        }
        return $this;
    }

    /**
     * set items of item
     *
     * @param array     $itemArray
     * @param reference $attachArray
     * @return
     */
    private function setMultipleItemsWithinItems(array $itemArray, array &$attachArray)
    {
        // set default multiple items
        $multipleItems = [];
        // check for array
        if (!isset($itemArray[0])) {
            $multipleItems[] = $itemArray;
        } else {
            $multipleItems = $itemArray;
        }
        // loop through items
        foreach ($multipleItems as $v0) {
            // set item array
            $itemArray = [];
            // set identifier
            $identifierRef = $v0['@attributes']['identifierref'];
            $itemArray['title'] = $v0['title'];
            $itemArray['launchPage'] = '';
            $itemArray['parameters'] = $v0['@attributes']['parameters'] ?? '';
            $itemArray['props'] = $v0['@attributes'];
            $itemArray['resource'] = $this->parsedArray['resources'][$identifierRef] ?? [];
            // if resource was found
            if ($itemArray['resource']['props']['href']) {
                $itemArray['launchPage'] = $itemArray['resource']['props']['href'];
            }
            //
            $attachArray[$identifierRef] = $itemArray;
        }
        return $this;
    }

    /**
     * set resources
     *
     * @return
     */
    private function setResources()
    {
        // set launch page
        $this->parsedArray['launchPage'] = '';
        // set default resources
        $this->parsedArray['resources'] = [];
        // set resources
        $resourcesArray = [];
        // check if multiple resources exists
        if (!isset($this->parsedData['resources'][0])) {
            $resourcesArray[] = $this->parsedData['resources'];
        } else {
            $resourcesArray = $this->parsedData['resources'];
        }
        // loop through resources
        foreach ($resourcesArray as $v0) {
            // set single resources
            $this->setSingleResource($v0);
        }
        // return this for chaining
        return $this;
    }

    /**
     * set single resource
     *
     * @param array $resource
     * @return
     */
    private function setSingleResource(array $resource)
    {
        // set default multiple organizations
        $multipleResources = [];
        // check for array
        if (!isset($resource['resource'][0])) {
            $multipleResources[] = $resource['resource'];
        } else {
            $multipleResources = $resource['resource'];
        }
        // loop through organizations
        foreach ($multipleResources as $i0 => $v0) {
            // set identifier
            $identifier = $v0['@attributes']['identifier'] ?? $i0;
            // set resource array
            $resourceArray = [];
            $resourceArray['props'] = $v0['@attributes'];
            $resourceArray['files'] = [];
            // set resource type
            $resourceArray['type'] = $v0['@attributes']['adlcp_scormtype'] ?? '';
            // only work for web-content
            if ($v0['@attributes']['type'] === 'webcontent' && $v0['@attributes']['href']) {
                // set the LMS
                // if 'sco' it will call the LMS otherwise not
                $this->parsedArray['callsLMS'] = $resourceArray['type'] == 'sco' ? true : false;
            }
            // set dependency
            $resourceArray['dependency_identifier'] = $v0['dependency']['@attributes']['identifierref'] ?? '';
            $resourceArray['dependency'] = [];
            // set the files
            $this->setResourceFiles($v0['file'], $resourceArray['files']);
            // add to resources
            $this->parsedArray['resources'][$identifier] = $resourceArray;
        }
        return $this;
    }

    /**
     * set resource files
     *
     * @param array     $fileArray
     * @param reference $attachArray
     * @return
     */
    private function setResourceFiles(array $fileArray, array &$attachArray)
    {
        // set default multiple items
        $multipleFiles = [];
        // check for array
        if (!isset($fileArray[0])) {
            $multipleFiles[] = $fileArray;
        } else {
            $multipleFiles = $fileArray;
        }
        // loop through items
        foreach ($multipleFiles as $v0) {
            //
            $attachArray[] = $v0['@attributes']['href'];
        }
        return $this;
    }

    /**
     * set resource dependencies
     *
     * @param array     $fileArray
     * @return
     */
    private function setResourceDependencies()
    {
        // loop through resources
        foreach ($this->parsedArray['resources'] as $i0 => $v0) {
            // check if there is dependency identifier
            if ($this->parsedArray['resources'][$v0['dependency_identifier']]) {
                // set the dependency
                $this->parsedArray['resources'][$i0]['dependency'] = $this->parsedArray['resources'][$v0['dependency_identifier']]['files'];
            }
        }
        //
        return $this;
    }

    /**
     * set course type
     *
     * @param array     $sequency
     * @param reference $attachArray
     * @return
     */
    private function setSequency(array $sequency, array &$attachArray)
    {
        // set the controls
        if ($sequency['imsss_controlMode']) {
            $attachArray['controlMode'] = [];
            $attachArray['controlMode']['choice'] = (bool)$sequency['imsss_controlMode']['@attributes']['choice'] ?? false;
            $attachArray['controlMode']['choiceExit'] = (bool)$sequency['imsss_controlMode']['@attributes']['choiceExit'] ?? false;
            $attachArray['controlMode']['flow'] = (bool)$sequency['imsss_controlMode']['@attributes']['flow'] ?? false;
            $attachArray['controlMode']['forwardOnly'] = (bool)$sequency['imsss_controlMode']['@attributes']['forwardOnly'] ?? false;
            $attachArray['controlMode']['useCurrentAttemptObjectiveInfo'] = (bool)$sequency['imsss_controlMode']['@attributes']['useCurrentAttemptObjectiveInfo'] ?? false;
            $attachArray['controlMode']['useCurrentAttemptProgressInfo'] = (bool)$sequency['imsss_controlMode']['@attributes']['useCurrentAttemptProgressInfo'] ?? false;
        }

        // set the objectives
        if ($sequency['imsss_objectives']) {
            // check for primary objective
            if ($sequency['imsss_objectives']['imsss_primaryObjective']) {
                $attachArray['primaryObjective'] = [];
                $attachArray['primaryObjective']['objectiveID'] = $sequency['imsss_objectives']['imsss_primaryObjective']['@attributes']['objectiveID'] ?? '';
                $attachArray['primaryObjective']['satisfiedByMeasure'] = (bool)$sequency['imsss_objectives']['imsss_primaryObjective']['@attributes']['satisfiedByMeasure'] ?? false;
                $attachArray['primaryObjective']['minNormalizedMeasure'] = $sequency['imsss_objectives']['imsss_primaryObjective']['imsss_minNormalizedMeasure'];
            }
            //
            $objArray = [];
            //
            if ($sequency['imsss_objectives']['imsss_objective']) {
                // check if objective array or not
                if (!isset($sequency['imsss_objectives']['imsss_objective'][0])) {
                    $objArray[] = $sequency['imsss_objectives']['imsss_objective'];
                } else {
                    $objArray = $sequency['imsss_objectives']['imsss_objective'];
                }
                // check for objectives
                if ($objArray) {
                    //
                    $attachArray['objectives'] = [];
                    // loop through objectives
                    foreach ($objArray as $objective) {
                        //
                        $objectiveArray = [];
                        $objectiveArray = $objective['@attributes'];
                        //
                        if ($objective['imsss_minNormalizedMeasure']) {
                            //
                            $objectiveArray['minNormalizedMeasure'] =
                                $objective['imsss_minNormalizedMeasure'];
                        }
                        // for map info
                        if ($objective['imsss_mapInfo']) {
                            //
                            $objectiveArray['mapInfo'] = [];
                            $objectiveArray['mapInfo'] = $objective['imsss_mapInfo']['@attributes'];
                        }
                        //
                        $attachArray['objectives'][] = $objectiveArray;
                    }
                }
            }
        }

        // set the rules
        if ($sequency['imsss_sequencingRules']) {
            // check for preConditionRules
            if ($sequency['imsss_sequencingRules']['imsss_preConditionRule']) {
                $conditionArray['preConditions'] = [];
                // set the pre condition rule
                if ($sequency['imsss_sequencingRules']['imsss_preConditionRule']['imsss_ruleConditions']) {
                    //
                    $loopData =
                        $this->checkAndGetArray(
                            $sequency['imsss_sequencingRules']['imsss_preConditionRule']['imsss_ruleConditions']['imsss_ruleCondition']
                        );
                    //
                    $conditionArray =
                        $sequency['imsss_sequencingRules']['imsss_preConditionRule']['imsss_ruleConditions']['@attributes']
                        ?? [];
                    // loop through the data
                    foreach ($loopData as $condition) {
                        //
                        $conditionArray['conditions'][] = $condition['@attributes'];
                    }
                    //
                    $attachArray['preConditions'] = $conditionArray;
                }
                // set the rule action
                if ($sequency['imsss_sequencingRules']['imsss_preConditionRule']['imsss_ruleAction']) {
                    //
                    $attachArray['preConditions']['ruleAction'] =
                        $sequency['imsss_sequencingRules']['imsss_preConditionRule']['imsss_ruleAction']['@attributes'];
                }
            }
        }
        //
        return $this;
    }


    /**
     * set course type
     *
     * @return
     */
    private function setCourseType()
    {
        // run time calls on multiple SCOs
        if ($this->parsedArray['type'] == 'multiple_sco' && $this->parsedArray['callsLMS']) {
            $this->parsedArray['type'] = 'run_time_multiple_sco';
            $this->parsedArray['about'] = [];
            $this->parsedArray['about'][] =
                'All of the resources are marked as SCOs instead of Assets because they communicate with the LMS.';
            $this->parsedArray['about'][] =
                'Each SCO will locate the SCORM API using the ADL-provided API discovery algorithm.';
            $this->parsedArray['about'][] =
                'The SCOs simply call Initialize when they load and Terminate when they unload.';
        }
        //
        return $this;
    }

    /**
     * check and send multiple array
     *
     * @param array $dataArray
     * @return
     */
    private function checkAndGetArray(array $dataArray)
    {
        return isset($dataArray[0]) ? $dataArray : [$dataArray];
    }
}
