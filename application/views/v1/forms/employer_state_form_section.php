<div id="jsStateFormEmployerView" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="jsFormTitle"></h4>
            </div>
            <div class="modal-body">
                <div id="jsStateEmployerSection">

                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

        let formRef;

        let XHR = null;

        $('.jsEmployerStateSectionPrefill').on('click', function() {
            var stateFormId = $(this).attr('form_sid');
            XHR = null;
            //
            $.ajax({
                    url: '<?php echo base_url('hr_documents_management/get_state_employer_section/' . $user_sid . '/' . $user_type); ?>' + '/' + stateFormId,
                    method: "GET",
                })
                .success(function(response) {
                    $('#jsStateFormEmployerView').modal('show');
                    $("#jsStateEmployerSection").html(response.view);
                    $("#jsFormTitle").html(response.title);

                    formRef = $("#jsStateFormW4EmployerSection").validate({
                        rules: {
                            first_name: {
                                required: true
                            },
                            last_name: {
                                required: true
                            },
                            ssn: {
                                required: true,
                                pattern: /\d/g,
                                minlength: 9,
                                maxlength: 9
                            },
                            mn_tax_number: {
                                required: true,
                            },
                            street_1: {
                                required: true
                            },
                            city: {
                                required: true
                            },
                            state: {
                                required: true
                            },
                            zip_code: {
                                required: true,
                                pattern: /\d/g,
                                minlength: 5,
                                maxlength: 5
                            },
                        },
                        submitHandler: function() {
                            //
                            let data = $("#jsStateFormW4EmployerSection").serialize();
                            //
                            saveFormData(data);


                        }
                    });

                });
        });


        function saveFormData(passOBJ) {
            //
            if (XHR !== null) {
                return false;
            }
            //
            let hookRef = callButtonHook($(".jsStateFormW4EmployerSectionBtn"), true)
            //
            XHR = $.ajax({
                    url: window.location.origin + "/state/forms/1/employer/<?= $user_sid; ?>/<?= $user_type; ?>",
                    method: "POST",
                    data: passOBJ,
                })
                .success(function() {
                    //
                    return alertify.alert(
                        "Success!",
                        "You have successfully signed the State form employer section.",
                        function() {
                            //
                            window.location.reload();
                        }
                    );
                })
                .fail(function() {
                    //
                    return alertify.alert(
                        "Error!",
                        "Something went wrong, Try again!."
                    );
                })
                .always(function() {
                    XHR = null;
                    callButtonHook(hookRef, false)
                });
        }

        if (typeof callButtonHook === "undefined") {
            /**
             * button hook
             *
             * @param {object} appendUrl
             * @param {bool}   doShow
             * @return
             */
            function callButtonHook(reference, doShow = true) {
                //
                if (doShow) {
                    const obj = {
                        pointer: reference,
                        html: reference.html(),
                    };
                    reference.html(
                        '<i class="fa fa-circle-o-notch fa-spin csW csF16" aria-hidden="true"></i>'
                    );
                    //
                    reference.off("click");
                    return obj;
                }
                //
                reference.pointer.html(reference.html);
            }
        }
    })
</script>