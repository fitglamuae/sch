<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('category'); ?></th>
            <td><?php echo $product->category; ?></td>
        </tr>        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $product->name; ?></td>
        </tr>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('product_code'); ?></th>
            <td><?php echo $product->code; ?></td>
        </tr>         
        <tr>
            <th width="20%"> <?php echo $this->lang->line('warehouse'); ?></th>
            <td><?php echo $product->warehouse_name; ?></td>
        </tr> 
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $product->note; ?></td>
        </tr>
        <tr>
            <th> <?php echo $this->lang->line('status'); ?></th>
            <td><?php echo $product->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>
        </tr>
    </tbody>
</table>