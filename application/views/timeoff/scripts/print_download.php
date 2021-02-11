<script>
	function getPrintDownloadButtons(
		requestSid,
		panel
	){
		var 
		urls = {
			print: '<?=base_url('timeoff/public/pd/print');?>/'+requestSid,
			download: '<?=base_url('timeoff/public/pd/download');?>/'+requestSid
		},
		rows = '';
		//
		panel = panel === undefined ? 'gp' : panel;
		//
		rows += '<a href="'+( urls.download )+'" class="pull-right" style="color: #0000ff;" target="_blank"><i class="fa fa-download" title="Download Time Off"></i></a>';
		rows += '<a href="'+( urls.print )+'" class="pull-right" style="color: #0000ff;" target="_blank"><i class="fa fa-print" title="Print Time Off"></i></a>';

		return rows;
	}
</script>