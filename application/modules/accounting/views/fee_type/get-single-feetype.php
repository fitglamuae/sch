<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th><?php echo $this->lang->line('title'); ?></th>
            <td><?php echo $feetype->title; ?></td>
        </tr>
        
       <?php if($feetype->head_type == 'fee'){ ?> 
            <tr style="background: #e7e7e7;">
                <th><?php echo $this->lang->line('class'); ?> <i class="fa fa-long-arrow-down blue"></i></th>
                <th><?php echo $this->lang->line('amount'); ?> <i class="fa fa-long-arrow-down blue"></i></th>
            </tr>        
            <?php foreach($classes as $obj){ ?>
            <?php $fee_amount = get_fee_amount($feetype->id, $obj->id); ?>
                <tr>
                    <th><?php echo $obj->name; ?></th>
                    <td><?php echo @$fee_amount->fee_amount; ?></td>
                </tr>
            <?php } ?> 
        <?php } ?> 
            
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $feetype->note; ?>   </td>
        </tr>
    </tbody>
</table>
