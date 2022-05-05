<tr>
    <td class="col-lg-8">
        <?php
            echo $history_document['document_title'] . '&nbsp;';
            echo $history_document['status'] ? '' : '<b>(revoked)</b>';
            echo $history_document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
            echo $history_document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

            if (isset($history_document['assigned_date']) && $history_document['assigned_date'] != '0000-00-00 00:00:00') {
                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $history_document['assigned_date'], '_this' => $this));
            }
        ?>
    </td>

    <?php if ($history_document['document_type'] == 'hybrid_document') { ?>
        <td>
            <button 
                data-id="<?=$history_document['sid'];?>"
                data-type="document"
                data-document="submitted"
                class="btn btn-info btn-sm btn-block js-hybrid-preview"
            >
                View Sign
            </button>
        </td>
    <?php } else if ($history_document['document_type'] == 'uploaded') { ?>
        <td class="col-lg-2">
            <button class="btn btn-info btn-sm btn-block"
                    onclick="preview_latest_generic_function(this);"
                    date-letter-type="uploaded"
                    data-on-action="submitted"
                    data-preview-url="<?php echo AWS_S3_BUCKET_URL . $history_document['uploaded_file']; ?>"
                    data-s3-name="<?php echo $history_document['uploaded_file']; ?>"
                    >
                View Sign
            </button>
        </td>
    <?php } else { ?>
        <td class="col-lg-2">
            <button class="btn btn-info btn-sm btn-block"
                    onclick="preview_latest_generic_function(this);"
                    date-letter-type="generated"
                    data-doc-sid="<?php echo $history_document['sid']; ?>"
                    data-on-action="submitted"
                    data-from="assigned_document_history">
                View Sign
            </button>
        </td>
    <?php } ?>
    <td>    
    </td>
</tr>