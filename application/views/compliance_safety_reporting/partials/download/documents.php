<!-- Report Document section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="5">
                <strong>Document(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($documents)) { ?>
            <tr>
                <th class="text-center">Title</th>
                <th class="text-center">Type</th>
                <th class="text-center">Added By</th>
                <th class="text-center">Added Date</th>
                <th class="text-center">Link</th>
            </tr>
            <?php foreach ($documents as $document) { ?>
                <tr>
                    <?php 
                        $documentAddedBy = '';
                        //
                        if ($document['created_by'] != 0) {
                            $documentAddedBy = getEmployeeOnlyNameBySID($document['created_by']);
                        } else {
                            $documentAddedBy = $document['manual_email'];
                        }
                        //
                        $documentURL = '';
                        //
                        if ($document["file_type"] === "image") {
                            $documentURL = AWS_S3_BUCKET_URL . $document["s3_file_value"]; 
                        } elseif ($document["file_type"] === "document") {
                            if (preg_match("/doc|docx|xls|xlsx|ppt|pptx/i", $document["s3_file_value"])) {
                                $documentURL = 'https://view.officeapps.live.com/op/embed.aspx?src='.AWS_S3_BUCKET_URL . $document["s3_file_value"];
                            } else {
                                $documentURL = AWS_S3_BUCKET_URL . $document["s3_file_value"];
                            }
                        } 
                        //
                        $allReportLinks[$document['sid']]['title'] = $document['title'];
                        $allReportLinks[$document['sid']]['type']  = ucwords($document['file_type']);
                        $allReportLinks[$document['sid']]['url'] = $documentURL;
                        //
                    ?>
                    <td class="text-center"><?php echo $document['title']; ?></td>
                    <td class="text-center"><?php echo ucwords($document['file_type']); ?></td>
                    <td class="text-center"><?php echo $documentAddedBy; ?></td>
                    <td class="text-center"><?php echo formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                    <td class="text-center"><?php echo '<a href="'.$documentURL.'" target="_blank">'.$document['title'].'</a>'; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Documents Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- Report Document section End -->   