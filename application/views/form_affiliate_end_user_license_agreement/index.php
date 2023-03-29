<?php $this->load->view('main/static_header'); ?>
<body>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php
                    $readonly = '';
                    if ($company_document['status'] == 'signed' && $is_pre_fill == 0) {
                        $readonly = ' readonly="readonly" ';
                    }
                    ?>
                    <div class="end-user-wrp-outer">
                        <div class="top-logo text-center">
                            <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                        </div>
                        <!-- page print -->
                        <?php //if($is_pre_fill == 0) { ?>
                        <div class="top-logo" id="print_div">
                            <?php if(isset($pre_fill_flag)){?>
                                <a href="<?php echo base_url('manage_admin/marketing_agencies/edit_marketing_agency/'.$company_document['marketing_agencies_sid']);?>" class="btn btn-success"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
<!--                                <input type="button" value="Send Agreement" class="btn btn-success" onclick="func_send_affiliate_agreement();">-->
                            <?php }?>
                            <a href="javascript:;" class="btn btn-success affiliate_end_user_agreement_color_btn" onclick="print_page('.container');"><i class="fa fa-print" aria-hidden="true"></i> Print or Save</a>
                        </div>
                        <?php //} ?>
                        <!-- page print -->
                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">AFFILIATE END USER LICENSE AGREEMENT</span>
                        <div class="end-user-agreement-wrp">
                            <input type="hidden" name="page_url" id="page_url" value="<?php echo $this->uri->segment(1); ?>">
                            <form action="<?php echo base_url('form_affiliate_end_user_license_agreement' . '/' . $verification_key) . ( $is_pre_fill == 1 ? '/pre_fill' : '' ); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo isset($company_document['marketing_agencies_sid']) ? $company_document['marketing_agencies_sid'] : $company_document['affiliate_sid']; ?>" />
                                <input type="hidden" id="is_pre_fill" name="is_pre_fill" value="<?php echo $is_pre_fill; ?>" />
                                <div>End User License Agreement (this “Agreement”) by and between <?php echo STORE_NAME; ?> / AutomotoSocial LLC (“COMPANY”) and <div class="form-outer company-name" style="max-width:700px;"><input type="text" class="invoice-fields" name="the_entity" id="the_entity" value="<?php echo set_value('the_entity', $company_document['the_entity']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('the_entity'); ?></div>, an entity located at <div class="form-outer" style="max-width: 700px !important; display: inline-block;" ><input style="width: 700px !important; max-width: 100% !important;" type="text" class="invoice-fields" name="the_client" id="the_client" value="<?php echo set_value('the_client', $company_document['the_client']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('the_client'); ?></div>   (the “CLIENT”). COMPANY and AFFILIATE are sometimes referred to herein collectively as the “parties” or individually as a “party.”</div>
                               
<!--                            <p><strong>A.</strong>  &nbsp;&nbsp;&nbsp;&nbsp;COMPANY provides, among other things, <?php echo STORE_NAME; ?> / AutomotoSocial LLC, which are web platforms for both entities/employers looking to hire employees in the automotive industry and potential job candidates looking for potential jobs in the automotive industry.  <?php echo STORE_NAME; ?> / AutomotoSocial LLC were created with the purpose of providing a dynamic network of jobseekers and employers in the automotive industry in the United States and Canada (“SOFTWARE”).  SOFTWARE includes COMPANY’s software, documentation, training, and support as described below.</p>-->
                                <p>This <?php echo STORE_NAME; ?> / AutomotoSocial LLC Affiliate Referral Agreement (“Agreement”) is made by and between <?php echo STORE_NAME; ?> / AutomotoSocial LLC, a limited liability company with offices located at 23522 Cutter Dr Canyon Lake, Ca. 92587  ("<?php echo STORE_NAME; ?>"), and the above-named party (“Affiliate”). The Agreement is effective as of the date set forth above (the “<?= isset($company_document['company_date']) ? date('m-d-Y', strtotime(str_replace('-', '/', $company_document['company_date']))) : date('m-d-Y'); ?>”).</p>
                                <h3>Now, therefore, <?php echo STORE_NAME; ?> and Company hereby agree as follows:</h3>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <p><strong>1. PARTICIPATION IN AFFILIATE REFERRAL PROGRAM.</strong> </p>
                                    <p>Company shall submit leads to <?php echo STORE_NAME; ?> via the “Affiliate Referral Registration Form” located at <a href="<?php echo base_url('schedule_your_free_demo') ?>" target="_blank">https://www.automotohr.com/schedule_your_free_demo</a>  or other approved URLs to be provided by <?php echo STORE_NAME; ?>. Affiliate’s submission will not be considered eligible to be a “Qualified Referral” where (a) <?php echo STORE_NAME; ?> already has a business relationship with that referral, as evidenced by <?php echo STORE_NAME; ?>’s written or electronic records, or (b) <?php echo STORE_NAME; ?> has already been introduced to or talked within the last year, as evidenced by <?php echo STORE_NAME; ?>’s written or electronic records, (c) the referral has been earned through fraudulent, illegal, or overly aggressive, questionable sales or marketing methods as determined solely at <?php echo STORE_NAME; ?>’s discretion, or (d) Affiliate does not have a direct relationship and direct communication (in other words, no cold referrals). <?php echo STORE_NAME; ?> will notify Affiliate in writing within 15 business days if referral has been rejected. Written notification includes, but is not limited to, email and system notification. Leads may also be submitted via a phone call, an email introduction, or other means when approved by <?php echo STORE_NAME; ?>.  All Qualified Referrals made by Affiliate are valid for a period of 12 months from the date of Affiliate’s submission of the lead.</p>

                                    <p><strong>2. FEES/COMMISSIONS AND PAYMENTS.</strong></p>
                                    <ul>
                                        <p><strong>2.1</strong>&nbsp;&nbsp;&nbsp;&nbsp;QUALIFIED CUSTOMER.  A Qualified Customer is defined as a Qualified Referral that subscribes to <?php echo STORE_NAME; ?>’s software as a service offering.</p>
                                        <p><strong>2.2</strong>&nbsp;&nbsp;&nbsp;&nbsp;QUALIFICATIONS.  Affiliate shall not be eligible to receive any compensation from <?php echo STORE_NAME; ?> pursuant to a Qualified Customer unless and until all of the following requirements are met: (i) Affiliate completes an Affiliate Referral Registration Form and submits it to <?php echo STORE_NAME; ?> before the Qualified Referral subscribes to <?php echo STORE_NAME; ?>’s software services; (ii) <?php echo STORE_NAME; ?> approves the Referral Registration Form submission in writing; (iii) Affiliate introduces <?php echo STORE_NAME; ?> to the appropriate contact at the Qualified Referral; (iv) Affiliate cooperates actively with <?php echo STORE_NAME; ?> and the Qualified Referral in subsequent meetings and discussions regarding the subject of the Referral Registration Form; and (v) Qualified Customer is current on all payments to <?php echo STORE_NAME; ?>. At no time shall Affiliate: (i) make any false or misleading representations with respect to <?php echo STORE_NAME; ?> and/or its products or services; (ii) make any representations with respect to <?php echo STORE_NAME; ?> and/or its products or services that are inconsistent with documentation supplied by <?php echo STORE_NAME; ?>; or (iii) make any other representation to a Qualified Referral or other company that would give rise, or could reasonably be expected to give rise, to any claim or cause of action against <?php echo STORE_NAME; ?>.</p>
                                        <p><strong>2.3</strong>&nbsp;&nbsp;&nbsp;&nbsp;COMPENSATION.  Provided that Affiliate has fully complied with Section “Qualifications” above, Affiliate shall be entitled to the following compensation for a Qualified Customer: (i) Commission percentage is outlined in the commission schedule agreed upon by <?php echo STORE_NAME; ?> and Affiliate found in Appendix A of this Agreement; (ii) Commissions are earned on first year revenue of software subscription fees only that are billed and actually collected from the Qualified Customer and are based on the initial plan signed up for by Qualified Customer; (iii) No fees of any kind will be paid on other revenues derived from the Qualified Customer, including but not limited to: implementation and service fees, third party products and applications resold by <?php echo STORE_NAME; ?>, training, professional services, annual renewals, or additional purchase of <?php echo STORE_NAME; ?> or third party services; (iv) Affiliate will not receive prorated commissions or a commission for the month of cancellation by Qualified Customer; (v) Commission payments will be processed Monthly after payment has cleared and is verified for any Qualified Customer who has fully complied with the “Qualifications” section above during the month, including but not limited to, being current on their billing; (vi) to the extent a Qualified Customer cancels their subscription with <?php echo STORE_NAME; ?> prior to completing twelve monthly payments, or if <?php echo STORE_NAME; ?> refunds a Qualified Customer’s pre-payment due to cancellation by Qualified Customer, the amount of the corresponding Commission previously paid to Affiliate shall be deducted for the next Commission Payment due; and (vii) Affiliate’s purchase of <?php echo STORE_NAME; ?> Products for its own use will not be a part of this Agreement.</p>
                                        <p><strong>2.4</strong>&nbsp;&nbsp;&nbsp;&nbsp;PAYMENTS BY PAYPAL OR CHECK.  Commissions are paid by PayPal or check. If Affiliate doesn’t have a PayPal account Affiliate can sign up for one at any time.  If Affiliate prefers to be paid by check, a mailing address must be provided. <?php echo STORE_NAME; ?> must have a current copy of Affiliate’s W-9 or W-8 on file in order to remit payment to Affiliate.</p>
                                    </ul>
                                    <p><strong>3. LICENSING TO QUALIFIED REFERRALS.</strong></p>
                                    <ul>
                                    <p><strong>3.1.</strong> &nbsp;&nbsp;&nbsp;&nbsp;IDENTIFYING AS an <?php echo STORE_NAME; ?> Affiliate REFERRER.  Affiliate may not issue any press release with respect to this Agreement or its participation in the Program without written consent from <?php echo STORE_NAME; ?>; such action may result in Affiliate termination from the Program. In addition, Affiliate may not in any manner misrepresent or embellish the relationship between <?php echo STORE_NAME; ?> and Affiliate, say Affiliate develops our products, say Affiliate is part of <?php echo STORE_NAME; ?> or express or imply any relationship or affiliation between <?php echo STORE_NAME; ?> and Affiliate or any other person or entity except as expressly permitted by this Agreement (including by expressing or implying that <?php echo STORE_NAME; ?> supports, sponsors, endorses, or contributes money to any charity or other cause).</p>
                                    <p><strong>3.2.</strong> &nbsp;&nbsp;&nbsp;&nbsp;NO RESELLER RELATIONSHIP.  Affiliate is not an authorized reseller of any <?php echo STORE_NAME; ?> Products, nor is Affiliate authorized to quote any prices different from what can be found on the <?php echo STORE_NAME; ?> website to third parties for the <?php echo STORE_NAME; ?> Products.  Affiliate acknowledges and agrees that in order to resell the <?php echo STORE_NAME; ?> Products, Affiliate must execute a reseller agreement with <?php echo STORE_NAME; ?>.  Affiliate agrees not to make any representations or warranties, express or implied, written or oral, on behalf of <?php echo STORE_NAME; ?> or any commitments regarding the function of the <?php echo STORE_NAME; ?> Products, except that a general statement of the benefits of the <?php echo STORE_NAME; ?> Products or their features and functions by Affiliate is not a violation of this Agreement.</p>
                                    <p><strong>3.3.</strong> &nbsp;&nbsp;&nbsp;&nbsp;AGREEMENTS WITH QUALIFIED REFERRALS. <?php echo STORE_NAME; ?> may enter into license agreements or other arrangements with Qualified Referrals in <?php echo STORE_NAME; ?>’s sole discretion.  <?php echo STORE_NAME; ?> has sole and complete control over the pricing and terms and conditions for all transactions related to <?php echo STORE_NAME; ?> Products. <?php echo STORE_NAME; ?> is solely responsible for negotiating contracts and fulfilling all orders for the <?php echo STORE_NAME; ?> Products and providing related services and support.  Affiliate shall not represent to anyone that Affiliate has the power to bind <?php echo STORE_NAME; ?> in any way, including to any arrangement with any prospect, or that any prospects will become Qualified Referrals under this Agreement.</p>
                                    </ul>
                                    <p><strong>4. CUSTOMER DEFINITION.</strong>  Customers who buy products through this Program will be deemed to be <?php echo STORE_NAME; ?> customers. Accordingly, all of our rules, policies, and operating procedures concerning customer orders, customer service, and product sales will apply to those customers. We may change our policies and operating procedures at any time. For example, we will determine the prices to be charged for products sold under this Program in accordance with our own pricing policies. Product prices and availability may vary from time to time. Because price changes may affect Products that Affiliate may list on its site, Affiliate shall not display product prices on its site.</p>
                                    <p><strong>5. COMPLIANCE WITH LAWS.</strong> As a condition to Affiliate participation in the Program, Affiliate agrees that while Affiliate is a Program participant Affiliate will comply with all laws, ordinances, rules, regulations, orders, licenses, permits, judgments, decisions or other requirements of any governmental authority that has jurisdiction over Affiliate, whether those laws, etc. are now in effect or later come into effect during the time Affiliate is a Program participant. Without limiting the foregoing obligation, Affiliate agree that as a condition of Affiliate’s participation in the Program Affiliate will comply with all applicable laws (federal, state or otherwise) that govern marketing email, including without limitation, the CAN-SPAM Act of 2003 and all other anti-spam laws.</p>
                                    <p><strong>6. TERM OF THE AGREEMENT AND PROGRAM.</strong> The term of this Agreement begins on the Effective Date and will end when terminated by either party. Either Affiliate or <?php echo STORE_NAME; ?> may terminate this Agreement at any time, with or without cause, by giving the other party written notice of termination. The definitions in this Agreement and the rights, duties and obligations of the parties that, by their nature, continue and survive, will survive termination of this Agreement. Upon the termination of this Agreement for any reason, Affiliate will immediately cease use of, and remove from Affiliate’s site, all links to any Product Site, and all <?php echo STORE_NAME; ?> trademarks, trade dress, and logos, and all other materials provided by or on behalf of <?php echo STORE_NAME; ?> to Affiliate pursuant hereto or in connection with the Program. Termination of the Service will result in the deactivation or deletion of Affiliate’s Account or Affiliate’s access to Affiliate’s Account, and the forfeiture and relinquishment of all potential or to-be-paid commissions in Affiliate’s Account if they were earned through fraudulent, illegal, or overly aggressive, questionable sales or marketing methods. <?php echo STORE_NAME; ?> reserves the right to end the Program at any time.</p> <br/>

                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <p><strong>7. CONFIDENTIALITY.</strong> The parties acknowledge that by virtue of the relationship contemplated by this Agreement the parties may have access to Confidential Information.  “Confidential Information” means the all non-public information of either party disclosed under this Agreement that is marked as confidential or proprietary or that is disclosed verbally and identified as confidential or proprietary at the time of disclosure and summarized in writing by the disclosing party within 30 days.  The parties agree, both during and after the term of this Agreement to hold each other’s Confidential Information in confidence. The parties agree not to (a) disclose or otherwise make each other’s Confidential Information available in any form to any third party (other than those of its employees, subcontractors, agents, or consultants who have a need to know and are under written nondisclosure obligations sufficient to enable that party to comply with its obligations under this Section) and (b) use each other’s Confidential Information for any purpose other than as required to perform under this Agreement.  Each party agrees to take all commercially reasonable steps to ensure that Confidential Information is not used, disclosed or distributed in violation of the provisions of this Section 7. The parties agree that the terms and conditions of this Agreement are considered confidential to <?php echo STORE_NAME; ?>.  Notwithstanding any provision contained in this Agreement, neither party shall be required to maintain in confidence any of the following information:  (i) information which, at the time of disclosure to the receiving party, is in the public domain; (ii) information which, after disclosure, becomes part of the public domain, except by breach of this Agreement; (iii) information which was in the receiving party’s possession at the time of disclosure, and which was not acquired, directly or indirectly, from the disclosing party; (iv) information which the receiving party can demonstrate resulted from its own research and development, independent of disclosure from the disclosing party (e.g., without use of or reference to the disclosing party’s Confidential Information); or (v) information which the receiving party receives from third parties, on condition that the information was not obtained by such third parties from the disclosing party on a confidential basis.  A disclosure of Confidential Information where required by applicable law or a court order is not a breach of this Agreement, on condition that the disclosing party is given reasonable notice of the requirement to disclose and a reasonable opportunity to attempt to contest, prevent or limit the disclosure and that the disclosing party asserts the confidential nature of the Confidential Information to the government or court, as applicable, or otherwise seeks confidential treatment and cooperates with the disclosing party in protecting against any such disclosure and/or obtaining a protective order narrowing the scope of the compelled disclosure and protecting its confidentiality.  Either party may provide a copy of this Agreement to its attorneys, accountants and profession advisers as well as in confidence to the following persons:  potential acquirers, merger partners or investors and to their employees, agents, attorneys, investment bankers, financial advisers and auditors in connection with the due diligence review of that party.  Either party may also provide a copy of this Agreement in connection with any litigation concerning this Agreement.<br/>
                                    <p><strong>8. PROPRIETARY RIGHTS OF <?php echo STORE_NAME; ?>.</strong> All materials, including but not limited to any computer software (in object code and source code form), data or information developed or provided by <?php echo STORE_NAME; ?> or its suppliers under this Agreement, and any know-how, methodologies, equipment, or processes used by <?php echo STORE_NAME; ?> to provide the Services to Affiliate, including, without limitation, all copyrights, trademarks, patents, trade secrets, and any other proprietary rights inherent therein and appurtenant thereto (collectively “<?php echo STORE_NAME; ?> Materials”) shall remain the sole and exclusive property of <?php echo STORE_NAME; ?> or its suppliers. Affiliate may not duplicate, copy, or reuse any portion of the HTML/CSS /FLASH or visual design elements without express written permission from <?php echo STORE_NAME; ?>.</p>
                                    <p><strong>9. LIMITS ON LIABILITY.</strong> CONSEQUENTIAL DAMAGES WAIVER.  IN NO EVENT SHALL <?php echo STORE_NAME; ?> BE LIABLE FOR ANY INDIRECT, SPECIAL, INCIDENTAL, CONSEQUENTIAL OR PUNITIVE DAMAGES, INCLUDING, BUT NOT LIMITED TO, LOSS OF ANTICIPATED REVENUE OR PROFITS, WHICH IN ANY WAY ARISE OUT OF OR RELATE TO THIS AGREEMENT, REGARDLESS OF THE THEORY OF LIABILITY, INCLUDING CONTRACT OR TORT (INCLUDING NEGLIGENCE AND STRICT LIABILITY), REGARDLESS OF WHETHER OR NOT FORESEEABLE AND WHETHER OR NOT <?php echo STORE_NAME; ?> HAS BEEN ADVISED OF THE POSSIBILITY OF THE DAMAGES AND NOTWITHSTANDING ANY FAILURE OF ESSENTIAL PURPOSE OF THIS AGREEMENT OR ANY LIMITED REMEDY HEREUNDER.</p>
                                    <p><strong>10. WARRANTY DISCLAIMER.</strong> <?php echo STORE_NAME; ?> MAKES NO WARRANTIES OF ANY TYPE OR KIND IN CONNECTION WITH THIS AGREEMENT. <?php echo STORE_NAME; ?> HEREBY DISCLAIMS AND EXCLUDES ALL WARRANTIES, WHETHER STATUTORY, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT OF THIRD PARTY RIGHTS AND THOSE ARISING FROM COURSE OF DEALING, USAGE OR TRADE.</p>

                                    <p><strong>11. ASSIGNMENT</strong> . Neither party may transfer or assign or otherwise, any rights or delegate any performance under this Agreement without the other party’s prior written consent, except that a party may, without prior written consent of the other party, assign this Agreement in connection with a merger, acquisition or sale of substantially all assets to which this Agreement relates.  Any purported assignment or delegation in violation of the foregoing is void. This Agreement will bind and inure to the benefit of the parties and their respective successors and permitted assigns.</p>
                                    <p><strong>12.</strong> NO EXCLUSIVITY.  This Agreement is non-exclusive.  Each party is free to enter into similar agreements with other parties.</p>
                                    <p><strong>13. GOVERNING LAW, JURISDICTION, AND DISPUTE RESOLUTION</strong> All matters arising out of or relating to this Agreement shall be governed by the laws of the State of California USA, and Affiliate consents to the jurisdiction and venue of the federal and state courts sitting in Riverside County, California USA. The party substantially prevailing at any subsequent proceeding (including without limitation trial, appellate, and arbitration proceedings) will be entitled to recover all related costs and expenses, including attorney’s fees. A printed version of the Terms and of any notice given in electronic form shall be admissible in judicial or administrative proceedings based upon or relating to the Terms to the same extent and subject to the same conditions as other business documents and records originally generated and maintained in printed form.</p>
                                    <p><strong>14. RELATIONSHIP OF THE PARTIES</strong>  The parties agree that each party is acting as an independent contractor and not as an agent, partner or joint venture with the other party for any purpose.  Neither party has the right, power or authority to act or to create any obligation, express or implied, on behalf of the other party.  Each party agrees to bear its own costs and expenses incurred by such party in connection with the negotiation, documentation, execution and performance under this Agreement.  No term or provision hereof will be considered waived by either party, and no breach excused by either party, unless the waiver or consent is set forth in writing signed on behalf of the party against whom the waiver is asserted.  No consent by either party to, or waiver of, a breach by either party, whether express or implied, constitutes a consent to, waiver of, or excuse of any other, different, or subsequent breach by either party.</p>
                                    <p><strong>15. ENTIRE AGREEMENT</strong> This Agreement constitutes the final agreement between the parties. It is the exclusive expression of the parties’ agreement on the matters contained in this Agreement. All earlier and contemporaneous negotiations and agreements between the parties on the matters contained in this Agreement are expressly merged into and superseded by this Agreement.  The parties may amend this Agreement only by a written agreement of the parties that identifies itself as an amendment to this Agreement.</p>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="appendix-title text-center">
                                        <h3>Appendix A</h3>
                                        <h4>Commission Schedule</h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-8">Status Level</th>
                                                    <th class="col-lg-2">Percentage</th>
                                                    <th class="col-lg-2">Flat $$ Commission</th>
                                                </tr>
                                                <tr>
                                                    <th>Closed Qualified Customers</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $uri_segment = $this->uri->segment(3); ?>

                                                <tr>
                                                    <td class="<?php echo $uri_segment=='pre_fill'? 'change_commission':'' ?>" src="cqc" id="replace_cqc">
                                                        <?php echo isset($company_document['closed_qualified_customers']) ? $company_document['closed_qualified_customers'] : 'Commission on 1st Year Subscriptions to be paid on a monthly basis after clients payment has been cleared and confirmed.' ?>
                                                    </td>
                                                    <td class="<?php echo $uri_segment=='pre_fill'? 'change_commission':'' ?>" src="csp" id="replace_csp">
                                                        <?php echo isset($company_document['commission_schedule_percentage']) ? $company_document['commission_schedule_percentage'] : '30% of the gross Monthly subscription payment'?>
                                                    </td>
                                                    <td class="text-right <?php echo $uri_segment=='pre_fill'? 'change_commission':'' ?>" src="csf" id="replace_csf">
                                                        <?php echo isset($company_document['commission_schedule_flat']) ? $company_document['commission_schedule_flat'] : '$0' ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="end-user-form-wrp">
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">COMPANY </span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>By:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_by = $company_document['company_by'];
                                                    if ($company_by == '') {
                                                        $company_by = STORE_NAME;
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_by" name="company_by" value="<?php echo set_value('company_by', $company_by); ?>"  <?php echo $readonly; ?>  <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?>/>
                                                    <?php echo form_error('company_by'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_name = $company_document['company_name'];
                                                    if ($company_name == '') {
                                                        $company_name = 'Steven Warner';
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_name" name="company_name" value="<?php echo set_value('company_name', $company_name); ?>"  <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_name'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Title:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    $company_title = $company_document['company_title'];
                                                    if ($company_title == '') {
                                                        $company_title = 'CEO';
                                                    }
                                                    ?>
                                                    <input type="text" class="invoice-fields" id="company_title" name="company_title" value="<?php echo set_value('company_title', $company_title); ?>"  <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
                                                    <?php echo form_error('company_title'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>DATE:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    //if ($company_document['company_date'] == '0000-00-00 00:00:00') {
                                                    if ($company_document['status'] == 'signed') {
                                                        $company_document['company_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['company_date'])));
                                                    } else {
                                                        $company_document['company_date'] = date('m-d-Y');
                                                    }
                                                    //} else {
                                                    //    $company_document['company_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['company_date'])));
                                                    //}
                                                    ?>
                                                    <input type="text" class="invoice-fields <?php echo $company_document['status'] != 'signed' && $is_pre_fill == 1 ? 'startdate' : '';?>" id="company_date" name="company_date" value="<?php echo set_value('company_date', $company_document['company_date']); ?>"  <?php echo $readonly; ?>  <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?>/>
                                                    <?php echo form_error('company_date'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">CLIENT</span>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>By:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_by" name="client_by" value="<?php echo set_value('client_by', $company_document['client_by']); ?>"  <?php echo $readonly; ?>  />
                                                    <?php echo form_error('client_by'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Name:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_name" name="client_name" value="<?php echo set_value('client_name', $company_document['client_name']); ?>"  <?php echo $readonly; ?>  />
                                                    <?php echo form_error('client_name'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>Title:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <input type="text" class="invoice-fields" id="client_title" name="client_title" value="<?php echo set_value('client_title', $company_document['client_title']); ?>"  <?php echo $readonly; ?>  />
                                                    <?php echo form_error('client_title'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-row">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <label>DATE:</label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <?php
                                                    //if ($company_document['client_date'] == '0000-00-00 00:00:00') {
                                                    if ($company_document['status'] == 'signed') {
                                                        $company_document['client_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['client_date'])));
                                                    } else {
                                                        $company_document['client_date'] = date('m-d-Y');
                                                    }
                                                    //} else {
                                                    //    $company_document['client_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $company_document['client_date'])));
                                                    //}
                                                    ?>

                                                    <input type="text" class="invoice-fields <?php echo $company_document['status'] != 'signed' ? 'startdate' : ''; ?>" id="client_date" name="client_date" value="<?php echo set_value('client_date', $company_document['client_date']); ?>"  <?php echo $readonly; ?>  />
                                                    <?php echo form_error('client_date'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p style="float:left; width:100%;" class="col-lg-12">IN WITNESS WHEREOF, the parties have caused this Agreement to be executed in its name and attested to by its duly authorized officers or individuals as of the Date below. </p>

                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card-fields-row">
                                                <div class="col-lg-3">
                                                    <label class="signature-label" style="font-size:14px;">E-SIGNATURE</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <?php
                                                    $company_signature = $company_document['company_signature'];
                                                    if ($company_signature == '') {
                                                        $company_signature = 'Steven Warner';
                                                    }
                                                    ?>
                                                    <input type="text" class="signature-field" name="company_signature" id="company_signature" value="<?php echo set_value('company_signature', $company_signature); ?>"  <?php echo $readonly; ?>  <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?>/>
                                                    <p>Please type your First and Last Name</p>
                                                    <?php echo form_error('company_signature'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card-fields-row">
                                                <div class="col-lg-3">
                                                    <label class="signature-label" style="font-size:14px;">E-SIGNATURE</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <?php if(isset($company_document['signature_bas64_image']) && !empty($company_document['signature_bas64_image'])) { ?>
                                                            <div class="img-full">
                                                                <img style="max-height: 150px;" src="<?php echo isset($company_document['signature_bas64_image']) && !empty($company_document['signature_bas64_image']) ? $company_document['signature_bas64_image'] : ''; ?>" class="esignaturesize" />
                                                            </div>
                                                    <?php } elseif (isset($company_document['client_signature']) && !empty($company_document['client_signature'])) { ?> 
                                                           <div class="img-full">
                                                                <input type="text" class="signature-field" name="company_signature" id="company_signature" value="<?php echo isset($company_document['client_signature']) && !empty($company_document['client_signature']) ? $company_document['client_signature'] : ''; ?>"  readonly="readonly" />
                                                            </div>
                                                    <?php } else { ?>
                                                        <?php $page_uri_segment = $this->uri->segment(3); ?>
                                                        <a class="btn btn-success btn-sm <?php echo $page_uri_segment != 'pre_fill'? 'get_signature':'' ?>" href="javascript:;">Create E-Signature</a>
                                                        <div class="img-full">
                                                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT?>;" src=""  id="draw_upload_img" />
                                                        </div>
                                                    <?php } ?>
                                                    <?php if(!isset($company_document['signature_bas64_image']) && empty($company_document['signature_bas64_image']) && !isset($company_document['client_signature']) && empty($company_document['client_signature'])) { ?>
                                                        <p>Please type your First and Last Name</p>
                                                    <?php } ?>    
                                                    <?php //echo form_error('client_signature'); ?>
                                                </div>
                                            </div>

                                            <?php $uri_segment = $this->uri->segment(3); ?>
                                            <?php if ($uri_segment == 'view' || $uri_segment == null) { ?>
                                                <div class="form-col-100">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                            <label class="" style="font-size:14px;">IP Address</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                                            <span>
                                                                <?php if(!empty($ip_track)) { ?>
                                                                    <?php echo $ip_track['ip_address']; ?>
                                                                <?php } else { ?>
                                                                    <?php echo getUserIP(); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-5 col-sm-3">
                                                            <label class="" style="font-size:14px;">Date/Time</label>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-7 col-sm-9">
                                                            <span>
                                                                <?php if(!empty($ip_track)) { ?>
                                                                    <?php echo date('m/d/Y h:i A', strtotime($ip_track['document_timestamp'])); ?>
                                                                <?php } else { ?>
                                                                    <?php echo date('m/d/Y h:i A'); ?>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-fields-row">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <p style="text-align: center !important;">

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-fields-row">
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_HEADING; ?>
                                    </p>
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_TITLE; ?>     
                                    </p>
                                    <p>
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_DESCRIPTION; ?>
                                    </p>
                                </div>
                                <div class="card-fields-row acknowledgment-row">
                                    <?php
                                    $is_default_accepted = false;
                                    if ($company_document['acknowledgement'] == 'terms_accepted') {
                                        $is_default_accepted = true;
                                    }
                                    ?>
                                    <label class="control control--checkbox" for="acknowledgement">
                                        <input type="checkbox" value="terms_accepted" id="acknowledgement" name="acknowledgement" <?php echo set_checkbox('acknowledgement', 'terms_accepted', $is_default_accepted); ?> <?php echo ($company_document['status'] == 'signed' ? 'onclick="return false"' : ''); ?> />&nbsp;
                                        <?php echo DEFAULT_SIGNATURE_CONSENT_CHECKBOX; ?>
                                        <div class="control__indicator"></div>
                                    </label>
                                    <?php echo form_error('acknowledgement'); ?>
                                </div>

                                <?php if ($company_document['status'] != 'signed') { ?>
                                    <div class="col-lg-6 col-lg-offset-3" id="signed">
                                        <?php if ($is_pre_fill == 1) { ?>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <input type="submit" class="page-heading" value="Save And Send" name="save">
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn">Save</button>
                                            </div>
                                        <?php } else { ?>
                                            <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn"><?php echo DEFAULT_SIGNATURE_CONSENT_BUTTON; ?></button>
                                        <?php } ?>
                                    </div>
                                <?php } else{
                                    if ($is_pre_fill == 1) {?>
                                    <div class="col-lg-6 col-lg-offset-3" id="signed">
                                        <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn"  value="Save And Send" name="save">Resend</button>
                                    </div>
                                    <?php }
                                }?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php $this->load->view('static-pages/marketing_agencies_e_signature_popup'); ?>

    <div id="myCommissionModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Commission Schedule</h4>
                </div>
                <div class="modal-body">
                    <form id="form_commission_detail">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <input type="hidden" name="submit_commission_type" id="submit_commission_type" value="">
                            <input type="hidden" name="record_sid" value="<?php echo $company_document['sid']; ?>">
                            <input type="hidden" name="record_ma_sid" value="<?php echo $agent_record['sid']; ?>">
                            <div class="field-row field-row-autoheight" id="commission_detail">
                                <label>Status Level (Closed Qualified Customers)</label>
                                <textarea id="commission_detail_txt" name="full_commission_details" cols="75" rows="10" class="hr-form-fileds field-row-autoheight" style="padding-left:14px;"></textarea>
                            </div>
                            <div class="field-row field-row-autoheight" id="commission_percentage">
                                <label>Percentage</label>
                                <textarea id="commission_percentage_txt" name="full_commission_percentage" cols="75" rows="10" class="hr-form-fileds field-row-autoheight" style="padding-left:14px;"></textarea>
                            </div>
                            <div class="field-row field-row-autoheight" id="commission_rate">
                                <label>Flat $ Commission</label>
                                <textarea id="commission_rate_txt" name="full_commission_rate" cols="75" rows="10" class="hr-form-fileds field-row-autoheight" style="padding-left:14px;"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="save" onclick="savecommission()" class="btn btn-width bkgrnd-cyan save-details" type="button" name="save-details">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#form_affiliate_end_user_license_agreement').validate();
            $('.startdate').datepicker({
                dateFormat: 'mm-dd-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
            }).val();

            $('.static-class').each(function () {
                $(this).on('change', function () {
                    var current = $(this).val();
                });
            });
        });

        $(document).on('click', '.change_commission', function() {
            var changes = $(this).attr('src');
            var text = $(this).text();

            if (changes == 'cqc') {
                $( "#commission_detail" ).show();
                $( "#commission_detail_txt" ).html($.trim(text));
                $( "#submit_commission_type" ).val('closed_qualified_customers');
                $( "#commission_percentage" ).hide();
                $( "#commission_rate" ).hide();
            } else if (changes == 'csp') {
                $( "#commission_percentage" ).show();
                $( "#commission_percentage_txt" ).html($.trim(text));
                $( "#submit_commission_type" ).val('commission_schedule_percentage');
                $( "#commission_detail" ).hide();
                $( "#commission_rate" ).hide();
            } else if (changes == 'csf') {
                $( "#commission_rate" ).show();
                $( "#commission_rate_txt" ).html($.trim(text));
                $( "#submit_commission_type" ).val('commission_schedule_flat');
                $( "#commission_detail" ).hide();
                $( "#commission_percentage" ).hide();
            }

            $('#myCommissionModal').modal('show'); 
        });

        function savecommission() {
            var type = $('#submit_commission_type').val();

            var myurl = "<?= base_url() ?>Form_affiliate_end_user_license_agreement/ajax_commission_detail";
            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
               
                data: $('#form_commission_detail').serialize(),
                url: myurl,

                success: function(data){
                    alertify.success('Commission Detail submit successfully');

                    $('#myCommissionModal').modal('hide');

                    if (type == 'closed_qualified_customers') {

                        $('#replace_cqc').html(data);

                    } else if (type == 'commission_schedule_percentage') {

                        $('#replace_csp').html(data);

                    } else if (type == 'commission_schedule_flat') {

                        $('#replace_csf').html(data);

                    }
                },
                error: function(){

                }
            });
        }

        function func_send_affiliate_agreement(){
            alertify.confirm('Confirmation', "Are you sure you want to send Affiliate End User License Agreement?",function () {
                    $.ajax({
                        type:'POST',
                        url:'<?php echo base_url('form_affiliate_end_user_license_agreement/send_agreement');?>',
                        date:{
                            'marketing_sid':<?php echo $company_document['marketing_agencies_sid'];?>
                        }
                    });
                },
                function () {
                    alertify.error('Cancelled');
                });
        }

        // print page button
        function print_page(elem) {
            $('form input[type=text]').each(function() {
                $(this).attr('value', $(this).val());
            });

            // hide the signed button
            $('#signed').hide();
            $('#print_div').hide();

            var data = ($(elem).html());
            var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

            mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/font-awesome.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery-ui-datepicker-custom.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/style.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/responsive.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');

            mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
            mywindow.document.close();
            mywindow.focus();

            // display the button again
            $('#signed').show();
            $('#print_div').show();
        }
    </script>
</body>
</html>