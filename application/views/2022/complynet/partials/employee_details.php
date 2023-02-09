<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
        Basic Details
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <caption></caption>
                <tbody>
                    <?php $jsonToArray = []; ?>
                    <?php if ($data) {
                        //
                        //
                        foreach ($data as $column => $value) {
                            //
                            if ($column == 'complynet_json') {
                                $jsonToArray = json_decode($value, true);
                                //
                                continue;
                            } else {

                    ?>
                                <tr>
                                    <th scope="col"><?= str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $column))); ?></th>
                                    <td><?= $value; ?></td>
                                </tr>
                        <?php
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="2">
                                <p class="alert alert-info text-center">
                                    <strong>No data found.</strong>
                                </p>
                            </td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php if ($jsonToArray) { ?>
    <?php foreach ($jsonToArray as $innerColumn => $innserValue) { ?>
        <!--  -->
        <div class="panel panel-default">
            <div class="panel-heading">
                ComplyNet Employee Details
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <caption></caption>
                        <tbody>

                            <table class="table table-striped table-bordered">
                                <caption>Record <?= $innerColumn; ?></caption>
                                <tbody>
                                    <?php foreach ($innserValue as $innerColumn1 => $innserValue1) { ?>
                                        <tr>
                                            <th scope="col"><?= ucwords(str_replace(['-', '_'], ' ', $innerColumn1)); ?></th>
                                            <td><?= $innserValue1; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php

    } ?>

<?php } ?>