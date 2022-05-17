<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/font-awesome.css') ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/employee_panel/alertifyjs/css/alertify.min.css') ?>" />
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/employee_panel/alertifyjs/css/themes/default.min.css') ?>" />
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/employee_panel/css/jquery.datetimepicker.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/select2.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.validate.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/additional-methods.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery.datetimepicker.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/alertifyjs/alertify.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/functions.js') ?>"></script>
    <?php if ($this->uri->segment(2) != 'sign_hr_document' && $this->uri->segment(2) != 'my_offer_letter') {?>
    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js') ?>"></script>
    <?php }?>
    <script src="<?php echo base_url('assets/bootstrap-filestyle/js/bootstrap-filestyle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/select2.js') ?>"></script>
    <style>
        @media only screen and (max-width: 576px){
        .arrow-links ul li a:before{
        display: none !important;
        }
        .arrow-links ul li a:after{
            display: none !important;
        }
        .col-xs-12{
            padding:0px;
        }
        .row{
            margin:0px !important;
        }
        .ajs-dialog{
            width:300px !important;
        }
        .alertify .ajs-dialog{
            margin: 0px !important;
            margin-top: 80px !important;
        }
        .ajs-dimmer{
            width:376px !important;
        }
        .cs_verflow_setting{
            overflow-x:hidden !important;
        }
    }
</style>
  
</head>
	<body>
	    <div>
	        <header>
	            <div>
	            	<div style="width:100%; float:left; padding:5px 20px; text-align:center; box-sizing: border-box; background-color:#0000FF;">
	            		<h2 style="color:#fff;">
	            			<?php echo $company_detail['CompanyName']; ?>
	            		</h2>
	            	</div>
	            </div>
	        </header>
			
			<div class="main">
			    <div class="container">
			        <!--  -->
					<div class="row">
					    <div class="col-sm-12">
					        <table class="table table-striped table-bordered">
					            <caption class="csF18 csB7">Document Details.</caption>
					            <tbody>
					                <tr>
					                    <td class="col-sm-3"><strong>Document Title</strong></td>
					                    <td class="col-sm-9"><?=ucfirst($document_title);?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Document Type</strong></td>
					                    <td class="col-sm-9"><?=ucfirst($document_type);?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Acknowledgment Required</strong></td>
					                    <td class="col-sm-9"><?php echo $document_detail['acknowledgment_required'] ? 'Yes' : 'No'; ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Download Required</strong></td>
					                    <td class="col-sm-9"><?php echo $document_detail['download_required'] ? 'Yes' : 'No'; ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Signature Required</strong></td>
					                    <td class="col-sm-9"><?php echo $document_detail['signature_required'] ? 'Yes' : 'No'; ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Document Required</strong></td>
					                    <td class="col-sm-9"><?php echo $document_detail['is_required'] ? 'Yes' : 'No'; ?></td>
					                </tr>
					            </tbody>
					        </table>
					    </div>
					</div>
					<!--  -->

					<!--  -->
					<div class="row">
					    <div class="col-sm-12">
					        <table class="table table-striped table-bordered">
					            <caption class="csF18 csB7">User Details.</caption>
					            <tbody>
					                <tr>
					                    <td class="col-sm-3"><strong>Assigned To</strong></td>
					                    <td class="col-sm-9"><?php echo $document_user_name; ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Assigned To Type</strong></td>
					                    <td class="col-sm-9"><?=ucfirst($document_user_type);?></td>
					                </tr>
					            </tbody>
					        </table>
					    </div>
					</div>
					<!--  -->

					<!--  -->
					<div class="row">
					    <div class="col-sm-12">
					        <table class="table table-striped table-bordered">
					            <caption class="csF18 csB7">Assigner Details.</caption>
					            <tbody>
					                <tr>
					                    <td class="col-sm-3"><strong>Assigned By</strong></td>
					                    <td class="col-sm-9"><?php echo getUserNameBySID($assigned_by); ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Assigned On</strong></td>
					                    <td class="col-sm-9">
					                        <?php 
					                            if (isset($assigned_date) && $assigned_date != '0000-00-00 00:00:00') {
					                                echo reset_datetime(array('datetime' => $assigned_date, '_this' => $this));
					                            }
					                        ?>
					                    </td>
					                </tr>
					            </tbody>
					        </table>
					    </div>
					</div>
					<!--  -->

					<!--  -->
					<?php if (!empty($approvers_note)) { ?>
						<div class="row">
						    <div class="col-sm-12">
						        <table class="table table-striped table-bordered">
						            <caption class="csF18 csB7">Note</caption>
						            <tbody>
						                <tr>
						                    <td class="col-sm-12"><?php echo $approvers_note;?></td>
						                </tr>
						            </tbody>
						        </table>
						    </div>
						</div>
					<?php } ?>
					<!--  -->

					<!--  -->
					<div class="row">
					    <div class="col-sm-12">
					        <table class="table table-striped table-bordered">
					            <caption class="csF18 csB7">Action</caption>
					            <tbody>
					                <tr>
					                    <td class="col-sm-12">
					                        <div class="field-row">
					                            <select id="jsSelectedApprover" class="form-control" >
					                                <option value="0" >Please Select an Action</option>
					                                <option value="accept" <?php echo $action == "accept" ? 'selected' : '' ?>>Accept</option>
					                                <option value="reject" <?php echo $action == "reject" ? 'selected' : '' ?>>Reject</option>
					                            </select>
					                        </div>
					                    </td>
					                </tr>
					                <tr>    
					                    <td class="col-sm-12">
					                        <div class="field-row">
					                            <textarea class="ckeditor" name="note" id="approval_note" cols="60" rows="10">
			                                            
			                                    </textarea>
					                        </div>
					                    </td>
					                </tr>
					                <tr>    
					                    <td class="col-sm-12">
					                        <div class="field-row">
					                            <button class="btn btn-success btn-block js-view">Save Action</button>
					                        </div>
					                    </td>
					                </tr>
					            </tbody>
					        </table>
					    </div>
					</div>
					<!--  -->
			    </div>
			</div>

			<footer>
			    <div>
			        <div class="footer" style="width:100%; float:left; background-color:#0000FF; padding:20px 30px; box-sizing:border-box;">
			        	<div style="float:left; width:100%; ">
			        		<p style="color:#fff; text-align:center; font-style:italic; line-height:normal; font-family:  'open sans', sans-serif; font-weight:600; font-size:14px;">
			        			<a style="color:#fff; text-decoration:none;" href="<?php echo STORE_PROTOCOL.$company_domain; ?>">
				        			<?php echo $company_domain; ?>
				        		</a>
				        	</p>
				        </div>
				    </div>
			    </div>
			</footer>
		</div>	
		<script type="text/javascript">
			  CKEDITOR.replace( 'approval_note', {
			    toolbar: [
				    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
				    { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
				]
			});

		    function googleTranslateElementInit() {
		        new google.translate.TranslateElement({
		            pageLanguage: 'en',
		            includedLanguages: 'de,es,fr,pt,it,zh-CN,zh-TW',
		            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
		        }, 'google_translate_element');
		    }
		</script>
		<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	</body>
</html>
		