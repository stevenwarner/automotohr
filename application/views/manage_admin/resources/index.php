<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-address-book"></i>Resources</h1>
                                    </div>

                                    <div class="hr-box">
                                        <div class="hr-box-header">
                                            <span class="pull-right">
                                                <a class="btn btn btn-success" href = "<?php echo base_url('manage_admin/edit_resource/0') ?>">Add Resource</a>
                                                <a class="btn btn btn-success" href = "<?php echo base_url('manage_admin/subscribers_list/' . $page['sid']) ?>">View Subscribers</a>

                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-3">Title</th>
                                                                    <th class="col-xs-2">Slug</th>
                                                                    <th class="col-xs-2">Type</th>
                                                                    <th class="col-xs-3">Created At</th>
                                                                    <th class="col-xs-3">Status</th>
                                                                    <th class="col-xs-1" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="jsDraggable">
                                                                <?php foreach ($pages_data as $dataRow) { ?>
                                                                    <tr class="jsResourcesSortOrder" data-key="<?= $dataRow['sid']; ?>">
                                                                        <td><?php echo $dataRow['title']; ?></td>
                                                                        <td><?php echo $dataRow['slug']; ?></td>
                                                                        <td><?php echo $dataRow['resource_type']; ?></td>
                                                                        <td>
                                                                            <?php echo  date_with_time($dataRow['created_at']); ?>
                                                                        </td>
                                                                        <td class=" <?php echo  $dataRow['status'] ==1 ? ' text-success' :' text-danger' ?> "> <strong><?php echo  strtoupper($dataRow['status'] ==1 ? "Published" :"Unpublished"); ?></strong></td>
                                                                        <td><a class="btn btn btn-success btn-sm" href = "<?php echo base_url('resources/' . $dataRow['slug']) ?>">View</a></td>
                                                                        <td>
                                                                        <a class="btn btn-success btn-sm" href = "<?php echo base_url('manage_admin/edit_resource/' . $dataRow['sid']) ?> "><i class="fa fa-pencil"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
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

<script>
    $(".jsDraggable").sortable({
		update: function(event, ui) {
			//
			var orderList = [];
			//
			$(".jsResourcesSortOrder").map(function (i) {
				orderList.push($(this).data("key"));
			});
			// 
			var obj = {};
			obj.sortOrders = orderList;
			//
			updateCardsSortOrder(obj);
		}
	});

    	//
	function updateCardsSortOrder(data) {
        console.log(data)
		// check if XHR already in progress
		if (XHR !== null) {
			XHR.abort();
		}
		//
		XHR = $.ajax({
			url: baseUrl("manage_admin/resources/update_sort_order"),
			method: "post",
			data,
		})
			.always(function () {
				XHR = null;
			})
			.fail(handleErrorResponse)
			.done(function (resp) {
				
			});
	}
</script>