<form action="javascript:void(0)" id="jsPageJobSiteForm">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="text-medium panel-heading-text">
                    <i class="fa fa-save text-orange" aria-hidden="true"></i>
                    Add Job Site
                </h2>
            </div>
            <div class="panel-body">
                <!--  -->
                <div class="form-group">
                    <!-- name -->
                    <label class="text-medium">
                        Name
                        <strong class="text-red">*</strong>
                    </label>
                    <input type="text" class="form-control" name="site_name" />
                </div>

                <!--  -->
                <div class="form-group">
                    <!-- name -->
                    <label class="text-medium">
                        Street 1
                        <strong class="text-red">*</strong>
                    </label>
                    <input type="text" class="form-control" name="street_1" />
                </div>

                <!--  -->
                <div class="form-group">
                    <!-- name -->
                    <label class="text-medium">
                        Street 2
                    </label>
                    <input type="text" class="form-control" name="street_2" />
                </div>

                <!--  -->
                <div class="form-group">
                    <!-- name -->
                    <label class="text-medium">
                        City
                        <strong class="text-red">*</strong>
                    </label>
                    <input type="text" class="form-control" name="city" />
                </div>

                <!--  -->
                <div class="form-group">
                    <!-- state -->
                    <label class="text-medium">
                        State
                        <strong class="text-red">*</strong>
                    </label>
                    <select class="form-control" name="state">
                        <?php if ($states) {
                            foreach ($states as $v0) {
                        ?>
                                <option value="<?= $v0["sid"]; ?>"><?= $v0["state_code"]; ?></option>
                        <?php
                            }
                        } ?>
                    </select>
                </div>

                <!--  -->
                <div class="form-group">
                    <!-- zip code -->
                    <label class="text-medium">
                        Zip Code
                        <strong class="text-red">*</strong>
                    </label>
                    <input type="number" class="form-control" name="zip_code" />
                </div>

                <!--  -->
                <div class="form-group">
                    <!-- radius -->
                    <label class="text-medium">
                        How large is your workplace?
                        <strong class="text-red">*</strong>
                    </label>
                    <p class="text-small text-red">
                        Select a radius for your employees to clock in and clock out of this location. For best accuracy, ensure that the circle covers your entire physical address and provides a buffer around the location to account for minor GPS discrepancies.
                    </p>
                    <div class="input-group">
                        <input type="number" class="form-control" name="site_radius" value="500" />
                        <div class="input-group-addon">meters</div>
                    </div>
                </div>

                <!--  -->
                <div class="form-group">
                    <!-- name -->
                    <label class="text-medium">
                        Review and adjust workplace location
                        <strong class="text-red">*</strong>
                    </label>
                    <p class="text-small text-red">
                        Drag the pin on the map below to the center of your physical location. Then, ensure that the green circle in the map below covers the entire physical area of this workplace. Team members will be able to clock in anywhere within the green circle. We recommend providing a buffer around this workplace to account for minor and common geolocation discrepancies on team members’ devices.
                    </p>
                    <div id="job_site_map" style="width: 100%; height: 400px;">
                        <p class="alert alert-danger">
                            Please select the address first
                        </p>
                    </div>

                </div>
            </div>
            <!--  -->
            <div class="panel-footer text-right">
                <button class="btn btn-orange jsPageJobSiteBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save Job site
                </button>
                <button class="btn btn-black jsModalCancel" type="button">
                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                    &nbsp;Cancel
                </button>
            </div>
        </div>

    </div>
</form>