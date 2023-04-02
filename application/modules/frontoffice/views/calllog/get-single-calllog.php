<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>               
        <tr>
            <th><?php echo $this->lang->line('call_type'); ?></th>
            <td><?php echo $this->lang->line($calllog->call_type); ?></td>
        </tr>              
        <tr>
            <th><?php echo $this->lang->line('name'); ?> </th>
            <td><?php echo $calllog->name; ?></td>
        </tr>    
        <tr>
            <th><?php echo $this->lang->line('phone'); ?> </th>
            <td><?php echo $calllog->phone; ?></td>
        </tr>    
        <tr>
            <th><?php echo $this->lang->line('call_duration'); ?> </th>
            <td><?php echo $calllog->call_duration; ?></td>
        </tr>    
        <tr>
            <th><?php echo $this->lang->line('call_date'); ?> </th>
            <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($calllog->call_date)); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('follow_up'); ?> </th>
            <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($calllog->next_follow_up)); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $calllog->note; ?></td>
        </tr>  
        <tr>
            <th><?php echo $this->lang->line('status'); ?></th>
            <td><?php echo $calllog->status ? $this->lang->line('active') : $this->lang->line('in_active'); ?></td>                                           
        </tr>  
    </tbody>
</table>