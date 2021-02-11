<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time-off</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

    <?php echo $hf['header']; ?>

    <?php if($action == 'approve'): ?>
    <div class="row">
        <div class="col-sm-12">
            <h1 class="text-center text-success"  style="font-size: 60px;"><i class="glyphicon glyphicon-ok-sign"></i> APPROVED</h1>
            <br />
            <h3>You have successfully <strong>Approved</strong> the time-off request of <strong><?=$request['first_name'].' '.$request['last_name'];?></strong> with the following details.
            
            <h3><strong>Policy:</strong> <?=$request['title'];?></h3>
            <h3><strong>Date:</strong> <?= DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D');?><?= $request['request_from_date'] != $request['request_to_date'] ? ' - '.DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('M d Y, D') : '';?></h3>
        </div>
    </div>
    <?php elseif($action == 'reject'): ?>
    <div class="row">
        <div class="col-sm-12">
            <h1 class="text-center text-danger"  style="font-size: 60px;"><i class="glyphicon glyphicon-remove-sign"></i> REJECTED</h1>
            <br />
            <h3>You have successfully <strong>Rejected</strong> the time-off request of <strong><?=$request['first_name'].' '.$request['last_name'];?></strong> with the following details.
            
            <h3><strong>Policy:</strong> <?=$request['title'];?></h3>
            <h3><strong>Date:</strong> <?= DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D');?><?= $request['request_from_date'] != $request['request_to_date'] ? ' - '.DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('M d Y, D') : '';?></h3>
        </div>
    </div>
    <?php elseif($action == 'cancel'): ?>
        <div class="row">
        <div class="col-sm-12">
            <h1 class="text-center text-danger"  style="font-size: 60px;"><i class="glyphicon glyphicon-remove-sign"></i> CANCELED</h1>
            <br />
            <h3>You have successfully <strong>Canceled</strong> your time-off request  with the following details.
            
            <h3><strong>Policy:</strong> <?=$request['title'];?></h3>
            <h3><strong>Date:</strong> <?= DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('M d Y, D');?><?= $request['request_from_date'] != $request['request_to_date'] ? ' - '.DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('M d Y, D') : '';?></h3>
        </div>
    </div>
    <?php endif; ?>

    

    <?php echo $hf['footer']; ?>
    
</body>
</html>