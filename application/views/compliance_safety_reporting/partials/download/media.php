<!-- Report Audio/Video section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="4">
                <strong>Audio(s)/Video(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($audios)) { ?>
            <tr>
                <th class="text-center">Title</th>
                <th class="text-center">Type</th>
                <th class="text-center">Added By</th>
                <th class="text-center">Added Date</th>
            </tr>
            <?php foreach ($audios as $media) { ?>
                <tr>
                    <?php 
                        //
                        $mediaAddedBy = '';
                        $mediaType = '';
                        $mediaURL = '';
                        //
                        if ($media['created_by'] != 0) {
                            $mediaAddedBy = getEmployeeOnlyNameBySID($media['created_by']);
                        } else {
                            $mediaAddedBy = $media['manual_email'];
                        }

                        if ($media['file_type'] == 'link') {
                            if (strpos($media['file_value'], 'youtube') > 0) {
                                $mediaType = 'Youtube Link';
                            } elseif (strpos($media['file_value'], 'vimeo') > 0) {
                                $mediaType = 'vimeo Link';
                            } else {
                                $mediaType = 'Unknown Link';
                            }
                            //
                            $mediaURL = $media['file_value'];
                        } else {
                            $mediaType = ucwords($media['file_type']);
                            $mediaURL  = AWS_S3_BUCKET_URL . $media["s3_file_value"];
                            //
                        } 
                        //
                        $allReportLinks[$media['sid']]['title'] = $media['title'];
                        $allReportLinks[$media['sid']]['type'] = $mediaType;
                        $allReportLinks[$media['sid']]['url'] = $mediaURL;  
                    ?>
                    <td class="text-center"><?php echo $media['title']; ?></td>
                    <td class="text-center"><?php echo $mediaType; ?></td>
                    <td class="text-center"><?php echo $mediaAddedBy; ?></td>
                    <td class="text-center"><?php echo formatDateToDB($media['created_at'], DB_DATE_WITH_TIME, DATE); ?></td>
                </tr>
                <tr>
                    <td colspan="4"><?php echo '<b>Link:</b> '. $mediaURL; ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Audio/Video Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- Report Audio/Video section End -->