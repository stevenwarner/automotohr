<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <h4>Approval Document</h4>
                    </div>
                    <div class="col-sm-6">
                        
                    </div>
                </div>
                <br />       
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row" style="margin: 5px 5px;">
                            <div class="col-lg-6">Document Title</div>
                            <div class="col-lg-6" style="padding: 6px; font-weight: 700;"><?php echo $document_title; ?></div>
                        </div>
                        <div class="row" style="margin: 5px 5px;">
                            <div class="col-lg-6">Document Type</div>
                            <div class="col-lg-6" style="padding: 6px; font-weight: 700;"><?php echo $document_type; ?></div>
                        </div>
                    </div>    
                    <div class="col-lg-6">
                        <div class="row" style="margin: 5px 5px;">
                            <div class="col-lg-6">User Name</div>
                            <div class="col-lg-6" style="padding: 6px; font-weight: 700;">
                                <?php 
                                    if ($document_info['user_type'] == "employee") {
                                        echo getUserNameBySID($document_info['user_sid']);
                                    } else {
                                        echo getApplicantNameBySID($document_info['user_sid']);
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="row" style="margin: 5px 5px;">
                            <div class="col-lg-6">User Type</div>
                            <div class="col-lg-6" style="padding: 6px; font-weight: 700;"><?php echo ucfirst($document_info['user_type']); ?></div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="csLisitingArea"> 
                        <div class="csBoxWrap jsBoxWrap">   
                            <table class="table table-plane cs-w4-table">
                                <thead>
                                    <tr>
                                        <th class="col-lg-4">Employee Name</th>
                                        <th class="col-lg-2">Assign On</th>
                                        <th class="col-lg-2">Assign By</th>
                                        <th class="col-lg-2">Approver Status</th>
                                        <th class="col-lg-2">Action Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($assigners) && !empty($assigners)) { ?>
                                        <?php foreach ($assigners as $assigner) { ?>
                                            <tr>
                                                <td>
                                                    <?php 
                                                        echo getUserNameBySID($assigner['assigner_sid']);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if (isset($assigner['assign_on']) && $assigner['assign_on'] != '0000-00-00 00:00:00') {
                                                            echo reset_datetime(array('datetime' => $assigner['assign_on'], '_this' => $this));
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        echo getUserNameBySID($document_info['assigned_by']);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        echo $assigner['approval_status'];
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if (isset($assigner['action_date']) && $assigner['action_date'] != '0000-00-00 00:00:00') {
                                                            echo reset_datetime(array('datetime' => $assigner['action_date'], '_this' => $this));
                                                        }
                                                    ?>
                                                </td>
                                            </tr>           
                                        <?php } ?>    
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="7" class="col-lg-12 text-center"><b>No Approval Document(s) Assign!</b></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>    
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php 
                            if ($document_type == "uploaded") {
                                $this->load->view('Hr_documents_management/approver_document_info');
                                
                            } else if ($document_type == "generated") {
                                $this->load->view('Hr_documents_management/generate_new_document');
                            } else if ($document_type == "offer_letter") {
                                $this->load->view('Hr_documents_management/generate_new_offer_letter');
                            }
                        ?>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>        