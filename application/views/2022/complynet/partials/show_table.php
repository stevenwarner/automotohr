<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong> <?= $title; ?></strong>
    </div>

    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record) {
                    ?>
                        <tr>
                            <td><?= $record['Id']; ?></td>
                            <td><?= $record['Name']; ?></td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>