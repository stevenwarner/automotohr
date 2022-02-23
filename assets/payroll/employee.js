$(function Employee() {
    //
    var LOADER = 'employee';

    /**
     * Get Company Bank Account
     */
    function GetTax() {
        //
        ml(true, LOADER);
        //
        $.get(API_URL)
            .done(function(resp) {
                // Hides the loader
                ml(false, LOADER);
                //
                if (resp.response) {
                    //
                    $('.jsStatus').text(resp.response.VerifiedStatus ? "VERIFIED" : "UNVERIFIED");
                    $('.jsStatus').removeClass("csFC3").removeClass("csFC1");
                    $('.jsStatus').addClass(resp.response.VerifiedStatus ? "csFC1" : "csFC3");
                    //
                    $('.jsTaxLegalName').val(resp.response.LegalName);
                    //
                    $('.jsTaxEIN').val(resp.response.EIN);
                    //
                    $('.jsTaxPayerType option[value="' + (resp.response.TaxPayerType) + '"]').prop("selected", true);
                    //
                    $('.jsTaxFillingForm option[value="' + (resp.response.FillingForm) + '"]').prop("selected", true);
                    //
                    $('.jsTaxScorp').prop("checked", resp.response.Scorp);
                }
            });
    }

    /**
     * Set AJAX default setting
     */
    $.ajaxSetup({ headers: { "Key": API_KEY } });

    /**
     * Call
     */
    // GetTax();
});