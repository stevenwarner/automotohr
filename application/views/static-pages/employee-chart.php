<script type="text/javascript" src="<?= base_url() ?>assets/chart-files/prettify.js"></script>

<!-- jQuery includes -->
<script type="text/javascript" src="<?= base_url() ?>assets/chart-files/jquery_002.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/chart-files/jquery-ui.js"></script>

<script src="<?= base_url() ?>assets/chart-files/org-chart.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $("#org").jOrgChart({
            chartElement : '#chart',
            dragAndDrop  : false
        });
    });
</script>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="employee-scheduling">
                    <div class="table-responsive scheduling">
                        <div class="scheduling-inner">
                            <ul id="org" style="display: none;">
                                <li>
                                   <div class="employee-box">
                                        <a href="javascript:;">
                                        <figure>
                                            <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                        </figure>
                                        <div class="text">
                                            <p class="employee-name">Parent</p>
                                            <span>Designation</span>
                                        </div>
                                        </a> 
                                    </div>
                                   <ul>
                                     <li id="beer">
                                        <div class="employee-box">
                                            <a href="javascript:;">
                                            <figure>
                                                <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                            </figure>
                                            <div class="text">
                                                <p class="employee-name">child-1</p>
                                                <span>Designation</span>
                                            </div>
                                            </a> 
                                        </div>
                                     </li>
                                     <li>
                                        <div class="employee-box">
                                            <a href="javascript:;">
                                            <figure>
                                                <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                            </figure>
                                            <div class="text">
                                                <p class="employee-name">child-2</p>
                                                <span>Designation</span>
                                            </div>
                                            </a> 
                                        </div>
                                       <ul>
                                         <li>
                                            <div class="employee-box">
                                                <a href="javascript:;">
                                                <figure>
                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                </figure>
                                                <div class="text">
                                                    <p class="employee-name">Admin</p>
                                                    <span>Designation</span>
                                                </div>
                                                </a> 
                                            </div>
                                         </li>
                                         <li>
                                            <div class="employee-box">
                                                <a href="javascript:;">
                                                <figure>
                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                </figure>
                                                <div class="text">
                                                    <p class="employee-name">Admin</p>
                                                    <span>Designation</span>
                                                </div>
                                                </a> 
                                            </div>
                                         </li>
                                       </ul>
                                     </li>
                                     <li class="fruit">
                                        <div class="employee-box">
                                            <a href="javascript:;">
                                            <figure>
                                                <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                            </figure>
                                            <div class="text">
                                                <p class="employee-name">child-3</p>
                                                <span>Designation</span>
                                            </div>
                                            </a> 
                                        </div>
                                       <ul>
                                         <li>
                                            <div class="employee-box">
                                                <a href="javascript:;">
                                                <figure>
                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                </figure>
                                                <div class="text">
                                                    <p class="employee-name">Admin</p>
                                                    <span>Designation</span>
                                                </div>
                                                </a> 
                                            </div>
                                           <ul>
                                             <li>
                                                <div class="employee-box">
                                                    <a href="javascript:;">
                                                    <figure>
                                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                    </figure>
                                                    <div class="text">
                                                        <p class="employee-name">Admin</p>
                                                        <span>Designation</span>
                                                    </div>
                                                    </a> 
                                                </div>
                                             </li>
                                           </ul>
                                         </li>
                                         <li>
                                            <div class="employee-box">
                                                <a href="javascript:;">
                                                <figure>
                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                </figure>
                                                <div class="text">
                                                    <p class="employee-name">Admin</p>
                                                    <span>Designation</span>
                                                </div>
                                                </a> 
                                            </div>
                                           <ul>
                                             <li>
                                                <div class="employee-box">
                                                    <a href="javascript:;">
                                                    <figure>
                                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                    </figure>
                                                    <div class="text">
                                                        <p class="employee-name">Admin</p>
                                                        <span>Designation</span>
                                                    </div>
                                                    </a> 
                                                </div>
                                             </li>
                                             <li>
                                                <div class="employee-box">
                                                    <a href="javascript:;">
                                                    <figure>
                                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                    </figure>
                                                    <div class="text">
                                                        <p class="employee-name">Admin</p>
                                                        <span>Designation</span>
                                                    </div>
                                                    </a> 
                                                </div>
                                             </li>
                                             <li>
                                                <div class="employee-box">
                                                    <a href="javascript:;">
                                                    <figure>
                                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                    </figure>
                                                    <div class="text">
                                                        <p class="employee-name">Admin</p>
                                                        <span>Designation</span>
                                                    </div>
                                                    </a> 
                                                </div>
                                             </li>
                                           </ul>
                                         </li>
                                       </ul>
                                     </li>
                                     <li>
                                        <div class="employee-box">
                                            <a href="javascript:;">
                                            <figure>
                                                <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                            </figure>
                                            <div class="text">
                                                <p class="employee-name">child-4</p>
                                                <span>Designation</span>
                                            </div>
                                            </a> 
                                        </div>
                                     </li>
                                     <li class="collapsed">
                                        <div class="employee-box">
                                            <a href="javascript:;">
                                            <figure>
                                                <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                            </figure>
                                            <div class="text">
                                                <p class="employee-name">child-5</p>
                                                <span>Designation</span>
                                            </div>
                                            </a> 
                                        </div>
                                       <ul>
                                         <li>
                                            <div class="employee-box">
                                                <a href="javascript:;">
                                                <figure>
                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                </figure>
                                                <div class="text">
                                                    <p class="employee-name">Admin</p>
                                                    <span>Designation</span>
                                                </div>
                                                </a> 
                                            </div>
                                         </li>
                                         <li>
                                            <div class="employee-box">
                                                <a href="javascript:;">
                                                <figure>
                                                    <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                </figure>
                                                <div class="text">
                                                    <p class="employee-name">Admin</p>
                                                    <span>Designation</span>
                                                </div>
                                                </a> 
                                            </div>
                                         </li>
                                       </ul>
                                     </li>
                                   </ul>
                                 </li>
                            </ul>
                            <div id="chart" class="orgChart"></div>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>