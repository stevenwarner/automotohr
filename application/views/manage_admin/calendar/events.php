<link href="<?=base_url('assets');?>/calendar/fullcalendar.css" rel="stylesheet"/>
<link href="<?=base_url('assets');?>/calendar/fullcalendar.print.css" rel="stylesheet" media="print"/>
<script src="<?=base_url('assets');?>/calendar/moment.min.js"></script>
<script src="<?=base_url('assets');?>/calendar/fullcalendar.min.js"></script>

<!-- lodash -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>


<style> 
	.cs-loader-file{ z-index: 1061 !important; display: block !important; height: 1353px !important; }
	.cs-loader-box{ position: fixed; top: 100px; bottom: 0; right: 0; left: 0; max-width: 300px; margin: auto; z-index: 1539; }
	.cs-loader-box i{ font-size: 14em; color: #81b431; }
	.cs-loader-box div.cs-loader-text{ display: block; padding: 10px; color: #000; background-color: #fff; border-radius: 5px; text-align: center; font-weight: 600; margin-top: 35px; }
	.cs-calendar{ margin-top: 10px; }
	/* Calendar CSS Overrides*/
	.fc-day-header.fc-widget-header { background-color: #81b431; color: #fff; padding: 12px; text-transform: uppercase; }
	.fc-axis,.fc-widget-header { color: #fff; background-color: #4496d2; }
	.fc-basic-view .fc-week-number, .fc-basic-view .fc-day-number { padding: 8px 12px !important; }
	.fc-row .fc-content-skeleton td, .fc-row .fc-helper-skeleton td{ border-color: #e0dfdf !important; }
    .fc-event-cc{ border-width: 20px; border-bottom: 0 !important; border-top: 0 !important; border-right: 0 !important; padding: 5px; margin-bottom: 1px; }
    .fc-more-popover .fc-event-container{ max-height: 303px; overflow: auto; }
    .fc-ltr .fc-popover .fc-header .fc-title{ color: #000000; }
    .fc-unthemed .fc-today{ background: #fcf8e3 !important; }
    /* button in page heading*/
    .js-fix-btn > a{ position: absolute; top: 13px; left: 10px; }
    #js-event-colors td{ text-transform: capitalize; }
    .cs-pill-wrap{ margin-top: 60px; }
    .modal-backdrop { z-index: 1040 !important; } 
    .modal-dialog { z-index: 1100 !important; }
    /* fix of NAV icons for IE*/
    .fc-icon::after{ margin: 0 0 !important; }
    ul.ui-front{ z-index: 9999 !important; }
    /* To set loader precedence */
    /* higher than modal*/
    #my_loader{ z-index: 9999; }
    /* Trianing session tab bg*/
    .btn-event-training-session{ color: #ffffff; background-color: #337ab7; }
    .ui-autocomplete{ z-index: 1234; }
    /*Event tab border*/
    .fc-event-cc{ border-width: 20px; border-bottom: 0 !important; border-top: 0 !important; border-right: 0 !important; padding: 5px; margin-bottom: 1px; }
    /*Added on: 06-05-2019*/
    .fc-more-popover .fc-event-container{ max-height: 303px; overflow: auto; }
    .fc-ltr .fc-popover .fc-header .fc-title{ color: #000000; }
    /**/
	.cs-select{ background: none !important; border: none !important; padding: 0 !important;}
	/**/
	.select2-container{ display: block; }
	/**/
	.control--radio{ display: inline-block; margin-bottom: 10px; }
	/**/
	.form-control{ border-radius: 0 !important; }
    .select2-choices{ z-index: 10; max-height: 50px; overflow-y: auto !important; }
</style>

<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
            	<!-- Side Menu -->
            	<?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
            	<div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
	                <div class="dashboard-content">
	                	<div class="dash-inner-block">
	                		<div class="row">
	                			<!-- Block heading -->
	                			<div class="col-sm-12">
                    				<div class="heading-title page-title">
                                    	<h1 class="page-title"><i class="fa fa-calendar"></i> Calendar</h1>                                        
                                        <a class="btn black-btn pull-right" href="<?=base_url('manage_admin/dashboard');?>"><i class="fa fa-long-arrow-left"></i>Dashboard</a>
                                    </div>   
                                </div>         
                                <!-- Calendar -->
	                			<div class="col-sm-12" style="margin: 30px 0;">
                            		<div id="js-calendar" class="cs-calendar"></div>
                                </div>         
                            	<!-- Calendar Info -->
	                			<div class="col-sm-12">
                            		<div class="cs-info">
                            			<!-- Note -->
                            			<div class="alert alert-info">
					                        <strong>Note:</strong> Below tables represent the meaning of the colors and event status on the calendar.
					                    </div>
					                    <!-- Event ST -->
					                    <div class="row">
					                    	<!-- Event Types -->
					                        <div class="col-sm-6">
					                            <!--  -->
					                            <div class="responsive-table">
					                                <table class="table table-bordered">
					                                    <thead>
					                                        <tr>
					                                            <th>Event Color</th>
					                                            <th>Event Type</th>
					                                        </tr>
					                                    </thead>

					                                    <tbody id="js-event-colors"></tbody>
					                                </table>
					                            </div>
					                        </div>
					                        <div class="col-sm-6">
						                        <!--Event Status  -->
						                        <div class="responsive-table">
						                            <table class="table table-bordered">
						                                <thead>
						                                    <tr>
						                                        <th style="width: 30%;">Event Status Color</th>
						                                        <th>Event Status</th>
						                                    </tr>
						                                </thead>

						                                <tbody id="js-event-status"></tbody>
						                            </table>
						                        </div>
						                        <!-- Event Hints -->
                                                <div class="row" style="margin-bottom: 100px;">
                                                    <div class="col-sm-4 col-xs-8 col-sm-offset-1 col-xs-offset-2">
                                                        <div class="cs-pill-wrap">
                                                            <a 
                                                            class="fc-popover"
                                                                href="javascript:void(0)" 
                                                                data-placement="top" 
                                                                data-content="Event Status"
                                                                style="
                                                                border-left: 20px solid #5cb85c; 
                                                                position: absolute;
                                                                top: 60px;
                                                                left: 0;
                                                                z-index: 1;
                                                                height: 25px;
                                                                margin-right: 0;
                                                                border-radius: 3px 0 0 3px;
                                                            "></a>
                                                            <a 
                                                            class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-event-cc fc-event-cc-confirmed fc-popover" 
                                                            style="background-color:#0000ff;border-color:#0000ff; border-width: 0"  
                                                            data-placement="bottom" 
                                                            data-content="Event Type"
                                                            >
                                                                <div class="fc-content">
                                                                    <span class="fc-time">12:</span> 
                                                                    <span class="fc-title">John Doe, In-Person Interview, Interviewer(s):...</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4 col-xs-8 col-sm-offset-1 col-xs-offset-2">
                                                        <div class="cs-pill-wrap js-event-tab-blinker">
                                                            <a 
                                                                href="javascript:void(0)" 
                                                                style="
                                                                border-left: 20px solid #f0ad4e; 
                                                                position: absolute;
                                                                top: 60px;
                                                                left: 0;
                                                                z-index: 1;
                                                                height: 25px;
                                                                margin-right: 0;
                                                                border-radius: 3px 0 0 3px;
                                                            "></a>
                                                            <a 
                                                            class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-event-cc fc-event-cc-confirmed fc-popover" 
                                                            style="background-color:#b910ff;border-color:#b910ff; border-width: 0"  
                                                            data-placement="bottom" 
                                                            data-content="New Reschedule/Cancellation requests"
                                                            >
                                                                <div class="fc-content">
                                                                    <span class="fc-time">11:</span> 
                                                                    <span class="fc-title">John Doe, Email Interview, Interviewer(s):...</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                            				</div>
                            			</div>
                            		</div>
	                			</div>
	                		</div>
	                	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loader -->
<div class="text-center cs-loader js-loader">
    <div id="file_loader" class="cs-loader-file"></div>
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait while we generate a preview...</div>
    </div>
</div>
	
<!-- Event Modal -->
<?php $this->load->view('manage_admin/calendar/modal'); ?>
<!-- Event Script -->
<?php $this->load->view('manage_admin/calendar/script'); ?>



<script>
    setInterval(function(){$('.js-event-tab-blinker a').css('opacity', $('.js-event-tab-blinker a').css('opacity') != 1 ? 1 : .5);}, 600);
</script>
<!--  -->
<style>
.cs-pill-wrap{ margin-top: 60px; }
.cs-pill-wrap .popover-content{ text-align: center; }
.event-category-dropdown .btn{
    text-align: left !important;
    margin: 0;
}
.event-category-dropdown .dropdown-menu{
    width: 100%;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
    padding: 0;
    border:none;
}
.event-category-dropdown .dropdown-menu .btn{
    margin: 0;
    border: none;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
}
.event-category-dropdown .btn-event-call{
    color: #fff !important;
    background-color: #dd7600 !important;
}
.event-category-dropdown .btn-event-email{
    color: #fff !important;
    background-color: #b910ff !important;
}
.event-category-dropdown .btn-event-meeting{
    color: #fff !important;
    background-color: #0091dd !important;
}
.event-category-dropdown .btn-event-interview{
    color: #fff !important;
    background-color: #0000ff !important;
}
.event-category-dropdown .btn-event-interview-phone{
    color: #fff !important;
    background-color: #1c521d !important;
}
.event-category-dropdown .btn-event-interview-voip{
    color: #fff !important;
    background-color: #0fa600 !important;
}
.event-category-dropdown .btn-event-personal{
    color: #fff !important;
    background-color: #266d55 !important;
}
.event-category-dropdown .btn-event-other{
    color: #fff !important;
    background-color: #7e7b7b !important;
}
.event-category-dropdown .btn .caret{
    float: right;
    margin: 8px 0 0 0;
}
.event-category-dropdown #add_event_selected_category{
    float: left;
}
/* DatePicker Container UI Start
Overwrites*/
.ui-datepicker {
    /* width: 250px; */
    /* height: auto; */
    /* display: none; */
    /* padding: 0; */
    /* border-radius: 0; */
    /* font-family: 'Open Sans', sans-serif; */
    /* -webkit-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5); */
    -moz-box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5);
    /* box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .5); */
}
.ui-datepicker a {
    text-decoration: none;
}
/* DatePicker Table */
.ui-datepicker table {
    background-color: #fff;
    width: 100%;
    margin: 0;
}
.ui-datepicker-header {
    background: #81b431;
    color: #fff;
    font-weight: bold;
    border-radius: 0;
    -webkit-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, 2);
    -moz-box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
    box-shadow: inset 0px 1px 1px 0px rgba(250, 250, 250, .2);
    line-height: 30px;
}
.ui-datepicker-header select{
    color: #000;
}
.ui-datepicker-title {
    text-align: center;
}
.ui-datepicker-prev, .ui-datepicker-next {
    display: inline-block;
    width: 30px;
    height: 30px;
    text-align: center;
    cursor: pointer;
    background-image: url('../images/arrow.png');
    background-repeat: no-repeat;
    line-height: 600%;
    overflow: hidden;
}
.ui-datepicker-prev:hover, .ui-datepicker-next:hover{
    background-color: #518401;
}
.ui-datepicker-prev {
    float: left;
    background-position: center -30px;
}
.ui-datepicker-next {
    float: right;
    background-position: center 0px;
}
.ui-datepicker thead {
    background-color: #f7f7f7;
    background-image: -moz-linear-gradient(top,  #f7f7f7 0%, #f1f1f1 100%);
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f7f7f7), color-stop(100%,#f1f1f1));
    background-image: -webkit-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);
    background-image: -o-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);
    background-image: -ms-linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);
    background-image: linear-gradient(top,  #f7f7f7 0%,#f1f1f1 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7f7f7', endColorstr='#f1f1f1',GradientType=0 );
    border-bottom: 1px solid #bbb;
}
.ui-datepicker th {
    text-transform: uppercase;
    text-align: center;
    padding: 5px 0;
    color: #666666;
    text-shadow: 1px 0px 0px #fff;
    filter: dropshadow(color=#fff, offx=1, offy=0);
}
.ui-datepicker tbody td {
    border-right: 1px solid #bbb;
}
.ui-datepicker tbody td:last-child {
    border-right: 0px;
}
.ui-datepicker tbody tr {
    border-bottom: 1px solid #bbb;
}
.ui-datepicker tbody tr:last-child {
    border-bottom: 0px;
}
.ui-datepicker td span, .ui-datepicker td a {
    display: block;
    font-weight: bold;
    text-align: center;
    height: 31px;
    line-height: 25px;
    color: #666666;
    text-shadow: 1px 1px 0px #fff;
    filter: dropshadow(color=#fff, offx=1, offy=1);
}
.ui-datepicker-calendar .ui-state-default {
    background: #ededed;
    background: -moz-linear-gradient(top,  #ededed 0%, #dedede 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ededed), color-stop(100%,#dedede));
    background: -webkit-linear-gradient(top,  #ededed 0%,#dedede 100%);
    background: -o-linear-gradient(top,  #ededed 0%,#dedede 100%);
    background: -ms-linear-gradient(top,  #ededed 0%,#dedede 100%);
    background: linear-gradient(top,  #ededed 0%,#dedede 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#dedede',GradientType=0 );
    -webkit-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
    -moz-box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
    box-shadow: inset 1px 1px 0px 0px rgba(250, 250, 250, .5);
}
.ui-datepicker-calendar .ui-state-hover {
    background: #f7f7f7;
}
.ui-datepicker-calendar .ui-state-active {
    background: #81b431;
    -webkit-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
    -moz-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
    box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, .1);
    color: #fff;
    text-shadow: 0px 1px 0px #4d7a85;
    filter: dropshadow(color=#4d7a85, offx=0, offy=1);
    border: 1px solid #55838f;
    position: relative;
}
.ui-datepicker-unselectable .ui-state-default {
    background: #f4f4f4;
    color: #b4b3b3;
}
.ui-datepicker-calendar td:first-child .ui-state-active {
    margin-left: 0;
}
.ui-datepicker-calendar td:last-child .ui-state-active {
    height: 31px;
    margin-right: 0;
    margin-bottom: 0;
}
/* DatePicker Container UI End*/
</style>