
<?php if(!empty($department)) { ?>

    <div class="hr-box">
        <div class="hr-box-body hr-innerpadding">
            <div class="text-center">
                <h4 class=""><?php echo $department['dept_name']; ?></h4>
            </div>

                    <?php $positions = $department['positions']; ?>

                    <?php if(!empty($positions)) { ?>

                        <div class="row">

                            <?php foreach($positions as $position) { ?>

                                <?php $view_data = array('position' => $position, 'parent_employee_sid' => 0); ?>
                                <?php $this->load->view('organizational_hierarchy/partials/position_tree_partial', $view_data); ?>

                            <?php } ?>
                        </div>

                    <?php } ?>




            <?php $sub_departments = $department['sub_departments']; ?>
            <?php foreach($sub_departments as $sub_department) { ?>

                <?php $view_data = array('department' => $sub_department); ?>
                <?php $this->load->view('organizational_hierarchy/partials/department_tree_partial', $view_data); ?>

            <?php } ?>
        </div>
    </div>

<?php } ?>


