<div <?= !isset($pictures) && sizeof($pictures) ? 'class="table-responsive"' : 'style="padding: 10px;"' ?> >
    <?php if (!isset($pictures) || empty($pictures)) { ?>
        <span>No photos found</span>
    <?php } else { ?>
        <div style="padding: 5px;">
            <table class="table table-bordered" style="margin: 0;">
                <thead>
                    <tr>
                        <th class="col-lg-1">Thumbnail</th>
                        <th>Image Title</th>
                        <th>URL</th>  
                    </tr> 
                </thead>
                <tbody>
                    <?php foreach ($pictures as $picture) { ?>
                        <tr id='row_<?php echo $picture['sid']; ?>'>
                            <td>
                                <figure>
                                    <img src="<?php echo STORE_PROTOCOL_SSL . CLOUD_GALLERY .'.s3.amazonaws.com/' . $picture['uploaded_name']; ?>" width="100"/>
                                </figure>
                            </td>
                            <td><?php echo ucwords($picture['title']); ?></td>
                            <td><?php echo STORE_PROTOCOL_SSL . CLOUD_GALLERY .'.s3.amazonaws.com/' . $picture['uploaded_name']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="clear"></div>
        </div>
    <?php } ?> 
</div>
