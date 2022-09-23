$(function MigrateGroupWithDocuments() {

    //
    const dataOBJ = {
        fromId: 0,
        toId: 0
    };
    //
    let XHR = null;

    // Add select2
    $('#jsFromCompany').select2();
    $('#jsToCompany').prop('disabled', true).select2();


    // Capture the on change
    $('#jsFromCompany').change(function () {
        //
        dataOBJ.fromId = parseInt($(this).prop('value'));
        dataOBJ.toId = 0;
        //
        resetView();
        // Remove all restrictions
        $('#jsToCompany')
            .prop('disabled', false)
            .find('option')
            .prop('disabled', false);
        //
        if ($(this).val() == 0) {
            return $('#jsToCompany').prop('disabled', true).select2();
        }
        // Add restrictions according to need
        $('#jsToCompany')
            .find('option[value="' + ($(this).prop('value')) + '"]')
            .prop('disabled', true)
            .select2();
    });

    // Capture to id change
    $('#jsToCompany').change(function () {
        dataOBJ.toId = parseInt($(this).prop('value'));
    });

    // Trigger the listing event
    $('#jsFetchSelectedGroups').click(function (event) {
        //
        event.preventDefault();
        //
        if (dataOBJ.fromId === 0) {
            return alertify.alert('Warning!', 'Please select a source company', function () { });
        }
        //
        if (dataOBJ.toId === 0) {
            return alertify.alert('Warning!', 'Please select a destination company', function () { });
        }

        //
        startListingProcess();
    });

    //
    $('#jsSelectAll').click(function(){
        $('.jsSelectRow').prop('checked', $(this).prop('checked'));
        $('#jsGroupWithDocumentsSelectedCount').text($('.jsSelectRow:checked').length);
    });

    //
    $(document).on('click', '.jsSelectRow', function(){
        //
        $('#jsSelectAll').prop('checked', false);
        //
        if($('.jsSelectRow:checked').length == $('.jsSelectRow').length){
            $('#jsSelectAll').prop('checked', true);
        }
        //
        $('#jsGroupWithDocumentsSelectedCount').text($('.jsSelectRow:checked').length);
    });


    $('.jsStartMigrationProcess').click(function(event){
        //
        event.preventDefault();
        //
        if($('.jsSelectRow:checked').length === 0){
            return alertify.alert('Warning!', 'Please select at least one group.', function(){});
        }
        //
        let ids = [];
        //
        $('.jsSelectRow:checked').map(function(){
            ids.push(
                $(this).closest('tr.jsParentRow').data('id')
            );
        });
        //
        startMigrationProcess(ids);
    });

    /**
     * Start the listing process
     * The groups with documents will be fetched from the server
     */
    function startListingProcess() {
        //
        resetView();
        //
        loader(false, 'Please wait, while we are fetching data.');
        //
        XHR = $.get(baseURL('migrate_company_groups_handler/' + dataOBJ.fromId))
            .success(function (data) {
                //
                if (!data) {
                    //
                    return resetView();
                }
                //
                setView(data);
            })
            .fail(function () {
                return resetView();
            });
    }

    /**
     * Start the listing process
     * The groups with documents will be fetched from the server
     */
    function startMigrationProcess(ids) {
        //
        loader(false, 'Please wait, while we are copying groups with documents. It might take a few minutes.');
        //
        XHR = $.post(baseURL('migrate_company_groups_handler'), {
            fromId: dataOBJ.fromId,
            toId: dataOBJ.toId,
            ids: ids
        })
            .success(function () {
                loader(true);
                return alertify.alert('Success!', 'Company groups with documents have been copied.', resetView);
            })
            .fail(function () {
                loader(true);
                console.log('ERROR');
            });
    }

    /**
     * Sets the data container view
     * 
     * @param {object} groups 
     */
    function setView(groups) {
        //
        $('#jsGroupWithDocumentsCount').text(Object.keys(groups).length);
        //
        let rows = '';
        //
        if (!Object.keys(groups).length) {
            rows += '<tr>';
            rows += '   <td colspan="4"><p class="alert alert-info text-center">No data found.</p></td>';
            rows += '</tr>';
        } else {
            for(let i in groups){
                //
                let sg = groups[i];
                //
                rows += '<tr class="jsParentRow" data-id="'+(sg.sid)+'">';
                rows += '    <td class="vam">';
                rows += '        <label class="control control--checkbox">';
                rows += '            <input type="checkbox" class="jsSelectRow" />';
                rows += '            <div class="control__indicator"></div>';
                rows += '        </label>';
                rows += '    </td>';
                rows += '    <td class="vam">';
                rows += '        <strong>'+(sg.name)+'</strong>';
                rows += '    </td>';
                rows += '    <td class="vam">';
                rows += '        <button class="btn btn-link jsToggleBtns"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;'+(sg.total)+' Documents</button>';
                rows += '    </td>';
                rows += '</tr>';
                if(sg.documents.length){
                    rows += '<tr class="jsSub" style="display: none;" data-id="'+(sg.sid)+'">';
                    rows += '<td colspan="3"><table class="table table-striped">';
                    rows += '<thead>';
                    rows += '   <tr>';
                    rows += '       <th>Document</th>';
                    rows += '       <th>Document Type</th>';
                    rows += '       <th class="text-center">Acknowledged Required</th>';
                    rows += '       <th class="text-center">Download Required</th>';
                    rows += '       <th class="text-center">Signature Required</th>';
                    rows += '   </tr>';
                    rows += '</thead>';
                    rows += '<tbody>';
                    //
                    sg.documents.map(function(dct){
                        rows += '<tr>';
                        rows += '<td class="vam">';
                        rows += '    <strong>'+(dct.document_title)+'</strong>';
                        rows += '</td>';
                        rows += '<td class="vam">';
                        rows += '    <strong>'+(dct.document_type.toUpperCase())+'</strong>';
                        rows += '</td>';
                        rows += '<td class="vam text-center">';
                        rows += '    <strong class="text-'+(dct.acknowledgment_required ? "success" : "danger")+'"><i class="fa fa-'+(dct.acknowledgment_required ? "check" : "times")+'"></i></strong>';
                        rows += '</td>';
                        rows += '<td class="vam text-center">';
                        rows += '    <strong class="text-'+(dct.download_required ? "success" : "danger")+'"><i class="fa fa-'+(dct.download_required ? "check" : "times")+'"></i></strong>';
                        rows += '</td>';
                        rows += '<td class="vam text-center">';
                        rows += '    <strong class="text-'+(dct.signature_required ? "success" : "danger")+'"><i class="fa fa-'+(dct.signature_required ? "check" : "times")+'"></i></strong>';
                        rows += '</td>';
                        rows += '</tr>';
                    });
                    rows += '</tbody>';
                    rows += '</table></td>';
                    rows += '</tr>';
                }
            };
        }
        //
        $('#jsGroupWithDocumentsTBody').html(rows);
        $('.jsGroupWithDocumentsWrap').show(0);
        //
        loader(true);
        //
        $('.jsToggleBtns').on('click', function(event){
            // 
            event.preventDefault();
            //
            $(this).find('i').toggleClass('fa-plus-square');
            $(this).find('i').toggleClass('fa-minus-square');
            //
            $('.jsSub[data-id="'+($(this).closest('tr.jsParentRow').data('id'))+'"]').toggle();
        });
    }

    /**
     * Resets the data container
     */
    function resetView() {
        $('#jsGroupWithDocumentsCount').text(0);
        $('#jsGroupWithDocumentsSelectedCount').text(0);
        $('.jsGroupWithDocumentsWrap').hide(0);
        $('#jsGroupWithDocumentsTBody').html('');
        loader(true);
    }

    /**
     * Loader
     * 
     * @param {boolean} show 
     * @param {string}  msg 
     */
    function loader(show, msg) {
        //
        $('#jsGroupsWithDocumentsIframe').find('.jsLoaderText').text(msg || 'Please wait, while we process your request.');
        //
        if (!show) {
            $('#jsGroupsWithDocumentsIframe').show();
        } else {
            $('#jsGroupsWithDocumentsIframe').hide();
        }
    }

    /**
     * Creates a URI
     * @param {string} uri 
     * @returns 
     */
    function baseURL(uri) {
        //
        return window.location.origin + '/' + (uri ? uri + '/' : '');
    }

    //
    resetView();
});