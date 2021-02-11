
<style>
    /* Modal */
    .csModal{
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        z-index: 1000;
        background-color: #ffffff;
    }
    .csModal .csModalHeader{
        height: 45px;
        color: #333;
        border-bottom: 1px solid #ddd;
        background-color: #ffffff;
    }
    .csModal .csModalHeader .csModalHeaderTitle{
        font-weight: 500;
    }
    .csModal .csModalHeader .csModalHeaderTitle button{
        margin-top: -5px;
    }

    .csModal .csModalBody{
        color: #333;
        padding: 10px;
        overflow-y: auto;
        position: absolute;
        top: 65px;
        right: 0;
        left: 0;
        bottom: 0;
    }

    /* Common loader CSS */
    .csIPLoader {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        width: 100%;
        background: rgba(255, 255, 255, .9);
        z-index: 1;
    }

    .csIPLoader i {
        position: relative;
        top: 50%;
        left: 50%;
        font-size: 60px;
        color: #000000;
        /* color: #81b431; */
        transform: translate(-50%, -50%);
    }
</style>

<script>
    $(document).ready(function () {
        $(document).on('keyup', '#link_title', function() {
               $('#link_title_error').html('');   
        });
       

        $( "#add_custom_link" ).click(function() {
            var body = '<input type="hidden" id="perform_action" name="perform_action" value="insert_new_useful_link" />';
                body += '<input type="hidden" id="company_sid" value="<?php echo $company_sid; ?>" />';
                body += '<input type="hidden" id="user_type" value="<?php echo $user_type; ?>" />';
                body += '<input type="hidden" id="user_sid" value="<?php echo $user_sid; ?>" />';
                body += '<div class="universal-form-style-v2">';
                body += '<ul>';
                body += '<li class="form-col-100">';
                body += '<label for="link_title">Link Title:<span class="staric">*</span></label>';
                body += '<input type="text" name="link_title" id="link_title" class="invoice-fields" autocomplete="off">';
                body += '<span id="link_title_error" class="text-danger"></span>';
                body += '</li>';
                body += '<li class="form-col-100 autoheight">';
                body += '<label for="linkDescription">Link Description:</label>';
                body += '<textarea name="linkDescription" cols="40" rows="10" class="invoice-fields autoheight" id="linkDescription" spellcheck="false"></textarea>';
                body += '</li>';
                body += '<li class="form-col-100">';
                body += '<label for="link_url">Link Url:</label>';
                body += '<input type="text" name="link_url" value="" class="invoice-fields" id="link_url">';
                body += '<span id="link_url_error" class="text-danger"></span>';
                body += '</li>';
                body += '</ul>';
                body += '</div>';

            Modal({
                Id: 'usefullLinkModal',
                Title: 'Add a custom useful link',
                Body: body,
                Loader: 'jsUsefullLinkLoader',
                Buttons: ['<button type="button" class="btn btn-success" id="add_useful_link_button">Save</button>']
            });
        });

    });

    // Modal
    function Modal(
        options,
        cb
    ){
        //
        let html = `
        <!-- Custom Modal -->
        <div class="csModal" id="${options.Id}">
            <div class="container-fluid">
                <div class="csModalHeader">
                    <h3 class="csModalHeaderTitle">
                        ${options.Title}
                        <span class="pull-right">
                        ${ options.Buttons !== undefined && options.Buttons.length !== 0 ? options.Buttons.map((button) => button ) : '' }
                            <button class="btn btn-black jsModalCancel" title="Close this window">Cancel</button>
                        </span>
                    </h3>
                </div>
                <div class="csModalBody">
                    <div class="csIPLoader jsIPLoader" data-page="${options.Loader}"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                    ${options.Body}
                </div>
            </div>
        </div>
        `;
        //
        $(`#${options.Id}`).remove();
        $('body').append(html);
        $(`#${options.Id}`).fadeIn(300);
        $(`.csIPLoader`).hide();
        //
        $('body').css('overflow-y', 'hidden');
        // alert('here i am')
        if (cb != undefined) {
            cb();
        }
        return false;
        
    }

    $(document).on('click', '.jsModalCancel', (e) => {
        //
        e.preventDefault();
        //
        $(e.target).closest('.csModal').fadeOut(300);
        //
        $('body').css('overflow-y', 'auto');
    });

    $(document).on('click', '#add_useful_link_button', function() {
        var link_title = $('#link_title').val();


        if (link_title === "") {
            $('#link_title_error').html('<strong>Title is required.</strong>');
            return false;
        } else if (link_title.length > 0 ) {
            $(`[data-page="jsUsefullLinkLoader"]`).show();
            var id = $('#company_sid').val();
            var u_sid = $('#user_sid').val();
            var u_type = $('#user_type').val();
            var link_des = $('#linkDescription').val();
            var url = $('#link_url').val();
            
            var customURL = "<?= base_url() ?>onboarding/customOfficeUsefullLink";
            $.ajax({
                type: 'POST',
                data:{
                    company_sid:id,
                    user_sid:u_sid,
                    user_type:u_type,
                    link_title: link_title,
                    link_description: link_des,
                    link_url: url
                },
                url: customURL,
                success: function(data){

                    $('#usefullLinkModal .jsModalCancel').trigger('click');
                    $(`[data-page="jsUsefullLinkLoader"]`).hide();

                    var new_custom_link = '<tr class="package_label">';
                    new_custom_link += '<td>';
                    new_custom_link += '<strong>'+link_title+'</strong>';
                    if(url != ''){
                        new_custom_link += '</br>';
                        new_custom_link += '<small>('+url+')</small>';
                    }
                    new_custom_link += '</td>';
                    new_custom_link += '<td>';
                    new_custom_link += '<textarea class="invoice-fields autoheight" cols="40" rows="2" id="link_description_'+data+'">'+link_des+'</textarea>';
                    new_custom_link += '</td>';
                    new_custom_link += '<td>';
                    new_custom_link += '<label class="control control--checkbox">';
                    new_custom_link += '<input type="checkbox" checked="checked" />';
                    new_custom_link += '<div class="control__indicator"></div>';
                    new_custom_link += '</label>';
                    new_custom_link += '</td>';
                    new_custom_link += '<td>';
                    new_custom_link += '<a href="javascript:;" data-id="'+data+'" data-title="'+link_title+'" data-url="'+url+'" data-des_id="link_description_'+data+'" class="btn btn-success edit_useful_link">';
                    new_custom_link += 'Edit';
                    new_custom_link += '</a>';
                    new_custom_link += '</td>';
                    new_custom_link += '</tr>';

                    $("#custom_useful_link_section").append(new_custom_link);
                    alertify.alert('SUCCESS!', 'You have successfully added a new link.');
                },
                error: function(){

                }
            });
        }
    });

    $(document).on('click', '.edit_useful_link', function() {
        var link_sid = $(this).attr('data-id');
        var link_title= $(this).attr('data-title');
        var link_url = $(this).attr('data-url');
        var link_des_sid = $(this).attr('data-des_id');
        var link_description = $("#"+link_des_sid).val();

        var body = '<input type="hidden" id="perform_action" value="update_custom_useful_link" />';
            body = '<input type="hidden" id="link_record_sid" value="'+link_sid+'" />';
            body += '<div class="universal-form-style-v2">';
            body += '<ul>';
            body += '<li class="form-col-100">';
            body += '<label for="link_title">Link Title:<span class="staric">*</span></label>';
            body += '<input type="text" name="link_title" id="edit_link_title" class="invoice-fields" value="'+link_title+'" autocomplete="off">';
            body += '<span id="link_title_error" class="text-danger"></span>';
            body += '</li>';
            body += '<li class="form-col-100 autoheight">';
            body += '<label for="linkDescription">Link Description:</label>';
            body += '<textarea name="linkDescription" cols="40" rows="10" class="invoice-fields autoheight" id="edit_linkDescription" spellcheck="false">'+link_description+'</textarea>';
            body += '</li>';
            body += '<li class="form-col-100">';
            body += '<label for="link_url">Link Url:</label>';
            body += '<input type="text" name="link_url" value="'+link_url+'" class="invoice-fields" id="edit_link_url">';
            body += '<span id="link_url_error" class="text-danger"></span>';
            body += '</li>';
            body += '</ul>';
            body += '</div>';

        Modal({
            Id: 'editfullLinkModal',
            Title: 'Edit a custom useful link',
            Body: body,
            Loader: 'jsEditfullLinkLoader',
            Buttons: ['<button type="button" class="btn btn-success" id="update_useful_link_button">Update</button>']
        });
    });

    $(document).on('click', '#update_useful_link_button', function() {
        var link_title = $('#edit_link_title').val();


        if (link_title === "") {
            $('#link_title_error').html('<strong>Title is required.</strong>');
            return false;
        } else if (link_title.length > 0 ) {
            $(`[data-page="jsEditfullLinkLoader"]`).show();
            var id = $('#link_record_sid').val();
            var link_des = $('#edit_linkDescription').val();
            var url = $('#edit_link_url').val();
            
            var customURL = "<?= base_url() ?>onboarding/customOfficeUsefullLink_update";
            $.ajax({
                type: 'POST',
                data:{
                    record_sid:id,
                    link_title: link_title,
                    link_description: link_des,
                    link_url: url
                },
                url: customURL,
                success: function(data) {
                    $("#row_"+data).html('');
                    var new_custom_link_title = '<strong>'+link_title+'</strong>';
                    new_custom_link_title += '</br>';
                    new_custom_link_title += '<small>'+url+'</small>';

                    var new_custom_link_des = '<textarea class="invoice-fields autoheight" cols="40" rows="2" id="link_description_'+data+'">'+link_des+'</textarea>';

                    $("#row_"+id).find("td").eq(0).html(new_custom_link_title);
                    $("#row_"+id).find("td").eq(1).html(new_custom_link_des);

                    $('#editfullLinkModal .jsModalCancel').trigger('click');
                    $(`[data-page="jsEditfullLinkLoader"]`).hide();
                    alertify.alert('SUCCESS!', 'You have successfully updated the link.');
                },
                error: function(){

                }
            });
        }
    });
</script>

