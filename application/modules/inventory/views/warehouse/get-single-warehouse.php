<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('warehouse'); ?> </th>
            <td><?php echo $warehouse->name; ?></td>
        </tr>
        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('warehouse_keeper'); ?></th>
            <td><?php echo $warehouse->keeper; ?></td>
        </tr>

        <tr>
            <th width="20%"> <?php echo $this->lang->line('email'); ?></th>
            <td><?php echo $warehouse->email; ?></td>
        </tr>
        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('phone'); ?></th>
            <td><?php echo $warehouse->phone; ?></td>
        </tr>
        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('address'); ?></th>
            <td><?php echo $warehouse->address; ?></td>
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $warehouse->note; ?></td>
        </tr>
        <tr>
            <th> <?php echo $this->lang->line('status'); ?></th>
            <td><?php echo $warehouse->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>
        </tr>
         
    </tbody>
</table>
