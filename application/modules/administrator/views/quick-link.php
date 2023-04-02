<?php echo $this->lang->line('quick_link'); ?>:

<?php if(has_permission(VIEW, 'administrator', 'year')){ ?>
    <a href="<?php echo site_url('administrator/year/index'); ?>"><?php echo $this->lang->line('academic_year'); ?></a>
    <?php } ?>
    
    <?php if(has_permission(VIEW, 'administrator', 'role')){ ?>
    | <a href="<?php echo site_url('administrator/role/index'); ?>"><?php echo $this->lang->line('user_role'); ?></a>
    <?php } ?>
    
    <?php if(has_permission(VIEW, 'administrator', 'permission')){ ?>
    | <a href="<?php echo site_url('administrator/permission/index'); ?>"><?php echo $this->lang->line('role_permission'); ?></a>                   
    <?php } ?>
    
    <?php if(has_permission(VIEW, 'administrator', 'user')){ ?>
    | <a href="<?php echo site_url('administrator/user/index'); ?>"><?php echo $this->lang->line('manage_user'); ?></a>                
    <?php } ?>
    
    <?php if(has_permission(EDIT, 'administrator', 'password')){ ?>
    | <a href="<?php echo site_url('administrator/password/index'); ?>"><?php echo $this->lang->line('reset_password'); ?></a>                   
    <?php } ?>
    
    <?php if(has_permission(VIEW, 'administrator', 'usercredential')){ ?>
    | <a href="<?php echo site_url('administrator/usercredential/index'); ?>"><?php echo $this->lang->line('user_credential'); ?></a>                   
    <?php } ?>   
    
    <?php if(has_permission(EDIT, 'administrator', 'email')){ ?>
    | <a href="<?php echo site_url('administrator/email/index'); ?>"><?php echo $this->lang->line('reset_email'); ?></a>                   
    <?php } ?>
    
    <?php if(has_permission(VIEW, 'administrator', 'backup')){ ?>
    | <a href="<?php echo site_url('administrator/backup/index'); ?>"><?php echo $this->lang->line('backup_database'); ?></a>                  
    <?php } ?>  
    
    <?php if(has_permission(VIEW, 'administrator', 'activitylog')){ ?>
    | <a href="<?php echo site_url('administrator/activitylog/index'); ?>"><?php echo $this->lang->line('activity_log'); ?></a>                  
    <?php } ?>
    
    <?php if(has_permission(VIEW, 'administrator', 'feedback')){ ?>
    | <a href="<?php echo site_url('administrator/feedback/index'); ?>"><?php echo $this->lang->line('feedback'); ?></a>                  
    <?php } ?>