$(function ComplyNetManageJobRoles() {
    /**
     * Holds loader
     * @type string
     */
    let loaderRef = "#jsMainLoader";

    /**
     * Holds ComplyNet job role id
     * @type int
     */
    let jobRoleId = 0;

    /**
     * Holds the XHR
     * @type null|object
     */
    let xhr = null;

    $('.jsAddJobRole').click(function (event) {
        //
        event.preventDefault();
        //
        jobRoleId = $(this).closest('tr').data('id');
        //
        startAddJobRoleProcess();
    });

    $('.jsShowLinkedJobs').click(function (event) {
        //
        event.preventDefault();
        //
        jobRoleId = $(this).closest('tr').data('id');
        //
        startShowJobRoleProcess();
    });

    $(document).on('click', '.jsDeleteJobRole', function (event) {
        //
        event.preventDefault();
        //
        var roleId = $(this).closest('tr').data('id');
        //
        return alertify.confirm(
            '<p><em><strong>This action is not revertible.</strong></em></p><p>This action will delete ComplyNet job title against all employees matching the selected job title.</p>',
            function () {
                deleteJobRole(roleId)
            }
        );
    });

    $(document).on('click', '.jsLinkJobRoleBtn', function (event) {
        //
        event.preventDefault();
        //
        var obj = {
            id: jobRoleId,
            job_roles: $('.jsSelect2Tags').val()
        };
        //
        if (obj.job_roles == null) {
            return alertify.alert(
                'Warning!',
                'Please select at least one job role.',
                CB
            );
        }
        ml(true, 'jsAddJobRoleModalLoader');
        //
        xhr = $.post(
            baseURI + 'cn/manage/job_role/' + (jobRoleId) + '/link',
            obj
        )
            .success(function () {
                //
                xhr = null;
                //
                $('#jsAddJobRoleModal .jsModalCancel').trigger('click');
                //
                return alertify.alert(
                    'Success',
                    'You have successfully linked the job roles.',
                    windlow.location.reload
                )
            })
            .fail(handleFailure);

    });


    function startAddJobRoleProcess() {
        //
        if (xhr !== null) {
            xhr.abort();
        }
        //
        Modal({
            Id: 'jsAddJobRoleModal',
            Loader: 'jsAddJobRoleModalLoader',
            Title: 'Link System Job Role To ComplyNet',
            Body: '<div class="container"><div id="jsAddJobRoleModalBody"></div></div>'
        }, function () {
            //
            xhr = $.get(
                baseURI + "cn/job_role_view/" + jobRoleId
            )
                .success(function (resp) {
                    //
                    xhr = null;
                    //
                    $('#jsAddJobRoleModalBody').html(resp.view);
                    //
                    $('.jsSelect2').select2();
                    //
                    $('.jsSelect2Tags').select2({
                        tags: true,
                        closeOnSelect: false
                    });
                    ml(false, 'jsAddJobRoleModalLoader');
                })
                .fail(handleFailure);
        });
    }

    function startShowJobRoleProcess() {
        //
        if (xhr !== null) {
            xhr.abort();
        }
        //
        Modal({
            Id: 'jsViewJobRoleModal',
            Loader: 'jsViewJobRoleModalLoader',
            Title: 'View job roles',
            Body: '<div class="container"><div id="jsViewJobRoleModalBody"></div></div>'
        }, function () {
            //
            xhr = $.get(
                baseURI + "cn/job_role_view_details/" + jobRoleId
            )
                .success(function (resp) {
                    //
                    xhr = null;
                    //
                    $('#jsViewJobRoleModalBody').html(resp.view);
                    ml(false, 'jsViewJobRoleModalLoader');
                })
                .fail(handleFailure);
        });
    }

    function deleteJobRole(recordId) {
        //
        ml(true, 'jsViewJobRoleModalLoader');
        //
        $.ajax({
            url: baseURI + 'cn/manage/job_role/' + recordId,
            method: "DELETE"
        })
            .success(function () {
                $('#jsViewJobRoleModal .jsModalCancel').trigger('click');
                startShowJobRoleProcess();
            })
            .fail(handleFailure);
    }

    /**
     * Controls the loader
     * @param {boolean} cond
     * @param {string} msg
     */
    function loader(cond, msg = "") {
        if (cond) {
            $(loaderRef).show();
            $(loaderRef + " .jsLoaderText").html(
                msg || "Please wait, while we process your request."
            );
        } else {
            $(loaderRef).hide();
            $(loaderRef + " .jsLoaderText").html(
                "Please wait, while we process your request."
            );
        }
    }

    /**
     * Handles failure
     * @param {object} resp
     */
    function handleFailure(resp) {
        //
        xhr = null;
        //
        if (resp.status === 401) {
            return alertify.alert(
                "Error",
                "Your login session expired. Please log in!",
                function () {
                    window.location.reload();
                }
            );
        }
    }

    /**
     * Empty callback function
     */
    function CB() { }
});
