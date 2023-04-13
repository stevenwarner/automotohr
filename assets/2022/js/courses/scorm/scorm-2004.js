var SCORM_TYPE = SCORM_XML.type;
var SCORM_VERSION = SCORM_XML.version;
var SCORM_OBJECTIVES = SCORM_XML.objectives;
var SCORM_INTERACTIONS = [];
var CHAPTER_START_TIME = 0;
//
var indexes = {};
LAST_LOCATION = '5';
//
indexes['cmi._version'] = '';
indexes['cmi.comments_from_learner._children'] = '';
indexes['cmi.comments_from_learner._count'] = '0';
indexes['cmi.comments_from_learner.n.comment'] = '';
indexes['cmi.comments_from_learner.n.location'] = '';
indexes['cmi.comments_from_learner.n.timestamp'] = '';
indexes['cmi.comments_from_lms._children'] = '';
indexes['cmi.comments_from_lms._count'] = '0';
indexes['cmi.comments_from_lms.n.comment'] = '';
indexes['cmi.comments_from_lms.n.location'] = '';
indexes['cmi.comments_from_lms.n.timestamp'] = ''; 
indexes['cmi.completion_status'] = 'not attempted'; 
indexes['cmi.completion_threshold'] = ''; 
indexes['cmi.credit'] = 'RO'; 
indexes['cmi.entry'] = ''; 
indexes['cmi.exit'] = ''; 
indexes['cmi.launch_data'] = ''; 
indexes['cmi.learner_id'] = ''; 
indexes['cmi.learner_name'] = ''; 
indexes['cmi.learner_preference._children'] = ''; 
indexes['cmi.learner_preference.audio_level'] = ''; 
indexes['cmi.learner_preference.language'] = ''; 
indexes['cmi.learner_preference.delivery_speed'] = ''; 
indexes['cmi.learner_preference.audio_captioning'] = ''; 
indexes['cmi.location'] = LAST_LOCATION; 
indexes['cmi.max_time_allowed'] = ''; 
indexes['cmi.mode'] = ''; 
indexes['cmi.progress_measure'] = '';
indexes['cmi.scaled_passing_score'] = ''; 
indexes['cmi.score._children'] = ''; 
indexes['cmi.score.scaled'] = ''; 
indexes['cmi.score.raw'] = ''; 
indexes['cmi.score.min'] = ''; 
indexes['cmi.score.max'] = ''; 
indexes['cmi.score.success_status'] = 'score_success_status';
indexes['cmi.session_time'] = ''; 
indexes['cmi.success_status'] = 'unknown'; 
indexes['cmi.suspend_data'] = SUSPEND_DATA; 
indexes['cmi.time_limit_action'] = ''; 
indexes['cmi.total_time'] = ''; 
indexes['adl.data._count'] = SCORM_XML.storage;
indexes['adl.nav.request'] = ''; 
indexes['adl.nav.request_valid.continue'] = '';
indexes['adl.nav.request_valid.previous'] = ''; 
indexes['adl.nav.request_valid.choice.{target=}'] = '';
indexes['adl.nav.request_valid.jump.{target=}'] = ''; 

if (SCORM_OBJECTIVES.length) {
    indexes['cmi.objectives._count'] = SCORM_OBJECTIVES.length;
    indexes['cmi.objectives._children'] = ''; 
    //
    for (var i=0; i < indexes['cmi.objectives._count']; i++){
        var obj = SCORM_OBJECTIVES[i]
        indexes['cmi.objectives.'+i+'.id'] = obj; 
        indexes['cmi.objectives.'+i+'.score._children'] = ''; 
        indexes['cmi.objectives.'+i+'.score.scaled'] = ''; 
        indexes['cmi.objectives.'+i+'.score.raw'] = '';
        indexes['cmi.objectives.'+i+'.score.min'] = '';
        indexes['cmi.objectives.'+i+'.score.max'] = ''; 
        indexes['cmi.objectives.'+i+'.success_status'] = ''; 
        indexes['cmi.objectives.'+i+'.completion_status'] = ''; 
        indexes['cmi.objectives.'+i+'.progress_measure'] = ''; 
        indexes['cmi.objectives.'+i+'.description'] = ''; 
    }
}

if (SCORM_INTERACTIONS.length) {
    indexes['cmi.interactions._children'] = ''; 
    indexes['cmi.interactions._count'] = SCORM_INTERACTIONS.length; 
    //
    for (var i=0; i < indexes['cmi.interactions._count']; i++){
        indexes['cmi.interactions.'+i+'.id'] = ''; 
        indexes['cmi.interactions.'+i+'.type'] = ''; 
        indexes['cmi.interactions.'+i+'.timestamp'] = ''; 
        indexes['cmi.interactions.'+i+'.correct'] = '';
        indexes['cmi.interactions.'+i+'.weighting'] = ''; 
        indexes['cmi.interactions.'+i+'.learner_response'] = ''; 
        indexes['cmi.interactions.'+i+'.result'] = 'neutral'; 
        indexes['cmi.interactions.'+i+'.latency'] = ''; 
        indexes['cmi.interactions.'+i+'.description'] = ''; 
        //
        if (SCORM_INTERACTIONS[i]['objectives'].length) {
            indexes['cmi.interactions'+i+'objectives._count'] = SCORM_INTERACTIONS[i]['objectives'].length;
            //
            for (var j=0; j < indexes['cmi.interactions'+i+'objectives._count']; j++){
                indexes['cmi.interactions.'+i+'.objectives.'+j+'.id'] = ''; 
                indexes['cmi.interactions.'+i+'.correct_responses.'+j+'.pattern'] = '';
            }
        }
         
        
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
        console.log(element)
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
        } else if (element == 'cmi.location' && typeof value === 'string' && value !== null) {
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
                SCORM_CONTENT = 'assessment';
                saveStepProgress('result');
            } else if (SCORM_CONTENT && SCORM_CONTENT.indexOf('assessment') != -1) {
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
        // saveStepProgress('suspend_data');
        // Check if Objective is not assessment and objective is fullfill then unlock the next chapter
        if (SCORM_CONTENT && SCORM_CONTENT.indexOf('assessment') == -1) {
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
            var id = next_chapter.trim().toLowerCase().replace(/[^A-Z0-9]/ig, "_");
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
    var target_id = SCORM_CHAPTER.trim().toLowerCase().replace(/[^A-Z0-9]/ig, "_");
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
        if (SCORM_CONTENT && SCORM_CONTENT.indexOf('assessment') != -1) { 
            scormObject.type = 'quiz';
            if (indexes['cmi.success_status'] == 'unknown') {
                indexes['cmi.success_status'] = 'failed'
            }
        } else if (SCORM_CONTENT && SCORM_CONTENT.indexOf('assessment') == -1) {
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
    scormObject.suspend_data = indexes['cmi.suspend_data'];
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