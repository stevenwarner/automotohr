<table class="table table-bordered">
    <tbody>
        <?php if (!empty($data)) {
            $breakdown = $data;
            if (!empty($breakdown)) {
                foreach ($breakdown as $key => $rowData) {
                    if (!empty($rowData)) {
                        if (is_array($rowData) && !empty($rowData)) {
        ?>
                            <?php if (!is_numeric($key)) { ?>
                                <tr>
                                    <td class="bg-primary" colspan="2"><strong><?php echo ucwords(str_replace("_", " ", $key)); ?></strong></td>
                                </tr>
                            <?php } ?>
                            <?php
                            foreach ($rowData as $key2 => $rowData2) {
                                if (is_array($rowData2) && !empty($rowData2)) {
                            ?>
                                    <?php if (!is_numeric($key2)) { ?>
                                        <tr>
                                            <td class="bg-primary" colspan="2"><strong><?php echo ucwords(str_replace("_", " ", $key2)); ?></strong></td>
                                        </tr>
                                    <?php } ?>

                                    <?php
                                    $i2 = 1;
                                    foreach ($rowData2 as $key3 => $rowData3) {
                                        $i2devider = '';
                                        $i2++;
                                        if ($i2 == count($rowData2)) {
                                            $i2devider = 'style="border-bottom: 2pt solid black;"';
                                        }
                                    ?>

                                        <?php
                                        if (is_array($rowData3) && !empty($rowData3)) {
                                            if (!is_numeric($key3)) { ?>
                                                <tr>
                                                    <td class="bg-primary" colspan="2"><strong><?php echo ucwords(str_replace("_", " ", $key3)); ?></strong></td>
                                                </tr>
                                            <?php } ?>

                                            <?
                                            foreach ($rowData3 as $key4 => $rowData4) {
                                                if (is_array($rowData4) && !empty($rowData4)) {
                                                    if (!is_numeric($key4)) { ?>
                                                        <tr>
                                                            <td class="bg-primary" colspan="2"><strong><?php echo ucwords(str_replace("_", " ", $key4)); ?></strong></td>
                                                        </tr>
                                                    <?php } ?>

                                                    <? $i4 = 1;
                                                    foreach ($rowData4 as $key5 => $rowData5) {
                                                        $i4devider = '';
                                                        if ($i4 == count($rowData4)) {
                                                            $i4devider = 'style="border-bottom: 2pt solid black;"';
                                                        }

                                                    ?>
                                                        <tr <?php echo $i4devider; ?>>
                                                            <td class="bg-success"><strong><?php echo ucwords(str_replace("_", " ", $key5)); ?>:</strong></td>
                                                            <td class="bg-success"><?php echo $rowData5; ?></td>
                                                        </tr>
                                                    <?php $i4++;
                                                    } ?>

                                                <?php } else { ?>

                                                    <tr>
                                                        <td class="bg-success"><strong><?php echo ucwords(str_replace("_", " ", $key4)); ?>:</strong></td>
                                                        <td class="bg-success"><?php echo $rowData4; ?></td>
                                                    </tr>

                                                <?php } ?>

                                            <?php } ?>

                                            <?php } else {
                                            if (!empty($rowData3)) {
                                            ?>
                                                <tr <?php echo $i2devider; ?>>
                                                    <td class="bg-success"><strong><?php echo  ucwords(str_replace("_", " ", $key3)); ?>:</strong></td>
                                                    <td class="bg-success"><?php echo $rowData3; ?></td>
                                                </tr>
                                        <?php }
                                        } ?>

                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td class="bg-success"><strong><?php echo ucwords(str_replace("_", " ", $key2)); ?>:</strong></td>
                                        <td class="bg-success"><?php echo $rowData2; ?></td>
                                    </tr>
                            <?php
                                }
                            }
                        } else {
                            ?>
                            <tr>
                                <td class="bg-success"><strong><?php echo ucwords(str_replace("_", " ", $key)); ?>:</strong></td>
                                <td class="bg-success"><?php echo $rowData; ?></td>
                            </tr>

        <?php
                        }
                    }
                }
            }
        }

        ?>
    </tbody>
</table>