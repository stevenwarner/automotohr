 <p>
     <strong>Oral Employee Counselling Report Form</strong>
 </p>
 <br>
 <p>
     <strong>Instructions</strong>
     Use this form to document the need for improvement in job performance or to document
     disciplinary action for misconduct. This form is intended to be used as part of a progressive
     disciplinary process to help improve an employee's performance or behavior in a formal and
     systematic manner. Before completing this form, review the Progressive Discipline Policy and any
     relevant supervisor guidances.
 </p>
 <p>
     If the employee is being terminated, review the policy on Immediate termination and related
     guidelines, and use the Termination Without Notice Form to process.
 </p>
 <br />
 <!--  -->
 <p>
     <strong>
         Employee name:
     </strong>
     <span>
         ---------------
     </span>
     <br />
 </p>

 <!--  -->
 <p>
     <strong>
         Department:
     </strong>
     <span>
         ---------------
     </span>
     <br />
 </p>
 <!--  -->
 <p>
     <strong>
         Date of occurrence:
     </strong>
     <span>
         --/--/----
     </span>
     <br />
 </p>
 <!--  -->
 <p>
     <strong>
         Supervisor:
     </strong>
     <span>
         ---------------
     </span>
     <br />
     <br />
 </p>

 <p>
     The following counseling has taken place (check all boxes that apply) and provide details in the
     summary section below:
     <br />
 </p>

 <!--  -->
 <p>
 <table>
     <tr>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Absence", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Absence" />
             Absence
         </td>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Harassment", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Harassment" />
             Harassment
         </td>
     </tr>

     <tr>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Tardiness", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Tardiness" />
             Tardiness
         </td>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Dishonesty", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Dishonesty" />
             Dishonesty
         </td>
     </tr>

     <tr>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Violation of company policies and/or procedures", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Violation of company policies and/or procedures" />
             Violation of company policies and/or procedures
         </td>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Violation of safety rules", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Violation of safety rules" />
             Violation of safety rules
         </td>
     </tr>

     <tr>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Horseplay", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Horseplay" />
             Horseplay
         </td>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Leaving work without authorization", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Leaving work without authorization" />
             Leaving work without authorization
         </td>
     </tr>

     <tr>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Smoking in unauthorized areas", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Smoking in unauthorized areas" />
             Smoking in unauthorized areas
         </td>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Unsatisfactory job performance", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Unsatisfactory job performance" />
             Unsatisfactory job performance
         </td>
     </tr>

     <tr>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Failure to follow instructions", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Failure to follow instructions" />
             Failure to follow instructions
         </td>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Insubordination", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Insubordination" />
             Insubordination
         </td>
     </tr>

     <tr>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Unauthorized use of equipment, materials", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Unauthorized use of equipment, materials" />
             Unauthorized use of equipment, materials
         </td>
         <td>
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Falsification of records", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Falsification of records" />
             Falsification of records
         </td>
     </tr>

     <tr>
         <td colspan="2">
             <input type="checkbox" class="js_counselling_form_fields" name="counselling_form_fields[]" <?= in_array("Other", $formData["counselling_form_fields"]) ? "checked" : ""; ?> value="Other" />
             Other:
             <textarea rows="5" class="form-control input-grey gray-background  <?= in_array("Other", $formData["counselling_form_fields"]) ? "" : "hidden"; ?> " name="counselling_form_fields_textarea"><?= in_array("Other", $formData["counselling_form_fields"]) ? $formData["counselling_form_fields_textarea"] : ""; ?></textarea>
         </td>
     </tr>
 </table>
 <br>
 <br>
 </p>

 <p>
     <strong>
         Summary of violation:
     </strong>
 <p>
     ----------------------------------------------------------------
     ----------------------------------------------------------------
 </p>
 <br>
 </p>

 <p>
     <strong>
         Summary of corrective plan:
     </strong>
 <p>
     ----------------------------------------------------------------
     ----------------------------------------------------------------
 </p>
 <br>
 </p>
 <p>
     <strong>
         Follow up dates:
     </strong>
 <p>
     ----------------------------------------------------------------
     ----------------------------------------------------------------
 </p>
 <br>
 </p>