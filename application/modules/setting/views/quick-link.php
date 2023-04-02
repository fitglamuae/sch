<?php echo $this->lang->line('quick_link'); ?>:
<?php if(has_permission(VIEW, 'setting', 'setting')){ ?>
    <a href="<?php echo site_url('setting/index'); ?>"><?php echo $this->lang->line('general_setting'); ?></a>
<?php } ?>
<?php if(has_permission(VIEW, 'setting', 'payment')){ ?>
   | <a href="<?php echo site_url('setting/payment/index'); ?>"><?php echo $this->lang->line('payment_setting'); ?></a>
<?php } ?>
<?php if(has_permission(VIEW, 'setting', 'sms')){ ?>
   | <a href="<?php echo site_url('setting/sms/index'); ?>"><?php echo $this->lang->line('sms_setting'); ?></a>                    
<?php } ?>                
<?php if(has_permission(VIEW, 'setting', 'email')){ ?>
   | <a href="<?php echo site_url('setting/email/index'); ?>"><?php echo $this->lang->line('email_setting'); ?></a>                    
<?php } ?>  
<?php if(has_permission(VIEW, 'setting', 'openinghour')){ ?>
   | <a href="<?php echo site_url('setting/openinghour/index'); ?>"><?php echo $this->lang->line('opening_hour'); ?></a>                    
<?php } ?> 


