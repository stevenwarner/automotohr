$(function () {
	//
	var courseURL = baseURI+'lms_courses/handler';
	//

	function getCompanyCourses (type) {
         $.ajax({
            type: 'POST',
            url: courseURL,
            data: {
            	'action': "get_all_courses",
	            'employeeId': eToken,
	            'companyId': cToken
            },
            beforeSend: function() {
                $('.jsLMSLoader').show();
            },
            success: function(resp) {
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
                if (resp.Status === true) {
                    generateOverviewPreview(resp.Courses);
                }
            },
            error: function() {
                alertify.alert("Notice", "Unable to save course detail</b>");
                $('.jsLMSLoader').hide();
            }
        });
    }

    function generateOverviewPreview (courses) {
    	var courseBox = '';
        var courseNo = 0;
        //
        if (courses.length) {
            courses.map(function(course) {
              	courseBox += '<div class="csCourseRow _csBDt _csBD6 _csPt20">';
				courseBox += '    <div class="row">';
				courseBox += '        <div class="col-md-10 col-sm-12">';
				courseBox += '            <div>';
				courseBox += '                <span><b>Title: </b>'+course.title+'</span><br>';
				courseBox += '                <span><b>Description: </b>'+course.description+'</span><br>';
				courseBox += '                <span><b>Course Type: </b>'+course.type+'</span><br>';
				courseBox += '                <span><b>Assign To: </b>'+course.employees+'</span><br>';
				courseBox += '                <span><b>Created By: </b>'+course.created_by+'</span><br>';
				courseBox += '                <span><b>Created On: </b>'+course.created_on+'</span>';
				courseBox += '            </div>';
				courseBox += '        </div>';
				courseBox += '    </div>';
				courseBox += '</div>';
                //
                courseNo++;
            });
        } else {
            
            courseBox += '<div class="row">';
            courseBox += ' <div class="col-md-12 col-xs-12">';
            courseBox += '     <p colspan="2" class="text-center"><b>No course added yet.</b></p>';
            courseBox += ' </div>';
            courseBox += '</div>';
        }

        $("#jsOverviewListing").html(courseBox)    
        $("#jsOverviewCount").html(courseNo); 
        //
         $('.jsLMSLoader').hide();           

    }

    getCompanyCourses();
})