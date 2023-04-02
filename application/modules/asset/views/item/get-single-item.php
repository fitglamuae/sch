<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('category'); ?></th>
            <td><?php echo $item->cat_name; ?></td>
        </tr>        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $item->name; ?></td>
        </tr>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('product_code'); ?></th>
            <td><?php echo $item->code; ?></td>
        </tr> 
        <tr>
            <th width="20%"> <?php echo $this->lang->line('type'); ?></th>
            <td><?php echo $this->lang->line($item->type); ?></td>
        </tr>  
        <tr>
            <th width="20%"> <?php echo $this->lang->line('store'); ?></th>
            <td><?php echo $item->store_name; ?></td>
        </tr> 
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $item->note; ?></td>
        </tr>     
        <tr>
            <th> <?php echo $this->lang->line('status'); ?></th>
            <td><?php echo $item->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>
        </tr>
    </tbody>
</table>