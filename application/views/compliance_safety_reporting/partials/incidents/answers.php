<!-- Manage Documents -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Questions/Answers</strong>
        </h1>
    </div>
    <div class="panel-body">
        <?php foreach ($report["question_answers"] as $item) {?>
            <div class="table-responsive">
                <div class="table-wrp data-table">
                    <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                        <b><?php echo $item['question']; ?></b>
                        <tbody>
                            <tr>
                                <td>
                                    <?php
                                    $ans = @unserialize($item['answer']);
                                    if ($ans !== false) {
                                        echo implode(', ', $ans);
                                    } else {
                                        echo ucfirst($item['answer']);
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </div>
</div>