<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('category'); ?> </th>
            <td><?php echo $issue->category_name; ?></td>
        </tr>        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('asset'); ?></th>
            <td><?php echo $issue->item_name; ?></td>
        </tr>
        <tr>
            <th width="20%"> <?php echo $this->lang->line('quantity'); ?></th>
            <td><?php echo $issue->qty; ?></td>
        </tr>        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('user_type'); ?></th>
            <td><?php echo $issue->role_name; ?></td>
        </tr>        
        <tr>
            <th width="20%"> <?php echo $this->lang->line('issue_to_user'); ?></th>
             <td>
                <?php
                    $user = get_user_by_role($issue->role_id, $issue->user_id);
                    echo $user->name;
                    if($issue->role_id == STUDENT){
                        echo ' [ '.$this->lang->line('class').': '.$user->class_name.', '. $this->lang->line('section').': '.$user->section.','. $this->lang->line('roll_no'). ':'. $user->roll_no . ']';
                    }
                 ?>
            </td>
        </tr>        
        <tr>
            <th><?php echo $this->lang->line('issue_date'); ?> </th>
            <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($issue->issue_date)); ?></td>      
        </tr>        
        <tr>
            <th><?php echo $this->lang->line('check_in'); ?> </th>
            <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($issue->check_in_date)); ?></td>      
        </tr>        
        <tr>
            <th><?php echo $this->lang->line('check_out'); ?> </th>
            <td>
                <?php 
                if($issue->check_out_date){
                    echo date($this->gsms_setting->sms_date_format, strtotime($issue->check_out_date));
                }else{ 
                  if(has_permission(EDIT, 'asset', 'issue')){         
                    echo '<a style="color:red;" href="javascript:void(0);" onclick="issue_check_out('.$issue->id.');">'.$this->lang->line('return_this').'</a>';
                  }
                }
               ?>
            </td>      
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td><?php echo $issue->note; ?></td>
        </tr>
         
    </tbody>
</table>
