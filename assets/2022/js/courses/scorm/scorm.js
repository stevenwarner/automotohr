//
$(document).ready(function () {
	//
	var links = '';
	var index = 0;
	var id = SCORM_CHAPTER.trim().toLowerCase().replace(/[^A-Z0-9]/ig, "_");
	//
	SCORM_XML.sequencing.map(function (sequence) {
		var linkID = sequence.title.trim().toLowerCase().replace(/[^A-Z0-9]/ig, "_");
        //
        if (SCORM_LEVEL > index) {
            links += '<li>';
            links += '  <a class="jsChangeChapter btn_scorm _csB4" href="javascript:;" id="'+linkID+'" data-index="'+index+'" data-status="unlock" data-parameter="'+sequence.parameter+'">';
            links += '      <strong>' + (sequence.title) + '</strong>';
            links += '      <i id="'+linkID+'_icon" class="fa fa-unlock"></i>';
            links += '  </a>';
            links += '</li>';
        } else {
            links += '<li>';
            links += '  <a class="jsChangeChapter jsLockBtn btn_scorm _csB4" href="javascript:;" id="'+linkID+'" data-index="'+index+'" data-status="lock" data-parameter="'+sequence.parameter+'">';
            links += '      <strong>' + (sequence.title) + '</strong>';
            links += '      <i id="'+linkID+'_icon" class="fa fa-lock"></i>';
            links += '  </a>';
            links += '</li>';
        }
        //
        index++;
    });
    //
    $('#jsScormChapterLinks').html(links);
    $(".jsLockBtn").prop('disabled', true);
    $("#"+id).addClass("active");
    //
	setNewChapter();	
});	

function setNewChapter () {
	var SCORM_URL = SCORM_DIR+'/'+SCORM_LAUNCH_FILE;
    if (SCORM_CONTENT) {
        SCORM_URL = SCORM_URL + "?"+SCORM_PARAM_KEY+"=" +SCORM_CONTENT
    }
	$("#jsScormCourse").attr('src',SCORM_URL); 
    console.log(SCORM_URL)
	//
	modifyNavBar();
}

function modifyNavBar () {
	var id = SCORM_CHAPTER.trim().toLowerCase().replace(/[^A-Z0-9]/ig, "_");
	//
	unlockNavLink(id);
	changeNavColor();
}

function unlockNavLink (id) {
	$("#"+id+"_icon").removeClass("fa-lock");
    $("#"+id+"_icon").addClass("fa-unlock");
    $("#"+id).prop('disabled', false);
    $("#"+id).attr("data-status","unlock");
}

function changeNavColor () {
	$(".jsChangeChapter").map(function (sequence) {
		var btn_status = $(this).attr("data-status");
		//
		$(this).removeClass("_csB3");
		$(this).removeClass("_csB4");
        $(this).removeClass("_csB5");
        //
        if ($(this).hasClass('active')) {
            $(this).addClass("_csB5");
        } else {
            
            if (btn_status == "unlock") {
                $(this).addClass("_csB3");
            } else {
                $(this).addClass("_csB4");
            }
        }
    });
}

function saveScormProgress (scormObject) {
    $.ajax({
        type: 'POST',
        url: BASE_URI,
        data: {
            'action': "save_scorm_progress",
            'companyId': COMPANY_SID,
            'employeeId': EMPLOYEE_SID,
            'courseId': COURSE_SID,
            'scorm': JSON.stringify(scormObject),
            'chapter': SCORM_LEVEL + 1
        },
        beforeSend: function() {
            $('.jsLMSLoader').show();
        },
        success: function(resp) {
            XHR = null;
            //
            if (resp.Status === false) {
                alertify.alert(
                    "WARNING!",
                    resp.Response
                );
                //
                $('.jsLMSLoader').hide();
                //
                return;
            }
            //
            if (resp.Status === true && resp.Chapter == 'completed') {
                alertify.alert(
                    "SUCCESS!",
                    resp.Response,
                    function () {
                        var limit = SCORM_XML.sequencing.length - 1;
                        LAST_CHAPTER = SCORM_LEVEL + 1;
                        //
                        if (SCORM_LEVEL == limit) {
                            window.location.reload();
                        }
                    }
                );
                //
            }
            //
            $('.jsLMSLoader').hide();
        },
        error: function() {
            alertify.alert(
                "WARNING!",
                "Unable to save scorm progress"
            );
            //
            $('.jsLMSLoader').hide();
        }
    });   
}