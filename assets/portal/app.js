$(function () {
    //
    if (window.sre !== undefined) {
        // Set default holders
        var lastLogs = {};
        var logs = [];

        // Get the history from database
        getUserSendReminderEmailHistory();

        /**
         * Capture click on Send Reminder Email
         */
        $("#JsSendReminderEmail").click(function (event) {
            //
            event.preventDefault();
            //
            loadReminderModal();
        });

        /**
         * Capture click on Send Reminder Email
         */
        $(".JsSendReminderEmailLI").click(function (event) {
            //
            event.preventDefault();
            //
            window.sre.userId = $(this).data("id");
            window.sre.userType = $(this).data("type");
            //
            loadReminderModal($(this).data("slug"));
        });

        /**
         * Capture click on Send Reminder Email History
         */
        $("#JsSendReminderEmailHistory").click(function (event) {
            //
            event.preventDefault();
            //
            loadReminderHistoryModal();
        });

        /**
         * Click
         *
         * Send the email reminder
         *
         * @param Object event
         */
        $(document).on(
            "click",
            ".JsSendReminderEmailModalSaveBtn",
            function (event) {
                // Prevent default behaviour
                event.preventDefault();
                // Set data object
                var obj = {};
                obj.type = $("#JsSendReminderEmailType").val() || [];
                obj.note = CKEDITOR.instances["JsSendReminderEmailNote"].getData();
                // Validate
                if (obj.type.length == 0) {
                    alertify.alert(
                        "Warning!",
                        "Please, select at least one document type.",
                        function () { }
                    );
                    return;
                }
                //
                obj.userId = window.sre.userId;
                obj.userType = window.sre.userType;
                //
                if (obj.userType == "applicant") {
                    alertify
                        .confirm(
                            "This action will assign the applicant the selected document in order to send a reminder email. <br /> Do you want to continue?",
                            function () {
                                sendReminderEmail(obj);
                            }
                        )
                        .setHeader("Confirm!")
                        .set("labels", {
                            ok: "Assign & Send Email",
                            cancel: "No",
                        });
                } else {
                    sendReminderEmail(obj);
                }
            }
        );

        /**
         * Click
         *
         * Accordian icon changer
         *
         */
        $(document).on("click", ".jsHistoryToggle", function () {
            //
            $(this).find("i").toggleClass("fa-plus-circle");
            $(this).find("i").toggleClass("fa-minus-circle");
        });

        /**
         * Creates Send History Email Modal
         */
        function loadReminderModal(type) {
            //
            var html = "";
            html += '<div class="modal fade" id="JsSendReminderEmailModal">';
            html += '    <div class="modal-dialog modal-lg">';
            html += '        <div class="modal-content">';
            html += '            <div class="modal-header">';
            html +=
                '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            html +=
                '                <h4 class="modal-title"><strong>Send An Email Reminder</strong></h4>';
            html += "            </div>";
            html += '            <div class="modal-body" style="min-height: 200px;">';
            html +=
                '                <div class="csCommonLoader JsSendReminderEmailModalLoader"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></div>';
            html += '               <div id="JsSendReminderEmailModalBody"></div>';
            html += "            </div>";
            html += '            <div class="modal-footer">';
            html +=
                '                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            html +=
                '                <button type="button" class="btn btn-success JsSendReminderEmailModalSaveBtn dn">Send Reminder</button>';
            html += "            </div>";
            html += "        </div>";
            html += "    </div>";
            html += "</div>";
            //
            $("#JsSendReminderEmailModal").modal("hide");
            $("#JsSendReminderEmailModal").remove();
            $("body").append(html);
            $("#JsSendReminderEmailModal").modal();
            //
            var options = [
                {
                    id: "emergency-contact",
                    text: "",
                    html:
                        "<strong>Emergency Contacts </strong>" +
                        getLastSent("emergency-contact") +
                        "",
                },
                {
                    id: "occupational-license",
                    text: "",
                    html:
                        "<strong>Occupational License </strong>" +
                        getLastSent("occupational-license") +
                        "",
                },
                {
                    id: "drivers-license",
                    text: "",
                    html:
                        "<strong>Drivers License </strong>" +
                        getLastSent("drivers-license") +
                        "",
                },
                {
                    id: "dependents",
                    text: "",
                    html: "<strong>Dependents </strong>" + getLastSent("dependents") + "",
                },
                {
                    id: "direct-deposit-information",
                    text: "",
                    html:
                        "<strong>Direct Deposit Information</strong>" +
                        getLastSent("direct-deposit-information") +
                        "",
                },
            ];
            //
            var selectedOptions = [];
            //
            if (type !== undefined) {
                //
                type = type.split("|");
                //
                options.map(function (opt) {
                    if ($.inArray(opt.id, type) !== -1) {
                        selectedOptions.push(opt);
                    }
                });
            } else {
                selectedOptions = options;
            }
            //
            $.get(window.sre.url + "get_send_reminder_email_body").done(function (
                resp
            ) {
                $("#JsSendReminderEmailModalBody").html(resp);
                $("#JsSendReminderEmailType").select2({
                    closeOnSelect: false,
                    data: selectedOptions,
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    templateResult: function (data) {
                        return data.html;
                    },
                    templateSelection: function (data) {
                        return data.html;
                    },
                });
                //
                if (type !== undefined && type.length == 1) {
                    $("#JsSendReminderEmailType").prop("disabled", true);
                    $("#JsSendReminderEmailType").select2(
                        "val",
                        selectedOptions[0]["id"]
                    );
                }
                //
                delete CKEDITOR.instances["JsSendReminderEmailNote"];
                CKEDITOR.replace("JsSendReminderEmailNote");
                //
                $(".JsSendReminderEmailModalLoader").hide(0);
                $(".JsSendReminderEmailModalSaveBtn").removeClass("dn");
            });
        }

        /**
         * Creates Send History Email History Modal
         */
        function loadReminderHistoryModal() {
            //
            var html = "";
            html += '<div class="modal fade" id="JsSendReminderEmailHistoryModal">';
            html += '    <div class="modal-dialog modal-lg">';
            html += '        <div class="modal-content">';
            html += '            <div class="modal-header">';
            html +=
                '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            html +=
                '                <h4 class="modal-title"><strong>Email Reminder History</strong></h4>';
            html += "            </div>";
            html += '            <div class="modal-body" style="min-height: 200px;">';
            html += '               <div class="panel-group">';
            if (logs.length) {
                logs.map(function (log) {
                    //
                    var key = Math.ceil(Math.random() * 1000);
                    //
                    html += '                   <div class="panel panel-default">';
                    html += '                       <div class="panel-heading">';
                    html += '                           <h4 class="panel-title">';
                    html +=
                        '                               <a data-toggle="collapse" class="jsHistoryToggle" href="#' +
                        key +
                        '"><span class="pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i></span><strong>' +
                        slugToName(log["module_type"]) +
                        "</strong> <br /> The last email reminder was sent on at " +
                        moment(log["updated_at"], "YYYY-MM-DD H:m:s").format(
                            "MMM DD YYYY, ddd hh:mm a"
                        ) +
                        " by " +
                        log["name"] +
                        " </a>";
                    html += "                           </h4>";
                    html += "                       </div>";
                    html +=
                        '                       <div id="' +
                        key +
                        '" class="panel-collapse collapse">';
                    html += '                           <div class="panel-body">';
                    html +=
                        '                               <div class="table-responsive">';
                    html +=
                        '                                   <table class="table table-striped table-bordered">';
                    html += "                                       <caption></caption>";
                    html += "                                       <thead>";
                    html += "                                           <tr>";
                    html +=
                        '                                               <th scope="col">Employee</th>';
                    html +=
                        '                                               <th scope="col">Note</th>';
                    html +=
                        '                                               <th scope="col">Action Date</th>';
                    html += "                                           </tr>";
                    html += "                                       </thead>";
                    html += "                                       <tbody>";
                    if (log["logs"].length === 0) {
                        html += "                                           <tr>";
                        html += "                                               <td>";
                        html +=
                            '                                                   <p class="text-center alert alert-info"><strong>The log is empty.</strong></p>';
                        html += "                                               </td>";
                        html += "                                           </tr>";
                    } else {
                        //
                        log["logs"].map(function (emp) {
                            html += "                                           <tr>";
                            html +=
                                "                                               <td><strong>" +
                                emp["name"] +
                                "</strong> <br /> " +
                                emp["role"] +
                                "</td>";
                            html +=
                                "                                               <td>" +
                                (emp["note"] == "" || emp["note"] == null ? "-" : emp["note"]) +
                                "</td>";
                            html +=
                                "                                               <td>" +
                                moment(emp["created_at"], "YYYY-MM-DD H:m:s").format(
                                    "MMM DD YYYY, ddd hh:mm a"
                                ) +
                                "</td>";
                            html += "                                           </tr>";
                            html += "                                       </tbody>";
                        });
                    }
                    html += "                                   </table>";
                    html += "                               </div>";
                    html += "                           </div>";
                    html += "                       </div>";
                    html += "                   </div>";
                });
            } else {
                html +=
                    '<p class="text-center alert alert-info"><strong>No history found.</strong></p>';
            }

            html += "            </div>";
            html += '            <div class="modal-footer">';
            html +=
                '                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            html += "            </div>";
            html += "        </div>";
            html += '            <div class="clearfix"></div>';
            html += "    </div>";
            html += "</div>";
            //
            $("#JsSendReminderEmailHistoryModal").modal("hide");
            $("#JsSendReminderEmailHistoryModal").remove();
            $("body").append(html);
            $("#JsSendReminderEmailHistoryModal").modal();
        }

        /**
         * Get history via AJAX
         */
        function getUserSendReminderEmailHistory() {
            $.get(
                window.sre.url +
                "get_send_reminder_email_history/" +
                window.sre.userId +
                "/" +
                window.sre.userType
            ).done(function (resp) {
                //
                if (resp.length === 0) {
                    return;
                }
                //
                logs = resp;
                //
                resp.map(function (log) {
                    //
                    lastLogs[log.module_type] = {
                        sent_at: log.update_at,
                        sent_by: log.name + " " + log.role,
                    };
                });
            });
        }

        /**
         *
         * @param {String} slug
         * @returns
         */
        function getLastSent(slug) {
            return lastLogs[slug] !== undefined
                ? " ( The last email reminder was sent on at " +
                moment(lastLogs[slug]["sent_at"]).format("MM/DD/YY H:mm A") +
                " by " +
                lastLogs[slug]["sent_by"] +
                " )"
                : "";
        }

        /**
         *
         * @param {String} slug
         * @returns
         */
        function slugToName(slug) {
            return slug.replace(/-/g, " ").ucwords();
        }

        function sendReminderEmail(obj) {
            //
            $(".JsSendReminderEmailModalLoader").show(0);
            $(".JsSendReminderEmailModalSaveBtn").addClass("dn");
            //
            $.post(window.sre.url + "send_reminder_email_by_type", obj).done(
                function (resp) {
                    //
                    if (resp == "success") {
                        $("#JsSendReminderEmailModal").modal("hide");
                        $("#JsSendReminderEmailModal").remove();
                        //
                        alertify.alert(
                            "Success!",
                            "You have successfully sent a reminder email.",
                            function () {
                                window.location.reload();
                            }
                        );
                        //
                        return;
                    }
                    //
                    $(".JsSendReminderEmailModalLoader").hide(0);
                    $(".JsSendReminderEmailModalSaveBtn").removeClass("dn");
                    //
                    alertify.alert(
                        "Error!",
                        "Something went wrong while sending a reminder email. Please, try again in a few moments."
                    );
                }
            );
        }
    }

    // ComplyNet
    $(document).on("click", ".jsAddEmployeeToComplyNet", function (event) {
        //
        event.preventDefault();
        //
        let employeeId = $(this).data("id");
        let companyId = $(this).data("cid");

        //
        return alertify.confirm(
            "Are you sure you want to sync this employee with ComplyNet.<br />In case the employee is not found on ComplyNet, the system will add the employee to ComplyNet.",
            function () {
                addEmployeeToComplyNet(companyId, employeeId)
            }
        );
    });

    function addEmployeeToComplyNet(companyId, employeeId) {
        //
        Model(
            {
                Id: "jsModelEmployeeToComplyNet",
                Title: "Add Employee To ComplyNet",
                Body: '<div class="container"><div id="jsModelEmployeeToComplyNetBody"><p class="alert alert-info text-center">Please wait while we are syncing employee with ComplyNet. It may take a few moments.</p></div></div>',
                Loader: "jsModelEmployeeToComplyNetLoader",
            },
            function () {
                //
                $.post(window.location.origin + "/cn/" + companyId + "/employee/sync", {
                    employeeId: employeeId,
                    companyId: companyId,
                })
                    .success(function (resp) {
                        //
                        if (resp.hasOwnProperty("errors")) {
                            //
                            let errors = '';
                            errors += '<strong class="text-danger">';
                            errors += '<p><em>In order to sync employee with ComplyNet the following details are required.';
                            errors += ' Please fill these details from employee\'s profile.</em></p><br />';
                            errors += resp.errors.join("<br />");
                            errors += '</strong>';
                            //
                            $('#jsModelEmployeeToComplyNetBody').html(errors);
                        } else {
                            $('#jsModelEmployeeToComplyNet .jsModalCancel').trigger('click');
                            alertify.alert(
                                'Success',
                                'You have successfully synced the employee with ComplyNet',
                                window.location.reload
                            );
                        }
                    })
                    .fail(window.location.reload);
                ml(false, "jsModelEmployeeToComplyNetLoader");
            }
        );
    }
});

/**
 * Capitalize first letter of each word
 * Make it available on String prototype
 * @returns
 */
String.prototype.ucwords = function () {
    var str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function (s) {
        return s.toUpperCase();
    });
};

/**
 * Manges the loader
 * @param {boolean} status
 * @param {string}  target
 * @param {string}  msg
 */
function ml(status, target, msg) {
    //
    if (status) {
        $('.jsIPLoader[data-page="' + target + '"]').show();
    } else {
        $('.jsIPLoader[data-page="' + target + '"]').hide();
    }

    if (msg) {
        //
        $('.jsIPLoader[data-page="' + target + '"] .jsIPLoaderText').html(msg);
    } else {
        $(".jsIPLoader .jsIPLoaderText").html(
            "Please wait while we process your request."
        );
    }
}
