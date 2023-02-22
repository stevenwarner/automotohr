//
$(document).ready(function () {
	//
	var links = '';
	var index = 0;
	var id = SCORM_CHAPTER.trim().toLowerCase().replace(/ /g, "_");
	//
	SCORM_XML.sequencing.map(function (sequence) {
		var linkID = sequence.title.trim().toLowerCase().replace(/ /g, "_");
        links += '<li>';
        links += '	<a class="jsChangeChapter btn_scorm _csB4" href="javascript:;" id="'+linkID+'" data-index="'+index+'" data-status="lock" data-parameter="'+sequence.parameter+'">';
        links += '  	<strong>' + (sequence.title) + '</strong>';
        links += '  	<i id="'+linkID+'_icon" class="fa fa-lock"></i>';
        links += '  </a>';
        links += '</li>';
        //
        index++;
    });
    //
    $('#jsScormChapterLinks').html(links);
    $(".jsChangeChapter").prop('disabled', true);
    $("#"+id).addClass("active");
    //
	setNewChapter();	
});	

function setNewChapter () {
	var SCORM_URL = SCORM_PATH + SCORM_CONTENT;
	$("#jsScormCourse").attr('src',SCORM_URL); 
	//
	modifyNavBar();
}

function modifyNavBar () {
	var id = SCORM_CHAPTER.trim().toLowerCase().replace(/ /g, "_");
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