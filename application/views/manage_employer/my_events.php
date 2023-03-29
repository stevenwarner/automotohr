<div class="job-main-content">
    <div class="job-container">
        <header class="hr-page-header">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <h2 class="no-margin">
                <span><img src="<?= base_url() ?>assets/images/calendar-icon.png" alt="image"></span>
                My Events
            </h2>
            <div class="back-btn">
                <a class="siteBtn redBtn" style="margin-bottom: 10px;" id="" href="<?= base_url('dashboard') ?>">&laquo; BACK</a>
            </div>
        </header>
        <div class="job-feature-main m_job">
            <div class="portalmid">      
                <div id='calendar'></div>
                <div id="file_loader" style="display:block; height:1353px;"></div>     
                <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                <div class="loader_message" style="display:block; margin-top: 35px;">Please wait while calendar is loading...</div>
                <div id="popup1" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Event Management</h4>
                            </div>
                            <form class="date_form" id="event_form" method="post" >
                                <div class="modal-body">                                                
                                    <div class="event-modal-inner">
                                        <ul>
                                            <input type="hidden" id="event_id" name="event_id" value="">
                                            <li style="display: none;" id="select_new">
                                                <label>Select Applicant Name:</label>
                                                <div class="fields-wrapper">
                                                    <select id='contact_id' > 
                                                        <?php foreach ($applicants as $contact) { ?>
                                                            <option value="<?= $contact['sid'] ?>">
                                                            <strong><?= $contact['first_name'] ?> <?= $contact['last_name'] ?></strong>  (<?= $contact['email'] ?>)
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Title:</label>
                                                <div class="fields-wrapper">
                                                    <input type="text" id="title" name="title" placeholder='Enter title here' class='event_input eventtitle' required>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Category:</label>
                                                <div class="fields-wrapper">               
                                                    <select id='category' name='category' style="width: 130px;margin-left: 5px;">
                                                        <option value="call">Call</option>
                                                        <option value="email">Email</option>
                                                        <option value="meeting">Meeting</option>
                                                        <option selected="selected" value="interview">Interview</option>
                                                        <option value="personal">Personal</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>

                                            </li>
                                            <li>
                                                <label>Event Date: </label>
                                                <div class="fields-wrapper"> 
                                                    <input name="eventdate" id="eventdate" type="text" class="datepicker101"  required="">
                                                </div>
                                            </li>
                                            <li>
                                                <label>Start Time: </label>
                                                <div class="fields-wrapper">
                                                    <input name="eventstarttime" id="eventstarttime" readonly="readonly" type="text" class="stepExample1">
                                                </div>
                                            </li>
                                            <li>
                                                <label>End Time: </label>
                                                <div class="fields-wrapper">
                                                    <input name="eventendtime" id="eventendtime" readonly="readonly" type="text" class="stepExample2">
                                                </div>
                                            </li>
                                            <li>
                                                <label>Description:</label>
                                                <div class="fields-wrapper">
                                                    <textarea name="description" id='description' class="eventtextarea"></textarea>
                                                </div>

                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="javascript:;" class="btn_popup btn_popup-small" data-dismiss="modal" >Close</a>
                                    <input class="btn_popup btn_popup-small" name="event_submit" style="display: none;" type="button"  value="Save" id="save">
                                    <input class="btn_popup btn_popup-small" name="event_submit" type="button" style="display: none;"  value="Delete" id="delete">
                                    <input class="btn_popup btn_popup-small" name="event_submit" type="button" style="display: none;"  value="Update" id="update">

                                </div>
                            </form>
                        </div>

                    </div>
                </div>


            </div>
        </div>         
    </div>
</div>




<link href='<?= base_url() ?>assets/calendar/fullcalendar.css' rel='stylesheet' />
<link href='<?= base_url() ?>assets/calendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?= base_url() ?>assets/calendar/moment.min.js'></script>
<script src='<?= base_url() ?>assets/calendar/fullcalendar.min.js'></script>
<script>
            $('#file_loader').click(function(){
    $('#select_new').css('display', 'none');
            $('#update').css('display', 'none');
            $('#save').css('display', 'none');
            $('#title').val('');
            $('#eventdate').val('');
            $('#eventstarttime').val('');
            $('#eventendtime').val('');
            $('#description').val('');
            $('#file_loader').css("display", "none");
            $('#popup1').modal('hide');
            $('body').css("overflow", "auto");
    });
            $(".js-modal-close").click(function () {
    $('#select_new').css('display', 'none');
            $('#update').css('display', 'none');
            $('#save').css('display', 'none');
            $('#title').val('');
            $('#eventdate').val('');
            $('#eventstarttime').val('');
            $('#eventendtime').val('');
            $('#description').val('');
            $('#popup1').modal('hide');
            $('#file_loader').css("display", "none");
    });
            function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
            }
    function convertTime(time) {
    var hours = Number(time.match(/^(\d+)/)[1]);
            var minutes = Number(time.match(/:(\d+)/)[1]);
            var AMPMposition = time.indexOf(":");
            var AMPM = time.substr(AMPMposition + 3, time.length);
            if (AMPM == "pm" && hours < 12) hours = hours + 12;
            if (AMPM == "am" && hours == 12) hours = hours - 12;
            var sHours = hours.toString();
            var sMinutes = minutes.toString();
            if (hours < 10) sHours = "0" + sHours;
            if (minutes < 10) sMinutes = "0" + sMinutes;
            return sHours + ":" + sMinutes;
    }

    function tConvert (time) {
    // Check correct time format and split into components
    time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
            if (time.length > 1) { // If time format correct
    time = time.slice (1); // Remove full string match value
            time[5] = + time[0] < 12 ? 'am' : 'pm'; // Set AM/PM
            time[0] = + time[0] % 12 || 12; // Adjust hours
    }
    return time.join (''); // return adjusted time or original string
    }

    $(document).ready(function () {
    var eventt = new Event('main');
            //populating date and time in Popup
            $(".datepicker101").datepicker({
                     dateFormat: 'mm-dd-yy',
                     changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
                 }).val();
            $('#eventendtime').datetimepicker({
    datepicker:false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step:15,
            onShow:function(ct){
            time = $('#eventstarttime').val();
                    timeAr = time.split(":");
                    last = parseInt(timeAr[1].substr(0, 2)) + 15;
                    if (last == 0)
                    last = "00";
                    mm = timeAr[1].substr(2, 2);
                    timeFinal = timeAr[0] + ":" + last + mm;
                    this.setOptions({
                    minTime: $('#eventstarttime').val()? timeFinal :false
                    }
                    )
                    console.log(timeFinal);
            }
    });
            $('#eventstarttime').datetimepicker({
    datepicker:false,
            format: 'g:iA',
            formatTime: 'g:iA',
            step:15,
            onShow:function(ct){
            this.setOptions({
            maxTime:$('#eventendtime').val()?$('#eventendtime').val():false
            }
            )
            }
    });
            function form_check(){
            alertify.defaults.glossary.title = 'Event Management Error!';
                    if ($('#title').val() == '' && $('#eventdate').val() == '') {
            alertify.alert("Please provide Event Title and Date");
                    return false;
            }

            if ($('#title').val() == '') {
            alertify.alert("Please provide Event Title");
                    return false;
            }

            if ($('#eventdate').val() == '') {
            alertify.alert("Please Provide Event Date");
                    return false;
            }

            if ($('#eventstarttime').val() == '') {
            alertify.alert("Please provide Event Start Time");
                    return false;
            }

            if ($('#eventendtime').val() == '') {
            alertify.alert("Please provide Event End Time");
                    return false;
            }
            return true;
            }
//send ajax request to save new data
    $('#update').click(function(){
    if (form_check()) {
    //getting form data by ID to save
    event_id = $('#event_id').val();
            title = $('#title').val();
            category = $('#category').val();
            date = $('#eventdate').val();
            eventstarttime = $('#eventstarttime').val();
            eventendtime = $('#eventendtime').val();
            description = $('#description').val();
            $.ajax({
            url:"<?= base_url() ?>dashboard/calendar_task",
                    type:'POST',
                    data:{
                    action: 'update_event',
                            sid: event_id,
                            title: title,
                            category:category,
                            date:date,
                            eventstarttime:eventstarttime,
                            eventendtime:eventendtime,
                            description:description,
                    },
                    success: function(msg) {

                    alertify.success(msg);
                            //convert date into yyyy-mm-dd
                            var newDate = date.split("-");
                            newDate = newDate['2'] + '-' + newDate['0'] + '-' + newDate['1'];
                            //
                            $('#popup1').modal('hide');
                            $('#file_loader').css("display", "none");
                            var position = eventt.title.indexOf(":");
                            eventt.title = capitalizeFirstLetter(category) + eventt.title.substr(position, eventt.title.length);
                            //event start time

                            eventstarttime24Hr = convertTime(eventstarttime);
                            eventnewstart = moment(newDate + 'T' + eventstarttime24Hr + ':00', moment.ISO_8601);
                            eventt.start.set('year', eventnewstart.get('year'));
                            eventt.start.set('month', eventnewstart.get('month'));
                            eventt.start.set('date', eventnewstart.get('date'));
                            eventt.start.set('hour', eventnewstart.get('hour'));
                            eventt.start.set('minute', eventnewstart.get('minute'));
                            eventt.start.set('second', eventnewstart.get('second'));
                            //event end time
                            eventendtime24Hr = convertTime(eventendtime);
                            eventnewend = moment(newDate + 'T' + eventendtime24Hr + ':00', moment.ISO_8601);
                            eventt.end.set('year', eventnewend.get('year'));
                            eventt.end.set('month', eventnewend.get('month'));
                            eventt.end.set('date', eventnewend.get('date'));
                            eventt.end.set('hour', eventnewend.get('hour'));
                            eventt.end.set('minute', eventnewend.get('minute'));
                            eventt.end.set('second', eventnewend.get('second'));
                            if (category == 'interview')
                            eventt.color = 'green';
                            else if (category == 'call')
                            eventt.color = 'rgb(221, 118, 0)';
                            else if (category == 'email')
                            eventt.color = 'rgb(185, 16, 255)';
                            else if (category == 'meeting')
                            eventt.color = 'rgb(0, 145, 221)';
                            else if (category == 'personal')
                            eventt.color = 'rgb(255, 16, 80)';
                            else if (category == 'other')
                            eventt.color = 'rgb(126, 123, 123)';
                            eventt.event_title = title;
                            eventt.event_id = event_id;
                            eventt.category = category;
                            eventt.eventdate = date;
                            eventt.eventstarttime = eventstarttime;
                            eventt.eventendtime = eventendtime;
                            eventt.description = description;
                            eventt.eventstarttime_12hr = eventstarttime;
                            eventt.eventendtime_12hr = eventendtime;
                            $('#calendar').fullCalendar('updateEvent', eventt);
                    }
            });
    }
    else
            return false;
    });
            //delete an event
            $('#delete').click(function(){
    event_id = $('#event_id').val();
            $.ajax({
            url:"<?= base_url() ?>dashboard/deleteEvent?sid=" + event_id,
                    type:'GET'
                    , success: function(msg) {
                    $('#popup1').modal('hide');
                            alertify.alert(msg, function(){
                            location.reload();
                            });
                    }
            });
    });
            //Save new event
            $('#save').click(function(){
    if ($('#contact_id').val() == '') {
    alertify.alert("Please select an Applicant");
            return false;
    }
    if (form_check())
    {
    //getting form data by ID to save
    event_id = $('#contact_id').val();
            title = $('#title').val();
            category = $('#category').val();
            date = $('#eventdate').val();
            eventstarttime = $('#eventstarttime').val();
            eventendtime = $('#eventendtime').val();
            description = $('#description').val();
            $.ajax({
            url:"<?= base_url() ?>dashboard/calendar_task",
                    type:'POST',
                    data:{
                    action: 'save_event',
                            sid: event_id,
                            title: title,
                            category:category,
                            date:date,
                            eventstarttime:eventstarttime,
                            eventendtime:eventendtime,
                            description:description,
                    },
                    success: function(msg) {
                    $('#file_loader').css("display", "none");
                            $('#popup1').modal('hide');
                            $('body').css("overflow", "auto");
                            alertify
                            .alert(msg, function(){
                            location.reload();
                            });
                    }
            });
    }
    else
            return false;
    });
//start of calendar
            $('#calendar').fullCalendar({
    header: {
    left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
    },
            forceEventDuration:true,
            selectHelper: true,
            select: function (start, end) {
            var title = prompt('Event Title:');
                    var eventData;
                    if (title) {
            eventData = {
            title: title,
                    start: start,
                    end: end
            };
                    $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
            }
            $('#calendar').fullCalendar('unselect');
            },
            eventLimit: true, // allow "more" link when too many events

            eventClick: function (calEvent, jsEvent, view) {
            //open popup
            $('#select_new').css('display', 'none');
                    $('#update').css('display', 'block');
                    $('#delete').show();
                    $('#save').css('display', 'none');
                    $('#popup1').modal('show');
                    //$('#file_loader').css("display", "block");
                    $('html,body').animate({
            scrollTop: $("#popup1").offset().top
            });
                    //fill up the popup
                    $('#event_id').val(calEvent.event_id);
                    $('#title').val(calEvent.event_title);
                    $('#category').val(calEvent.category);
                    $('#eventdate').val(calEvent.eventdate);
                    $('#eventstarttime').val(calEvent.eventstarttime_12hr);
                    $('#eventendtime').val(calEvent.eventendtime_12hr);
                    $('#description').val(calEvent.description);
                    eventt = calEvent;
                    console.log(calEvent);
            },
            events: [
<?php foreach ($events as $event) { ?>
                {
                title  : '<?= $event['category_uc'] ?>: <?= $event['f_name_uc'] ?> <?= $event['l_name_uc'] ?>',
                                    start  : '<?= $event['backDate'] ?>T<?= $event['eventstarttime24Hr'] ?>',
                                                        end  : '<?= $event['date'] ?>T<?= $event['eventendtime24Hr'] ?>}',
    <?php if ($event['category'] == 'interview') { ?>
                                                    color:'green'
    <?php } else if ($event['category'] == 'call') { ?>
                                                    color:'rgb(221, 118, 0)'
    <?php } else if ($event['category'] == 'email') { ?>
                                                    color:'rgb(185, 16, 255)'
    <?php } else if ($event['category'] == 'meeting') { ?>
                                                    color:'rgb(0, 145, 221)'
    <?php } else if ($event['category'] == 'cpersonalall') { ?>
                                                    color:'rgb(255, 16, 80)'
    <?php } else if ($event['category'] == 'other') { ?>
                                                    color:'rgb(126, 123, 123)'
    <?php } ?>,
                                                        event_title:'<?= $event['title'] ?>',
                                                        event_id : '<?= $event['sid'] ?>',
                                                        category : '<?= $event['category'] ?>',
                                                        date : '<?= $event['date'] ?>',
                                                        eventdate :'<?= $event['frontDate'] ?>',
                                                        eventstarttime:'<?= $event['eventstarttime'] ?>',
                                                        eventendtime:'<?= $event['eventendtime'] ?>',
                                                        eventstarttime:'<?= $event['eventstarttime'] ?>',
                                                        eventendtime:'<?= $event['eventendtime'] ?>',
                                                        description : '<?= $event['description'] ?>',
                                                        eventstarttime_12hr:'<?= $event['eventstarttime'] ?>',
                                                        eventendtime_12hr:'<?= $event['eventendtime'] ?>',
                                                        editable: true,
                                                },
<?php } ?>
                                            ],
                                            eventDrop: function(event, delta, revertFunc) {
                                            console.log(event);
                                                    //.................eventstarttime..............................................
                                                    datetime = new Date(event.start.format());
                                                    eventDate = datetime.getDate() + '-' + (datetime.getMonth() + 1) + '-' + datetime.getFullYear();
                                                    hours = event.start.format().substr(event.start.format().indexOf('T') + 1, 2);
                                                    if (datetime.getUTCMinutes() == 0)
                                                    mins = '00';
                                                    else
                                                    mins = datetime.getUTCMinutes();
                                                    if (hours == '0')
                                                    hours = '00';
                                                    eventstarttime = hours + ':' + mins;
                                                    eventstarttime12hr = tConvert(eventstarttime);
                                                    //.,................eventendtime.........................................................

                                                    datetime = new Date(event.end.format());
                                                    hours = event.end.format().substr(event.end.format().indexOf('T') + 1, 2);
                                                    if (datetime.getUTCMinutes() == 0)
                                                    mins = '00';
                                                    else
                                                    mins = datetime.getUTCMinutes();
                                                    if (hours == '0')
                                                    hours = '00';
                                                    eventendtime = hours + ':' + mins;
                                                    eventendtime12hr = tConvert(eventendtime);
                                                    //............................//


                                                    olddate = eventDate;
                                                    newdate = eventDate.split('-');
                                                    eventDate = newdate[1] + '-' + newdate[0] + '-' + newdate[2];
                                                    console.log(event);
                                                    console.log(eventDate);
                                                    $.ajax({
                                                    url:"<?= base_url() ?>dashboard/calendar_task",
                                                            type:'POST',
                                                            data:{
                                                            action: 'update_event',
                                                                    sid: event.event_id,
                                                                    title: event.event_title,
                                                                    category:event.category,
                                                                    date:eventDate,
                                                                    eventstarttime:event.eventstarttime,
                                                                    eventendtime:event.eventendtime,
                                                                    description:event.description,
                                                            },
                                                            success: function(msg) {
                                                            alertify.success(msg);
                                                                    event.eventdate = eventDate;
                                                                    event.eventstarttime = eventstarttime;
                                                                    event.eventendtime = eventendtime;
                                                                    event.eventstarttime_12hr = eventstarttime12hr;
                                                                    event.eventendtime_12hr = eventendtime12hr;
                                                            }
                                                    });
                                            },
                                            eventResize: function(event, delta, revertFunc) {
                                            //.................eventstarttime..............................................
                                            datetime = new Date(event.start.format());
                                                    eventDate = datetime.getDate() + '-' + (datetime.getMonth() + 1) + '-' + datetime.getFullYear();
                                                    hours = event.start.format().substr(event.start.format().indexOf('T') + 1, 2);
                                                    if (datetime.getUTCMinutes() == 0)
                                                    mins = '00';
                                                    else
                                                    mins = datetime.getUTCMinutes();
                                                    if (hours == '0')
                                                    hours = '00';
                                                    eventstarttime = hours + ':' + mins;
                                                    eventstarttime12hr = tConvert(eventstarttime);
                                                    //.,................eventendtime.........................................................
                                                    datetime = new Date(event.end.format());
                                                    hours = event.end.format().substr(event.end.format().indexOf('T') + 1, 2);
                                                    if (datetime.getUTCMinutes() == 0)
                                                    mins = '00';
                                                    else
                                                    mins = datetime.getUTCMinutes();
                                                    if (hours == '0')
                                                    hours = '00';
                                                    eventendtime = hours + ':' + mins;
                                                    eventendtime12hr = tConvert(eventendtime);
                                                    //..............................................
                                                    olddate = eventDate;
                                                    newdate = eventDate.split('-');
                                                    eventDate = newdate[1] + '-' + newdate[0] + '-' + newdate[2];
                                                    $.ajax({
                                                    url:"<?= base_url() ?>dashboard/calendar_task",
                                                            type:'POST',
                                                            data:{
                                                            action: 'update_event',
                                                                    sid: event.event_id,
                                                                    title: event.event_title,
                                                                    category:event.category,
                                                                    date:eventDate,
                                                                    eventstarttime:eventstarttime12hr,
                                                                    eventendtime:eventendtime12hr,
                                                                    description:event.description,
                                                            },
                                                            success: function(msg) {
                                                            alertify.success(msg);
                                                                    event.eventstarttime = eventstarttime;
                                                                    event.eventendtime = eventendtime;
                                                                    event.eventstarttime_12hr = eventstarttime12hr;
                                                                    event.eventendtime_12hr = eventendtime12hr;
                                                            }
                                                    });
                                            },
                                            dayClick: function(date, allDay, jsEvent, view) {

                                            //fill up the popup
                                            $('#select_new').css('display', 'block');
                                                    $('#update').css('display', 'none');
                                                    $('#delete').hide();
                                                    $('#save').css('display', 'block');
                                                    $('#title').val('');
                                                    newdate = moment(date).format('MM-DD-YYYY');
                                                    $('#eventdate').val(newdate);
                                                    $('#eventstarttime').val('');
                                                    $('#eventendtime').val('');
                                                    $('#description').val('');
                                                    $('#popup1').modal('show');
                                                    //$('#file_loader').css("display", "block");
                                                    $('html,body').animate({
                                            scrollTop: $("#popup1").offset().top
                                            });
                                            },
                                            allDaySlot:false,
                                            timeFormat: 'h(:mm)'
                                    });
                                            $('#file_loader').css("display", "none");
                                            $('.my_spinner').css("visibility", "hidden");
                                            $('.loader_message').css("display", "none");
                                    });</script>


<script type="text/javascript">

                                            $('#contact_id').select2({
                                    placeholder: "Enter applicant name",
                                            allowClear:true
                                    });
                                            $('.select2-dropdown').css('z-index', '99999999999999999999999');
</script>


