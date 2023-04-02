<?php echo $this->lang->line('quick_link'); ?>:

 <?php if(has_permission(VIEW, 'student', 'type')){ ?>
<a href="<?php echo site_url('student/type/index'); ?>"> <?php echo $this->lang->line('student_type'); ?></a>
<?php } ?>

<?php if(has_permission(VIEW, 'student', 'group')){ ?>
| <a href="<?php echo site_url('student/group/index'); ?>"> <?php echo $this->lang->line('student_group'); ?></a>
<?php } ?>

<?php if(has_permission(VIEW, 'student', 'student')){ ?>
| <a href="<?php echo site_url('student/index'); ?>"> <?php echo $this->lang->line('manage_student'); ?></a>                
<?php } ?>
<?php if(has_permission(ADD, 'student', 'student')){ ?>
| <a href="<?php echo site_url('student/add'); ?>"> <?php echo $this->lang->line('admit_student'); ?></a>
<?php } ?>

<?php if(has_permission(ADD, 'student', 'student')){ ?>
| <a href="<?php echo site_url('student/advanced'); ?>"> <?php echo $this->lang->line('advanced_admission'); ?></a>
<?php } ?>

<?php if(has_permission(ADD, 'student', 'bulk')){ ?>
| <a href="<?php echo site_url('student/bulk/add'); ?>"> <?php echo $this->lang->line('bulk_admission'); ?></a>
<?php } ?>

<?php if(has_permission(VIEW, 'student', 'admission')){ ?>
 | <a href="<?php echo site_url('student/admission/index'); ?>"><?php echo $this->lang->line('online_admission'); ?></a>
<?php } ?>  
<?php if(has_permission(VIEW, 'student', 'activity')){ ?>
| <a href="<?php echo site_url('student/activity/index'); ?>"> <?php echo $this->lang->line('student_activity'); ?></a>                    
<?php } ?>