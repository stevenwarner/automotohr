<?php

foreach ($records as $index => $roles) {
?>
    <!--  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong> <?= $index; ?></strong>
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
                        <?php if (empty($roles)) { ?>
                            <tr>
                                <td colspan="2">
                                    <p class="alert alert-info text-center">
                                        <strong>No job title found.</strong>
                                    </p>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($roles as $role) {
                            ?>
                                <tr>
                                    <td><?= $role['Id']; ?></td>
                                    <td><?= $role['Name']; ?></td>
                                </tr>
                            <?php
                            } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
}
?>