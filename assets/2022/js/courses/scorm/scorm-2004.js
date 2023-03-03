var SCORM_TYPE = SCORM_XML.type;
var SCORM_VERSION = SCORM_XML.version;
var SCORM_OBJECTIVES = SCORM_XML.objectives;
var CHAPTER_START_TIME = 0;
//
var indexes = {};
//
indexes['cmi.exit'] = "";
indexes['cmi.location'] = LAST_LOCATION;
indexes['cmi.session_time'] = '0';
indexes['cmi.completion_status'] = 'unknown';
indexes['cmi.success_status'] = 'unknown';
indexes['cmi.progress_measure'] = '0';
indexes['cmi.score.max'] = 'score_max';
indexes['cmi.score.min'] = 'score_min';
indexes['cmi.score.raw'] = 'score_raw';
indexes['cmi.score.scaled'] = 'score_scaled';
indexes['cmi.score.success_status'] = 'score_success_status';
indexes['adl.data._count'] = SCORM_XML.storage;
indexes['adl.nav.request'] = 'continue';

if (SCORM_TYPE == "advancedruntime") {
    indexes['cmi.objectives._count'] = SCORM_OBJECTIVES.length;
    //
    for (var i=0; i < indexes['cmi.objectives._count']; i++){
        var obj = SCORM_OBJECTIVES[i]
        indexes['cmi.objectives.'+i+".id"] = obj;
        indexes['cmi.objectives.'+i+".progress_measure"] = "";
        indexes['cmi.objectives.'+i+".completion_status"] = "";
    }
}
//
if (SCORM_XML.storage > 0) {
    //
    for (var i=0; i < SCORM_XML.storage; i++){
        //
        if (i == LAST_CHAPTER) {
            indexes['adl.data.'+i+".id"] = "com.scorm.golfsamples.sequencing.forcedsequential.notesStorage";
        } else {
            indexes['adl.data.'+i+".id"] = "";
        }
        //
        indexes['adl.data.'+i+".store"] = SCORM_XML.chapters_notes[i];
    }
}


window.API_1484_11 = {
    Initialize: function(result){
        CHAPTER_START_TIME = moment().utc();
        console.log("Initialize event accure");
    },
    Terminate: function(){},
    GetValue: function(element, checkError){
        //
        // Get requested indexes value and return it
        // console.log(element+';;;')
        console.log(indexes[element])
        return indexes[element];
    },
    SetValue: function(element, value){
        //
        console.log(element +" = "+ value)
        // Set indexes value
        indexes[element] = value;
        //
        if (element == "cmi.success_status" && indexes['cmi.completion_status'] == 'completed') {
            saveStepProgress('result');
        } else if (SCORM_VERSION == "20044th" && element == "cmi.completion_status" && value == "completed") {
            saveStepProgress('result');
        } else if (SCORM_XML.storage > 0 && element == 'adl.data.'+SCORM_LEVEL+'.store') {
            saveStepProgress('note');
        } else if (element == 'cmi.location' && value > 0) {
            saveStepProgress('location');
        }
        //
        
        //
        // If assessment is pass or fail then unlock the next chapter
        if (element == "cmi.score.scaled") {
            //
            if (!SCORM_CONTENT) {
                //
                // indexes['cmi.location'] = indexes['cmi.location'] + 1;
                saveStepProgress('result&result');
            } else if (SCORM_CONTENT.indexOf('assessment') != -1) {
                // 
                unlockNextChapter();    
            }
        }
        //
        // If Set nav request then call jump function to check validation and jump to assessment
        if (element == "adl.nav.request") {
            jumpToAssessment(value);
            
        }
        //
        
    },
    Commit: function(){
        //
        console.log("commit step");
        // Check if Objective is not assessment and objective is fullfill then unlock the next chapter
        if (SCORM_CONTENT.indexOf('assessment') == -1) {
            unlockNextChapter();
        }
    },
    GetLastError: function(){},
    GetErrorString: function(){},
    GetDiagnostic: function(){},
}

$(document).on('click', '.jsChangeChapter', function() {
    //
    // Call a function to remove active class
    resetActiveNav("remove");
    //
    // Set next scorm content, level and chapter.
    SCORM_CONTENT = $(this).data('parameter');
    SCORM_LEVEL = $(this).data('index');
    SCORM_CHAPTER = SCORM_XML.sequencing[SCORM_LEVEL]["title"];
    //
    // Reset CMI location 
    indexes["cmi.location"] = 0;
    //
    // Call a function to add active class
    resetActiveNav("add");
    //
    // If course has storage option then below code execute
    if (SCORM_XML.storage > 0) {
        //
        // Inactive all chapters data storage
        for (var i=0; i < SCORM_XML.storage; i++){
            indexes['adl.data.'+i+".id"] = "";
        }
        //
        // Active only current chapter data storage
        indexes['adl.data.'+SCORM_LEVEL+".id"] = "com.scorm.golfsamples.sequencing.forcedsequential.notesStorage";
    }
    //
    // Call a function to set new chapter in iframe
    setNewChapter();
});

function jumpToAssessment (value) {
    //
    if (SCORM_VERSION == "20044th") {
        //
        var request = value.split('=')[1].split('}')[0];
        //
        if (request == "assessment_item") {
            //
            SCORM_XML.sequencing.map(function (sequence) {
                if (sequence.parameter.indexOf('assessment') != -1) {
                    //
                    // Call a function to add active class
                    resetActiveNav("remove");
                    //
                    // Set next scorm content, level and chapter.
                    SCORM_CONTENT = sequence.parameter;
                    SCORM_LEVEL = SCORM_XML.sequencing.length - 1;
                    SCORM_CHAPTER = sequence.title;
                    //
                    // Call a function to add active class
                    resetActiveNav("add");
                    //
                }
            });
            //
            indexes["cmi.location"] = 0
            setNewChapter();
        }
    } else if (SCORM_VERSION == "20043rd") {
        if (value == "suspendAll" || value == "exitAll") {
            console.log(indexes["cmi.location"])
        }
    }
}

function unlockNextChapter () {
    var limit = SCORM_XML.sequencing.length - 1;
    //
    if (SCORM_LEVEL < limit) {
        var next_level = SCORM_LEVEL + 1;
        //
        if (SCORM_VERSION == "20044th" || SCORM_VERSION == "20043rd" || SCORM_TYPE == "simpleremediation") {
            var next_chapter = SCORM_XML.sequencing[next_level]["title"];
            var id = next_chapter.trim().toLowerCase().replace(/ /g, "_");
            //
            unlockNavLink(id);
            changeNavColor();
        }
        //
        indexes['cmi.completion_status'] = 'unknown';
        indexes['cmi.success_status'] = 'unknown';
        indexes['cmi.location'] = '';
    }
}

function resetActiveNav (type) {
    //
    // Get ID to add or remove active class
    var target_id = SCORM_CHAPTER.trim().toLowerCase().replace(/ /g, "_");
    //
    if (type == "add"){
        $("#"+target_id).addClass("active");
    } else if (type == "remove") {
        $("#"+target_id).removeClass("active");
    }
}

function saveStepProgress (type) {
    //
    var current_time =  moment().utc();
    //
    scormObject = {};
    //
    if (SCORM_CONTENT) {
        if (SCORM_CONTENT.indexOf('assessment') != -1) { 
            scormObject.type = 'quiz';
        } else if (SCORM_CONTENT.indexOf('assessment') == -1) {
            scormObject.type = 'content';
        }
    }    
    //
    scormObject.action = type;
    scormObject.name = SCORM_CHAPTER;
    scormObject.level = SCORM_LEVEL;
    scormObject.location = indexes['cmi.location'];
    scormObject.completion_status = indexes['cmi.completion_status'];
    scormObject.success_status = indexes['cmi.success_status'];
    scormObject.session_time = indexes['cmi.session_time'];
    scormObject.progress_measure = indexes['cmi.progress_measure'];
    scormObject.date = moment().utc().format('MMM DD YYYY, ddd');
    scormObject.spent_seconds = current_time.diff(CHAPTER_START_TIME, 'seconds');
    //
    if (scormObject.type == "quiz") {
        scormObject.score_max = indexes['cmi.score.max'];
        scormObject.score_min = indexes['cmi.score.min'];
        scormObject.score_raw = indexes['cmi.score.raw'];
        scormObject.score_scaled = indexes['cmi.score.scaled'];
    }    
    //
    if (SCORM_XML.storage > 0) {
        //
        scormObject.chapter_note = indexes['adl.data.'+SCORM_LEVEL+".store"];
    }
    //
    if (LAST_CHAPTER < (SCORM_LEVEL + 1)) {
        saveScormProgress(scormObject);
    } else if (type = 'note') {
        saveScormProgress(scormObject);
    }
}