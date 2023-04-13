var indexes = {};
var CHAPTER_START_TIME = 0;
// 1.1 - 1.2
indexes['cmi.launch_data'] = "";
indexes['cmi.core.student_id'] = "0";
indexes['cmi.core.student_name'] = "unknown";
indexes['cmi.core.credit'] = "0";
indexes['cmi.core.lesson_location'] = LAST_LOCATION;
indexes['cmi.core.lesson_status'] = 'browsed';
indexes['cmi.core.lesson_mode'] = '';
indexes['cmi.suspend_data'] = '0';
indexes['cmi.core.entry'] = '';
indexes['cmi.core.score_children'] = '';
indexes['cmi.core.score.raw'] = 'score_raw';
indexes['cmi.core.score.max'] = 'score_max';
indexes['cmi.core.score.min'] = 'score_min';
indexes['cmi.core.total_time'] = '0';
indexes['cmi.core.session_time'] = '0';
indexes['cmi.interactions._count'] = '0';
indexes['cmi.core._children'] = '';
indexes['cmi.core.exit'] = '';
indexes['cmi.comments'] = ''; 
indexes['cmi.comments_from_lms'] = ''; 
indexes['cmi.objectives._children'] = '';
indexes['cmi.objectives._count'] = '';  
indexes['cmi.objectives.n.id'] = '';  
indexes['cmi.objectives.n.score._children'] = '';  
indexes['cmi.objectives.n.score.raw'] = '';  
indexes['cmi.objectives.n.score.max'] = '';  
indexes['cmi.objectives.n.score.min'] = '';  
indexes['cmi.objectives.n.status'] = '';  
indexes['cmi.student_data._children'] = '';
indexes['cmi.student_data.mastery_score'] = '';
indexes['cmi.student_data.max_time_allowed'] = '';
indexes['cmi.student_data.time_limit_action'] = '';
indexes['cmi.student_preference._children'] = ''; 
indexes['cmi.student_preference.audio'] = ''; 
indexes['cmi.student_preference.language'] = ''; 
indexes['cmi.student_preference.speed'] = ''; 
indexes['cmi.student_preference.text'] = ''; 
indexes['cmi.interactions._children'] = '';
indexes['cmi.interactions.n.id'] = '';  
indexes['cmi.interactions.n.objectives._count'] = '';
indexes['cmi.interactions.n.objectives.n.id'] = '';  
indexes['cmi.interactions.n.time'] = ''; 
indexes['cmi.interactions.n.type'] = ''; 
indexes['cmi.interactions.n.correct_responses._count'] = '';  
indexes['cmi.interactions.n.correct_responses.n.pattern'] = '';
indexes['cmi.interactions.n.weighting'] = '';  
indexes['cmi.interactions.n.student_response'] = '';  
indexes['cmi.interactions.n.result'] = '';  
indexes['cmi.interactions.n.latency'] = ''; 

window.API = {
    LMSInitialize: function(result){
        return true;
        console.log("Initialize event accure 1")
    },
    LMSCommit: function(){
        console.log("LMSCommit "+indexes['cmi.core.lesson_location'])
        console.log("the process is commit")
        if (indexes['cmi.core.lesson_status'] == 'completed') {
            unlockNextChapter();
        }
        return "true";
    },
    LMSGetValue: function(element, checkError){
        console.log("get value "+element)
        return indexes[element];
    },
    LMSSetValue: function(element, value){
        console.log("Set this = " +element+ " and value is "+ value)
        indexes[element] = value;
        if (element == 'cmi.core.lesson_location') {
            saveStepProgress('location');
        } else if (element == 'cmi.core.lesson_status') {
            saveStepProgress('result');
            //
            if (value == 'completed') {
                if (SCORM_CONTENT > SCORM_XML.sequencing.length) {
                    SCORM_CONTENT = parseInt(SCORM_CONTENT) + 1;
                }
                
                // console.clear();
                // console.log(parseInt(SCORM_CONTENT)+1)
                // console.log(LAST_CHAPTER)
                // console.log(SCORM_CONTENT)
                // console.log(SCORM_XML.sequencing.length)
            }
        }
        //
        // if (element == 'cmi.core.lesson_status' && value == "completed") {
            
        // }
        //
        return true;
    },
    LMSFinish: function(){
        console.log("the process is finish")
        if (indexes['cmi.core.lesson_status'] == 'passed' || indexes['cmi.core.lesson_status'] == 'completed') {
            window.location.reload();
        }
        unlockNextChapter();
        return true;
    },
    LMSGetLastError: function(){
        return 0;
    },
    LMSGetErrorString: function(){},
    LMSGetDiagnostic: function(){},
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
    if (SCORM_LAUNCH_FILE != SCORM_XML.sequencing[SCORM_LEVEL]["href"]) {
        SCORM_LAUNCH_FILE = SCORM_XML.sequencing[SCORM_LEVEL]["href"];
    }
    //
    // Reset CMI location 
    indexes["cmi.core.lesson_location"] = SCORM_XML.sequencing[SCORM_LEVEL]["location"];
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

function unlockNextChapter () {
    var limit = SCORM_XML.sequencing.length - 1;
    //
    if (SCORM_LEVEL < limit) {
        var next_level = SCORM_LEVEL + 1;
        //
        var next_chapter = SCORM_XML.sequencing[next_level]["title"];
        var id = next_chapter.trim().toLowerCase().replace(/[^A-Z0-9]/ig, "_");
        //
        unlockNavLink(id);
        changeNavColor();
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
    scormObject.action = type;
    scormObject.name = SCORM_CHAPTER;
    scormObject.level = SCORM_LEVEL;
    scormObject.version = 12;
    scormObject.location = indexes['cmi.core.lesson_location'];
    scormObject.lesson_status = indexes['cmi.core.lesson_status'];
    scormObject.suspend_data = indexes['cmi.suspend_data'];
    scormObject.session_time = indexes['cmi.core.session_time'];
    scormObject.total_time = indexes['cmi.core.total_time'];
    scormObject.date = moment().utc().format('MMM DD YYYY, ddd');
    scormObject.spent_seconds = current_time.diff(CHAPTER_START_TIME, 'seconds');
    //
    if (type == "result") {
        scormObject.score_max = indexes['cmi.core.score.max'];
        scormObject.score_min = indexes['cmi.core.score.min'];
        scormObject.score_raw = indexes['cmi.core.score.raw'];
    }
    //
    if (LAST_CHAPTER < (SCORM_LEVEL + 1)) {
        saveScormProgress(scormObject);
    }
     
}