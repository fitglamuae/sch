<?php echo $this->lang->line('quick_link'); ?>:

<?php if(has_permission(VIEW, 'announcement', 'notice')){ ?>
<a href="<?php echo site_url('announcement/notice/index'); ?>"><?php echo $this->lang->line('notice'); ?></a>
<?php } ?>    

<?php if(has_permission(VIEW, 'announcement', 'news')){ ?>
| <a href="<?php echo site_url('announcement/news/index'); ?>"><?php echo $this->lang->line('news'); ?></a>
<?php } ?>    

<?php if(has_permission(VIEW, 'announcement', 'holiday')){ ?>
| <a href="<?php echo site_url('announcement/holiday/index'); ?>"><?php echo $this->lang->line('holiday'); ?></a>                    
<?php } ?>

<?php if(has_permission(VIEW, 'frontend', 'frontend')){ ?>
| <a href="<?php echo site_url('frontend/index'); ?>"><?php echo $this->lang->line('frontend'); ?> </a>
<?php } ?>  