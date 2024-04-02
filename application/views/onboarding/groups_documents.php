  <?php
    $action_btn_flag = false;
    $document_all_permission = false;
    if ($session['employer_detail']['access_level_plus'] == 1) {
        $action_btn_flag = true;
        $document_all_permission = true;
    }
    //
    $GLOBALS['ad'] = $assigned_documents;
    //
    $modifyBTN = '<button
        class="btn btn-success btn-sm btn-block js-modify-assigned-document-btn"
        data-id="{{sid}}"
        data-type="{{type}}"
        title="Modify assigned document"
    >Modify</button>';
    //
    $assignIdObj = $confidential_sids;

    //_e($assignIdObj ,true);
    //
    ?>

  <!-- Active Group Document Start -->

  <?php //_e($active_groups,true)
    ?>
  <?php if (!empty($active_groups)) { ?>
      <?php foreach ($active_groups as $active_group) {
            if ($active_group['documents_count'] == 0) {
                continue;
            } ?>
          <div class="row">
              <div class="col-xs-12">
                  <div class="panel panel-default hr-documents-tab-content js-search-header">
                      <div class="panel-heading">
                          <h4 class="panel-title">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_ag<?php echo $active_group['sid']; ?>">
                                  <span class="glyphicon glyphicon-plus"></span>
                                  <?php echo $active_group['name']; ?>
                                  <div class="btn btn-xs btn-success">Active Group</div>
                                  <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . $active_group['documents_count']; ?></b></div>
                              </a>

                              <?php if ($action_btn_flag == true) { ?>
                                  <?php if (in_array($active_group['sid'], $assigned_groups)) { ?>
                                   
                                      <?php $group_status = get_user_assign_group_status($active_group['sid'], $user_type, $user_sid); ?>

                                      <?php if ($group_status == 1) { ?>
                                          <button type="button" style="margin-left: 5px;" class="btn btn-danger btn-xs pull-right" id="btn_group_<?php echo $active_group['sid']; ?>" onclick="func_revoke_document_group('<?php echo $active_group['sid']; ?>','<?php echo $user_type; ?>','<?php echo $user_sid; ?>', '<?php echo $active_group['name'] ?>')">
                                              Revoke Document Group
                                          </button>
                                      <?php }  ?>
                                      <button type="button" class="btn btn-warning btn-xs pull-right" id="btn_group_<?php echo $active_group['sid']; ?>" onclick="func_reassign_document_group('<?php echo $active_group['sid']; ?>','<?php echo $user_type; ?>','<?php echo $user_sid; ?>', '<?php echo $active_group['name'] ?>')">
                                          Reassign Document Group
                                      </button>
                                      <?php //} 
                                        ?>
                                  <?php } else { ?>
                                      <button class="btn btn-primary btn-xs pull-right" id="btn_group_<?php echo $active_group['sid']; ?>" onclick="func_assign_document_group('<?php echo $active_group['sid']; ?>','<?php echo $user_type; ?>','<?php echo $user_sid; ?>', '<?php echo $active_group['name'] ?>')">
                                          Assign Document Group
                                      </button>
                                  <?php } ?>
                              <?php } ?>
                          </h4>
                      </div>

                      <div id="collapse_ag<?php echo $active_group['sid']; ?>" class="panel-collapse collapse">
                          <div class="table-responsive">
                              <table class="table table-plane">
                                  <thead>
                                      <tr>
                                          <th scope="column" class="col-xs-8">Document Name</th>
                                          <th scope="column" class="col-xs-2">Document Type</th>
                                          <th scope="column" class="col-xs-2 text-center" colspan="2">Actions</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <!-- List verification and general documents with no actions -->
                                      <?php if ($active_group['other_documents']) : ?>
                                          <?php foreach ($active_group['other_documents'] as $otherDocument) : ?>
                                              <tr class="js-search-row">
                                                  <td class="col-xs-8"><?= $otherDocument; ?></td>
                                                  <td>-</td>
                                                  <td class="text-center">-</td>
                                              </tr>
                                          <?php endforeach; ?>
                                      <?php endif; ?>
                                      <?php if ($active_group['documents_count'] > 0) { ?>
                                          <?php foreach ($active_group['documents'] as $document) { ?>
                                              <tr class="js-search-row">
                                                  <td class="col-xs-8"><?php echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : ''); ?></td>
                                                  <td class="col-xs-2">
                                                      <?php echo ucwords($document['document_type']); ?>
                                                  </td>
                                                  <?php if (($user_type == 'applicant' && check_access_permissions_for_view($security_details, 'app_assign_revoke_doc')) || ($user_type == 'employee' && check_access_permissions_for_view($security_details, 'emp_assign_revoke_doc')) || $canAccessDocument) { ?>
                                                      <?php if ($action_btn_flag == true || $session['employer_detail']['pay_plan_flag'] == 0) { ?>
                                                          <td>
                                                              <?php if ($document_all_permission) { ?>
                                                                  <?php if (in_array($document['sid'], $assigned_sids) || in_array($document['sid'], $revoked_sids) || in_array($document['sid'], $completed_sids) || in_array($document['sid'], $signed_document_sids)) { ?>

                                                                      <?php if (in_array($document['sid'], $assigned_sids)) { ?>
                                                                          <!-- revoke here  -->
                                                                          <button onclick="func_remove_document('<?php echo $document['document_type']; ?>', <?php echo $document['sid']; ?>);" class="btn btn-danger btn-block btn-sm" type="button">Revoke</button>
                                                                      <?php } else if (in_array($document['sid'], $signed_document_sids)) { ?>
                                                                          <button class="btn blue-button btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Completed and Reassign</button>
                                                                      <?php } else { // re-assign here 
                                                                        ?>
                                                                          <button class="btn btn-warning btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Reassign</button>
                                                                      <?php } ?>
                                                                  <?php } else { ?>
                                                                      <!-- assign here -->
                                                                      <button class="btn btn-success btn-sm btn-block js-modify-assign-document-btn" data-id="<?= $document['sid']; ?>">Modify and Assign</button>
                                                                  <?php } ?>
                                                              <?php } ?>
                                                          </td>
                                                      <?php } ?>
                                                  <?php } ?>
                                                  <td>
                                                      <?php if ($document['document_type'] == 'uploaded') {
                                                            $document_filename = !empty($document['uploaded_document_s3_name']) ? $document['uploaded_document_s3_name'] : '';
                                                            $document_file = pathinfo($document_filename);
                                                            $name = explode(".", $document_filename);
                                                            $url_segment_original = $name[0]; ?>
                                                          <button class="btn btn-success btn-sm btn-block" onclick="view_original_uploaded_doc(this);" data-preview-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-print-url="<?php echo $url_segment_original; ?>" data-download-url="<?php echo AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name']; ?>" data-document-sid="<?php echo $document['sid']; ?>" data-file-name="<?php echo $document['uploaded_document_original_name']; ?>" data-document-title="<?php echo $document['uploaded_document_original_name']; ?>">View Doc</button>
                                                      <?php } else { ?>
                                                          <button onclick="view_original_generated_document(<?php echo $document['sid']; ?>, 'generated', 'original');" class="btn btn-success btn-sm btn-block">View Doc</button>
                                                      <?php } ?>
                                                  </td>
                                                  <?php if ($document['document_type'] == 'uploaded') { ?>
                                                      <td class="col-lg-1">
                                                          <?php
                                                            $document_filename = $document['uploaded_document_s3_name'];
                                                            $document_file = pathinfo($document_filename);
                                                            $document_extension = $document_file['extension'];
                                                            $name = explode(".", $document_filename);
                                                            $url_segment_original = $name[0];
                                                            ?>
                                                          <?php if ($document_extension == 'pdf') { ?>
                                                              <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_original . '.pdf' ?>" class="btn btn-success btn-sm btn-block">Print</a>

                                                          <?php } else if ($document_extension == 'docx') { ?>
                                                              <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edocx&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                          <?php } else if ($document_extension == 'doc') { ?>
                                                              <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Edoc&wdAccPdf=0' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                          <?php } else if ($document_extension == 'xls') { ?>
                                                              <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exls' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                          <?php } else if ($document_extension == 'xlsx') { ?>
                                                              <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_original . '%2Exlsx' ?>" class="btn btn-success btn-sm btn-block">Print</a>
                                                          <?php } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) { ?>
                                                              <a target="_blank" href="<?php echo base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original'); ?>" class="btn btn-success btn-sm btn-block">
                                                                  Print
                                                              </a>
                                                          <?php } else { ?>
                                                              <a class="btn btn-success btn-sm btn-block" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-download-url="<?= AWS_S3_BUCKET_URL . $document_filename; ?>" data-file-name="<?php echo $document_filename; ?>" data-document-title="<?php echo $document_filename; ?>" data-preview-ext="<?php echo $document_extension ?>">Print</a>
                                                          <?php } ?>
                                                      </td>
                                                      <td class="col-lg-1">
                                                          <a href="<?= base_url('hr_documents_management/download_upload_document/' . $document['uploaded_document_s3_name']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                      </td>
                                                  <?php } else { ?>
                                                      <td class="col-lg-1">
                                                          <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid']); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                      </td>

                                                      <td class="col-lg-1">
                                                          <a href="<?= base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document['sid'] . '/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                      </td>

                                                  <?php } ?>
                                              </tr>
                                          <?php } ?>
                                      <?php } else { ?>
                                          <tr>
                                              <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
                                          </tr>
                                      <?php } ?>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      <?php } ?>
  <?php } ?>
  <!-- Active Group Document End -->




  <script>
      function view_original_generated_document(document_sid, doc_flag = 'generated', doc_title = 'Preview Generated Document') {
          var my_request;
          my_request = $.ajax({
              'url': '<?php echo base_url('hr_documents_management/ajax_responder'); ?>',
              'type': 'POST',
              'data': {
                  'perform_action': 'get_generated_document_preview',
                  'document_sid': document_sid,
                  'source': doc_flag,
                  'fetch_data': 'original'
              }
          });

          my_request.done(function(response) {
              footer_content = '<a target="_blank" class="btn btn-success" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original'); ?>' + '/' + doc_flag + '/' + document_sid + '/download">Download</a>';
              footer_print_btn = '<a target="_blank" href="<?php echo base_url('hr_documents_management/print_generated_and_offer_later/original'); ?>' + '/' + doc_flag + '/' + document_sid + '" class="btn btn-success">Print</a>';

              $('#document_modal_body').html(response);
              $('#document_modal_footer').html(footer_content);
              $('#document_modal_footer').append(footer_print_btn);
              $('#document_modal_title').html(doc_title);
              $('#document_modal').modal("toggle");
          });
      }




      function func_reassign_document_group(group_sid, user_type, user_sid, group_name) {
        
        var user_name = "<?php echo $user_info['first_name']; ?> <?php echo $user_info['last_name']; ?>";
        alertify.confirm(
            'Confirm Document Group Reassign?',
            'Are you sure you want to reassign <strong><i>' + group_name + '</i></strong> group to <strong><i>' + user_name + '</i></strong> ?',
            function() {
                var myurl = "<?php echo base_url('hr_documents_management/ajax_reassign_document_group'); ?>" + '/' + group_sid + "/" + user_type + "/" + user_sid;

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(data) {
                        alertify.alert('SUCCESS!', "Group Reassigned Successfully", function() {
                          window.location.reload();
                        });
                    },
                    error: function(data) {

                    }
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }



    function func_revoke_document_group(group_sid, user_type, user_sid, group_name) {
        var user_name = "<?php echo $user_info['first_name']; ?> <?php echo $user_info['last_name']; ?>";
        alertify.confirm(
            'Confirm Document Group Revoke?',
            'Are you sure you want to revoke <strong><i>' + group_name + '</i></strong> group ?',
            function() {
                var myurl = "<?php echo base_url('hr_documents_management/ajax_revoke_document_group'); ?>" + '/' + group_sid + "/" + user_type + "/" + user_sid;

                $.ajax({
                    type: "GET",
                    url: myurl,
                    async: false,
                    success: function(data) {
                        alertify.alert('SUCCESS!', "Group Revoked Successfully", function() {
                            window.location.reload();
                        });
                    },
                    error: function(data) {

                    }
                });
            },
            function() {
                alertify.alert("Warning", 'Cancelled!');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    }
  </script>