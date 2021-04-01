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
    };

    //
    $('#js-year-add').change(function(e){
        $('#js-from-date-add').val(''); 
        $('#js-from-date-add').datepicker('option', 'yearRange', getYearRange()); 
        $('#js-from-date-add').datepicker('option', 'defaultDate', moment().format('MM-DD-')+getYearRange('add', true)); 

        $('#js-to-date-add').val(''); 
        $('#js-to-date-add').datepicker('option', 'yearRange', getYearRange()); 
        $('#js-to-date-add').datepicker('option', 'minDate', moment().format('MM-DD-')+getYearRange('add', true)); 
    });

    //
    $('#js-icon-add').click(function(e){
        //
        e.preventDefault();
        //
        $('#js-holiday-icon-type').val('add');
        //
        Modal({
            Id: 'jsModal1',
            Title: 'Holiday Icons',
            Body: getIconBody('add'),
            Buttons: [
                '<button class="btn btn-success jsSaveIcon" data-type="add">Save Icon</button>'
            ],
            Loader: 'jsModalAddIconLoader'
        }, () => {
            ml(false, 'jsModalAddIconLoader');
        });
    });

    //
    $(document).on('click', '.jsSaveIcon[data-type="add"]', function(e){
        //
        e.preventDefault();
        //
        holidayOBJ.icon = '';
        //
        $('#js-icon-plc-add').prop('src', '');
        $('#js-icon-plc-box-add').addClass('hidden');
        $('#js-holiday-icon-add').val(0);
        //
        if($('.js-icon-select.active').length != 0){
            let type = $('#js-holiday-icon-type').val();
            //
            $('#js-icon-plc-add').prop('src', $('.js-icon-select.active').find('img').prop('src'));
            $('#js-icon-plc-box-add').removeClass('hidden');
            $('#js-holiday-icon-add').val($('.js-icon-select.active').find('img').data('id'));
            //
            holidayOBJ.icon = $('.js-icon-select.active').find('img').data('id');
        }
        //
        $('#jsModal1').fadeOut(300).remove();
        $('body').css('overflow-y', 'auto');
    });

    //
    $('#js-icon-remove-add').click(function(){
        $('#js-icon-plc-add').prop('src', false);
        $('#js-icon-plc-box-add').addClass('hidden');
        $('#js-holiday-icon-add').val('');
        holidayOBJ.icon = '';
    });
    
    //
    $('#js-save-add-btn').click(function(e){
        //
        e.preventDefault();
        //
        holidayOBJ.holiday = getField('#js-holiday-add');
        holidayOBJ.startDate = getField('#js-from-date-add');
        holidayOBJ.endDate = getField('#js-to-date-add');
        holidayOBJ.workOnHoliday = getField('.js-to-date-add:checked');
        holidayOBJ.deactivate = $('#js-archive-check-add').prop('checked') === true ? 1 : 0;
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
        addHoliday(holidayOBJ);
    });

    //
    function addHoliday(type){
        //
        ml(true, 'holiday');
        //
        let post = Object.assign({}, holidayOBJ, {
            action: 'create_holiday',
            companyId: companyId,
            employeeId: employeeId,
            employerId: employerId,
            public: 0
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
})