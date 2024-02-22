<?php $this->load->view('main/static_header'); ?>

<body>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php
                    $readonly = '';
                    if ($company_document['status'] == 'signed') {
                        $readonly = ' readonly="readonly" ';
                    }
                    ?>
                    <div class="end-user-wrp-outer">
                        <div class="top-logo text-center">
                            <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                        </div>
                        <!-- page print -->
                        <?php //if($is_pre_fill == 0) { 
                        ?>
                        <div class="top-logo" id="print_div">
                            <a href="javascript:;" class="btn btn-success affiliate_end_user_agreement_color_btn" onclick="print_page('.container');">
                                <i class="fa fa-print" aria-hidden="true"></i> Print or Save
                            </a>
                        </div>
                        <?php //} 
                        ?>
                        <!-- page print -->
                        <span class="page-heading down-arrow affiliate_end_user_agreement_color">END USER LICENSE AGREEMENT</span>
                        <div class="end-user-agreement-wrp">
                            <form action="<?php echo base_url('form_end_user_license_agreement' . '/' . $verification_key) . ($is_pre_fill == 1 ? '/pre_fill' : ''); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_document['company_sid']; ?>" />
                                <input type="hidden" id="is_pre_fill" name="is_pre_fill" value="<?php echo $is_pre_fill; ?>" />
                                <div style="font-size:18px;">End User License Agreement (this “Agreement”) by and between AUTOMOTOSOCIAL, LLC (“COMPANY”) and <div class="form-outer company-name" style="max-width:700px;"><input type="text" class="invoice-fields" name="the_entity" id="the_entity" value="<?php echo set_value('the_entity', $company_document['the_entity']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('the_entity'); ?></div>, an entity located at <div class="form-outer" style="max-width: 700px !important; display: inline-block;"><input style="width: 700px !important; max-width: 100% !important;" type="text" class="invoice-fields" name="the_client" id="the_client" value="<?php echo set_value('the_client', $company_document['the_client']); ?>" <?php echo $readonly; ?> /> <?php echo form_error('the_client'); ?></div> (the “CLIENT”). COMPANY and CLIENT are sometimes referred to herein collectively as the “parties” or individually as a “party.”</div>

                                <!--                            <p><strong>A.</strong>  &nbsp;&nbsp;&nbsp;&nbsp;COMPANY provides, among other things, <?php echo STORE_NAME; ?> / AutomotoSocial LLC, which are web platforms for both entities/employers looking to hire employees in the automotive industry and potential job candidates looking for potential jobs in the automotive industry.  <?php echo STORE_NAME; ?> / AutomotoSocial LLC were created with the purpose of providing a dynamic network of jobseekers and employers in the automotive industry in the United States and Canada (“SOFTWARE”).  SOFTWARE includes COMPANY’s software, documentation, training, and support as described below.</p>-->
                                <p><strong>A.</strong> &nbsp;&nbsp;&nbsp;&nbsp;COMPANY <?php echo STORE_NAME; ?> / AutomotoSocial LLC provides, among other things, an Applicant tracking system, Career site platforms and employee onboarding for employers looking to hire employees and potential job candidates looking for potential jobs. <?php echo STORE_NAME; ?> / AutomotoSocial LLC were created with the purpose of providing a dynamic network of jobseekers and employers in the United States and Canada (“SOFTWARE”). SOFTWARE includes COMPANY’s software, documentation, training, and support as described below.</p>
                                <p><strong>B.</strong> &nbsp;&nbsp;&nbsp;&nbsp;CLIENT desires to license COMPANY’s SOFTWARE. NOW, THEREFORE, in consideration of the above premises, the representations, warranties and covenants set forth herein, and for other good and valuable consideration, the receipt and sufficiency of which is hereby acknowledged, and intending to be legally bound hereby, the parties agree as follows: </p>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <p><strong>1.</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">Purchase of License and Representations of Use the SOFTWARE</span>. COMPANY, at its absolute discretion, upon payment by CLIENT for the license, will grant a non-exclusive license to CLIENT to use the SOFTWARE created by COMPANY. Other services and/or products available for purchase from COMPANY will be set forth in a separate written agreement. The license is effective upon payment as set forth herein, and shall entitle CLIENT to an unlimited number of in-house web browser users on a single instance of the SOFTWARE supplied by COMPANY. This license is not transferable. The SOFTWARE is licensed, not sold. The Parties specifically warrant and represent in connection with the use of the SOFTWARE the following:</p>
                                    <p> COMPANY is the sole and exclusive owner of the SOFTWARE and by the terms hereof does not relinquish any ownership rights or interests.</p>
                                    <p> CLIENT shall not use anyone’s copyright(s) or trademark(s) not specifically provided for in this Agreement. CLIENT shall have public internet access and Microsoft Internet Explorer 6.0 or higher or the latest version of Google Chrome; and</p>
                                    <ul>
                                        <p><strong>1.2.</strong> &nbsp;&nbsp;&nbsp;&nbsp;CLIENT shall not distribute, alter, decompose, disassemble, or copy any COMPANY SOFTWARE contrary to this Agreement without prior written approval of COMPANY.</p>
                                    </ul>
                                    <p><strong>2.</strong> &nbsp;&nbsp;&nbsp;&nbsp;Fees and Payment Terms. For the web-based deployment of the SOFTWARE, the monthly license maintenance and support fee is as follows:</p>
                                    <div> An amount equal to $ <div class="form-outer"><input type="text" class="invoice-fields" name="development_fee" id="development_fee" value="<?php echo set_value('development_fee', $company_document['development_fee']); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> /> <?php echo form_error('development_fee'); ?></div> is due and payable at the time of this Agreement toward the setup, development, and deployment of the SOFTWARE by COMPANY to CLIENT; and</div>
                                    <div class="form-col-100" <?php if ($is_pre_fill == 0) {
                                                                    echo 'style="visibility:hidden;"';
                                                                } ?>>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="control control--radio">
                                                    Monthly Subscription
                                                    <input class="static-class" type="radio" name="payment_method" value="monthly_subscription" checked="checked">
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control control--radio">
                                                    Trial Period
                                                    <input class="static-class" type="radio" name="payment_method" value="trial_period" <?php if ((isset($company_document['payment_method']) && $company_document['payment_method'] == 'trial_period') || $company_document['is_trial_period'] == '1') {
                                                                                                                                            echo 'checked="checked"';
                                                                                                                                        } ?>>
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="monthly-subscription">
                                        <?php echo form_error('monthly_fee'); ?>
                                        <?php echo form_error('number_of_rooftops_locations'); ?>
                                        A monthly fee of <b>$</b>
                                        <div class="form-outer">
                                            <input type="text" class="invoice-fields" name="monthly_fee" id="monthly_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '0') {
                                                                                                                                        echo set_value('monthly_fee', $company_document['monthly_fee']);
                                                                                                                                    } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                        echo 'disabled';
                                                                                                                                                                    } ?> />
                                            <?php if ($is_pre_fill == 0) { ?>
                                                <input type="hidden" name="monthly_fee" id="monthly_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '0') {
                                                                                                                    echo set_value('monthly_fee', $company_document['monthly_fee']);
                                                                                                                } ?>" />
                                            <?php } ?>
                                        </div>
                                        for <div class="form-outer"><input type="text" class="invoice-fields" name="number_of_rooftops_locations" id="number_of_rooftops_locations" value="<?php if (isset($company_document['number_of_rooftops_locations']) && $company_document['is_trial_period'] == '0') {
                                                                                                                                                                                                echo set_value('number_of_rooftops_locations', $company_document['number_of_rooftops_locations']);
                                                                                                                                                                                            } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                                                                                echo 'disabled';
                                                                                                                                                                                                                            } ?> /></div> Rooftop Location(s). The monthly fees contracted in this Agreement do not include tax. Additional taxes may be collected dependent on the CLIENT’s local jurisdiction tax laws.
                                        <p>
                                            Monthly fees include a multi-user, single-server license and unlimited telephone technical support, which will commence once any installation contracted on the Agreement is initiated. Monthly fees may be prorated based on the date services are initiated. CLIENT shall pay the monthly license, maintenance, and support fee on the first business day of each month, after the initiation of the SOFTWARE. SOFTWARE services by the COMPANY are subject to termination if any COMPANY invoice is more than 15 days past due. Written email notification will be given to the CLIENT prior to this termination.
                                        </p>
                                    </div>

                                    <div class="trial-period">
                                        <?php echo form_error('trial_limit'); ?>
                                        <?php echo form_error('trial_fee'); ?>
                                        <?php echo form_error('recurring_payment_day'); ?>
                                        <?php echo form_error('number_of_rooftops_locations_trial'); ?>
                                        Trial Period and Requirements to Convert to a full Subscription License:<br><br>
                                        The Trial Period for the Trial Services will be for
                                        <?php if ($is_pre_fill == 0) { ?>
                                            <?php echo $company_document['trial_limit']; ?>
                                        <?php } else { ?>
                                            <input type="number" name="trial_limit" id="trial_limit" min="0" value="<?php echo $company_document['trial_limit']; ?>" />
                                        <?php } ?>
                                        days from the Trial Service Activation Date, unless: a) such Trial Period is for a longer term as specified by <?php echo STORE_NAME; ?> / AutomotoSocial LLC; or such Trial Period is extended by mutual Agreement of the parties. Customer acknowledges and agrees that, at the end of the Trial Period, Customer’s access to the Trial Services will be AUTOMATICALLY converted, with or without notice, to license the Services on a paid subscription basis at a rate of <b>$</b>
                                        <div class="form-outer">
                                            <input type="text" class="invoice-fields" name="trial_fee" id="trial_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '1') {
                                                                                                                                    echo set_value('trial_fee', $company_document['monthly_fee']);
                                                                                                                                } ?>" <?php if ($is_pre_fill == 0) {
                                                                                                                                            echo 'disabled';
                                                                                                                                        } ?> />
                                            <?php if ($is_pre_fill == 0) { ?>
                                                <input type="hidden" name="trial_fee" id="trial_fee" value="<?php if (isset($company_document['monthly_fee']) && $company_document['is_trial_period'] == '1') {
                                                                                                                echo set_value('trial_fee', $company_document['monthly_fee']);
                                                                                                            } ?>" />
                                            <?php } ?>
                                        </div>
                                        a month billed on the
                                        <div class="form-outer">
                                            <!--
                                                <input type="text" class="invoice-fields" name="recurring_payment_day" id="recurring_payment_day" value="<?php if (isset($company_document['recurring_payment_day']) && $company_document['is_trial_period'] == '1') {
                                                                                                                                                                echo set_value('recurring_payment_day', $company_document['recurring_payment_day']);
                                                                                                                                                            } ?>"/>
                                            -->
                                            <select name="recurring_payment_day" id="recurring_payment_day" class="invoice-fields" <?php if ($is_pre_fill == 0) {
                                                                                                                                        echo 'disabled';
                                                                                                                                    } ?>>
                                                <?php for ($i = 1; $i < 29; $i++) {
                                                    echo '<option value="' . $i . '"';
                                                    if (isset($company_document['recurring_payment_day']) && $company_document['recurring_payment_day'] == $i) {
                                                        echo 'selected';
                                                    }
                                                    echo '>' . $i . '</option>';
                                                } ?>
                                            </select>
                                            <?php if ($is_pre_fill == 0) { ?>
                                                <input type="hidden" name="recurring_payment_day" id="recurring_payment_day" value="<?php echo $company_document['recurring_payment_day']; ?>" />
                                            <?php } ?>
                                        </div>
                                        day of the month for <div class="form-outer"><input type="text" class="invoice-fields" name="number_of_rooftops_locations_trial" id="number_of_rooftops_locations" value="<?php if (isset($company_document['number_of_rooftops_locations']) && $company_document['is_trial_period'] == '0') {
                                                                                                                                                                                                                        echo set_value('number_of_rooftops_locations', $company_document['number_of_rooftops_locations']);
                                                                                                                                                                                                                    } ?>" <?php echo $readonly; ?> <?php if ($is_pre_fill == 0) {
                                                                                                                                                                                                                                                        echo 'disabled';
                                                                                                                                                                                                                                                    } ?> /></div> Rooftop Location(s). The monthly fees contracted in this Agreement do not include tax. Additional taxes may be collected dependent on the CLIENT’s local jurisdiction tax laws.
                                        <p>
                                            Customer must contact <?php echo STORE_NAME; ?> / AutomotoSocial LLC at least fifteen (15) business days prior to the end of the Trial Period if Customer wishes to cancel the Services beyond the Trial Period.
                                        </p>
                                    </div>

                                    <?php
                                    $dateToTakeEffectFrom = "2024-02-21"; // set a date to take effect
                                    $formSignedDate = formatDateToDB(
                                        $company_document['status_date'],
                                        DB_DATE_WITH_TIME,
                                        DB_DATE
                                    ); // reformat the date
                                    // the form either is not yet signed
                                    // or the date is in future
                                    $agreementDateForSectionThree = $company_document["status"] != "signed"
                                        || $formSignedDate >= $dateToTakeEffectFrom
                                        ? 45
                                        : 15;
                                    ?>

                                    <!--                                    <p><strong>3.</strong>  &nbsp;&nbsp;&nbsp;&nbsp;Term.  I understand that this authorization will remain in effect until I cancel it in writing, and I agree to notify AutomotoSocial LLC / <?php echo STORE_NAME; ?> in writing of any changes in my account information or termination of this authorization at least 15 days prior to the next billing date. If the above noted payment dates fall on a weekend or holiday, I understand that the payments may be executed on the next business day.</p>-->
                                    <p><strong>3.</strong> &nbsp;&nbsp;&nbsp;&nbsp;Term. CLIENT understands that this authorization will remain in effect until CLIENT cancels it in writing, and CLIENT agrees to notify AutomotoSocial LLC / <?php echo STORE_NAME; ?> in writing of any changes in CLIENT's account information or termination of this authorization at least <?php echo $agreementDateForSectionThree ?> days prior to the next billing date. If the above noted payment dates fall on a weekend or holiday, CLIENT understands that the payments may be executed on the next business day.</p>
                                    <p><strong>4.</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">Data</span>. It is hereby acknowledged and agreed that CLIENT authorizes COMPANY to perform various functions that deal with CLIENT data including potential or existing CLIENT information. CLIENT warrants that all privacy notifications, client permissions and disclosures have been made and that COMPANY is not responsible for any unauthorized transactions or inquiries.</p>
                                    <p>The Parties will provide safeguards of confidential information as required by State and Federal law, as well as required by Canadian and Provincial law. CLIENT assumes responsibility for disabling access to any unauthorized users including past employees.</p>
                                    <p><strong>5.</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">Training</span>. Upon implementation, COMPANY shall conduct on-line training such that all designated personnel involved with the SOFTWARE are trained in the operation of the SOFTWARE. COMPANY will provide additional on-line training sessions as needed at no charge to the CLIENT</p>
                                    <p><strong>6.</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">Support</span>. For the duration of this Agreement, COMPANY shall provide CLIENT with telephone support from 8:00 AM to 6:00 PM Monday through Friday Pacific Standard time (except public holidays). There is no additional charge to CLIENT for this service. COMPANY’s technical support shall only be provided to CLIENT staff that has been trained to operate the SOFTWARE. COMPANY shall maintain adequate resources so as to provide CLIENT with a response from a qualified technician within 4 business hours of the time of logging the support request<br />
                                        For Client Care Support, CLIENT agrees to allow COMPANY staff to periodically monitor activity within the industry, and contact CLIENT when opportunities arise that may benefit the CLIENT. This activity may include sending reports and lists to CLIENT’s employees for follow-up and other purposes.</p>
                                    <p><strong>7.</strong> &nbsp;&nbsp;&nbsp;&nbsp;Upon payment, CLIENT is provided the Non-Exclusive use of COMPANY’s SOFTWARE, trademarks, logos and trade names for the SOFTWARE under this Agreement (the “Marks”) <br />
                                        <span class="underline-text">License by COMPANY</span>. Subject to the terms and conditions of this Agreement, COMPANY hereby grants to CLIENT a non-exclusive, non-assignable, non-sublicenseable, royalty-free, paid up, limited license to use and display and use COMPANY’s Marks solely as necessary to perform CLIENT’s obligations under this Agreement and as specifically described above.<br />
                                        <span class="underline-text">Ownership of Intellectual Property</span>. In its use of the SOFTWARE of the COMPANY, CLIENT will comply with any trademark and copyright usage guidelines, including complying with all laws, regulations, and ordinances of any governmental authority having jurisdiction over the services to be provided in this Agreement. CLIENT understands that COMPANY is the sole owner of the SOFTWARE and may change the usage guidelines and policies to CLIENT from time to time. Each use of COMPANY’S Marks by CLIENT will be accompanied by the appropriate trademark symbol (either “™” or “®”) and a legend specifying that such Marks are trademarks of COMPANY, and will be in accordance with COMPANY’S then-current trademark usage policies as provided in writing to CLIENT from time to time. CLIENT will provide COMPANY with copies of any materials bearing any of COMPANY’S Marks as requested by COMPANY from time to time. If CLIENT’s use of any of COMPANY’s Marks, or if any material bearing such Marks, does not comply with the then-current trademark usage policies provided in writing by COMPANY, CLIENT will promptly remedy such deficiencies upon receipt of written notice of such deficiencies from COMPANY. Other than the express licenses granted herein with respect to each COMPANY’s Marks, nothing herein will grant to CLIENT any other right, title or interest in COMPANY’s Marks, including the use or right to use the Marks/SOFTWARE. All goodwill resulting from CLIENT’s use of COMPANY’s Marks will inure solely to COMPANY. CLIENT will not, at any time during or after this Agreement, register, attempt to register, claim any interest in, contest the use of, or otherwise adversely affect the validity of any of COMPANY’s Marks (including, without limitation, any act or assistance to any act, which may infringe or lead to the infringement of any such Marks).
                                    </p>
                                    <p><span class="underline-text">Reservation of Rights</span>. The parties acknowledge and agree that, except for the rights and licenses expressly granted under this Agreement, COMPANY retains all right, title and interest in and to its SOFTWARE, products, services, Marks, and all content, information and other materials on its website(s), and nothing contained in this Agreement will be construed as conferring upon such party, by implication, operation of law or otherwise, any other license or other right.</p>
                                    <p><strong>8.</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">Warranties; Limitation of Liability</span>.</p>
                                    <p><span class="underline-text">Warranties</span>. Each party represents and warrants to the other that <strong>(a)</strong> it has the full power to enter into this Agreement and to perform its obligations hereunder, <strong>(b)</strong> this Agreement constitutes a legal, valid and binding obligation of such party, enforceable against such party in accordance with its terms, and <strong>(c)</strong> this Agreement does not contravene, violate or conflict with any other agreement of such party.<br /><br /> <span class="underline-text">AS IS</span>. The SOFTWARE is provided “As Is,” without warranties of any kind, either express or implied, including without limitation warranties that the SOFTWARE is <strong>(A)</strong> free of defects or errors, <strong>(B)</strong> virus free, <strong>(C)</strong> able to meet any requirements of CLIENT or anyone else, <strong>(D)</strong> able to operate on an uninterrupted basis, <strong>(E)</strong> merchantable, <strong>(F)</strong> fit for a particular purpose, or <strong>(G)</strong> non-infringing. <br /><br /><span class="underline-text">Limitation of Liability</span>. COMPANY IS NOT LIABLE TO THE CLIENT FOR ANY SPECIAL, CONSEQUENTIAL, PUNITIVE, INCIDENTAL, OR INDIRECT DAMAGES, OR ANY DAMAGES, ARISING OUT OF OR IN CONNECTION WITH THIS AGREEMENT OR CLIENT’S USE OF THE SOFTWARE OR MARKS CAUSED AND BASED ON ANY THEORY OF LIABILITY, ARISING OUT OF THIS AGREEMENT, WHETHER OR NOT CLIENT HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGE, AND NOTWITHSTANDING ANY FAILURE OF ESSENTIAL PURPOSE OF ANY LIMITED REMEDY.</p>
                                    <p><strong>9.</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">No Agency; No Disparagement</span>. Notwithstanding anything in this Agreement, neither party will make any claims, representations or warranties on behalf of the other party or bind the other party, nor neither party is authorized to do so by this Agreement. The relationship between the parties will be that of licensor and CLIENT. Nothing contained herein will be construed to imply a joint venture, principal or agent relationship, or other joint relationship, and neither party will have the right, power or authority to bind or create any obligation, express or implied, on behalf of the other party. During the term of this Agreement, each party shall not make any public statements disparaging COMPANY’S Marks, products or services.</p>
                                    <p><strong>10.</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">Indemnification</span>. Except as expressly set forth in this Section, neither party shall have any obligations to indemnify the other party.</p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <p><strong>10.0</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text"></span>COMPANY shall indemnify and hold harmless CLIENT for any and all liabilities as a result of claims or suits due to, because of, or arising in any way out of COMPANY’s operations. CLIENT agrees to indemnify and hold harmless COMPANY from and against any and all claims, damages, liabilities, losses, judgments, costs, and attorneys’ fees arising directly out of, or relating to CLIENT’S operations of CLIENT’s business. </p>
                                    <p><strong>11</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">General</span></p>
                                    <p><span class="underline-text">Confidential Information</span>. The disclosure and use of any confidential information exchanged by the parties is governed by the mutual non-disclosure Agreement, which is incorporated by reference into this Agreement.</p>
                                    <p><span class="underline-text">Governing Law; Venue</span>. MANDATORY ARBITRATION. Any claim, dispute, or controversy (“Claim”) arising out of or relating to this Agreement or the relationships among the parties hereto shall be resolved by one arbitrator through binding arbitration administered by the American Arbitration Association (“AAA”), under the AAA Consumer Rules in effect at the time the Claim is filed (“AAA Rules”). Copies of the AAA Rules and forms can be located at www.adr.org, or by calling 1-800-778-7879. The parties will split the cost of the arbitrator and arbitration equally. The arbitrator’s decision shall be final, binding, and non-appealable. Judgment upon the award may be entered and enforced in any court having jurisdiction. This clause is made pursuant to a transaction involving interstate commerce and shall be governed by the Federal Arbitration Act. Neither party shall sue the other party other than as provided herein or for enforcement of this clause or of the arbitrator’s award. Any such suit may be brought only in Federal District Court in the State of CA, or if any such court lacks jurisdiction, in any state court that has jurisdiction. The arbitrator, and not any federal, state, or local court, shall have exclusive authority to resolve any dispute relating to the interpretation, applicability, unconscionability, arbitrability, enforceability, or formation of this Agreement including any claim that all or any part of the Agreement is void or voidable. However, the preceding sentence shall not apply to the clause entitled “Class Action Waiver.”</p>
                                    <p><span class="underline-text">CLASS ACTION WAIVER</span>. Any Claim must be brought in the respective party’s individual capacity, and not as a plaintiff or class member in any purported class, collective, representative, multiple plaintiff, or similar proceeding (“Class Action”). The parties expressly waive any ability to maintain any Class Action in any forum. The arbitrator shall not have authority to combine or aggregate similar claims or conduct any Class Action nor make an award to any person or entity not a party to the arbitration. Any claim that all or part of this Class Action Waiver is unenforceable, unconscionable, void, or voidable may be determined only by a court of competent jurisdiction and not by an arbitrator. THE PARTIES UNDERSTAND THAT THEY WOULD HAVE HAD A RIGHT TO LITIGATE THROUGH A COURT, TO HAVE A JUDGE OR JURY DECIDE THEIR CASE AND TO BE PARTY TO A CLASS OR REPRESENTATIVE ACTION. HOWEVER, THEY UNDERSTAND AND CHOOSE TO HAVE ANY CLAIMS DECIDED INDIVIDUALLY, THROUGH ARBITRATION.”</p>
                                    <p><span class="underline-text">Waiver; Severability</span>. No waiver of a party’s rights shall be effective unless such waiver is in writing signed by the waiving party. If any provision of this Agreement or the application of such provision to any person or circumstance shall be held invalid, illegal, against public policy or is otherwise unenforceable, the remainder of this Agreement or the application of such provision to persons or circumstances other than those to which it is held invalid shall not be affected thereby.</p>
                                    <p><span class="underline-text">Assignment</span>. CLIENT shall not have the right to assign this Agreement without written consent of COMPANY.</p>
                                    <p><span class="underline-text">Notices</span>. Any notice required or permitted to be given by either party under this Agreement shall be in writing and sent to each party at its address, facsimile number, or e-mail, or such new address or facsimile number as may from time to time be supplied by the parties.</p>
                                    <p><span class="underline-text">Captions; Entire Agreement; Amendment</span>. The captions or headings of the Sections of this Agreement are for reference only and are not to be construed in any way as part of this Agreement. This Agreement constitutes the complete understanding and agreement of the parties and supersedes all prior and contemporaneous negotiations, understandings and agreements with respect to the subject matter of this Agreement. Any modification or amendment of any provision of this Agreement will be effective only if in writing and signed by an authorized representative of both parties.</p>
                                    <p><span class="underline-text">Counterparts</span>. This Agreement may be executed in one or more counterparts, each of which shall constitute an original, but all of which together shall constitute one instrument. A facsimile or electronic copy of this Agreement is deemed to be binding.</p>
                                    <p><strong>12</strong> &nbsp;&nbsp;&nbsp;&nbsp;<span class="underline-text">Proprietary Rights and Data.</span></p>
                                    <p><strong>A.) Ownership.</strong> <?php echo STORE_NAME; ?> / AutomotoSocial LLC owns all right, title and interest, including all intellectual property rights, in and to the <?php echo STORE_NAME; ?> / AutomotoSocial LLC Product, and all Modifications thereto (collectively, the “<?php echo STORE_NAME; ?> / AutomotoSocial LLC Property”). CLIENT hereby does and will assign to <?php echo STORE_NAME; ?> / AutomotoSocial LLC all right, title and interest worldwide in the intellectual property rights embodied in any and all Modifications. To the extent any of the rights, title and interest are not assignable by CLIENT to <?php echo STORE_NAME; ?> / AutomotoSocial LLC, CLIENT grants and agrees to grant to <?php echo STORE_NAME; ?> / AutomotoSocial LLC an exclusive, royalty-free, transferable, irrevocable, worldwide, fully paid-up license (with rights to sublicense through multiple tiers of sublicensees) under CLIENTS intellectual property rights to use, disclose, reproduce, license, sell, offer for sale, distribute, import and otherwise exploit the Modifications in its discretion, without restriction or obligation of any kind or nature. Except as expressly stated otherwise in this Agreement, <?php echo STORE_NAME; ?> / AutomotoSocial LLC retains all of its right, title and ownership interest in and to the <?php echo STORE_NAME; ?> / AutomotoSocial LLC, and no other intellectual property rights or license rights are granted by <?php echo STORE_NAME; ?> / AutomotoSocial LLC to CLIENT under this Agreement, either expressly or by implication, estoppel or otherwise, including, but not limited to, any rights under any of <?php echo STORE_NAME; ?> / AutomotoSocial LLC or its affiliates patents.</p>
                                    <p><strong>B.) Business Information;</strong> CLIENT Data. CLIENT agrees to allow <?php echo STORE_NAME; ?> / AutomotoSocial LLC and its Affiliates to store and use CLIENT business contact information, including names, business phone numbers, and business e-mail addresses, anywhere it does business that is provided by CLIENT to <?php echo STORE_NAME; ?> / AutomotoSocial LLC. Such information will be processed and used in connection with <?php echo STORE_NAME; ?> / AutomotoSocial LLC business relationship, and may be provided to contractors acting on <?php echo STORE_NAME; ?> / AutomotoSocial LLC behalf for uses consistent with <?php echo STORE_NAME; ?> / AutomotoSocial LLC business relationship. To the extent that (i) CLIENT data is input into or resides in the <?php echo STORE_NAME; ?> / AutomotoSocial LLC Product as part of this evaluation (the “CLIENT Data”) and (ii) the CLIENT Data contains personal data about any living individual (“Data”), <?php echo STORE_NAME; ?> / AutomotoSocial LLC will process that Data only as a Data Processor acting on behalf of CLIENT (as the Data Controller) and in accordance with the requirements of this Agreement.</p>
                                    <p><strong>C.) CLIENT Compliance with Privacy Laws;</strong> Purpose Limitation. CLIENT will at all times comply in full with the requirements of any applicable privacy and data protection laws (including where applicable, European Union Directives 95/46/EC and 2002/58/EC and any national implementation(s) of them) to which it is subject as a Data Controller (“Applicable Privacy Law(s)”). <?php echo STORE_NAME; ?> / AutomotoSocial LLC will process the Data in accordance with CLIENTS instructions under Applicable Privacy Law(s) and will not: (a) assume any responsibility for determining the purposes for which and the manner in which the Data is processed, or (b) process the Data for its own purposes.</p>
                                    <p><strong>D.) HIPAA and PHI in Relation to <?php echo STORE_NAME; ?> / AutomotoSocial LLC Products.</strong> CLIENT understands and acknowledges that neither the service provided on-demand nor the <?php echo STORE_NAME; ?> / AutomotoSocial LLC Products or systems are configured to receive and store personal health information (“PHI”), as that term is defined under the Health Insurance Portability and Accountability Act (“HIPAA”) and that <?php echo STORE_NAME; ?> / AutomotoSocial LLC is neither a “Covered Entity” nor a “Business Associate,” as those terms are defined in HIPAA. As such, CLIENT agrees, not to use the service or provide access to or submit any PHI to <?php echo STORE_NAME; ?> / AutomotoSocial LLC when requesting technical and/or support services, in either case, to, directly or indirectly, submit, store or include any PHI as part of the CLIENT Data. CLIENT agrees that <?php echo STORE_NAME; ?> / AutomotoSocial LLC may terminate this Agreement immediately, if CLIENT is found to be in violation of this Section</p>
                                    <p><strong>E.) Usage Data.</strong> In the course of providing CLIENT with the services contemplated in the Agreement, <?php echo STORE_NAME; ?> / AutomotoSocial LLC may collect, use, process and store diagnostic and usage related content from the computer, mobile phone or other devices the CLIENT uses to access the <?php echo STORE_NAME; ?> / AutomotoSocial LLC Product or service. This may include, but is not limited to, IP addresses and other information like internet service, location, the type of browser and modules that are used and/or accessed (the “Usage Data”). Usage Data does not, however, include CLIENT Data. CLIENT agrees that <?php echo STORE_NAME; ?> / AutomotoSocial LLC may process Usage Data to create and compile anonymized, aggregated datasets and/or statistics about the <?php echo STORE_NAME; ?> / AutomotoSocial LLC products or services in order to: (a) maintain and improve the performance and integrity of <?php echo STORE_NAME; ?> / AutomotoSocial LLC products or services, (b) understand which <?php echo STORE_NAME; ?> / AutomotoSocial LLC products or services are most commonly deployed and preferred by customers and how customers interact with <?php echo STORE_NAME; ?> / AutomotoSocial LLC products or services, (c) identify the types of <?php echo STORE_NAME; ?> / AutomotoSocial LLC services that may require additional maintenance or support, and (d) comply with all regulatory, legislative and/or contractual requirements, provided in each case that such aggregated datasets and statistics will not enable CLIENT or any living individual to be identified.</p>
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
                                                    <input type="text" class="invoice-fields" id="company_by" name="company_by" value="<?php echo set_value('company_by', $company_by); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
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
                                                    <input type="text" class="invoice-fields" id="company_name" name="company_name" value="<?php echo set_value('company_name', $company_name); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
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
                                                    <input type="text" class="invoice-fields" id="company_title" name="company_title" value="<?php echo set_value('company_title', $company_title); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
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
                                                    <input type="text" class="invoice-fields startdate" id="company_date" name="company_date" value="<?php echo set_value('company_date', $company_document['company_date']); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
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
                                                    <input type="text" class="invoice-fields" id="client_by" name="client_by" value="<?php echo set_value('client_by', $company_document['client_by']); ?>" <?php echo $readonly; ?> />
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
                                                    <input type="text" class="invoice-fields" id="client_name" name="client_name" value="<?php echo set_value('client_name', $company_document['client_name']); ?>" <?php echo $readonly; ?> />
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
                                                    <input type="text" class="invoice-fields" id="client_title" name="client_title" value="<?php echo set_value('client_title', $company_document['client_title']); ?>" <?php echo $readonly; ?> />
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

                                                    <input type="text" class="invoice-fields startdate" id="client_date" name="client_date" value="<?php echo set_value('client_date', $company_document['client_date']); ?>" <?php echo $readonly; ?> />
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
                                                    <input type="text" class="signature-field" name="company_signature" id="company_signature" value="<?php echo set_value('company_signature', $company_signature); ?>" <?php echo $readonly; ?> <?php echo ($is_pre_fill == 0) ? 'readonly="readonly"' : ''; ?> />
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
                                                    <input data-rule-required="true" type="text" class="signature-field" name="client_signature" id="client_signature" value="<?php echo set_value('client_signature', $company_document['client_signature']); ?>" <?php echo $readonly; ?> />
                                                    <p>Please type your First and Last Name</p>
                                                    <?php echo form_error('client_signature'); ?>
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
                                                                <?php if (!empty($ip_track)) { ?>
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
                                                                <?php if (!empty($ip_track)) { ?>
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
                                            <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn">Save</button>
                                        <?php } else { ?>
                                            <button type="submit" class="page-heading affiliate_end_user_agreement_color_btn"><?php echo DEFAULT_SIGNATURE_CONSENT_BUTTON; ?></button>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php //$this->load->view('static-pages/marketing_agencies_e_signature_popup'); 
    ?>

    <script>
        $(document).ready(function() {
            $('#form_end_user_license_agreement').validate();

            var value = $("input[name='payment_method']:checked").val();
            display(value);
        });

        function display(value) {
            if (value == 'monthly_subscription') {
                $('.monthly-subscription').show();
                $('.trial-period').hide();
            } else {
                $('.monthly-subscription').hide();
                $('.trial-period').show();
            }
        }
    </script>

    <?php if ($company_document['status'] != 'signed') { ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.startdate').datepicker({
                    dateFormat: 'mm-dd-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "<?php echo DOB_LIMIT; ?>"
                }).val();

                $('.static-class').each(function() {
                    $(this).on('change', function() {
                        var current = $(this).val();
                    });
                });
            });



            $("input[type='radio']").click(function() {
                var value = $("input[name='payment_method']:checked").val();
                display(value);
            });
        </script>
    <?php } ?>
    <script>
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