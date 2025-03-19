<!-- Email Section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="3">
                <strong>Email(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($emails)) { ?>
            <?php foreach ($emails as $key => $email) { ?>
                <tr>
                    <td>
                        <table class="incident-table">
                            <thead>
                                <tr class="bg-gray">
                                    <th colspan="3">
                                        <strong>
                                            <?php
                                                echo $email['userName'];
                                            ?>
                                        </strong>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($email['user_emails'] as $emailData) { ?>
                                    <tr>
                                        <td>
                                            <table class="incident-table">
                                                <thead>
                                                    <tr class="bg-gray">
                                                        <th colspan="3">
                                                            <strong>
                                                                <?php
                                                                    echo 'Sent Date: '. formatDateToDB($emailData['send_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                                                                ?>
                                                            </strong>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <label>
                                                                <strong>To :</strong>
                                                            </label>
                                                            <span class="value-box bg-gray">
                                                                <?php
                                                                    if ($emailData['receiver_sid'] != 0) {
                                                                        echo getEmployeeOnlyNameBySID($emailData['receiver_sid']);
                                                                    } else {
                                                                        echo $emailData['manual_email'];
                                                                    }
                                                                ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <label>
                                                                <strong>From :</strong>
                                                            </label>
                                                            <span class="value-box bg-gray">
                                                                <?php
                                                                    if ($emailData['sender_sid'] != 0) {
                                                                        echo getEmployeeOnlyNameBySID($emailData['sender_sid']);
                                                                    } else {
                                                                        echo $emailData['manual_email'];
                                                                    }
                                                                ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <label>
                                                                <strong>Subject :</strong>
                                                            </label>
                                                            <span class="value-box bg-gray">
                                                                <?php echo $emailData['subject']; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <?php echo $emailData['message_body']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($emailData['attachments']) { ?>
                                                        <tr>
                                                            <td colspan="3">
                                                                <table class="incident-table">
                                                                    <thead>
                                                                        <tr class="bg-gray">
                                                                            <th colspan="2">
                                                                                <strong>
                                                                                    Attachment(s)
                                                                                </strong>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Title</th>
                                                                            <th>Link</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($emailData['attachments'] as $attachment) { ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <?php
                                                                                        echo $attachment['title'];
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                        echo $attachment['link'];
                                                                                    ?>
                                                                                </td>
                                                                            </tr>   
                                                                        <?php } ?>    
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>    
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>        
                                <?php } ?>    
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Email Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>    
    </tbody>
</table>
<!-- Email Section End -->