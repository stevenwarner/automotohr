<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management'); ?>"><i class="fa fa-chevron-left"></i>Documents Management</a>
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                </div>
                            </div>
                            
                            <hr />
                        </div>

                    
                        <form id="form_new_document" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                        <div class="col-sm-12" >
        <div class="panel panel-default" style="position: relative;">
            <div class="panel-heading">
                <span><strong>General Document(s)</strong></span>
            </div>
            <div class="panel-body" style="min-height: 200px;">
               <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="overflow: hidden;">
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th class="text-center">Is this document required?</th>
                               <Input Type="hidden" value="yes" name="perform_action">
                               
                            </tr>
                        </thead>
                
                <tbody>

              <?php 
         
             if(empty($manage_general_documents)){
                $uncheckedDep = "checked='true'";
                $uncheckedDir = "checked='true'";
                $uncheckedDri = "checked='true'"; 
                $uncheckedEme = "checked='true'";
                $uncheckedOcc = "checked='true'";  
             }

              foreach ($manage_general_documents as $row_manage_general_documents){
              if($row_manage_general_documents->document_name=="Dependents" && $row_manage_general_documents->is_required==1){
                $checkedDep = "checked='true'";
              }else if ($row_manage_general_documents->document_name=="Dependents" && $row_manage_general_documents->is_required==0){
                $uncheckedDep = "checked='true'";  
             }

             if($row_manage_general_documents->document_name=="Direct Deposit Information" && $row_manage_general_documents->is_required==1){
                $checkedDir = "checked='true'";
              }else if ($row_manage_general_documents->document_name=="Direct Deposit Information" && $row_manage_general_documents->is_required==0){
                $uncheckedDir = "checked='true'";  
             } 

             if($row_manage_general_documents->document_name=="Drivers License Information" && $row_manage_general_documents->is_required==1){
                $checkedDri = "checked='true'";
              }else if ($row_manage_general_documents->document_name=="Drivers License Information" && $row_manage_general_documents->is_required==0){
                $uncheckedDri = "checked='true'";  
             } 

             if($row_manage_general_documents->document_name=="Emergency Contacts" && $row_manage_general_documents->is_required==1){
                $checkedEme = "checked='true'";
              }else if ($row_manage_general_documents->document_name=="Emergency Contacts" && $row_manage_general_documents->is_required==0){
                $uncheckedEme = "checked='true'";  
             } 

             if($row_manage_general_documents->document_name=="Occupational License Information" && $row_manage_general_documents->is_required==1){
                $checkedOcc = "checked='true'";
              }else if ($row_manage_general_documents->document_name=="Occupational License Information" && $row_manage_general_documents->is_required==0){
                $uncheckedOcc = "checked='true'";  
             } 
          
          }

     ?>


                    <tr>
                        <td>Dependents
                        <Input Type="hidden" value="Dependents" name="generaldocs[]">
                        </td>
                           <td class="text-center">
                    <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredDep" type="radio" value="0" <?php echo $uncheckedDep ?>>
                                            No &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredDep" type="radio" value="1" <?php echo $checkedDep;?> >
                                            Yes &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                        </td>
                        
                    </tr>
                
                    <tr >
                        <td>Direct Deposit Information
                        <Input Type="hidden" value="Direct Deposit Information" name="generaldocs[]">
                        </td>
                        <td class="text-center">
                        <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredDir" type="radio" value="0" <?php echo $uncheckedDir;?>>
                                            No &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredDir" type="radio" value="1" <?php echo $checkedDir;?>>
                                            Yes &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                        </td>
                    </tr>
                
                    <tr >
                        <td>Drivers License Information
                        <Input Type="hidden" value="Drivers License Information" name="generaldocs[]">
                        </td>
                        <td class="text-center">
                        <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredDri" type="radio" value="0" <?php echo $uncheckedDri;?>>
                                            No &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredDri" type="radio" value="1" <?php echo $checkedDri;?>>
                                            Yes &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                        </td>
                    </tr>
                
                    <tr >
                        <td>Emergency Contacts
                        <Input Type="hidden" value="Emergency Contacts" name="generaldocs[]">
                        </td>
                        <td class="text-center">
                        <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredEme" type="radio" value="0" <?php echo $uncheckedEme;?>>
                                            No &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredEme" type="radio" value="1" <?php echo $checkedEme;?>>
                                            Yes &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                        </td>
                    </tr>
                
                    <tr >
                        <td>Occupational License Information
                        <Input Type="hidden" value="Occupational License Information" name="generaldocs[]">
                        </td>
                        <td class="text-center">
                        <div class="row">
                                    <div class="col-xs-12">
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredOcc" type="radio" value="0" <?php echo $uncheckedOcc?>>
                                            No &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio font-normal">
                                            <input class="disable_doc_checkbox" name="isRequiredOcc" type="radio" value="1" <?php echo $checkedOcc?>>
                                            Yes &nbsp;
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                        </td>
                    </tr>
                </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <button type="submit" id="gen_boc_btn" class="btn btn-success">Save</button>
         <a href="http://automotohr.local/hr_documents_management" class="btn black-btn">Cancel</a>
        </div>

</form>


                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
