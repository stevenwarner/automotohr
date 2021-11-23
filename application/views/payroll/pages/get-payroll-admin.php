<div class="container">
   <div class="row">
       <div class="col-sm-12">
           <label>First name</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['first_name'] != null ? $primaryAdmin['first_name'] : '' ?>" />
       </div>
   </div>
   <br>
   <div class="row">
       <div class="col-sm-12">
           <label>Last name</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['last_name'] != null ? $primaryAdmin['last_name'] : '' ?>" />
       </div>
   </div>
   <br>
   <div class="row">
       <div class="col-sm-12">
           <label>Email</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['email_address'] != null ? $primaryAdmin['email_address'] : '' ?>" />
       </div>
   </div>
   <br>
   <div class="row">
       <div class="col-sm-12">
           <label>Phone number</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['phone_number'] != null ? $primaryAdmin['phone_number'] : '' ?>" />
       </div>
   </div>
   <br>
</div>