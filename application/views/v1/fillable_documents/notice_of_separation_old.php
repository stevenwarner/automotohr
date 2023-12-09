<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <style>
        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 10px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 0px solid #ddd;
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .invoice-fields {
            float: left;
            width: 100%;
            height: 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #000;
            padding: 0 5px;
            background-color: #eee;
        }

        .auto-height {
            height: auto !important;
        }
    </style>



    <div class="container">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <h3>Notice of Separation</h3>
                <p><strong>Instructions:</strong> Use this form to officially notify thhe company that you are ending your employment.
                    Please provide your notice at least 2 weeks in advance of your last day of work.
                </p>
                <p>If you supervise an employee who leaves the company without first filling out this form, please notify
                    Human Resources by filling out a <strong>Termination Without Notice Form</strong></p>
            </div>

        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <table class="table borderless">
                    <tbody>
                        <tr>
                            <td class="" width="50%">
                                <label>Your Name:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <label>Your Supervisor:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>
                        <tr>
                            <td width="50%">
                                <label>Your Department:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>
                        <tr>
                            <td width="50%">
                                <label>Your Job Title:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>
                        <tr>
                            <td width="50%">
                                <label>Last day of work:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>

                        <tr>
                            <td width="50%">
                                <label>Please fully explain the reasons you are leaving the company:</label><br>
                                <textarea id="" name="" class="invoice-fields auto-height" rows="6"></textarea>
                            </td>
                            </td>

                        </tr>

                        <tr>
                            <td width="50%">
                                <label>Forwarding information: Please include your full address:</label><br>
                                <textarea id="" name="" class="invoice-fields auto-height" rows="6"></textarea>
                            </td>

                        </tr>

                        <tr>
                            <td width="50%">
                                <label>Employee Signature:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>

                        <tr>
                            <td width="50%">
                                <label>Employee Printed Name:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>

                        <tr>
                            <td width="50%">
                                <label>Date:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>

                        <tr>
                            <td width="50%">
                                <label>Date: Authorize Sign Date:</label>
                                <input class="invoice-fields" type="text" value="" />
                            </td>

                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>