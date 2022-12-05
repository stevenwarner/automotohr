Model = Modal;
$(function(){
    //
    $('#employeeIds').select2();
    //
    $('.jsStartDatePicker')
    .datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function(time) {
            $('.jsEndDatePicker').datepicker('option', 'minDate', time);
        }
    });
    //
    $('.jsEndDatePicker').datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function(time) {
            $('.jsStartDatePicker').datepicker('option', 'maxDate', time);
        }
    });
});