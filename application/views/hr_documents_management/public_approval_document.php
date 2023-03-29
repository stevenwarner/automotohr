<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/font-awesome.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/alertifyjs/css/alertify.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/alertifyjs/css/themes/default.min.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/style.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/employee_panel/css/responsive.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets\css\SystemModel.css') ?>">
    
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-1.11.3.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/employee_panel/alertifyjs/alertify.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/ckeditor/ckeditor.js') ?>"></script>
    <script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/SystemModal.js"></script>

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
			    	<?php if ($request_type == "view") { ?>
				        <div class="row">
				            <br>
				            <div class="col-sm-12">
				                <!--  -->
				                <?php $this->load->view('document_view'); ?>
				            </div>
				        </div>
				    <?php } ?>
			        <!--  -->
					<div class="row">
					    <div class="col-sm-12">
					        <table class="table table-striped table-bordered">
					            <caption class="csF18 csB7">Document Details</caption>
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
					                    <td class="col-sm-9"><?php echo $document_info['acknowledgment_required'] ? 'Yes' : 'No'; ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Download Required</strong></td>
					                    <td class="col-sm-9"><?php echo $document_info['download_required'] ? 'Yes' : 'No'; ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Signature Required</strong></td>
					                    <td class="col-sm-9"><?php echo $document_info['signature_required'] ? 'Yes' : 'No'; ?></td>
					                </tr>
					                <tr>
					                    <td class="col-sm-3"><strong>Document Required</strong></td>
					                    <td class="col-sm-9"><?php echo $document_info['is_required'] ? 'Yes' : 'No'; ?></td>
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
					                    <td class="col-sm-9"><?=ucfirst($user_type);?></td>
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
					<?php if ($approver_reference == $current_approver_reference && $request_type != "view") { ?>
						<div class="row">
						    <div class="col-sm-12">
						        <table class="table table-striped table-bordered">
						            <caption class="csF18 csB7">Action</caption>
						            <tbody>
						                <tr>
						                    <td class="col-sm-12">
						                        <div class="field-row">
						                            <select id="approver_action_status" class="form-control" >
						                                <option value="0" >Please Select an Action</option>
						                                <option value="Approve" <?php echo $action == "accept" ? 'selected' : '' ?>>Approve</option>
						                                <option value="Reject" <?php echo $action == "reject" ? 'selected' : '' ?>>Reject</option>
						                            </select>
						                        </div>
						                    </td>
						                </tr>
						                <tr>    
						                    <td class="col-sm-12">
						                        <div class="field-row">
						                            <textarea class="ckeditor" name="note" id="approver_action_note" cols="60" rows="10">
				                                            
				                                    </textarea>
						                        </div>
						                    </td>
						                </tr>
						                <tr>    
						                    <td class="col-sm-12">
						                        <div class="field-row">
						                            <button class="btn btn-success btn-block" id="jsSaveActionBtn">Save Action</button>
						                        </div>
						                    </td>
						                </tr>
						            </tbody>
						        </table>
						    </div>
						</div>
					<?php } ?>
					<!--  -->

					<!--  -->
					<?php if (!empty($approvers_list)) { ?>
						<div class="row">
						    <div class="col-sm-12">
						        <table class="table table-striped table-bordered">
						            <caption class="csF18 csB7">Approvers Details</caption>
						            <thead>
						                <tr>
						                    <td class="csB6">Name</td>
						                    <td class="csB6">Note</td>
						                    <td class="csB6">Status</td>
						                    <td class="csB6">Action Date</td>
						                </tr>
						            </thead>
						            <tbody>
					                    <?php foreach ($approvers_list as $approver) { ?>
					                        <tr>
					                            <td class="col-sm-3 csB6">
					                                <?php 
					                                    if(is_numeric($approver['assigner_sid']) && $approver['assigner_sid'] > 0){
					                                        echo getUserNameBySID($approver['assigner_sid']);
					                                    } else {
					                                        echo getDefaultApproverName($company_sid, $approver['approver_email']);
					                                    }
					                                ?>
					                                    
					                            </td>
					                            <td class="col-sm-3"><?php echo !empty($approver['approval_note']) ? $approver['approval_note'] : "N/A"; ?></td>
					                            <?php 
					                                $status_color = "";
					                                $status_text = "";
					                                if ($approver['approval_status'] == "Approve") {
					                                    $status_color = "text-success";
					                                    $status_text = "APPROVED";
					                                } else if ($approver['approval_status'] == "Reject") {
					                                    $status_color = "text-danger";
					                                    $status_text = "REJECTED";
					                                } else {
					                                    $status_color = "text-warning";
					                                    $status_text = "PENDING";
					                                }
					                            ?>
					                            <td class="col-sm-2 csB6 <?php echo $status_color; ?>">
					                                <?php echo $status_text; ?>
					                            </td>
					                            <td class="col-sm-2">
					                            	<?php echo !empty($approver['action_date']) ? $approver['action_date'] : "N/A"; ?>
					                            </td>
					                        </tr>
					                    <?php } ?>
						            </tbody>
						        </table>
						    </div>
						</div>
					<?php } ?>
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
		<?php $this->load->view('loader_new', ['id' => 'jsApprovalStatusLoader']); ?>
		<script type="text/javascript">
			//
			ml(false, 'jsApprovalStatusLoader');
			//
			CKEDITOR.replace( 'approver_action_note', {
			    toolbar: [
				    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
				    { name: 'colors', items: [ 'TextColor', 'BGColor' ] }
				]
			});

			$('#jsSaveActionBtn').on('click', function() {
		        //
		        var action = $('#approver_action_status').val();
		        //
		        if (action != 0) {
		        	//
		        	$("#jsSaveActionBtn").prop("disabled",true);
		        	ml(true, 'jsApprovalStatusLoader');
		        	//
		        	var approver_reference = '<?php echo $approver_reference; ?>';
			        var document_sid = '<?php echo $document_sid; ?>';
			        var action_note = CKEDITOR.instances.approver_action_note.getData();
			        //
			        var form_data = new FormData();
			        form_data.append('approver_reference', approver_reference);
			        form_data.append('approver_action', action);
			        form_data.append('approver_note', action_note);
			        form_data.append('document_sid', document_sid);
			        //
			        $.ajax({
			            url: '<?= base_url('hr_documents_management/save_approval_document_action') ?>',
			            cache: false,
			            contentType: false,
			            processData: false,
			            type: 'post',
			            data: form_data,
			            success: function (resp) {
			                //
			                if (resp.Status === false) {
			                    ml(false, 'jsApprovalStatusLoader');
			                    alertify.alert("Notice", resp.Msg);
			                    return;
			                }
			                //
			                alertify.alert("Notice", resp.Msg, function(){
			                    window.location.reload();
			                });
			            },
			            error: function () {
			            }
			        });
		        } else if (action == 0) {
		        	alertify.alert("Notice", "Please select any action!");
		        }
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
		