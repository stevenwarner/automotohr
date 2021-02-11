<div class="col-sm-4">
	<span class="pull-right">
		<div class="cs-timeoff-box">
			<ul>
				<li><b>Time Off</b></li>
				<li><b id="js-timeoff-pending">0</b> <span>Hours Pending</span></li>
				<li><b id="js-timeoff-approved">0</b> <span>Hours Approved</span></li>
				<li><b id="js-timeoff-allowed">0</b> <span>Hours Allowed</span></li>
			</ul>
		</div>
		
	</span>
</div>

<style>
	.cs-timeoff-box ul{
		list-style: none;
	}
	.cs-timeoff-box ul li{
		text-align: left;
		color: #fff;
		font-size: 19px;
	}
	.cs-timeoff-box ul li b{
		font-weight: bold;
		font-size: 22px;
	}
</style>

<script>
	$(function(){
		fetchMatricsList1();
		 //
        function fetchMatricsList1(){
            $.post("<?=base_url('timeoff/handler');?>", {
                action: 'get_employee_policies_status',
                employeeSid: <?=$employer['sid'];?>,
                companySid: <?=$session['company_detail']['sid'];?>
            }, function(resp){

                $('#js-timeoff-allowed').text(resp.Data.Total.active.hours);
                $('#js-timeoff-approved').text(resp.Data.Consumed.active.hours);
                $('#js-timeoff-pending').text(resp.Data.Pending.active.hours);
            });
        }
	})
</script>