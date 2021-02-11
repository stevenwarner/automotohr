<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="hr-actionSelected">
    <select name="selectedAction_header" id="top_select_box">
        <option value="">Select action</option>
        <option value="activate" class="activate_all">Activate</option>
        <option value="deactivate" id="deactivate_all">Deactivate</option>
<!--        <option value="send_activation_letter" id="send_activation_email">Send Activation Email</option>-->
        <option value="delete" id="delete_all">Delete</option>
    </select>
    <input type="button" class="hr-grayButton execute_multiple_dropdown" value="Go">
</div>
<script type="text/javascript">
        $(document).ready(function () {
            $('#check_all').click(function () {
                if ($('#check_all').is(":checked")) {
                    $('.my_checkbox:checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.my_checkbox:checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });
            $('.execute_multiple_dropdown').click(function () {
                var action = $('#top_select_box').val();
                var type = $('#type').val();
                var popup_msg = 'Are you sure?';
//                console.log(type);
//                console.log(action);
                if(action=='delete' && type=='company'){
                    popup_msg = 'Are you sure you want to delete company? <br><br>Warning: It will delete all its Employees, Jobs and Job applicants';
                }
                //console.log($('#top_select_box :selected').text());
                if(action){
                    if ($(".my_checkbox:checked").size() > 0) {
                        alertify.confirm('Please confirm '+action,popup_msg,
                                    function () {
                                        $('#multiple_actions_'+type).append('<input type="hidden" name="action" value="'+action+'" />');
                                        $("#multiple_actions_"+type).submit();
                                        alertify.success('Success');
                                    },
                                    function () {
                                        alertify.error('Cancel');
                                    });
                    } else {
                        alertify.alert('Error! No '+type+' selected','Please Select at-least one '+type);
                    }
                } else {
                    alertify.alert('Error! No Action Selected','Please Select Action to be performed!');
                }
            });
        });
</script>