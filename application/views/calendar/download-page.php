

<style>
	.main-content > div{ margin-top: 100px; }
	.main-content h3 i{ font-size: 150px; color: #81b431; }
	.main-content p{ padding-top: 10px; font-size: 16px; }
	.main-content p a{ color: #81b431; font-weight: 900; text-decoration: underline; }
</style>
<!-- TODO  -->
<div class="row">
	<div class="main-content" style="min-height: 500px;">
		<div class="col-sm-4 col-sm-offset-4">
			<h3 class="text-center">
				<i class="fa fa-calendar"></i>
			</h3>
			<p class="text-center"><a href="javascript:download_file()">Click here</a> to re-download the file</p>
		</div>
    </div>
</div>

<?php if($type == 'ics' || $type == 'vcs') { ?>
	<script>
		download_file();
		function download_file(){
			window.location.href = "<?=base_url('download');?>/<?=$type?>/<?=$event_sid;?>/<?=$uid;?>/<?=$utype;?>/<?=urlencode(strtolower($uname));?>/<?=urlencode(strtolower($uemail));?>";
		}
	</script>
<?php } ?>