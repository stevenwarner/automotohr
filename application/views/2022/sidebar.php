<div class="csPageSideBar _csR5 _csPb10">
    <div class="text-right _csM0 _csB4 _csP5 _csF2">
        <a href="<?=base_url('my_profile');?>" class="btn _csB3 _csF2 _csF16 _csR5">Edit Profile</a>
    </div>
    <!--  -->
    <img src="<?=getImageURL($employee['profile_picture']);?>" alt="" class="_csImg" />
    <!--  -->
    <dl class="_csP5 _csPt10">
        <dt class="_csSeparator"></dt>
        <dt>Name</dt>
        <dd><?=remakeEmployeeName($employee, true, true);?></dd>
        <dt class="_csSeparator"></dt>
        <dt>Role</dt>
        <dd><?=remakeEmployeeName($employee, false);?></dd>
        <dt class="_csSeparator"></dt>
    </dl>
</div>