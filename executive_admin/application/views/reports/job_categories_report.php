<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-dashboard"></i><?php echo $title; ?></h1>
                    <a class="black-btn pull-right" href="<?php echo base_url('dashboard/reports/' . $company_sid); ?>">
                        <i class="fa fa-long-arrow-left"></i> 
                        Back to Reports
                    </a>
                </div>       
                <?php if (isset($categories) && sizeof($categories) > 0) { ?>
                <div class="bt-panel">
                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                        <i class="fa fa-print" aria-hidden="true"></i> 
                        Print
                    </a>
                    <form method="post" id="export" name="export">
                        <input type="hidden" name="submit" value="Export" />
                        <button class="btn btn-success" type="submit">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> 
                            Export To Excel
                        </button>
                    </form>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <hr>
                </div>
                <div class="page-header-area">
                    <span class="page-heading pull-right">
                        <b><?= 'Total number of categories:    ' . sizeof($categories)?></b>
                    </span>
                </div>
                <?php } ?>
                <!-- table -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="hr-box">
                            <div class="table-responsive hr-innerpadding" id="print_div">
                                <table class="table table-stripped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-1">Sr. No</th>
                                            <th class="col-xs-9">Category</th>
                                            <th class="col-xs-2 text-center">Hire Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($categories) && sizeof($categories) > 0) { ?>
                                            <?php foreach ($categories as $key => $category) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $key + 1; ?></td>
                                                    <td class=""><?php echo $category['category']; ?></td>
                                                    <td class="text-center"><?php echo $category['count']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="3">
                                                    <div class="no-data">No job categories found.</div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table -->
                <?php if (isset($categories) && sizeof($categories) > 0) { ?>
                <div class="bt-panel">
                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                        <i class="fa fa-print" aria-hidden="true"></i> 
                        Print
                    </a>
                    <form method="post" id="export" name="export">
                        <input type="hidden" name="submit" value="Export" />
                        <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                    </form>
                </div> 
                <?php } ?>
            </div>               					
        </div>
    </div>
</div>
<script type="text/javascript">
    function print_page(elem)
    {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>