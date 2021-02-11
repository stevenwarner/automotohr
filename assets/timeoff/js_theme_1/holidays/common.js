let cmnOBJ = {
    CompanyYears:{
        Main: {
            action: 'get_company_holiday_years',
            companyId: companyId,
            employerId: employerId,
            employeeId: employeeId,
            public: 0,
        }
    }
};
//
fetchCompanyYears();

//
if(page == 'add') loadAddPage();

//
$('.jsViewPoliciesBtn').click(loadViewPage);

//
$('#js-add-holiday-btn').click((e) => {
    //
    e.preventDefault();
    //
    loadAddPage();
});
//
// $('.js-view-holiday-btn').click((e) => {
//     //
//     e.preventDefault();
//     //
//     loadViewPage();
// });

//
$('.js-view-holiday-btn').click(function(e){
    e.preventDefault();
    //
    alertify.confirm(
        'WARNING!', 
        'Any unsaved changes will be lost.',
        loadViewPage,
        () => {}
    ).set('labels', {
        ok: "Leave",
        cancel: "No, I will stay"
    });
});
//
$(document).on('click', '.js-edit-row-btn', (e) => {
    //
    e.preventDefault();
    //
    loadEditPage($(e.target).closest('tr').data('id'));
});
//
$(document).on('click', '.js-icon-select', function(){
    $('.js-icon-select').removeClass('active');
    $(this).addClass('active');
});

// 
function fetchCompanyYears(){
    $.post(handlerURL, cmnOBJ.CompanyYears.Main, (resp) => {
        //
        if(resp.Redirect === true){
            alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                window.location.reload();
            });
            return;
        }
        //
        let rows = '';
        //
        if(resp.Status === false){
            //
            $('#js-filter-year').html('<option value="2020">2020</option>').select2();
            $('#js-filter-year').select2('val', moment().format('YYYY'));
            //
            rows = '<option value="-1">Please Select a Year</option><option value="2020">2020</option>';
            //
            $('#js-year-add').html(rows);
            $('#js-year-edit').html(rows);
            return;
        }
        //
        window.timeoff.companyYears = resp.Data;
        //
        if(window.timeoff.companyYears.length === 0) {
            //
            $('#js-filter-year').html('<option value="2020">2020</option>').select2();
            $('#js-filter-year').select2('val', moment().format('YYYY'));
            //
            rows = '<option value="-1">Please Select a Year</option><option value="2020">2020</option>';
            //
            $('#js-year-add').html(rows);
            $('#js-year-edit').html(rows);
            return;
        }
        
        //
        window.timeoff.companyYears.map(function(v){
            rows += '<option value="'+(v)+'">'+(v)+'</option>';
        });
        //
        $('#js-filter-year').html(rows).select2();
        $('#js-filter-year').select2('val', moment().format('YYYY'));
        //
        rows = '<option value="-1">Please Select a Year</option>'+rows;
        //
        $('#js-year-add').html(rows);
        $('#js-year-edit').html(rows);
    });
}

//
function getYearRange(type, single){
    type = type === undefined ? 'add' : type;
    var selection = $('#js-year-'+( type )+'').val();
    //
    if(selection == null || selection == '-1'){
        if(single !== undefined) return moment().format('YYYY');
        return moment().format('YYYY')+':'+moment().format('YYYY');
    }
    return single !== undefined ? selection : `${selection}:${selection}`;
}

//
function getIconList(){
    //
    let 
    i = 1,
    il = 28,
    iconList = [];
    //
    
    for(i; i <= il; i++){
        //
        let n = i+'.png';
        //
        iconList.push(n);
    }
    //
    return iconList;
}

//
function getIconBody(type){
    //
    let html = '';
    //
    getIconList().map((icon) => {
        //
        html += `
        <div class="col-sm-2">
            <div class="cs-icon-box js-icon-select">
                <img src="${baseURL}assets/images/holidays/${icon}" data-id="${icon}" data-type="${type}" alt="${icon}" />                        
            </div>
        </div>
        `;
    });
    //
    return html;
}


// Pages
// Add policy page
function loadAddPage(){
    //
    page = 'add';
    // Show loader
    ml(true, 'holiday');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-add').fadeIn(500);
    // Check if polciies are loaded
    if(
        window.timeoff.companyYears === undefined
    ){
        setTimeout(loadAddPage, 2000);
        return;
    }

    // Reset view
    //
    $('#js-year-add').select2();
    $('#js-year-add').select2('val', '-1');
    //
    $('#js-frequency-add').select2();
    $('#js-frequency-add').select2('val', 'yearly');
    //
    $('#js-holiday-add').val('');
    //
    $('#js-sort-order-add').val(1);
    //
    $('#js-from-date-add').val('');
    //
    $('#js-to-date-add').val('');
    //
    $('#js-archive-check-add').prop('checked', false);
    $('#js-icon-plc-add').prop('src', false);
    $('#js-icon-plc-box-add').addClass('hidden');
    $('#js-holiday-icon-add').val('');
    //
    $('#js-from-date-add').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        yearRange: getYearRange('add'),
        onSelect: function (v) { 
            $('#js-to-date-add').datepicker('option', 'minDate', v); 
            $('#js-to-date-add').val(v); 
        }
    })
    $('#js-to-date-add').datepicker({
        dateFormat: 'mm-dd-yy',
        yearRange: getYearRange('add'),
        changeMonth: true
    }).datepicker('option', 'minDate', $('#js-from-date-add').val());
    //
    ml(false, 'holiday');
}

//
function loadViewPage(){
    //
    page = 'view';
    //
    ml(true, 'holiday');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-view').fadeIn(500);
    //
    if(
        window.timeoff.companyYears === undefined
    ){
        setTimeout(loadViewPage, 2000);
        return;
    }
    //
    window.timeoff.fetchCompanyHolidays();
}

//
function loadEditPage(holidayId){
    //
    page = 'edit';
    // Show loader
    ml(true, 'holiday');
    // Hide all other pages
    $('.js-page').fadeOut(0);
    // Show page
    $('#js-page-edit').fadeIn(500);
    // Check if polciies are loaded
    if(
        window.timeoff.companyYears === undefined
    ){
        setTimeout(loadEditPage, 2000);
        return;
    }

    // Reset view
    //
    $('#js-year-edit').select2();
    $('#js-year-edit').select2('val', '-1');
    //
    $('#js-frequency-edit').select2();
    $('#js-frequency-edit').select2('val', 'yearly');
    //
    $('#js-holiday-edit').val('');
    //
    $('#js-sort-order-edit').val(1);
    //
    $('#js-from-date-edit').val('');
    //
    $('#js-to-date-edit').val('');
    //
    $('#js-archive-check-edit').prop('checked', false);
    $('#js-icon-plc-edit').prop('src', false);
    $('#js-icon-plc-box-edit').addClass('hidden');
    $('#js-holiday-icon-edit').val('');
    //
    $('#js-from-date-edit').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        yearRange: getYearRange('edit'),
        onSelect: function (v) { 
            $('#js-to-date-edit').datepicker('option', 'minDate', v); 
            $('#js-to-date-edit').val(v); 
        }
    })
    $('#js-to-date-edit').datepicker({
        dateFormat: 'mm-dd-yy',
        yearRange: getYearRange('edit'),
        changeMonth: true
    }).datepicker('option', 'minDate', $('#js-from-date-edit').val());
    //
    window.timeoff.startEditProcess(holidayId);
}