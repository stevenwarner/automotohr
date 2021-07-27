<?php 
    //
    // $folder = "1";
    // $folder = "12";
    // $folder = "A_2004";
    // $folder = "B_12";
    // $folder = "B_2004";
    // $folder = "SS_1_1";
    $folder = "SS_1_2";
    $fileXML = dirname(__FILE__)."/".($folder)."/imsmanifest.xml";
    $fileLaunch = "".($folder)."/shared/launchpage.html";
    //
    require_once("scorm_reader.php");
    $scorm = new SCORMReader();
    $scorm->LoadFile($fileXML);
    $scormArray = $scorm->GetIndex();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main SCORM Page</title>
</head>
<body>
    <!-- Add Adapter -->
    <script type="text/javascript" src="adapter/init.js"></script>
    <script type="text/javascript" src="adapter/constants.js"></script>
    <script type="text/javascript" src="adapter/jsonFormatter.js"></script>
    <script type="text/javascript" src="adapter/baseAPI.js"></script>
    <script type="text/javascript" src="adapter/scormAPI2004.js"></script>
    <script type="text/javascript" src="adapter/scormAPI.js"></script>

    <!--  -->
    <iframe src="<?=$fileLaunch;?>" title="123" width="100%" height="500px"></iframe>


    <!-- SCORM ADAPTER - AHR -->
    <script>
        //
        var SCORM_XML = <?=json_encode($scormArray);?>;
        var SCORM_VERSION = SCORM_XML.version;

        console.log(SCORM_XML);
        //
        var PRE_OBJ = {};
        PRE_OBJ.location = 0;
        // PRE_OBJ.location = 8;

        /**
         * 
         */
        (function(){
            // Default values
            var LMSOBJ = {};
            LMSOBJ.location = 0;
            //
            LMSOBJ = Object.assign(LMSOBJ, PRE_OBJ);
            //
            var SCORM_OBJ = {};
            //
            const _this = this;
            //
            const SA = SCORM_VERSION.match(/2004/) === null ? 1 : 2;


            /**
             * 
             */
            this.init = function(){
                this.Initialize();
                this.SetValueTrigger();
                this.FinishTrigger();
            }

            /**
             * 
             */
            this.Initialize = function(){
                //
                if(SA == 1){
                    API.on("LMSInitialize", function() {
                        //
                        if(LMSOBJ.location != 0){
                            API.LMSSetValue('cmi.core.lesson_location', LMSOBJ.location);
                        }
                    });
                } else{
                    //
                    API_1484_11.apiLogLevel = 5;
                    //
                    API_1484_11.on("Initialize", function() {
                        console.log(
                            // API_1484_11.SetValue('cmi.objectives.0.id', "123")
                        );
                        //
                        if(LMSOBJ.location != 0){
                            API_1484_11.SetValue('cmi.location', LMSOBJ.location);
                        }
                        //
                        if(SCORM_XML.objective.length > 0){
                            //
                            // SCORM_XML.objective.map(function(ID, i){
                            //     console.log(i, ID)
                            //     API_1484_11.SetValue('cmi.objectives.1.id', ID);
                            // });

                            console.log(
                                API_1484_11.GetValue('cmi.objectives.1.id')
                            )
                        }
                    });
                }
            }

            /**
             * 
             */
            this.SetValueTrigger = function(){
                if(SA == 1){
                    //Whenever a page is shifter
                    API.on("LMSSetValue", function(e) {
                        //
                        if(e == 'cmi.core.exit'){
                            console.log(SCORM_OBJ);
                            console.log("Exited");
                        }
                        //
                        console.log(e, API.LMSGetValue(e))
                        SCORM_OBJ[_this.ReIndex(e)] = API.LMSGetValue(e);
                    });
                } else {
                    API_1484_11.on("SetValue", function(e) {
                        //
                        if(e == 'cmi.exit'){
                            console.log(SCORM_OBJ);
                            console.log("Exited");
                        }else{
                            console.log(e, API_1484_11.GetValue(e));
                        }
                        //
                        SCORM_OBJ[_this.ReIndex(e)] = API_1484_11.GetValue(e);
                    });
                }

            }
            
            
            /**
             * 
             */
            this.FinishTrigger = function(){
                if(SA == 1){
                    //Whenever a page is shifter
                    API.on("LMSFinish", function(e) {
                        console.log("Transaction ended");
                    });
                } else{
                    API_1484_11.on("Terminate", function(e) {
                        console.log("Transaction ended");
                    });
                }
            }


            /**
             * 
             */
            this.ReIndex = function(index){
                //
                var indexes = {};
                // 2004
                indexes['cmi.location'] = 'location';
                indexes['cmi.completion_status'] = 'completion_status';
                indexes['cmi.score.max'] = 'score_max';
                indexes['cmi.score.min'] = 'score_min';
                indexes['cmi.score.raw'] = 'score_raw';
                indexes['cmi.score.scaled'] = 'score_scaled';
                indexes['cmi.score.success_status'] = 'score_success_status';
                // 1.1 - 1.2
                indexes['cmi.core.lesson_location'] = 'location';
                indexes['cmi.core.lesson_status'] = 'completion_status';
                indexes['cmi.core.score.max'] = 'score_max';
                indexes['cmi.core.score.min'] = 'score_min';
                indexes['cmi.core.score.raw'] = 'score_raw';
                indexes['cmi.core.session_time'] = 'time_spent';
                //
                return indexes[index] || index;
            }
            
            //
            this.init();

            //
            window.SCORM_OBJ= SCORM_OBJ;
        })();

    </script>

</body>
</html>