<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery.datetimepicker.css" />
        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/public-form-style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/responsive.css">

        <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
        <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
        <script src="<?php echo base_url('assets') ?>/alertifyjs/alertify.min.js"></script>

        <script src="<?php echo base_url('assets') ?>/js/functions.js"></script>
    </head>
    <body>
        <!-- Wrapper Start -->
        <div class="wrapper">
            <!-- Header Start -->
            <header class="header header-position">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                            <h2 style="color: #fff; text-align: center;"><?php echo $title; ?></h2>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Header End -->
            <div class="clear"></div>
            <div class="main" style="margin-top: 50px;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="box box-default">
                                <div class="box-body">
                                    <div class="alert alert-danger alert-dismissable">
                                        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                        <?php echo $error_message; ?>
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                    </div> <!-- /.row -->
<!--                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="box box-default">
                                <div class="box-body">
                                    <div class="alert alert-info alert-dismissable">
                                        <h4><i class="icon fa fa-check"></i> Help!</h4>
                                        <?php echo $information; ?>
                                    </div>
                                </div> /.box-body 
                            </div> /.box 
                        </div> /.col 
                    </div>  /.row -->
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper End -->
</body>
</html>
