<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<body>

<h1>My First Heading</h1>
<p>My first paragraph.</p>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url:'http://localhost:3000/employee_survey/templates',
            success: function(res){
                res.map(function (template) {
                    console.log(template.title)
                    console.log(template.description)
                    console.log(template.frequency)
                    console.log(template.questions_count)
                })
            },
            error: function(){

            }
        });
    });
</script>
</body>
</html>

