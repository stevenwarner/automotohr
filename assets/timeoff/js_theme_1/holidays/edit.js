$(function(){
    //
    let holidayOBJ = {
        year: 0,
        holiday: 0,
        icon: 0,
        startDate: 0,
        endDate: 0,
        workOnHoliday: 0,
        deactivate: 0
    },
    cmnOBJ = {
        Holidays:{
            Main: {
                action: 'get_single_holiday',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                public: 0,
            }
        }
    };
    holidayId = 0;

    //
    window.timeoff.startEditProcess  = startEditProcess ;

    //
    $('#js-year-edit').change(function(e){
        $('#js-from-date-edit').val(''); 
        $('#js-from-date-edit').datepicker('option', 'yearRange', getYearRange()); 
        $('#js-from-date-edit').datepicker('option', 'defaultDate', moment().format('MM-DD-')+getYearRange('add', true)); 

        $('#js-to-date-edit').val(''); 
        $('#js-to-date-edit').datepicker('option', 'yearRange', getYearRange()); 
        $('#js-to-date-edit').datepicker('option', 'minDate', moment().format('MM-DD-')+getYearRange('add', true)); 
    });

    //
    $('#js-icon-edit').click(function(e){
        //
        e.preventDefault();
        //
        $('#js-holiday-icon-type').val('add');
        //
        Modal({
            Id: 'jsModal2',
            Title: 'Holiday Icons',
            Body: getIconBody('edit'),
            Buttons: [
                '<button class="btn btn-success jsSaveIcon" data-type="edit">Update Icon</button>'
            ],
            Loader: 'jsModalEditIconLoader'
        }, () => {
            ml(false, 'jsModalEditIconLoader');
        });
    });

    //
    $(document).on('click', '.jsSaveIcon[data-type="edit"]', function(e){
        //
        e.preventDefault();
        //
        holidayOBJ.icon = '';
        //
        $('#js-icon-plc-edit').prop('src', '');
        $('#js-icon-plc-box-edit').addClass('hidden');
        $('#js-holiday-icon-edit').val(0);
        //
        if($('.js-icon-select.active').length != 0){
            let type = $('#js-holiday-icon-type').val();
            //
            $('#js-icon-plc-edit').prop('src', $('.js-icon-select.active').find('img').prop('src'));
            $('#js-icon-plc-box-edit').removeClass('hidden');
            $('#js-holiday-icon-edit').val($('.js-icon-select.active').find('img').data('id'));
            //
            holidayOBJ.icon = $('.js-icon-select.active').find('img').data('id');
        }
        //
        $('#jsModal2').fadeOut(300).remove();
        $('body').css('overflow-y', 'auto');
    });

    //
    $('#js-icon-remove-edit').click(function(){
        $('#js-icon-plc-edit').prop('src', false);
        $('#js-icon-plc-box-edit').addClass('hidden');
        $('#js-holiday-icon-edit').val('');
        holidayOBJ.icon = '';
    });
    
    //
    $('#js-save-edit-btn').click(function(e){
        //
        e.preventDefault();
        //
        holidayOBJ.holiday = getField('#js-holiday-edit');
        holidayOBJ.startDate = getField('#js-from-date-edit');
        holidayOBJ.endDate = getField('#js-to-date-edit');
        holidayOBJ.workOnHoliday = getField('.allow_work_on_holiday-edit:checked');
        holidayOBJ.deactivate = $('#js-archive-check-edit').prop('checked') === true ? 1 : 0;
        //
        if(holidayOBJ.holiday == 0){
            alertify.alert('WARNING!', 'Holiday is required.', () => {});
            return false;
        }
        //
        if(holidayOBJ.startDate == 0){
            alertify.alert('WARNING!', 'Please, select the holiday start date.', () => {});
            return false;
        }
        //
        if(holidayOBJ.endDate == 0){
            alertify.alert('WARNING!', 'Please, select the holiday end date.', () => {});
            return false;
        }
        holidayOBJ.year = mopment(holidayOBJ.startDate).format('YYYY');
        //
        updateHoliday(holidayOBJ);
    });

    //
    function updateHoliday(type){
        //
        ml(true, 'holiday');
        //
        let post = Object.assign({}, holidayOBJ, {
            action: 'update_holiday',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0,
            holidayId: holidayId
        });
        //
        $.post(handlerURL, post, (resp) => {
            ml(false, 'holiday');
            //
            if(resp.Redirect === true){
                //
                alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                    window.location.reload();
                });
                return;
            }
            // On fail
            if(resp.Status === false){
                alertify.alert('WARNING!', resp.Response, () => {});
                return;
            }
            // On success
            alertify.alert('SUCCESS!', resp.Response, () => {
                loadViewPage();
            });
            return;
        });
    }

    //
    function startEditProcess (
        id
    ){
        //
        holidayId = id;
        //
        $.post(
            handlerURL, 
            Object.assign(cmnOBJ.Holidays.Main, { holidayId: holidayId}), 
            (resp) => {
                //
                if(resp.Redirect === true){
                    //
                    alertify.alert('WARNING!', 'Your session expired. Please, re-login to continue.', () => {
                        window.location.reload();
                    });
                    return;
                }
                // On fail
                if(resp.Status === false){
                    alertify.alert('WARNING!', resp.Response, () => {
                        loadViewPage();
                    });
                    return;
                }
                //
                holidayOBJ.year = resp.Data.holiday_year;
                holidayOBJ.holiday = resp.Data.holiday_title;
                holidayOBJ.icon = resp.Data.icon;
                holidayOBJ.startDate = moment(resp.Data.from_date).format('MM-DD-YYYY');
                holidayOBJ.endDate = moment(resp.Data.to_date).format('MM-DD-YYYY');
                holidayOBJ.workOnHoliday = resp.Data.work_on_holiday;
                holidayOBJ.deactivate = resp.Data.is_archived;
                //
                $('#js-year-edit').select2('val', holidayOBJ.year);
                //
                $('#js-holiday-edit').val(holidayOBJ.holiday);
                //
                $('#js-from-date-edit').val(holidayOBJ.startDate);
                //
                $('#js-to-date-edit').val(holidayOBJ.endDate);
                //
                $('#js-archive-check-edit').prop('checked', holidayOBJ.is_archived == 1 ? true : false);
                //
                $(`.allow_work_on_holiday-edit[value="${holidayOBJ.workOnHoliday}"]`).prop('checked', true);
                //
                if(holidayOBJ.icon != null && holidayOBJ.icon != ''){
                    $('#js-icon-plc-edit').prop('src', `${baseURL}assets/images/holidays/${holidayOBJ.icon}`);
                    $('#js-icon-plc-box-edit').removeClass('hidden');
                    $('#js-holiday-icon-edit').val(holidayOBJ.icon);
                }
            }
        );
        //
        ml(false, 'holiday');
    }
})