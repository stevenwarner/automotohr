<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $title; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--<style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic);
        *{
            margin: 0;
            padding: 0;
            outline: none !important;
        }
        .clear{
            clear: both;
            font-size: 0;
            line-height: 0;
        }
        a{
            text-decoration: none;
        }
        body{
            font-size: 14px;
            color: #000;
            line-height: 1.5;
            font-family: 'Open Sans', sans-serif;
            position: relative;
            overflow-x:hidden;
        }
        body h1, body h2, body h3, body h4, body h5, body h6{
            line-height: 1.3;
        }
        .hr-registered{
            color: #000;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
        .heading-title {
            border-bottom: 1px solid #eee;
            float: left;
            margin: 20px 0;
            padding: 0 0 10px;
            width: 100%;
        }
        .page-title {
            color: #000;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 15px 0;
            text-transform: capitalize;
        }
        .hr-box {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 6px #ccc;
            clear: both;
            float: left;
            margin: 10px 0;
            padding: 0;
            width: 100%;
        }
        .hr-box-header {
            background: #eee;
            border-radius: 5px 5px 0 0;
            float: left;
            margin: 0;
            overflow: hidden;
            padding: 9px;
            width: 100%;
        }
        .hr-innerpadding {
            float: left;
            padding: 10px;
            width: 100%;
        }
        .bg-header-green {
            background-color: #00a700;
        }
        .bg-header-green .text-success,
        .bg-header-green .hr-registered,
        .bg-header-green div {
            color: #fff;
        }
        /*========Custom Checkbox and radio buttom style=================*/
        .control {
          display: inline-block;
          position: relative;
          padding-left: 30px;
          cursor: pointer;
          font-size: 14px;
          width: auto !important;
        }
        .control input {
          position: absolute;
          z-index: -1;
          opacity: 0;
        }
        .control__indicator {
          position: absolute;
          top: 2px;
          left: 0;
          height: 20px;
          width: 20px;
          background: #e6e6e6;
        }
        .control--radio .control__indicator {
          border-radius: 50%;
        }
        .control:hover input ~ .control__indicator,
        .control input:focus ~ .control__indicator {
          background: #ccc;
        }
        .control input:checked ~ .control__indicator {
          background: #00a700;
        }
        .control:hover input:not([disabled]):checked ~ .control__indicator,
        .control input:checked:focus ~ .control__indicator {
          background: #00a700;
        }
        .control input:disabled ~ .control__indicator {
          background: #e6e6e6;
          opacity: 0.6;
          pointer-events: none;
        }
        .control__indicator:after {
          content: '';
          position: absolute;
          display: none;
        }
        .control input:checked ~ .control__indicator:after {
          display: block;
        }
        .control--checkbox .control__indicator:after {
          left: 8px;
          top: 4px;
          width: 5px;
          height: 10px;
          border: solid #fff;
          border-width: 0 2px 2px 0;
          -webkit-transform: rotate(45deg);
          -moz-transform: rotate(45deg);
          -ms-transform: rotate(45deg);
          -o-transform: rotate(45deg);
          transform: rotate(45deg);
        }
        .control--checkbox input:disabled ~ .control__indicator:after {
          border-color: #7b7b7b;
        }
        .control--radio .control__indicator:after {
          left: 7px;
          top: 7px;
          height: 6px;
          width: 6px;
          border-radius: 50%;
          background: #fff;
        }
        .control--radio input:disabled ~ .control__indicator:after {
          background: #7b7b7b;
        }
        /*========Custom Checkbox and radio buttom style End=================*/
        .section-heading{
            float: left;
            width: 100%;
            padding: 0 0 5px 0;
            margin: 0;
            font-weight: 600;
            text-transform: capitalize;
        }
        .question-title{
            float: left;
            width: 100%;
            color: #000;
            font-weight: 600;
            font-size: 16px;
            text-transform: capitalize;
        }
        .question-wrp{
            float: left;
            width: 100%;
            padding: 15px 0;
        }
        .question-wrp .hr-innerpadding{
            border:1px solid #ddd;
        }
        .options-row{
            float: left;
            width: 100%;
            margin: 4px 0;
        }
        .invoice-fields {
            background-color: #eee;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #000;
            float: left;
            height: 40px;
            padding: 0 5px;
            width: 100%;
        }
        .textarea{
            margin: 30px 0 15px 0;
        }
        .textarea .invoice-fields{
            padding: 10px;
            height: 200px;
        }
    </style>-->
</head>
<body>
<?php echo $the_view_content;  ?>


<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo site_url('assets/manage_admin/js/bootstrap.min.js'); ?>"></script>

</body>
</html>