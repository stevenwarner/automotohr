<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php echo $title; ?></span>
                        </div>
                        <div class="create-job-wrap">
                            <div class="job-title-text">
                                <p class="form-setps-title">Step 2 (of 2): Select Documents
                                    for <?php echo $userData['first_name'] ?> to Complete</p>
                            </div>
                            <div class="form-col-100">
                                <p>We'll create a custom onboarding package from your selection and guide your new hire
                                    through the process automatically.</p>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-col-100 row-checkbox">
                                            <label class="control control--checkbox">Offer Letter
                                                <input type="checkbox" name="offer_letter_check" value="1"
                                                   id="offerLetterLabel" class="offer_letter_check">
                                                <div class="control__indicator"></div>
                                            </label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" onchange='openModal(this.value)'
                                                        id="offerLertterDropdown" name="offer_letter_id">
                                                    <option value="0">Select an offer letter</option>
                                                    <?php foreach ($offerLetters as $offerLetter) { ?>
                                                        <option
                                                            value="<?php echo $offerLetter['sid'] ?>"><?php echo $offerLetter['letter_name'] ?><?php if ($offerLetter['alreadySent'] == 'true') { ?> (Already Sent)<?php } ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="popup-buttons">
                                                <?php foreach ($offerLetters as $offerLetter) { ?>
                                                    <input style="display: none" type="button" value="View"
                                                           data-toggle="modal"
                                                           data-target="#modal-content_<?php echo $offerLetter['sid'] ?>"
                                                           class="myViewButton submit-btn"
                                                           id="viewButton_<?php echo $offerLetter['sid'] ?>">
                                                    <input style="display: none" type="button" value="Edit"
                                                           data-toggle="modal"
                                                           data-target="#offer_letter_<?php echo $offerLetter['sid'] ?>"
                                                           class="myEditButton submit-btn"
                                                           id="editButton_<?php echo $offerLetter['sid'] ?>">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php if (count($hr_documents)) { ?>
                                            <?php foreach ($hr_documents as $document) { ?>
                                                <div class="form-col-100 row-checkbox">
                                                    <label class="control control--checkbox"
                                                        <?php if ($document['alreadySent'] == 'true') { ?>class="tick-class" <?php } ?>
                                                        for="document_<?php echo $document['sid']; ?>"><?php echo $document['document_original_name']; ?>
                                                        <?php if ($document['alreadySent'] == 'true') { ?>
                                                            <small>( Already sent - Please Mark the checkbox to resend )</small>
                                                        <?php } ?>
                                                        
                                                        <!--                                                        --><?php //if ($document['alreadySent'] != 'true') { ?>
                                                        <input id="document_<?php echo $document['sid']; ?>"
                                                               type="checkbox"
                                                               value="<?php echo $document['sid']; ?>"
                                                               name="document[]" class="docs_to_send">
<!--                                                        --><?php //} ?>
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                    <span class="info-text"><?php echo $document['document_description']; ?></span>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php echo form_error('document[]'); ?>

                                        <input type="hidden" value="1" name="send_mail">

                                        <div class="information-text-block">
                                            <p>Have HR documents to distribute? Upload them to<a
                                                    style="text-decoration: underline"
                                                    href="<?php echo base_url('hr_documents') ?>" target="_blank"> HR
                                                    Docs</a> and refresh this page to see them listed here!</p>
                                        </div>
                                        <div class="btn-panel">
                                            <input id="submit-btn" type="submit" class="submit-btn" value="Send Selected Items">
                                        </div>
                                    </form>

                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <div class="tick-list-box">
                                        <h2>Benefits of a Signed Offer</h2>
                                        <ul>
                                            <li>Written communication of the job offer</li>
                                            <li>Signed paperwork you can view/print anytime</li>
                                            <li>Valuable time back to focus on your business</li>
                                        </ul>
                                    </div>
                                    <div class="tick-list-box">
                                        <h2><?php echo STORE_NAME; ?> is Secure</h2>
                                        <ul>
                                            <li>Transmissions encrypted by SSL</li>
                                            <li>Information treated confidential by AutomotHR</li>
                                            <li>Receive emails with your signed paperwork</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (!empty($offerLettersView)) {
    foreach ($offerLettersView as $offerLetter) {
        ?>
        <form>
            <div class="modal fade" id="modal-content_<?php echo $offerLetter['sid']; ?>" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?php echo $offerLetter['letter_name']; ?> (Offer letter)</h4>
                        </div>
                        <div class="modal-body">
                            <div class="description-editor">
                                <div class="offer-letter-text-block">
                                    <?php echo $offerLetter['letter_body']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
    }
}
?>
<?php foreach ($offerLetters as $offerLetter) { ?>
    <form method="POST" action="<?php echo base_url('update_offer_letter') ?>">
        <div class="modal fade" id="offer_letter_<?php echo $offerLetter['sid']; ?>" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Offer Letter Template</h4>
                    </div>
                    <div class="modal-body">
                        <div class="universal-form-style-v2">
                            <ul>
                                <li class="form-col-50-left">
                                    <label>Template Name:<span class="staric">*</span></label>
                                    <input type="text" class="invoice-fields" name="letter_name"
                                           value="<?php echo $offerLetter['letter_name']; ?>">
                                </li>
                                <div class="description-editor template-letter-body">
                                    <label>Template Letter Body:<span class="staric">*</span></label>
                                    <textarea class="ckeditor" name="letter_body" cols="167" rows="6">
    <?php echo $offerLetter['letter_body']; ?>           
                                    </textarea>
                                </div>
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="fromPage" value="send offer letter">
                                <input type="hidden" name="offer_letter_id" value="<?php echo $offerLetter['sid']; ?>">
                                <input type="submit" value="Update Offer Letter" onclick="" class="submit-btn">
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php } ?>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript"
        src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<!--<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/modernizr-custom.js"></script>-->
<script language="JavaScript" type="text/javascript">
    $(document).ready(function () {
        var myid = $('#state_id').html();
        setTimeout(function () {
            $("#country").change();
        }, 1000);
        if (myid) {
            setTimeout(function () {
                $('#state').val(myid);
            }, 1200);
        }
    });

    var update_dropdown = function () {
        if ($("#offerLetterLabel").is(":checked")) {
            $('#offerLertterDropdown').prop('disabled', false);
        }
        else {
            $('#offerLertterDropdown').prop('disabled', 'disabled');
        }
    };
    $(update_dropdown);
    $("#offerLetterLabel").change(update_dropdown);


    function openModal(val) {
        if (val != "") {
            $("#viewButton_" + val).css('display', 'inline-block');
            $(".myViewButton").not("#viewButton_" + val).hide();

            $("#editButton_" + val).css('display', 'inline-block');
            $(".myEditButton").not("#editButton_" + val).hide();
        } else {
            $(".myViewButton").hide();
            $(".myEditButton").hide();

        }
    }
    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }

    function validate_form() {
        $("#employers_add").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                job_title: {
                    required: true
                },
                salary_type: {
                    required: true
                },
                salary_amount: {
                    required: true
                },
                registration_date: {
                    required: true
                },
                username: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                },
                Location_City: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                Location_ZipCode: {
                    pattern: /^[0-9\-]+$/
                },
                salary: {
                    pattern: /^[0-9\-]+$/
                }
            },
            messages: {
                first_name: {
                    required: 'First Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                last_name: {
                    required: 'Last Name is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                job_title: {
                    required: 'Job Title is required'
                },
                salary_type: {
                    required: 'Salary type is required'
                },
                salary_amount: {
                    required: 'Salary amount is required'
                },
                registration_date: {
                    required: 'Starting Date is required'
                },
                email: {
                    required: 'email is required',
                    email: 'Valid email Please'
                },
                username: {
                    required: 'Username is required',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                Location_City: {
                    pattern: 'Letters, numbers, and dashes only please',
                },
                Location_ZipCode: {
                    pattern: 'Numeric values and dashes only please'
                },
                password: {
                    required: 'Password is required'
                },
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    function check_file(val) {
        var fileName = $("#" + val).val();
        console.log(fileName);
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            var ext = fileName.split('.').pop();
            ext = ext.toLowerCase();
            if (val == 'profile_picture') {
                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG") {
                    $("#" + val).val(null);
                    alertify.error("Please select a valid Image format.");
                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
                    return false;
                } else
                    return true;
            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    $('.startdate').datepicker({dateFormat: 'mm-dd-yy'}).val();



    $(document).ready(function () {
        $('#submit-btn').attr('disabled', 'disabled');
        $('#submit-btn').addClass('disabled-btn');

        $('.docs_to_send, #offerLertterDropdown').on('change', function () {
            var offerLetter = $('#offerLertterDropdown').val();
            var documentsToSend = $('.docs_to_send:checked');

            if(parseInt(offerLetter) > 0 || documentsToSend.length > 0) {
                $('#submit-btn').removeAttr('disabled');
                $('#submit-btn').removeClass('disabled-btn');

                console.log(offerLetter);
                console.log(documentsToSend);
            }else {
                $('#submit-btn').attr('disabled', 'disabled');
                $('#submit-btn').addClass('disabled-btn');
            }
        });
        
        $('.offer_letter_check').each(function () {
            $(this).on('change', function () {
                if($(this).is(':checked')){

                }else{
                    $('#offerLertterDropdown option').each(function () {
                        $(this).removeAttr('selected');
                        $('#offerLertterDropdown option:first-child').attr('selected', 'selected');
                    });
                    $('#offerLertterDropdown').trigger('change');
                }
            });
        });
    });



</script>