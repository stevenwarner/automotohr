<style type="text/css">
    * {
        margin: 0;
        padding: 0;
        text-indent: 0;
    }

    h2 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 16pt;
    }

    h3 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 13pt;
    }

    .s1 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9pt;
    }

    .s2 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 7pt;
    }

    .s3 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 7pt;
    }

    .s5 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9pt;
    }

    .s8 {
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: underline;
        font-size: 9pt;
    }

    .s9 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: italic;
        font-weight: normal;
        text-decoration: underline;
        font-size: 9pt;
    }

    .s11 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 7pt;
    }

    h1 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 18pt;
    }

    .s12 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: italic;
        font-weight: normal;
        text-decoration: none;
        font-size: 11pt;
    }

    h4 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 11pt;
    }

    .p,
    p {
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9.5pt;
        margin: 0pt;
    }

    .s13 {
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9.5pt;
    }

    .s14 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9.5pt;
    }

    .s15 {
        color: black;
        font-family: Calibri, sans-serif;
        font-style: italic;
        font-weight: normal;
        text-decoration: none;
        font-size: 9.5pt;
    }

    .s16 {
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9pt;
    }

    .a,
    a {
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9pt;
    }

    .s17 {
        color: black;
        font-family: "Times New Roman", serif;
        font-style: italic;
        font-weight: normal;
        text-decoration: none;
        font-size: 9.5pt;
    }

    li {
        display: block;
    }

    #l1 {
        padding-left: 0pt;
        counter-reset: c1 1;
    }

    #l1>li>*:first-child:before {
        counter-increment: c1;
        content: counter(c1, upper-latin)" ";
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9pt;
    }

    #l1>li:first-child>*:first-child:before {
        counter-increment: c1 0;
    }

    #l2 {
        padding-left: 0pt;
    }

    #l2>li>*:first-child:before {
        content: "• ";
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9pt;
    }

    li {
        display: block;
    }

    #l3 {
        padding-left: 0pt;
        counter-reset: d1 5;
    }

    #l3>li>*:first-child:before {
        counter-increment: d1;
        content: counter(d1, upper-latin)" ";
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9pt;
    }

    #l3>li:first-child>*:first-child:before {
        counter-increment: d1 0;
    }

    #l4 {
        padding-left: 0pt;
        counter-reset: d2 1;
    }

    #l4>li>*:first-child:before {
        counter-increment: d2;
        content: counter(d2, upper-latin)" ";
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9pt;
    }

    #l4>li:first-child>*:first-child:before {
        counter-increment: d2 0;
    }

    #l5 {
        padding-left: 0pt;
    }

    #l5>li>*:first-child:before {
        content: "• ";
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9pt;
    }

    li {
        display: block;
    }

    #l6 {
        padding-left: 0pt;
        counter-reset: e1 5;
    }

    #l6>li>*:first-child:before {
        counter-increment: e1;
        content: counter(e1, upper-latin)" ";
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9pt;
    }

    #l6>li:first-child>*:first-child:before {
        counter-increment: e1 0;
    }

    li {
        display: block;
    }

    #l7 {
        padding-left: 0pt;
        counter-reset: f1 1;
    }

    #l7>li>*:first-child:before {
        counter-increment: f1;
        content: counter(f1, decimal)" ";
        color: black;
        font-family: Calibri, sans-serif;
        font-style: normal;
        font-weight: bold;
        text-decoration: none;
        font-size: 9pt;
    }

    #l7>li:first-child>*:first-child:before {
        counter-increment: f1 0;
    }

    li {
        display: block;
    }

    #l8 {
        padding-left: 0pt;
    }

    #l8>li>*:first-child:before {
        content: "• ";
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
    }

    #l9 {
        padding-left: 0pt;
        counter-reset: h1 1;
    }

    #l9>li>*:first-child:before {
        counter-increment: h1;
        content: counter(h1, decimal)" ";
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9pt;
    }

    #l9>li:first-child>*:first-child:before {
        counter-increment: h1 0;
    }

    #l10 {
        padding-left: 0pt;
        counter-reset: h2 1;
    }

    #l10>li>*:first-child:before {
        counter-increment: h2;
        content: counter(h2, lower-latin)". ";
        color: black;
        font-family: "Times New Roman", serif;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
        font-size: 9pt;
    }

    #l10>li:first-child>*:first-child:before {
        counter-increment: h2 0;
    }

    table,
    tbody {
        vertical-align: top;
        overflow: visible;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="hr-box">
            <div class="hr-innerpadding">
                <ul id="l8">
                    <li data-list-text="•">
                        <p class="s16" style="padding-top: 3pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">Phone:
                            651-282-9999 or 1-800-657-3594 (toll-free)</p>
                        <p class="s17" style="padding-top: 1pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">Employer
                            instructions are on the next page.</p>
                        <h1 style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Form W-4MN Employer Instructions</h1>
                        <h4 style="padding-top: 10pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">What’s
                            New?</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Beginning in 2020, federal Form W-4 will not
                            determine withholding allowances used to determine the amount of Minnesota withholding. Employees
                            completing a 2020 Form W-4 will need to complete 2020 Form W-4MN to determine the appropriate amount of
                            Minnesota withholding.</p>
                        <p class="s13" style="padding-top: 5pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">Use the amount
                            on line 1 of page 1 for calculating the withholding tax for your employees.</p>
                        <h4 style="padding-top: 5pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">When
                            does an employee complete Form W-4MN?</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Employees complete Form
                            W-4MN when they begin employment or when their personal or financial situation changes.</p>
                        <h4 style="padding-top: 6pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">How
                            should I determine Minnesota withholding for an employee that does not complete Form W-4MN?</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">If an employee does not complete Form W-4MN
                            and they have a federal Form W-4 (from 2019 or prior years) on file, use the allowances on their federal
                            Form W-4. If the employee does not complete a Form W-4MN, withhold Minnesota tax as if the employee is
                            single with zero withholding allowances.</p>
                        <h4 style="padding-top: 5pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">What if
                            my employee claims to be exempt from Minnesota withholding?</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">If your employee claims exempt from
                            Minnesota withholding, they must complete Section 2 of Form W-4MN. They must provide you with a new Form
                            W-4MN by February 15 of each year.</p>
                        <h4 style="padding-top: 5pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">When do I need to submit
                            copies of a Form W-4MN to the department?</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">You must send copies of Form W-4MN to us if
                            any of the following apply:</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">The employee claims more
                            than 10 Minnesota withholding allowances</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">The employee checked box
                            A or B under Section 2, and you reasonably expect the employee’s wages to exceed $200 per week</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">You believe the employee
                            is not entitled to the number of allowances claimed</p>
                        <p style="padding-top: 4pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">You do not need to submit
                            Form W-4MN to us if the employee is asking to have additional Minnesota withholding deducted from their
                            pay.</p>
                        <p style="padding-top: 5pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">We may assess a $50 penalty
                            for each Form W-4MN you do not file with us when required.</p>
                        <p style="padding-top: 5pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">Mail Forms W-4MN to:</p>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Minnesota Department of Revenue Mail Station
                            6501</p>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">600 N. Robert St.</p>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">St. Paul, MN 55146-6501</p>
                        <h4 style="padding-top: 6pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">What if
                            my employee is a resident of a reciprocity state?</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">If your employee is a resident of North
                            Dakota or Michigan and they do not want you to withhold Minnesota tax from their wages, they must
                            complete Form MWR, <i>Reciprocity Exemption/Affidavit of Residency</i>. They must complete a Form MWR by
                            February 28 of each year, or within 30 days after they begin working or change their permanent
                            residence. See Withholding Fact Sheet 20, <i>Reciprocity - Employee Withholding, </i>for more
                            information.</p>
                        <h4 style="padding-top: 3pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">What is an invalid Form
                            W-4MN?</h4>
                        <p style="padding-top: 1pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">A Form W-4MN is considered
                            invalid if any of the following apply:</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">There is any
                            unauthorized change or addition to the form, including any change to the language certifying the form is
                            correct</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">The employee indicates
                            in any way the form is false by the date they provide you with the form</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">The form is incomplete
                            or lacks the necessary signatures</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">Both Section 1 and
                            Section 2 were completed</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 17pt;text-indent: -12pt;text-align: left;">The employer information
                            is incomplete</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>    
</div> 