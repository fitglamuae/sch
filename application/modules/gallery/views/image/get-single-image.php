<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"><?php echo $this->lang->line('title'); ?></th>
            <td><?php echo $image->title; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('image'); ?></th>
            <td>
                <?php if($image->image){ ?>
                    <img src="<?php echo UPLOAD_PATH; ?>/gallery/<?php echo $image->image; ?>" alt=""  class="img-responsive" /><br/><br/>
                <?php } ?>
            </td>
        </tr>       
        <tr>
            <th><?php echo $this->lang->line('caption'); ?></th>
            <td><?php echo $image->caption; ?></td>
        </tr>       
        <tr>
            <th><?php echo $this->lang->line('created'); ?></th>
            <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($image->created_at)); ?></td>
        </tr>   
        <tr>
            <th> <?php echo $this->lang->line('status'); ?></th>
            <td><?php echo $image->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>
        </tr>
    </tbody>
</table>
