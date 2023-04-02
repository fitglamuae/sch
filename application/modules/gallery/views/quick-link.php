<?php echo $this->lang->line('quick_link'); ?>:

<?php if(has_permission(VIEW, 'gallery', 'gallery')){ ?>
<a href="<?php echo site_url('gallery/index'); ?>"><?php echo $this->lang->line('gallery'); ?> </a>
<?php } ?>

<?php if(has_permission(VIEW, 'gallery', 'image')){ ?>
|  <a href="<?php echo site_url('gallery/image/index'); ?>"><?php echo $this->lang->line('gallery_image'); ?></a>
<?php } ?>

<?php if(has_permission(VIEW, 'frontend', 'frontend')){ ?>
|  <a href="<?php echo site_url('frontend/index'); ?>"><?php echo $this->lang->line('frontend'); ?></a>
<?php } ?>