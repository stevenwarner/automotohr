<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <title>Export Document(s)</title>
    <style>
        .cs-main {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
        }

        .cs-box {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .cs-box i {
            font-size: 90px;
        }

        .cs-box p {
            font-size: 16px;
        }

        .cs-box h6 {
            font-size: 16px;
        }
    </style>
</head>

<body style="overflow: hidden;">
    <div class="cs-main">
        <div class="col-sm-4 cs-box">
            <i class="fa fa-download"></i>
            <h5><strong id="js-dc">0</strong> of <strong id="js-dt"></strong> documents</h5>
            <div class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                </div>
            </div>
            <p>Please wait while we are loading documents.</p>

        </div>
    </div>

    <script>
        //
      

        // ucwords
        String.prototype.ucwords = function() {
            str = this.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                function(s) {
                    return s.toUpperCase();
                });
        };

        // fix
        String.prototype.fix = function() {
            str = this.toLowerCase();
            return str.replace(/_/g, ' ').ucwords();
        };

        //
        $(function() {

            let assigned = 1;
            let assignedLength = 1;
            let dt = 10;
            let dc = 0;
            let gd = 0;
            let token = "<?= $token; ?>";

          
           // if (has['W4MN'] != "null") assignedLength++;

            //
            $('#js-dt').text('2');

                 

            var XHR = null;
            //
            function uploadDocument(d) {
                //
                if (XHR !== null) {
                    setTimeout(() => {
                        uploadDocument(d);
                    }, 1000);
                    return;
                }
                //
                XHR = $.post("<?= base_url('performance-management/upload'); ?>", {
                    token: token,
                    data: d,
                    typo: 'report',
                    employeeSid: "12"
                }, () => {
                    XHR = null;
                }).fail(() => {
                    setTimeout(() => {
                        uploadDocument(d);
                    }, 1000);
                });
            }

           
        
            //



            let o = {
                        title: "ops",
                        content: "dataaa  eewrwerwer wer werwer qwerw"
                    };
                    //
                  //  $('#js-export-area div').html(o.content);
                    //
                    generatePDF($('#js-export-area div'), o);




                    ///
            function generatePDF(
                target,
                o
            ) {
                let draw = kendo.drawing;
                draw.drawDOM(target, {
                        avoidLinks: false,
                        paperSize: "A4",
                        multiPage: true,
                        height: 500,
                        forcePageBreak: '.js-break',
                        margin: {
                            bottom: "1cm",
                            left: "1cm",
                            top: ".3cm",
                            right: "1cm"
                        },
                        scale: 0.8
                    })
                    .then(function(root) {
                        return draw.exportPDF(root);
                    })
                    .done(function(data) {
                        //
                        o.content = ' flhksadfhsdfljksf  jskdf ';//data;
                        //
                        uploadDocument(
                            o
                        );
                    });
            }
        })
    </script>


    <!--  -->
    <div style="float: left; margin-left: -1000px; width: 800px;">dfgsdg
    </div>


    <!--  -->
    <div id="js-export-area" style="
position: fixed;
left: -1000px;
top: 0;
max-width: 800px;
overflow: hidden;
padding: 16px;
font-size: 16px;
word-break: break-all; 
">
        <div class="A4"></div>
    </div>
</body>

</html>