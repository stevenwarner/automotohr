<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body>

<h1>My First Heading</h1>
<p>My first paragraph.</p>
<input type="text" value="" id="jsCourseID" placeholder="Enter Course Id">
<input type="text" value="" id="jsLink" placeholder="Enter AWS link">
<button id="jsProceed">Proceed</button>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    $(document).on("click", "#jsProceed", function (event) {
        var baseURI = '<?php echo base_url(); ?>';
		const courseObj = {
            scorm_file: $('#jsLink').val(),
        };
        var courseCode = $('#jsCourseID').val();
        //
        $.ajax({
            url: baseURI + "lms/course/scorm/parse/" + courseCode,
            method: "POST",
            data: courseObj,
        })
            .success()
            .fail();
	});
    
    $(document).ready(function () {
        // $.ajax({
        //     type: 'GET',
        //     url:'http://localhost:3000/employee_survey/templates',
        //     success: function(res){
        //         res.map(function (template) {
        //             console.log(template.title)
        //             console.log(template.description)
        //             console.log(template.frequency)
        //             console.log(template.questions_count)
        //         })
        //     },
        //     error: function(){

        //     }
        // });
    });
</script>
</body>
</html>

