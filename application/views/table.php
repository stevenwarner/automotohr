<table border="1" style="width: 100%">
    <caption></caption>
    <thead>
        <tr>
            <th scope="col">Reviewee</th>
            <th scope="col">Cycle Period</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
            <?php
                $tmp = [];
                foreach($records as $record):
                    //
                    if(!isset($tmp[$record['reviewee_sid']])){
                        $tmp[$record['reviewee_sid']] = true;
                    } else{
                        continue;
                    }
                ?>
                <tr>
                    <td style="text-align: center;"><?=ucwords($record['reviewee_first_name'].' '.$record['reviewee_last_name'])?></td>
                    <td style="text-align: center;"><?=formatDateToDB($record['start_date'], DB_DATE, DATE);?> - <?=formatDateToDB($record['end_date'], DB_DATE, DATE);?></td>
                    <td style="text-align: center;">
                        <?=getButtonForEmail([
                            '{{url}}' => base_url('performance-management/review/'.$id.'/'.$record['reviewee_sid'].'/'.$record['reviewer_sid']),
                            '{{color}}' => '#fd7a2a',
                            '{{text}}' => 'Start Review',
                        ]);?>
                    </td>
                </tr>
            <?php endforeach; ?>
    </tbody>

</table>