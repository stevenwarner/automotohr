<html>
    <head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/default/css/design.css'); ?>">
    <body style="background: #fff !important; font-size: 11px; ">
    <body style="background: #fff !important">
        <div class="printPage">
            <div class="printLeft">
                <h2>Company Info</h2>
                <?php if (!empty($company_details["Logo"])) { ?>
                            <img src="<?php echo AWS_S3_BUCKET_URL . $company_details["Logo"] ?>" width="150"><br/><br/>
                <?php   } ?>
                            
                <span class="strong"><?=$company_details["CompanyName"]?></span>
                
                <?php if (!empty($company_details["address"])) { ?><br/><?=$company_details["address"]; } ?>
                <?php if (!empty($company_details["phone"])) { ?><br/>Phone: <?=$company_details["phone"]; } ?>
                <?php if (!empty($company_details["email"])) { ?><br/>Email: <?=$company_details["email"]; } ?>
                <?php if (!empty($company_details["server_name"])) { ?><br/><?php echo STORE_PROTOCOL . $company_details["server_name"]; } ?>
            </div>
            <div class="printRight">
                <h1><?= $listing['Title'] ?></h1>
                <div  class="narrow-col">
                    <fieldset id="col-narrow-left" class="active-fields sortable-column">
                        <legend class="fh-legend">col-narrow-left&nbsp;</legend>
                        <span class="fh-status">&nbsp;</span>
                        <div class="displayFieldBlock">
                            <h3>Job ID:</h3>
                            <div class="displayField"><?= $listing['sid'] ?></div>
                        </div>
                        <?php   if (!empty($listing['Location_State']) && !empty($listing['Location_Country'])) { ?>
                                    <div class="displayFieldBlock">
                                        <h3>Location:</h3>
                                        <div class="displayField">
                                            <?= $listing['Location_State'] ?>, <?= $listing['Location_Country'] ?>
                                        </div>
                                    </div>
                        <?php   }
                        
                        if (!empty($listing['JobCategory'])) { ?>
                            <div class="displayFieldBlock">
                                <h3>Category:</h3>
                                <div class="displayField"><?= $listing['JobCategory'] ?></div>
                            </div>
                        <?php }
                        
                        if (!empty($listing['Salary'])) { ?>
                            <div class="displayFieldBlock">
                                <h3>Salary:</h3>
                                <div class="displayField">
                                    $<?= $listing['Salary'] ?> <?= $listing['SalaryType'] ?>																																																									</div>
                            </div>
                        <?php } ?>
                    </fieldset>
                    <div class="clr"></div>
                </div>
                <div  class="narrow-col">
                    <fieldset id="col-narrow-right" class="active-fields sortable-column">
                        <legend class="fh-legend">col-narrow-right&nbsp;</legend>
                        <span class="fh-status">&nbsp;</span>
                        <div class="displayFieldBlock">
                            <h3>Job Views:</h3>
                            <div class="displayField"><?= $listing['views'] ?></div>
                        </div>
                        <?php if (!empty($listing['Location_ZipCode'])) { ?>
                                    <div class="displayFieldBlock">
                                        <h3>Zip Code:</h3>
                                        <div class="displayField"><?= $listing['Location_ZipCode'] ?></div>
                                    </div>
                        <?php }
                        
                        if (!empty($listing['activation_date'])) { ?>
                                <div class="displayFieldBlock">
                                    <h3>Posted:</h3>
                                    <div class="displayField"><?= $listing['activation_date'] ?></div>
                                </div>
                        <?php } ?>
                    </fieldset>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
                <div class="clr"></div>
                <fieldset id="col-wide" class="active-fields sortable-column">
                    <legend class="fh-legend">col-wide&nbsp;</legend>
                    <span class="fh-status">&nbsp;</span>
                    <div class="displayFieldBlock">
                        <h3>Job Description:</h3>
                        <div class="displayField"><span style="font-size:16px;"><?= $listing['JobDescription'] ?></span></div>
                    </div>
                    <?php if (!empty($listing['JobRequirements'])) { ?>
                        <div class="displayFieldBlock">
                            <h3>Job Requirements:</h3>
                            <div class="displayField"><span style="font-size:16px;"><?= $listing['JobRequirements'] ?></span></div>
                        </div>
                    <?php } ?>
                </fieldset>
                <div class="clr"></div>
            </div>
        </div>
        <div id="print-button"><input type=button value="Print This Ad" onClick="window.print();
                window.close();
                return false;" class="standart-button" /></div>
    </body>
</html>