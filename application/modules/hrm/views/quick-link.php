<?php echo $this->lang->line('quick_link'); ?>:

<?php if(has_permission(VIEW, 'hrm', 'department')){ ?>
   <a href="<?php echo site_url('hrm/department/index'); ?>"><?php echo $this->lang->line('department'); ?></a>
<?php } ?>
<?php if(has_permission(VIEW, 'hrm', 'designation')){ ?>
  | <a href="<?php echo site_url('hrm/designation/index'); ?>"><?php echo $this->lang->line('designation'); ?></a>
<?php } ?>    
<?php if(has_permission(VIEW, 'hrm', 'employee')){ ?>
   | <a href="<?php echo site_url('hrm/employee/index'); ?>"><?php echo $this->lang->line('employee'); ?></a>
<?php } ?>
   