<table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
    <tbody>
        <tr>               
            <th><?php echo $this->lang->line('session_year'); ?></th>
            <td colspan="3"><?php echo $donar->session_year; ?></td>
        </tr>
        
        <tr>
            <th> <?php echo $this->lang->line('donar_type'); ?> </th>
            <td><?php echo $this->lang->line($donar->donar_type); ?></td>                  
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('donar_name'); ?></th>
             <td><?php echo $donar->donar_name; ?></td>    
        </tr>
           
        <tr>
            <th><?php echo $this->lang->line('contact_name'); ?> </th>
            <td><?php echo $donar->contact_name; ?></td>        
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('email'); ?> </th>
            <td><?php echo $donar->email; ?></td>        
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('phone'); ?> </th>
            <td><?php echo $donar->phone; ?></td>   
        </tr>
       
        <tr>
            <th><?php echo $this->lang->line('amount'); ?> </th>
            <td><?php echo $donar->amount; ?></td>   
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('address'); ?> </th>
            <td><?php echo $donar->address; ?></td>   
        </tr>
        
        <tr>
            <th><?php echo $this->lang->line('note'); ?> </th>
            <td><?php echo $donar->note; ?></td>        
        </tr>
         
    </tbody>
</table>
