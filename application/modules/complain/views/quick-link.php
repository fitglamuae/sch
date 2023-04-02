<?php echo $this->lang->line('quick_link'); ?>:

 <?php if(has_permission(VIEW, 'complain', 'type')){ ?>
<a href="<?php echo site_url('complain/type'); ?>"> <?php echo $this->lang->line('complain'); ?> <?php echo $this->lang->line('type'); ?></a>
<?php } ?>

<?php if(has_permission(VIEW, 'complain', 'complain')){ ?>
| <a href="<?php echo site_url('complain'); ?>"> <?php echo $this->lang->line('complain'); ?></a>
<?php } ?>