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
                                                <?php echo form_open('', array('class' => 'form-horizontal js-form', 'autocomplete' => 'off')); ?>
                                                <ul>
                                                    <li>
                                                        <?php echo form_label('First name <span class="hr-required">*</span>', 'first_name'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php echo form_input('first_name', set_value('first_name', $user->first_name), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('first_name'); ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <?php echo form_label('Last name <span class="hr-required">*</span>', 'last_name'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php echo form_input('last_name', set_value('last_name', $user->last_name), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('last_name'); ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <?php echo form_label('Company', 'company'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php echo form_input('company', set_value('company', $user->company), 'class="hr-form-fileds"'); ?>
                                                            <?php echo form_error('company'); ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <?php echo form_label('Phone', 'phone'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <span class="input-group-text">+1</span>
                                                                </div>
                                                                <?php echo form_input('phone', set_value('phone', phonenumber_format($user->phone, true)), 'class="hr-form-fileds js-phone" id="PhoneNumber"'); ?>
                                                            </div>
                                                            <?php echo form_error('phone'); ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <?php echo form_label('Username <span class="hr-required">*</span>', 'username'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php echo form_input('username', set_value('username', $user->username), array('id' => 'disabledTextInput', 'class' => 'hr-form-fileds form-control', 'disabled' => 'disabled'));
                                                            //echo form_error('username'); 
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <?php echo form_label('email <span class="hr-required">*</span>', 'Email'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <?php echo form_input('email', set_value('email', $user->email), 'class="hr-form-fileds" id="jsemail"'); ?>
                                                            <?php echo form_error('email'); ?>
                                                        </div>
                                                    </li>

                                                    <li class="form-col-100 auto-height">
                                                        <?php echo form_label('Change password', 'password'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <input class="invoice-fields" autocomplete="new-password" type="password" name="password" id="password" onkeyup="passwordStrength(this.value)">
                                                        </div>
                                                        <div class="password-trength-wrp">
                                                            <div id="passwordStrength">
                                                                <div class='pass0 strength0'></div>
                                                                <div class='pass1 strength0'></div>
                                                                <div class='pass1 strength0'></div>
                                                                <div class='pass1 strength0'></div>
                                                            </div>
                                                            <div class="hr-fields-wrap">
                                                                <div class="passwordDescription" id="passwordDescription">Password not entered</div>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('password'); ?>
                                                    </li>
                                                    <li class="form-col-100 auto-height">
                                                        <?php echo form_label('Confirm changed password', 'password_confirm'); ?>
                                                        <div class="hr-fields-wrap">
                                                            <input class="invoice-fields" autocomplete="new-password" type="password" name="password_confirm" id="password_confirm">
                                                        </div>
                                                    </li>

                                                    <?php if (IS_TIMEZONE_ACTIVE) { ?>
                                                        <!-- Timezone -->
                                                        <li class="js-timezone-row">
                                                            <?php echo form_label('Timezone <span class="hr-required">*</span>', 'Timezone'); ?>
                                                            <div class="hr-fields-wrap">
                                                                <?= timezone_dropdown(
                                                                    $user->timezone,
                                                                    array(
                                                                        'name' => 'timezone',
                                                                        'id' => 'timezone',
                                                                        'class' => 'hr-form-fileds js-timezone',
                                                                        'style' => 'padding: 0;'
                                                                    )
                                                                );  ?>
                                                                <?php echo form_error('email'); ?>
                                                            </div>
                                                        </li>
                                                    <?php } ?>

                                                    <?php if ($profile_type == 'team') { ?>
                                                        <li>
                                                            <label><?php echo form_label('Groups', 'groups[]'); ?></label>
                                                            <div class="hr-fields-wrap">
                                                                <?php if (isset($groups)) {
                                                                    foreach ($groups as $group) {
                                                                        echo '<div class="checkbox">';
                                                                        echo '<label>';
                                                                        echo form_radio('groups[]', $group->id, set_checkbox('groups[]', $group->id, in_array($group->id, $usergroups)));
                                                                        echo ' ' . $group->name;
                                                                        echo '</label>';
                                                                        echo '</div>';
                                                                    }
                                                                } ?>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                    <li>
                                                        <?php echo form_submit('submit', 'Update Profile', 'class="site-btn"'); ?>
                                                        <a href="<?= base_url('manage_admin/users'); ?>" class="btn black-btn" type="submit">Cancel</a>
                                                    </li>
                                                </ul>
                                                <?php echo form_hidden('user_id', $user->id); ?>
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

<?php if (IS_TIMEZONE_ACTIVE) { ?>
    <script>
        $('.js-timezone').select2();
    </script>
<?php } ?>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript">
    function passwordStrength(password) {
        var desc = new Array();
        if (password.length == 0) {
            desc[0] = "Password Not Entered";
        } else {
            desc[0] = "Very Weak";
        }
        desc[1] = "Very Weak";
        desc[2] = "Not secure enough";
        desc[3] = "Fair";
        desc[4] = "Strong";
        desc[5] = "Very Strong";
        var toggle_class = new Array();
        if (password.length == 0) {
            toggle_class[0] = "<div class='pass0 strength0'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        } else {
            toggle_class[0] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength0'></div>";
        }
        toggle_class[1] = "<div class='pass0 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength1'></div><div class='pass1 strength0'></div>";
        toggle_class[2] = "<div class='pass0 strength2'></div><div class='pass1 strength2'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[3] = "<div class='pass0 strength3'></div><div class='pass1 strength3'></div><div class='pass1 strength0'></div><div class='pass1 strength0'></div>";
        toggle_class[4] = "<div class='pass0 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength4'></div><div class='pass1 strength0'></div>";
        toggle_class[5] = "<div class='pass0 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div><div class='pass1 strength5'></div>";
        var score = 0;
        //if password bigger than 6 give 1 point
        if (password.length > 6)
            score++;
        //if password has both lower and uppercase characters give 1 point  
        if ((password.match(/[a-z]/)) && (password.match(/[A-Z]/)))
            score++;
        //if password has at least one number give 1 point
        if (password.match(/\d+/))
            score++;
        //if password has at least one special caracther give 1 point
        if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))
            score++;
        //if password bigger than 12 give another 1 point
        if (password.length > 10)
            score++;
        document.getElementById("passwordDescription").innerHTML = desc[score];
        document.getElementById("passwordStrength").innerHTML = toggle_class[score];

    }

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
        var default_number = '(___) ___-____';
        var cleaned = phone_number.replace(/\D/g, '');
        if (cleaned.length > 10) cleaned = cleaned.substring(0, 10);
        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);

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

    .passwordDescription {
        float: right;
        font-size: 12px;
        position: relative;
        top: -5px;
        color: #000;
        font-weight: 600;
        text-transform: capitalize;
    }

    #passwordStrength {
        height: 8px;
        float: right;
        width: 75%;
        margin-right: 30px;
    }

    .password-trength-wrp {
        float: left;
        width: 100%;
        margin: 0 0 10px 0;
    }

    .pass0,
    .pass1 {
        float: left;
        width: 20%;
        height: 8px;
        margin: 10px 5px !important;
        background: gray;
    }


    .strength0 {
        width: 250px;
        background: #e6e6e6;
    }

    .strength1 {
        width: 50px;
        background: #ff0000;
    }

    .strength2 {
        width: 100px;
        background: #ff5f5f;
    }

    .strength3 {
        width: 150px;
        background: #56e500;
    }

    .strength4 {
        background: #4dcd00;
        width: 200px;
    }

    .strength5 {
        background: #399800;
        width: 250px;
    }

    .pass0,
    .pass1 {
        float: left;
        width: 20%;
        height: 8px;
        margin: 0 5px;
    }

    .passwordDescription {
        float: right;
        font-size: 12px;
        position: relative;
        top: -5px;
        color: #000;
        font-weight: 600;
        text-transform: capitalize;
    }
</style>