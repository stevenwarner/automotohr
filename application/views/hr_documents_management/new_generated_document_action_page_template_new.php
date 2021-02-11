<style>
input[type='checkbox'].user_checkbox {
	margin-top: -30px;
}

input[type='checkbox'].user_checkbox {
	-webkit-font-smoothing: antialiased;
	text-rendering: optimizeSpeed;
	width: 25px;
	height: 25px;
	margin: 0;
	margin-right: 10px !important;
	display: block;
	float: left;
	position: relative;
	cursor: pointer;
}

input[type='checkbox'].user_checkbox:after {
	content: "";
	vertical-align: middle;
	text-align: center;
	line-height: 25px;
	position: absolute;
	cursor: pointer;
	height: 25px;
	width: 25px;
	left: 0;
	top: 0;
	font-size: 14px;
	background: #999999;
}

input[type='checkbox'].user_checkbox:hover:after, input[type='checkbox'].user_checkbox:checked:hover:after {
	background: #999999;
	content: '\2714';
	color: #fff;
}

input[type='checkbox'].user_checkbox:checked:after {
	background: #999999;
	content: '\2714';
	color: #fff;
}
</style>

<div class="row" id="jsContentArea" style="width: 800px;">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <?php if ($request_type == 'submitted' && $is_iframe_preview == 1) { ?>
            <canvas id="the-canvas" style="border:1px  solid black"></canvas>
        <?php } else { ?>
            <?php echo html_entity_decode($document_contant); ?>
        <?php } ?> 
    </div>
</div>