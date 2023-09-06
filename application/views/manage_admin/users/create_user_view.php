<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
  <div class="container-fluid">
    <div class="row">
      <div class="inner-content">
        <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
          <div class="dashboard-content">
            <div class="dash-inner-block">
              <div class="row">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                  <div class="heading-title page-title">
                    <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                      <div class="hr-user-form">
                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                        <?php echo form_open('', array('class' => 'form-horizontal js-form')); ?>
                        <ul>
                          <li>
                            <?php echo form_label('First name <span class="hr-required">*</span>', 'first_name'); ?>
                            <div class="hr-fields-wrap">
                              <?php
                              echo form_input('first_name', set_value('first_name'), 'class="hr-form-fileds"');
                              echo form_error('first_name');
                              ?>
                            </div>
                          </li>
                          <li>
                            <?php echo form_label('Last name <span class="hr-required">*</span>', 'last_name'); ?>
                            <div class="hr-fields-wrap">
                              <?php
                              echo form_input('last_name', set_value('last_name'), 'class="hr-form-fileds"');
                              echo form_error('last_name');
                              ?>
                            </div>
                          </li>
                          <li>
                            <?php echo form_label('Company', 'company'); ?>
                            <div class="hr-fields-wrap">
                              <?php
                              echo form_input('company', set_value('company'), 'class="hr-form-fileds"');
                              echo form_error('company');
                              ?>
                            </div>
                          </li>
                          <li>
                            <?php echo form_label('Phone', 'phone'); ?>
                            <div class="hr-fields-wrap">
                              <div class="input-group">
                                <div class="input-group-addon">
                                  <span class="input-group-text">+1</span>
                                </div>
                                <?php
                                echo form_input('phone', set_value('phone'), 'class="hr-form-fileds js-phone" id="PhoneNumber"'); ?>
                              </div>
                              <?php
                              echo form_error('phone');
                              ?>
                            </div>
                          </li>
                          <li>
                            <?php echo form_label('Username <span class="hr-required">*</span>', 'username'); ?>
                            <div class="hr-fields-wrap">
                              <?php
                              echo form_input('username', set_value('username'), 'class="hr-form-fileds"');
                              echo form_error('username');
                              ?>
                            </div>
                          </li>
                          <li>
                            <?php echo form_label('Email <span class="hr-required">*</span>', 'email'); ?>
                            <div class="hr-fields-wrap">
                              <?php
                              echo form_input('email', set_value('email'), 'class="hr-form-fileds" id="jsemail"');
                              echo form_error('email');
                              ?>
                            </div>
                          </li>
                          <!--                                            <li>-->
                          <!--                                              --><?php //echo form_label('Password','password'); 
                                                                                ?>
                          <!--                                              <div class="hr-fields-wrap">-->
                          <!--                                                  --><?php
                                                                                    //                                                    echo form_password('password','','class="hr-form-fileds"');
                                                                                    //                                                    echo form_error('password');
                                                                                    //                                                  
                                                                                    ?>
                          <!--                                              </div>-->
                          <!--                                            </li>-->
                          <!--                                            <li>-->
                          <!--                                              --><?php //echo form_label('Confirm password','password_confirm'); 
                                                                                ?>
                          <!--                                              <div class="hr-fields-wrap">-->
                          <!--                                                  --><?php
                                                                                    //                                                    echo form_password('password_confirm','','class="hr-form-fileds"');
                                                                                    //                                                    echo form_error('password_confirm');
                                                                                    //                                                  
                                                                                    ?>
                          <!--                                              </div>-->
                          <!--                                            </li>-->
                          <li>
                            <label><?php echo form_label('Groups', 'groups[]'); ?></label>
                            <div class="hr-fields-wrap">
                              <?php
                              if (isset($groups)) {
                                foreach ($groups as $group) {
                                  $check = $group->name == 'admin' ? TRUE : FALSE;
                                  echo '<div class="checkbox">';
                                  echo '<label>';
                                  echo form_radio('groups[]', $group->id, set_checkbox('groups[]', $group->id, $check));
                                  echo ' ' . $group->name;
                                  echo '</label>';
                                  echo '</div>';
                                }
                              }
                              ?>
                            </div>
                          </li>
                          <li>
                            <?php echo form_submit('submit', 'Create User', 'class="site-btn"'); ?>
                            <?php echo form_submit('create_submit', 'Create User and Send Email', 'class="site-btn"'); ?>
                            <a href="<?= base_url('manage_admin/users'); ?>" class="btn black-btn" type="submit">Cancel</a>
                          </li>
                        </ul>
                        <?php echo form_close(); ?>
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
  </div>
</div>



<script>
  $('.js-form').submit(function(event) {
    // Check for phone number
    if ($('#PhoneNumber').val() != '' && $('#PhoneNumber').val().trim() != '(___) ___-____' && !fpn($('#PhoneNumber').val(), '', true)) {
      alertify.alert('Error!', 'Invalid phone number.', function() {
        return;
      });
      event.preventDefault();
      return;
    }

    // Remove and set phone extension
    $('#js-phonenumber').remove();
    // Check the fields
    if ($('#PhoneNumber').val().trim() == '(___) ___-____') $('#PhoneNumber').val('');
    else $(this).append('<input type="hidden" id="js-phonenumber" name="txt_phonenumber" value="+1' + ($('#PhoneNumber').val().replace(/\D/g, '')) + '" />');
  });

  $.each($('.js-phone'), function() {
    var v = fpn($(this).val().trim());
    if (typeof(v) === 'object') {
      $(this).val(v.number);
      setCaretPosition(this, v.cur);
    } else $(this).val(v);
  });

  $('.js-phone').keyup(function(e) {
    var val = fpn($(this).val().trim());
    if (typeof(val) === 'object') {
      $(this).val(val.number);
      setCaretPosition(this, val.cur);
    } else $(this).val(val);
  })


  // Format Phone Number
  // @param phone_number
  // The phone number string that 
  // need to be reformatted
  // @param format
  // Match format 
  // @param is_return
  // Verify format or change format
  function fpn(phone_number, format, is_return) {
    // 
    var default_number = '(___) ___-____';
    var cleaned = phone_number.replace(/\D/g, '');
    if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
    match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
    //
    if (match) {
      var intlCode = '';
      if (format == 'e164') intlCode = (match[1] ? '+1 ' : '');
      return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
    } else {
      var af = '',
        an = '',
        cur = 1;
      if (cleaned.substring(0, 1) != '') {
        af += "(_";
        an += '(' + cleaned.substring(0, 1);
        cur++;
      }
      if (cleaned.substring(1, 2) != '') {
        af += "_";
        an += cleaned.substring(1, 2);
        cur++;
      }
      if (cleaned.substring(2, 3) != '') {
        af += "_) ";
        an += cleaned.substring(2, 3) + ') ';
        cur = cur + 3;
      }
      if (cleaned.substring(3, 4) != '') {
        af += "_";
        an += cleaned.substring(3, 4);
        cur++;
      }
      if (cleaned.substring(4, 5) != '') {
        af += "_";
        an += cleaned.substring(4, 5);
        cur++;
      }
      if (cleaned.substring(5, 6) != '') {
        af += "_-";
        an += cleaned.substring(5, 6) + '-';
        cur = cur + 2;
      }
      if (cleaned.substring(6, 7) != '') {
        af += "_";
        an += cleaned.substring(6, 7);
        cur++;
      }
      if (cleaned.substring(7, 8) != '') {
        af += "_";
        an += cleaned.substring(7, 8);
        cur++;
      }
      if (cleaned.substring(8, 9) != '') {
        af += "_";
        an += cleaned.substring(8, 9);
        cur++;
      }
      if (cleaned.substring(9, 10) != '') {
        af += "_";
        an += cleaned.substring(9, 10);
        cur++;
      }

      if (is_return) return match === null ? false : true;

      return {
        number: default_number.replace(af, an),
        cur: cur
      };
    }
  }

  // Change cursor position in input
  function setCaretPosition(elem, caretPos) {
    if (elem != null) {
      if (elem.createTextRange) {
        var range = elem.createTextRange();
        range.move('character', caretPos);
        range.select();
      } else {
        if (elem.selectionStart) {
          elem.focus();
          elem.setSelectionRange(caretPos, caretPos);
        } else elem.focus();
      }
    }
  }

  //
  $("#jsemail").focusout(function() {
    var email = $(this).val();
    $(this).val(email.toLowerCase());
  });
</script>

<style>
  /* Remove the radius from left fro phone field*/
  .input-group input {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }
</style>