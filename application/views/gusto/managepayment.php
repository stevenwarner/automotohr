<div class="container">
    <div class="row">
        <div class="col-sm-12">

            <div class="row">
                <div class="col-sm-12">
                    <label>Payment Speed <span class="csRequired"></span></label>
                    <select id="jsPayrollSettingsSpeed" style="width: 100%;">
                        <option <?= $payroll_settings['payment_speed'] == '2-day' ? 'selected' : ''; ?> value="2-day">2 days</option>
                        <option <?= $payroll_settings['payment_speed'] == '4-day' ? 'selected' : ''; ?> value="4-day">4 days</option>
                    </select>
                </div>
            </div>
            <div class="row" id="jsPayrollSettingsLimitRow">
                <br>
                <div class="col-xs-12">
                    <label class="csF16">Fast Payment Limit <span class="csRequired jsRequiredLimit <?= $payroll_settings['payment_speed'] == '2-day' ? '' : 'dn'; ?>"></span></label>
                    <p>Payment limit only applicable for 2-day payroll</p>
                    <input type="text" class="form-control" placeholder="5000" id="jsPayrollSettingsLimit" value="<?= $payroll_settings['fast_payment_limit']; ?>" />
                </div>
            </div>

            <!--  -->
            <div class="row">
                <br>
                <div class="col-xs-12 text-right">
                    <button class="btn btn-success jsPayrollSettingsSaveBtn">
                        <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    //
    var selectedDays = $('#jsPayrollSettingsSpeed').val();

    if (selectedDays == '2-day') {
        $("#jsPayrollSettingsLimitRow").show();
    } else {
        $("#jsPayrollSettingsLimitRow").hide();
    }

    //
    $("#jsPayrollSettingsSpeed").change(function() {
        var selectedDays = $('#jsPayrollSettingsSpeed').val();
        if (selectedDays == '2-day') {
            $("#jsPayrollSettingsLimitRow").show();
        } else {
            $("#jsPayrollSettingsLimitRow").hide();
        }
    });

    //
    $('#jsPayrollSettingsSpeed').select2();

    //

    $(function Settings() {
        /**
         * Saves the XHR (AJAX) object
         * @type {null|object}
         */
        var xhr = null;

        $(".jsPayrollSettingsSaveBtn").click(function(event) {

            //
            if (xhr !== null) {
                return;
            }
            //
            event.preventDefault();
            //
            var obj = {
                payment_speed: $("#jsPayrollSettingsSpeed").val(),
                fast_speed_limit: $("#jsPayrollSettingsLimit").val()
            };
            //
            var _this = $(this);
            //
            if (obj.payment_speed == "2-day" && !obj.fast_speed_limit) {

                return alertify.alert(
                    "Warning!",
                    "Fast payment limit is required.",
                    ECB
                );

            }

            //
            if (obj.payment_speed == "2-day" && !ifStringIsNumber(obj.fast_speed_limit)) {

                return alertify.alert(
                    "Warning!",
                    "Fast payment limit only numbers are allowed.",
                    ECB
                );

            }


            //
            $(this).text("Please wait, while we are updating.");
            //
            xhr = $.post(baseURI + "payroll/" + companyId + "/settings", obj)
                .success(function(response) {
                    //
                    xhr = null;
                    //
                    _this.html(
                        '<i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update'
                    );
                    //
                    if (response.errors) {
                        return alertify.alert(
                            "Error",
                            response.errors.join("<br />"),
                            function() {}
                        );
                    }
                })
                .fail(ErrorHandler);
        });




        /**
         * Handles XHR errors
         * @param {object} error
         */
        function ErrorHandler(error) {
            //
            xhr = null;
            //
            alertify.alert(
                "Error!",
                "The system failed to process your request. (" + error.status + ")",
                ECB
            );
        }


        /**
         * Alertify callback error
         * @returns
         */
        function ECB() {}

        //
        function ifStringIsNumber(_string) {
            return !(Number.isNaN(Number(_string)))
        }



    });
</script>