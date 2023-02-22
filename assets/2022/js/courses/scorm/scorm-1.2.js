var indexes = {};
// 1.1 - 1.2
indexes['cmi.core.lesson_location'] = "0";
indexes['cmi.core.lesson_status'] = 'incomplete';
indexes['cmi.core.score.max'] = 'score_max';
indexes['cmi.core.score.min'] = 'score_min';
indexes['cmi.core.score.raw'] = 'score_raw';
indexes['cmi.core.session_time'] = 'time_spent';

window.API = {
    LMSInitialize: function(result){
        console.log("Initialize event accure")
    },
    LMSCommit: function(){
        console.log("the process is commit")
    },
    LMSGetValue: function(element, checkError){
        console.log(element)
        return indexes[element];
    },
    LMSSetValue: function(element, value){
        console.log("Set this = "+element+ " and value is "+ value)
        indexes[element] = value;
    },
    LMSFinish: function(){
        console.log("the process is finish")
    },
    LMSGetLastError: function(){},
    LMSGetErrorString: function(){},
    LMSGetDiagnostic: function(){},
}