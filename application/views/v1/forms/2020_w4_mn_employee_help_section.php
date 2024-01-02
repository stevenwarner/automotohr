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
                <h1 style="padding-top: 1pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">Form W-4MN Employee Instructions
                </h1>
                <p class="s12" style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Complete this form for your employer to
                    calculate the amount of Minnesota income tax to be withheld from your pay.</p>

                <h4 style="padding-top: 1pt;padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">When must I complete Form W-4MN?</h4>
                <p style="padding-left: 5pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Complete Form W-4MN if any of the
                    following apply:</p>
                <ul id="l8">
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 15pt;text-indent: -10pt;text-align: left;">You begin employment</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 15pt;text-indent: -10pt;text-align: left;">You change your filing
                            status</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 15pt;text-indent: -10pt;text-align: left;">You reasonably expect to
                            change your filing status in the next calendar year</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 15pt;text-indent: -10pt;text-align: left;">Your personal or
                            financial situation changes</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 15pt;text-indent: -10pt;text-align: left;">You claim exempt from
                            Minnesota withholding (see Section 2 instructions for qualifications)</p>
                    </li>
                    <li data-list-text="•">

                        <p style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">If you have not had
                            sufficient Minnesota income tax withheld from your wages, we may assess penalty and interest when you
                            file your state</p>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">income tax return.</p>
                        <p class="s13" style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;line-height: 128%;text-align: left;"><b>Note:</b> Your employer may be required to submit a copy of your Form W-4MN to the Minnesota Department of Revenue. You may be subject to
                            a $500 penalty if you provide a false Form W-4MN.</p>


                        <p class="s13" style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;line-height: 128%;text-align: left;"><b>You must enter your Social Security Number for this Form W-4MN to be valid.</b></p>
                        <h4 style="padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">What if I have completed
                            federal Form W-4?</h4>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">If you completed a 2024 Form W-4, you must complete Form W-4MN to determine your Minnesota withholding allowances.</p>
                        <h4 style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">What if
                            I am exempt from Minnesota withholding?</h4>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">If you claim exempt from Minnesota withholding, complete only Section 2 of Form W-4MN and sign and date the form to validate it. If you
                            complete Section 2, you must complete a new Form W-4MN by February 15 in each following year in which you claim an exemption from
                            Minnesota withholding.</p>
                        <p style="padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">You cannot claim exempt
                            from withholding if all of the following apply:</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">Another person can claim
                            you as a dependent on their federal tax return</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">Your annual income
                            exceeds $1,100</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 3pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">Your annual income
                            includes more than $350 of unearned income</p>
                        <h4 style="padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">What if
                            I am a nonresident alien for U.S. income taxes?</h4>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">If you are a nonresident alien, you are not allowed to claim exempt from withholding. You will check the single box for marital status
                            regardless of your actual marital status and may enter one personal allowance on Step A of Section 1. Enter zero on steps B, C, and E of
                            Section 1.</p>
                        <p style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">If you are resident of Canada, Mexico, South Korea, or India, and are allowed to claim dependents, enter the number of dependents on Step
                            D.</p>
                        <h4 style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;line-height: 13pt;text-align: left;">Section
                            1 — Minnesota Allowances Worksheet</h4>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Complete Section 1 to find your allowances
                            for Minnesota withholding tax. For regular wages, withholding must be based on allowances you claimed
                            and may not be a flat amount or percentage of wages.</p>
                        <p style="padding-top: 2pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">If you expect to owe more
                            income tax for the year than will be withheld, you can claim fewer allowances or request additional
                            Minnesota withholding from your wages. Enter the amount of additional Minnesota income tax you want
                            withheld on line 2 of Section 1.</p>
                        <p class="s14" style="padding-top: 4pt;padding-left: 5pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Nonwage
                            Income</p>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">Consider making estimated payments if you
                            have a large amount of “nonwage income.” Nonwage income (other than tax-exempt income) includes
                            interest, dividends, net rental income, unemployment compensation, gambling winnings, prizes and awards,
                            hobby income, capital gains, royalties, and partnership income.</p>
                        <p class="s14" style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Two
                            Earners or Multiple Jobs</p>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">If your spouse works or you have more than
                            one job, figure the total number of allowances you are entitled to claim on all jobs using worksheets
                            from only one Form W-4MN. Usually, your withholding will be more accurate when all allowances are
                            claimed on the Form W-4MN for the highest paying job and zero allowances are claimed on the others.</p>
                        <p class="s14" style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Head of
                            Household</p>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: justify;">You may claim Head of Household as your
                            filing status if you are unmarried and pay more than 50 percent of the costs of keeping up a home for
                            yourself, your dependents, and other qualifying individuals. Enter “1” on Step E if you may claim Head
                            of Household as your filing status on your tax return.</p>
                        <p class="s14" style="padding-top: 4pt;padding-left: 5pt;text-indent: 0pt;line-height: 11pt;text-align: left;">What if
                            I itemize deductions on my Minnesota return or have other nonwage income?</p>
                        <p style="padding-left: 5pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Use the Itemized
                            Deductions and Additional Income Worksheet to find your Minnesota withholding allowances. Complete
                            Section 1 on page 1,</p>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;">then follow the steps in the worksheet on
                            the next page to find additional allowances.</p>
                        <p class="s15" style="padding-top: 3pt;padding-left: 5pt;text-indent: 0pt;text-align: left;">Continued</p>
                        <p style="padding-left: 3pt;text-indent: 0pt;line-height: 12pt;text-align: left;"><span style=" color: black; font-family:Calibri, sans-serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 10pt;">Itemized
                                Deductions and Additional Income Worksheet</span></p>
                        <ol id="l9">
                            <li data-list-text="1">
                                <p class="s16" style="padding-left: 16pt;text-indent: -9pt;line-height: 10pt;text-align: left;">
                                    Enter an estimate of your 2024 Minnesota itemized deductions. For 2024, you may have to reduce
                                    your itemized deductions</p>
                                <p class="s16" style="text-indent: 0pt;text-align: left;">&nbsp;&nbsp;&nbsp;f your income is over $232,500 ($116,250 for Married Filing Separately)........................................................... </p>
                            </li>
                            <li data-list-text="2">
                                <p class="s16" style="padding-top: 1pt;padding-left: 9pt;text-indent: -9pt;text-align: left;">Enter
                                    one of the following based on your filing status:
                                    ........................................................... </p>
                                <ol id="l10">
                                    <li data-list-text="a.">
                                        <p class="s16" style="padding-left: 24pt;text-indent: -9pt;text-align: left;">$29,150 if Married Filing Jointly</p>
                                    </li>
                                    <li data-list-text="b.">
                                        <p class="s16" style="padding-left: 24pt;text-indent: -9pt;text-align: left;">$21,900 if Head of Household</p>
                                    </li>
                                    <li data-list-text="c.">
                                        <p class="s16" style="padding-left: 24pt;text-indent: -9pt;text-align: left;">$14,575 if Single or Married Filing Separately</p>
                                    </li>
                                </ol>
                            </li>
                            <li data-list-text="3">
                                <p class="s16" style="padding-left: 16pt;text-indent: -10pt;text-align: left;">Subtract step 2 from
                                    step 1. If zero or less, enter 0 .............................................................
                                </p>
                            </li>
                            <li data-list-text="4">
                                <p class="s16" style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">
                                    Enter an estimate of your 2024 additional standard deduction (from page 11 of the Form M1 instructions) ................ </p>
                            </li>
                            <li data-list-text="5">
                                <p class="s16" style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">Add
                                    steps 3 and 4
                                    ....................................................................................... </p>
                            </li>
                            <li data-list-text="6">
                                <p class="s16" style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">
                                    Enter an estimate of your 2024 taxable nonwage income
                                    ........................................................ </p>
                            </li>
                            <li data-list-text="7">
                                <p class="s16" style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">
                                    Subtract step 6 from step 5. If zero, enter 0. If less than zero, enter the amount in
                                    parentheses........................... </p>
                            </li>
                            <li data-list-text="8">
                                <p class="s16" style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">
                                    Divide the amount on step 7 by $5,050. If a negative amount, enter in parentheses. Do not include fractions .............. </p>
                            </li>
                            <li data-list-text="9">
                                <p class="s16" style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">
                                    Enter the number on step F of Section 1 on page 1
                                    ............................................................. </p>
                            </li>
                            <li data-list-text="10">
                                <p class="s16" style="padding-top: 2pt;padding-left: 16pt;text-indent: -13pt;text-align: left;">Add
                                    step 8 and 9 and enter the total here. If zero or less, enter 0. Enter this amount on line 1 of
                                    page 1. . . . . . . . . . . . . . . . . . </p>
                            </li>
                        </ol>
                        <p style="padding-left: 5pt;text-indent: 0pt;text-align: left;" />
                        <h4 style="padding-left: 6pt;text-indent: 0pt;line-height: 12pt;text-align: left;">Section 2 — Minnesota
                            Exemption</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">Your employer will not withhold Minnesota
                            taxes from your pay if you are exempt from Minnesota withholding. You cannot claim exempt from
                            withholding if all of the following apply:</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 1pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">Another person can claim
                            you as a dependent on their federal tax return</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">Your annual income
                            exceeds $1,100</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">Your annual income
                            includes more than $350 of unearned income</p>
                        <p class="s14" style="padding-top: 2pt;padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Box A
                        </p>
                        <p style="padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Check box A of Section 2
                            to claim exempt if all of the following apply:</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">You meet the
                            requirements to be exempt from federal withholding</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">You had no Minnesota
                            income tax liability in the prior year and received a full refund of Minnesota tax withheld</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">You expect to have no
                            Minnesota income tax liability for the current year</p>
                        <p class="s14" style="padding-top: 2pt;padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Box B
                        </p>
                        <p style="padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Check box B of Section 2
                            if you are not claiming exempt from federal withholding, but meet the second and third requirements for
                            box A.</p>
                        <p class="s14" style="padding-top: 2pt;padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Box C
                        </p>
                        <p style="padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Check box C in Section 2
                            to claim exempt if all of the following apply:</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">You are the spouse of a
                            military member assigned to duty in Minnesota</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">You and your spouse are
                            domiciled in another state</p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 14pt;text-indent: -9pt;text-align: left;">You are in Minnesota
                            solely to be with your active duty military spouse member</p>
                        <p class="s14" style="padding-top: 2pt;padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">Boxes
                            D-F</p>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: justify;">If you receive income from the following
                            sources, it is exempt from Minnesota withholding. Your employer will not withhold Minnesota tax from
                            that income when you check the appropriate box in Section 2.</p>
                    </li>
                    <li data-list-text="•">
                        <p class="s13" style="padding-top: 1pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">Box D:
                            <span class="p">You receive wages as a member of an American Indian tribe living and working on the reservation of which you are an enrolled
                                member. Enter the name of your reservation and your Certificate of Degree of Indian or Alaskan Blood (CDIB) number/enrollment number.
                                <br><b>Members of the Minnesota Chippewa Tribe</b> can exclude income regardless of which Minnesota Chippewa Tribe reservation you live and
                                work on. This affects members of these tribes


                            </span>
                    <li data-list-text="•" style="margin-left: 10px;">
                        <p class="s13" style="padding-top: 1pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">
                            Mille Lacs
                        </p>
                    </li>
                    <li data-list-text="•" style="margin-left: 10px;">
                        <p class="s13" style="padding-top: 1pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">
                            Nett Lake (Bois Forte)
                        </p>
                    </li>
                    <li data-list-text="•" style="margin-left: 10px;">
                        <p class="s13" style="padding-top: 1pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">
                            Fond du Lac
                        </p>
                    </li>
                    <li data-list-text="•" style="margin-left: 10px;">
                        <p class="s13" style="padding-top: 1pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">
                            Leech Lake
                        </p>
                    </li>
                    <li data-list-text="•" style="margin-left: 10px;">
                        <p class="s13" style="padding-top: 1pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">
                            White Earth
                        </p>
                    </li>
                    <li data-list-text="•" style="margin-left: 10px;">
                        <p class="s13" style="padding-top: 1pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">
                            Grand Portage
                        </p>
                    </li>

                    </p>
                    </li>

                    <li data-list-text="•">
                        <p class="s13" style="padding-top: 2pt;padding-left: 15pt;text-indent: -9pt;text-align: justify;">Box E:
                            <span class="p">You receive wages for Minnesota National Guard (MNG) pay or for active duty U.S. military pay. MNG and active duty U.S.
military members can claim exempt from Minnesota withholding on these wages, even if they are taxable federally. For more information,
see Income Tax Fact Sheet 5, Military Personnel.</span>
                        </p>
                    </li>
                    <li data-list-text="•">
                        <p class="s13" style="padding-top: 2pt;padding-left: 15pt;text-indent: -9pt;text-align: left;">Box F: <span class="p">You receive a military pension or other military retirement pay calculated under U.S. Code
                        You receive a military pension or other military retirement pay calculated under U.S. Code title 10, sections 1401 through 1414, 1447
through 1455, and 12733. You may claim exempt from Minnesota withholding on this income even if it is taxable federally.</span></p>
                        <p class="s13" style="padding-top: 4pt;padding-left: 6pt;text-indent: 0pt;text-align: left;">Note: <span class="p">You may not want to claim exempt if you (or your spouse if filing a joint return) expect to have other forms of income subject to
Minnesota tax and you want to avoid owing tax at the end of the year.
If you complete Section 2, you must complete a new Form W-4MN by February 15 in each following year</span></p>
                        
                        <p class="s14" style="padding-top: 3pt;padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">
                            Nonresident Alien</p>
                        <p style="padding-left: 6pt;text-indent: 0pt;line-height: 11pt;text-align: left;">If you are a nonresident alien for federal tax purposes, do not complete Section 2. See IRS Publication 519, U.S. Tax Guide for Aliens.</p>
                        <h4 style="padding-top: 3pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">
                        Line 2 — Additional Minnesota Withholding</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: justify;">If you would like an additional amount of tax to be deducted per payment period, enter the amount on line 2. Do not enter a percentage of the
payment you want to be deducted.</p>
                        <h4 style="padding-top: 1pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">Use of
                            Information</h4>
                        <p style="padding-left: 6pt;text-indent: 0pt;text-align: left;">All information on Form W-4MN is private by state law. It cannot be given to others without your consent, except to the IRS, other states that
guarantee the same privacy, or by court order. Your name, address, and Social Security Number are required for identification. Information about
your allowances is required to determine your correct tax. We ask for your phone number so we can call if we have a question.</p>
                        <h4 style="padding-top: 1pt;padding-left: 6pt;text-indent: 0pt;line-height: 13pt;text-align: left;">
                            Questions?</h4>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-left: 16pt;text-indent: -10pt;line-height: 10pt;text-align: left;"><a href="http://www.revenue.state.mn.us/" class="a" target="_blank">Website: </a><a href="http://www.revenue.state.mn.us/" target="_blank">www.revenue.state.mn.us</a></p>
                    </li>
                    <li data-list-text="•">
                        <p style="padding-top: 2pt;padding-left: 16pt;text-indent: -10pt;text-align: left;"><a href="mailto:withholding.tax@state.mn.us" class="a" target="_blank">Email: </a><a href="mailto:withholding.tax@state.mn.us" target="_blank">withholding.tax@state.mn.us</a></p>
                        <p class="s16" style="padding-top: 3pt;padding-left: 16pt;text-indent: -10pt;text-align: left;">Phone:
                            651-282-9999 or 1-800-657-3594 (toll-free)</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>