<?php
    $columns = array_keys($records[0]);
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-bordered">
                <caption></caption>
                <thead>
                    <tr>
                        <?php foreach($columns as $column): ?>
                            <th><?=$column;?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($records as $company): ?>
                        <tr>
                            <td><?=$company['Id'];?></td>
                            <td><?=$company['Contact Name'];?></td>
                            <td><?=$company['Company Name'];?></td>
                            <td><?=phonenumber_format($company['Phone Number']);?></td>
                            <td><?=date_with_time($company['Registration Date']);?></td>
                            <td><?=!empty($company['Expiration Date']) ? date_with_time($company['Expiration Date']) : 'Not Set';?></td>
                            <td><?=$company['Status'] == 1 ? 'Active' : 'In-Active';?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>