<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('name'); ?></th>
            <td><?php echo $store->name; ?></td>
        </tr>

        <tr>
            <th width="20%"> <?php echo $this->lang->line('store_keeper'); ?></th>
            <td><?php echo $store->keeper; ?></td>
        </tr>

        <tr>
            <th width="20%"> <?php echo $this->lang->line('phone'); ?></th>
            <td><?php echo $store->phone; ?></td>
        </tr>

        <tr>
            <th width="20%"> <?php echo $this->lang->line('address'); ?></th>
            <td><?php echo $store->address; ?></td>
        </tr>

        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $store->note; ?></td>
        </tr> 
        <tr>
            <th> <?php echo $this->lang->line('status'); ?></th>
            <td><?php echo $store->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>
        </tr>        
    </tbody>
</table>
