<!-- Main Start -->
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>My HR Docs</span>
                        </div>
                        <div class="create-job-wrap">
                            <div class="row">
                              <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                  <form enctype="multipart/form-data" method="POST" action="" id="employers_add">
                                      <div class="universal-form-style-v2">
                                        <div class="upload-new-doc-heading">
                                            <i class="fa fa-file-text-o"></i>
                                            Upload a New Document
                                        </div>
                                        <p class="upload-file-type">Upload a .pdf, .doc or .docx to distribute to your employees.</p>
                                        <ul>
                                            <li class="form-col-100 autoheight">
                                                <label>Document Name<span class="staric">*</span></label>
                                                <input type="text" value="" class="invoice-fields">
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Upload <span class="staric">*</span></label>
                                                <div class="upload-file invoice-fields">
                                                    <span id="name_pictures" class="selected-file">No file selected</span>
                                                    <input type="file">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Include in Onboarding<span class="staric">*</span></label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields">
                                                        <option value="">Yes</option>    
                                                        <option value="">Yes</option>   
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    This doc will be available to select/send to new hires as part of the onboarding wizard.
                                                </div>
                                            </li>
                                            <li class="form-col-100 autoheight">
                                                <label>Action Required<span class="staric">*</span></label>
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields">
                                                        <option value="">No</option>    
                                                        <option value="">No</option>   
                                                    </select>
                                                </div>
                                                <div class="help-text">
                                                    If this doc requires a new hire to fill something out and return it to you, select this option and we'll notify the new hire during the onboarding process that this doc requires specific action.
                                                </div>
                                            </li>
                                            <div class="information-text-block no-margin">
                                                <div class="checkbox-field">
                                                    <figure>
                                                        <input type="checkbox" id="background-check" name="" value="">
                                                    </figure>
                                                    <div class="text">
                                                       <label for="background-check"> If this is a document you would like to distribute to your current employees, check this box & we will send them each an email alert and add the doc to their accounts!</label> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-panel">
                                                <input type="submit" class="submit-btn" value="upload">
                                            </div>
                                        </ul>
                                      </div>
                                  </form>
                              </div>
                              <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <div class="tick-list-box">
                                  <h2><?php echo STORE_NAME; ?> is Secure</h2>
                                  <ul>
                                    <li>Transmissions encrypted by VeriSign&reg; SSL</li>
                                    <li>Information treated confidential by AutomotHR</li>
                                    <li>Receive emails with your signed paperwork</li>
                                  </ul>
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
<!-- Main End -->