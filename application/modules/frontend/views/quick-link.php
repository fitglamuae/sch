<?php echo $this->lang->line('quick_link'); ?>:

 <?php if(has_permission(VIEW, 'frontend', 'frontend')){ ?>
<a href="<?php echo site_url('frontend/index'); ?>"><?php echo $this->lang->line('frontend_page'); ?> </a>                    
<?php } ?>

<?php if(has_permission(VIEW, 'frontend', 'slider')){ ?>
| <a href="<?php echo site_url('frontend/slider/index'); ?>"><?php echo $this->lang->line('slider'); ?> </a>
<?php } ?>

<?php if(has_permission(VIEW, 'frontend', 'about')){ ?>
| <a href="<?php echo site_url('frontend/about/index'); ?>"><?php echo $this->lang->line('about_school'); ?></a>
<?php } ?>               

<?php if(has_permission(VIEW, 'frontend', 'faq')){ ?>
| <a href="<?php echo site_url('frontend/faq/index'); ?>"><?php echo $this->lang->line('faq'); ?></a>
<?php } ?> 

<?php if(has_permission(VIEW, 'setting', 'setting')){ ?>                   
| <a href="<?php echo site_url('setting'); ?>"><?php echo $this->lang->line('setting'); ?></a>
<?php } ?>

<?php if(has_permission(VIEW, 'announcement', 'notice')){ ?>
| <a href="<?php echo site_url('announcement/notice/index'); ?>"><?php echo $this->lang->line('notice'); ?></a>
<?php } ?>    

<?php if(has_permission(VIEW, 'announcement', 'news')){ ?>
| <a href="<?php echo site_url('announcement/news/index'); ?>"><?php echo $this->lang->line('news'); ?></a>
<?php } ?>    

<?php if(has_permission(VIEW, 'announcement', 'holiday')){ ?>
| <a href="<?php echo site_url('announcement/holiday/index'); ?>"><?php echo $this->lang->line('holiday'); ?></a>                    
<?php } ?>

<?php if(has_permission(VIEW, 'teacher', 'teacher')){ ?>
| <a href="<?php echo site_url('teacher/index'); ?>"><?php echo $this->lang->line('teacher'); ?> </a>                    
<?php } ?>   

<?php if(has_permission(VIEW, 'hrm', 'employee')){ ?>
| <a href="<?php echo site_url('hrm/employee/index'); ?>"><?php echo $this->lang->line('employee'); ?></a>
<?php } ?>  