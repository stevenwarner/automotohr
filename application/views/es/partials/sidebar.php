<!--  -->
<div class="col-md-3 col-sm-12">
    <div class="csPageSideBar _csR5 _csPb10">
        <h3 class="_csM0 _csB4 _csP10 _csF2 _csF16 text-right">
            <a href="<?=base_url('my_profile');?>" class="btn _csR5 _csF2 _csB3">Edit Profile</a>
        </h3>
        <dl class="_csP10">
            <dt>
                <img
                    src="<?=getImageURL($employee['profile_picture']);?>"
                    class="_csImg"
                    alt="<?=makeFullName($employee);?>"
                />
            </dt>
            <dt class="_csMt10">Name</dt>
            <dd><?=makeFullName($employee);?></dd>
            <dt class="_csMt10">Role</dt>
            <dd><?=remakeEmployeeName($employee, false)?></dd>
        </dl>
    </div>
</div>