<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $maintenance_mode_page_content['page_title']; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="<?php echo base_url('assets'); ?>/images/favi-icon.png">
    <!--[if IE]><![endif]-->

    <style type="text/css">

        /* ==================================================
           Google Fonts
        ================================================== */
        @import url("http://fonts.googleapis.com/css?family=Roboto+Condensed:300,300italic,400,400italic,700,700italic");

        /* ==================================================
           Global Styles
        ================================================== */

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: table;
        }

        html {
            height: 100%;
            overflow: auto;
            background: #FFF url("<?php echo base_url('assets'); ?>/images/bg-under-construction.jpg") no-repeat;
            background-size: cover;

            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            font-family: "Roboto Condensed", Arial, Verdana, Helvetica, sans-serif;
            font-size: 20px;
            color: #FFF;
            line-height: 32px;
            font-weight: 300;

            text-rendering: optimizeLegibility;
            vertical-align: baseline;
        }

        /* ==================================================
           Layout Styles
        ================================================== */

        .under-construction {
            display: table-cell;
            vertical-align: middle;
        }

        .under-construction #innercont {
            display: inline-block;
            width: 100%;
            padding: 25px;
        }

        .under-construction #innercont .bodycontainer {
            margin: 0 auto;
            width: 100%;
            max-width: 1140px;
        }

        /* ==================================================
           Sections
        ================================================== */

        #content {
            padding: 40px 25px;
            text-align: left;

            text-align: center;
        }

        /** Main Header **/

        #content h1 {
            display: inline-block;
            color: #fff;
            font-size: 60px;
            line-height: 60px;
            font-weight: 700;
            letter-spacing: -0.6px;
            font-family: "Roboto Condensed";
            text-transform: uppercase;
            margin: 0 0 25px 0;
            padding: 10px 16px;
        }

        /* ==================================================
           Responsive Media Queries - Tablets
        ================================================== */

        @media screen and (max-width: 768px) {

            .under-construction #innercont {
                padding: 25px;
            }

            #content {
                padding: 25px;
            }

            #content h1 {
                font-size: 40px;
                line-height: 50px;
                margin: 0;
            }

            #content {
                padding: 0;
            }

            .under-construction #innercont {
                padding: 15px;
            }

        }

        /* ==================================================
           Responsive Media Queries - Mobiles
        ================================================== */

        @media screen and (max-width: 480px) {
        }


    </style>
</head>
<body>

<div class="under-construction" class="clearfix">
    <div id="innercont" class="clearfix">
        <div id="content" class="bodycontainer clearfix">
            <h1><?php echo $maintenance_mode_page_content['page_title']; ?></h1>
            <p><?php echo html_entity_decode($maintenance_mode_page_content['page_content']); ?></p>
        </div>
    </div>
</div>

</body>
</html>