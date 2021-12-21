<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="csF16">
                The person who will manage and run the payroll for this store.
            </h1>
        </div>
    </div>
   <div class="row">
       <div class="col-sm-6">
           <label>First name</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['first_name'] != null ? $primaryAdmin['first_name'] : '' ?>" />
       </div>
   </div>
   <br>
   <div class="row">
       <div class="col-sm-6">
           <label>Last name</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['last_name'] != null ? $primaryAdmin['last_name'] : '' ?>" />
       </div>
   </div>
   <br>
   <div class="row">
       <div class="col-sm-6">
           <label>Email</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['email_address'] != null ? $primaryAdmin['email_address'] : '' ?>" />
       </div>
   </div>
   <br>
   <div class="row">
       <div class="col-sm-6">
           <label>Phone number</label>
           <input type="text" class="form-control" disabled value="<?php echo $primaryAdmin['phone_number'] != null ? $primaryAdmin['phone_number'] : '' ?>" />
       </div>
   </div>
   <br>
</div>