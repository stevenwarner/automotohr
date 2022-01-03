<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>I-9 Form</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/css/i9-style.css')?>">
    </head>
    <!-- Set "A5", "A4" or "A3" for class name -->
    <!-- Set also "landscape" if you need -->
    <body class="A4">
        <section class="sheet padding-10mm">
            <article class="sheet-header">
                <div class="header-logo"><img src="<?php echo base_url('assets/images/i9-header.png')?>"></div>
                <div class="center-col">
                    <h2>Employment Eligibility Verification<br>Department of Homeland Security</h2>
                    <p>U.S. Citizenship and Immigration Services</p>
                </div>
                <div class="right-header">
                    <h3>USCIS <br> Form I-9</h3>
                    <p>OMB No. 1615-0047<br>Expires <?php echo I9_EXPIRES; ?></p>
                </div>
            </article>
            <p><strong>START HERE:</strong> Read instructions carefully before completing this form. The instructions must be available, either in paper or electronically, during completion of this form. Employers are liable for errors in the completion of this form.</p>
            <p><strong>ANTI-DISCRIMINATION NOTICE:</strong> It is illegal to discriminate against work-authorized individuals. Employers CANNOT specify which document(s) an employee may present to establish employment authorization and identity. The refusal to hire or continue to employ an individual because the documentation presented has a future expiration date may also constitute illegal discrimination.</p>
            <table class="i9-table">
                <thead>
                <tr class="bg-gray">
                    <th colspan="5">
                        <strong>Section 1. Employee Information and Attestation </strong><small>(Employees must complete and sign Section 1 of Form I-9 no later than the first day of employment, but not before accepting a job offer.)</small>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <label>Last Name (Family Name)</label>
                        <span class="value-box" id="last_name"></span>
                    </td>
                    <td>
                        <label>First Name (Given Name)</label>
                        <span class="value-box" id="first_name"></span>
                    </td>
                    <td>
                        <label>Middle Initial</label>
                        <span class="value-box" id="middle_initial"></span>
                    </td>
                    <td colspan="2">
                        <label>Other Last Names Used (if any)</label>
                        <span class="value-box" id="other_last_name"></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Address (Street Number and Name)</label>
                        <span class="value-box" id="street_number"></span>
                    </td>
                    <td>
                        <label>Apt. Number</label>
                        <span class="value-box" id="apt_number"></span>
                    </td>
                    <td>
                        <label>City or Town</label>
                        <span class="value-box" id="city_town"></span>
                    </td>
                    <td>
                        <label>State</label>
                        <span class="value-box" id="state"></span>
                    </td>
                    <td>
                        <label>ZIP Code</label>
                        <span class="value-box" id="zip_code"></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Date of Birth (mm/dd/yyyy)</label>
                        <span class="value-box" id="date_of_birth"></span>
                    </td>
                    <td>
                        <label>U.S. Social Security Number</label>
                        <span class="value-box" id="social_security_number"></span>
                    </td>
                    <td>
                        <label>Employee's E-mail Address</label>
                        <span class="value-box" id="employee_email"></span>
                    </td>
                    <td colspan="2">
                        <label>Employee's Telephone Number</label>
                        <span class="value-box" id="employee_telephone"></span>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="mb-2"><strong>I am aware that federal law provides for imprisonment and/or fines for false statements or use of false documents in connection with the completion of this form.</strong></div>
            <div class="mb-2"><strong>I attest, under penalty of perjury, that I am (check one of the following boxes):</strong></div>
            <table class="i9-table">
                <tbody>
                <tr>
                    <td colspan="2">
                        <input type="checkbox" name=""> 1. A citizen of the United States
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="checkbox" name=""> 2. A noncitizen national of the United States (See instructions)
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="checkbox" name=""> 3. A lawful permanent resident (Alien Registration Number/USCIS Number):
                        <div class="inline-value-box">
                            <span class="value-box" id=""></span>
                        </div>
                        <div class="inline-value-box" >
                            <span class="value-box" id=""></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 70%;">
                        <input type="checkbox" name=""> 4. An alien authorized to work until (expiration date, if applicable, mm/dd/yyyy): Some aliens may write "N/A" in the expiration date field. (See instructions)
                        <div class="inline-value-box">
                            <span class="value-box" id=""></span>
                        </div>
                        <p class="text-center italic">Aliens authorized to work must provide only one of the following document numbers to complete Form I-9: An Alien Registration Number/USCIS Number OR Form I-94 Admission Number OR Foreign Passport Number.</p>
                        <div class="full-width">
                            1. Alien Registration Number/USCIS Number:
                            <div class="inline-value-box">
                                <span class="value-box" id=""></span>
                            </div>
                            <div class="inline-value-box">
                                <span class="value-box" id=""></span>
                            </div>
                        </div>
                        <div class="full-width text-center">
                            <strong>OR</strong>
                        </div>
                        <div class="full-width">
                            2. Form I-94 Admission Number
                            <div class="inline-value-box">
                                <span class="value-box" id=""></span>
                            </div>
                        </div>
                        <div class="full-width text-center">
                            <strong>OR</strong>
                        </div>
                        <div class="full-width">
                            3. Foreign Passport Number:
                            <div class="inline-value-box">
                                <span class="value-box" id=""></span>
                            </div>
                        </div>
                        <div class="full-width">
                            Country of Issuance:
                            <div class="inline-value-box">
                                <span class="value-box" id=""></span>
                            </div>
                        </div>
                    </td>
                    <td style="width: 30%;">
                        <div class="qr-code-box">
                            QR Code - Section 1<br>Do Not Write In This Space
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="i9-table">
                <tr>
                    <td style="width: 60%;">
                        <label>Signature of Employee</label>
                        <span class="value-box" id="">
                            <div>
                            </div>
                        </span>
                    </td>
                    <td style="width: 40%;">
                        <label>Today's Date (mm/dd/yyyy)</label>
                        <span class="value-box" id=""></span>
                    </td>
                </tr>
            </table>
        </section>    
        <section class="sheet padding-10mm">
            <div class="bg-gray gray-box">
                <strong>Preparer and/or Translator Certification (check one):</strong>
                <div class="inline-element-wrp full-width">
                    <div class="element-box">
                        <input disabled type="checkbox" id="no_preparer_tranlator" name="">
                        <label for="no_preparer_tranlator">did not use a preparer or translator</label>
                    </div>
                    <div class="element-box">
                        <input disabled type="checkbox" id="preparer_tranlator" name="">
                        <label for="preparer_tranlator">A preparer(s) and/or translator(s) assisted the employee in completing Section 1.</label>
                    </div>
                    <div class="element-box">
                        How many?
                        <div class="inline-value-box">
                            <span class="value-box" id=""></span>
                        </div>
                    </div>
                </div>
                <strong>(Fields below must be completed and signed when preparers and/or translators assist an employee in completing Section 1.)</strong>
            </div>
            <strong>I attest, under penalty of perjury, that I have assisted in the completion of Section 1 of this form and that to the best of my knowledge the information is true and correct.</strong>
            <table class="i9-table">
                <tbody>
                <tr>
                    <td style="width: 40%">
                        <label>Signature of Preparer or Translator</label>
                    </td>
                    <td style="width: 30%" colspan="3">
                        <label>Today's Date (mm/dd/yyyy)</label>
                        <span class="value-box" id=""></span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%">
                        <label>Last Name (Family Name)</label>
                        <span class="value-box" id=""></span>
                    </td>
                    <td style="width: 50%" colspan="3">
                        <label>First Name (Given Name)</label>
                        <span class="value-box" id=""></span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <label>Address (Street Number and Name)</label>
                        <span class="value-box" id=""></span>
                    </td>
                    <td style="width: 20%">
                        <label>City or Town</label>
                        <span class="value-box" id=""></span>
                    </td>
                    <td style="width: 10%">
                        <label>State</label>
                        <span class="value-box" id=""></span>
                    </td>
                    <td style="width: 20%">
                        <label>ZIP Code</label>
                        <span class="value-box" id=""></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </section>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.487/pdf.min.js"></script>
        <script type="text/javascript">
            $(window).on( "load", function() { 
                setTimeout(function(){
                    window.print();
                }, 1000);  
            });

            window.onafterprint = function(){
                window.close();
            }
        </script>
    </body>
</html>
