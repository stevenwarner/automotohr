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
                <?php if (isset($users) && sizeof($users) > 0) { ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
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
                    </div>
                </div>
                <?php } ?>
                <!-- table -->
                
                    <div id="print_div">
                        <?php if (isset($users) && sizeof($users) > 0) { ?>
                            <?php foreach ($users as $user => $references) { ?>
                                <div class="hr-box">                                     
                                    <div class="hr-box-header bg-header-green">
                                        <h1 class="hr-registered">
                                            <span><?php echo $user; ?></span>
                                            <span style="float:right;"><?php echo sizeof($references) . ' ' . ucwords($references[0]['users_type']); ?></span>
                                        </h1>
                                    </div>
                                    <div class="table-responsive hr-innerpadding">
                                        <table class="table table-stripped table-bordered" id="example">
                                            <thead>
                                                <tr>
                                                    <th>Reference Title</th>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Relation</th>
                                                    <th>Email</th>
                                                    <th>Department</th>
                                                    <th>Branch</th>
                                                    <th>Reference Organization</th>
                                                </tr> 
                                            </thead>
                                            <tbody>
                                                <?php if (isset($references) && sizeof($references) > 0) { ?>
                                                    <?php foreach ($references as $reference) { ?>
                                                        <tr>
                                                            <td><?php echo $reference['reference_title']; ?></td>
                                                            <td><?php echo $reference['reference_name']; ?></td>
                                                            <td><?php echo ucwords($reference['reference_type']); ?></td>
                                                            <td><?php echo $reference['reference_relation']; ?></td>
                                                            <td><?php echo $reference['reference_email']; ?></td>
                                                            <td><?php echo $reference['department_name']; ?></td>
                                                            <td><?php echo $reference['branch_name']; ?></td>
                                                            <td><?php echo $reference['organization_name']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td class="text-center" colspan="8">
                                                            <div class="no-data">No references found.</div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="hr-box">
                                <div class="table-responsive hr-innerpadding">
                                    <table class="table table-stripped table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th>Reference Title</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Relation</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                                <th>Branch</th>
                                                <th>Reference Organization</th>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" colspan="8">
                                                    <div class="no-data">No references found.</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                
                <!-- table -->
                <?php if (isset($users) && sizeof($users) > 0) { ?>
                <div class="col-xs-12 col-sm-12 margin-top">
                    <div class="row">
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
                    </div>
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