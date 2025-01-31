<script src="https://maps.google.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY ?>" type="text/javascript"></script>

<div class="csPageWrap" style="background-color: #f1f1f1;">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('attendance/2022/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="row">
        <div class="container-fluid">
            <!-- Side Bar -->
            <?php $this->load->view('employee_info_sidebar_ems'); ?>
            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Heading -->
                <div class="row">
                    <div class="col-xs-12 col-md-8">
                        <h1 class="m0 p0 csB7">
                            Employee Map Location
                        </h1>
                    </div>

                </div>
                <!--  -->
                <p class="csF14 csB4 pa10"><?php echo formatDateToDB($from_date, DB_DATE, DATE); ?> - <?= formatDateToDB($to_date, DB_DATE, DATE); ?></p>
                <!--  -->
                <div class="csPageBox">
                    <div class="csPageBody">
                        <div class="row">
                            <div class="col-sm-12">
                                <h2 class="csF20 csB7 pl10">Advance Filter</h2>
                            </div>
                        </div>
                        <form action="<?= current_url(); ?>" method="GET">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="p10">
                                        <label class="label csF16 csB7 pl0">Select Employee(s)</label>
                                        <select name="id[]" id="jsSpecificEmployees" class="form-control">
                                            <option value="0">[Please Select]</option>
                                            <?php if (!empty($employees)) : ?>
                                                <?php foreach ($employees as $emp) : ?>
                                                    <option <?= in_array($emp['sid'], $selected_employee_ids) ? 'selected="true"' : ''; ?> value="<?= $emp['sid']; ?>"><?= $emp['name']; ?><?= $emp['role']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="p10">
                                        <label class="label csF16 csB7 pl0">From</label>
                                        <input type="text" class="form-control jsDatePicker" name="from" readonly required value="<?= formatDateToDB($from_date, DB_DATE, 'm/d/Y'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="p10">
                                        <label class="label csF16 csB7 pl0">To</label>
                                        <input type="text" class="form-control jsDatePicker" name="to" readonly required value="<?= formatDateToDB($to_date, DB_DATE, 'm/d/Y'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 text-right">
                                    <div class="p10">
                                        <label class="label csF16 csB7">&nbsp;</label> <br>
                                        <button class="btn btn-orange" type="submit"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search</button>
                                        <a href="<?= base_url('attendance/maplocation'); ?>" class="btn btn-black" type="clear"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Clear Filter</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12 text-center">
                        <div class="csPageBox csRadius5">
                            <div class="csPageBoxBody">
                                <div class="table-responsive">

                                    <?php
                                    if (!empty($lists)) {
                                        $locations = json_encode($lists);
                                        $initalLatLong = explode(',', $lists[0]);
                                    ?>

                                        <div id="map" style="width: 100%; height: 500px;"></div>

                                        <script type="text/javascript">
                                            var locations = <?= json_encode($lists) ?>;

                                            var map = new google.maps.Map(document.getElementById('map'), {
                                                zoom: 10,
                                                center: new google.maps.LatLng(<?= $initalLatLong[0] ?>, <?= $initalLatLong[1] ?>),
                                                mapTypeId: google.maps.MapTypeId.ROADMAP
                                            });

                                            var infowindow = new google.maps.InfoWindow();

                                            var marker, i;
                                            for (i = 0; i < locations.length; i++) {
                                                var locationsnew = locations[i].split(',');
                                                marker = new google.maps.Marker({
                                                    position: new google.maps.LatLng(locationsnew[0], locationsnew[1]),
                                                    map: map
                                                });


                                                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                                    return function() {

                                                        infowindow.setContent(locationsnew[0] + ', ' + locationsnew[1]);
                                                        infowindow.open(map, marker);
                                                    }
                                                })(marker, i));
                                            }
                                        </script>
                                    <?php } else { ?>
                                        <br><b>Location Record Not Found</b>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>