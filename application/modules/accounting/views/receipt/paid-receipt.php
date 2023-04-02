<div class="row no-print">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-calculator"></i><small> <?php echo $this->lang->line('manage_paid_receipt'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
                        
            <div class="x_content quick-link no-print">
                <?php $this->load->view('quick-link'); ?>  
            </div>
            
           <?php if($this->session->userdata('role_id') != STUDENT){ ?> 
            <div class="x_content no-print"> 
                <?php echo form_open_multipart(site_url('accounting/receipt/paidreceipt'), array('name' => 'paidreceipt', 'id' => 'paidreceipt', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">
                    
                    <?php $teacher_access_data = get_teacher_access_data(); ?> 
                    <?php $guardian_access_data = get_guardian_access_data('class'); ?>
                    
                     
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="item form-group"> 
                                <div><?php echo $this->lang->line('user_type'); ?> <span class="required"> *</span></div>
                                <select  class="form-control col-md-7 col-xs-12 "  name="role_id"  id="role_id" required="required" onchange="get_user_by_role(this.value, '', '');">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                    <?php foreach($roles as $obj ){ ?>
                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($role_id) && $role_id == $obj->id){ echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                    <?php } ?>                                            
                                </select>
                                <div class="help-block"><?php echo form_error('role_id'); ?></div>
                            </div>
                        </div>

                        <div class="display" style="display:<?php if(isset($class_id)){ echo 'block';} ?>">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="item form-group"> 
                                <div><?php echo $this->lang->line('class'); ?> <span class="required">*</span></div>
                                <select  class="form-control col-md-7 col-xs-12 " name="class_id" id="class_id"  required="required" onchange="get_student_by_class(this.value, '');">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php if(isset($classes) && !empty($classes)) { ?>
                                        <?php foreach ($classes as $obj) { ?>
                                        <?php
                                            if($this->session->userdata('role_id') == STUDENT){
                                                if ($obj->id != $this->session->userdata('class_id')){ continue; }
                                            }else if($this->session->userdata('role_id') == TEACHER){
                                              if (!in_array($obj->id, $teacher_access_data)) {continue; }
                                            }else if($this->session->userdata('role_id') == GUARDIAN){
                                               if (!in_array($obj->id, $guardian_access_data)) {continue; }
                                            } 
                                           ?>
                                        <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <div class="help-block"><?php echo form_error('class_id'); ?></div>
                            </div>
                        </div>  
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <div class="item form-group"> 
                                    <div><?php echo $this->lang->line('student'); ?></div>
                                    <select  class="form-control col-md-7 col-xs-12 "  name="student_id"  id="student_id">
                                        <option value="">--<?php echo $this->lang->line('select_student'); ?>--</option> 
                                    </select>
                                    <div class="help-block"><?php echo form_error('student_id'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 user_display" style="display:<?php if(isset($role_id)  && $role_id != STUDENT){ echo 'block';}else{ echo 'none'; } ?>">
                            <div class="item form-group"> 
                                <div><?php echo $this->lang->line('user'); ?></div>
                                <select  class="form-control col-md-12 col-xs-12 "  name="user_id"  id="user_id" >
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                                                                         
                                </select>
                                <div class="help-block"><?php echo form_error('user_id'); ?></div>
                            </div>
                        </div>
                    
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>

                </div>
                <?php echo form_close(); ?>
            </div>
           <?php } ?>            
            <div class="x_content">               
                    
                 <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">                 
                        <li  class="active"><a href="#paid_invoice" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-calculator"></i> <?php echo $this->lang->line('invoice_receipt'); ?></a></li>                          
                    </ul>
                    <br/>
                     <div class="tab-content">
                        <div  class="tab-pane fade in active" id="paid_invoice" >
                            <div class="x_content">   
                               <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                   <thead>
                                       <tr>
                                           <th><?php echo $this->lang->line('sl_no'); ?></th>                                           
                                           <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                           <th><?php echo $this->lang->line('student'); ?>/<?php echo $this->lang->line('sale_to'); ?></th>
                                           <th><?php echo $this->lang->line('status'); ?></th>
                                           <th><?php echo $this->lang->line('amount'); ?></th>
                                           <th><?php echo $this->lang->line('action'); ?></th>                                            
                                       </tr>
                                   </thead>
                                   <tbody>   
                                       <?php $count = 1; if(isset($receipts) && !empty($receipts)){ ?>
                                           <?php foreach($receipts as $obj){ ?>
                                           <tr>
                                               <td><?php echo $count++; ?></td>                                               
                                               <td><?php echo $obj->custom_invoice_id; ?></td>
                                              <td>
                                                <?php $user = get_user_by_role($obj->role_id, $obj->user_id); ?>
                                                <?php
                
                                                if(!empty($user)){    
                                                ?>
                                                    <?php echo  $user->name; ?> [<?php echo  $user->role; ?>]<br>                
                                                    <?php
                                                    if($obj->role_id == STUDENT){
                                                        echo $this->lang->line('class').': '.$user->class_name.', '. $this->lang->line('section').': '.$user->section.', '. $this->lang->line('roll_no'). ':'. $user->roll_no;
                                                    }
                                                    ?>
                                                <?php } ?> 
                                               </td> 
                                               <td><?php echo get_paid_status($obj->paid_status); ?></td>
                                               <td><?php echo $obj->amount; ?></td>
                                               <td>
                                                  <a  onclick="get_due_receipt_modal(<?php echo $obj->txn_id; ?>);"  data-toggle="modal" data-target=".bs-receipt-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                               </td>
                                           </tr>
                                           <?php } ?>
                                       <?php } ?>
                                   </tbody>
                               </table>
                           </div>
                        </div>
                 </div>
                </div>
            </div>       
    </div>
</div>
</div>


<div class="modal fade bs-receipt-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header  no-print">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_receipt_data">            
        </div>       
      </div>
    </div>
</div>

<script type="text/javascript">
         
    function get_due_receipt_modal(txn_id){
         
        $('.fn_news_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('accounting/receipt/get_single_paid_receipt'); ?>",
          data   : {txn_id:txn_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_receipt_data').html(response);
             }
          }
       });
    }
</script>


<!-- Super admin js START  -->
<script type="text/javascript">
        
    
    <?php if(isset($role_id)){ ?>
      get_user_by_role('<?php echo $role_id;  ?>', '<?php echo $class_id; ?>', '<?php echo $user_id; ?>');
    <?php } ?>
    function get_user_by_role(role_id, class_id, user_id){       
       
       if(role_id == <?php echo STUDENT; ?>){
           $('.display').show();
           $('.user_display').hide();
           $('#class_id').attr("required");
           $('#user_id').html('<option value="">--<?php echo $this->lang->line('select'); ?>--</option>'); 
           if(class_id !='' ){
                get_user(role_id, class_id, user_id);
           }
       }else{           
           $('.user_display').show();
           get_user(role_id, '', user_id);
           $('#class_id').removeAttr("required");
           $('.display').hide();
       }       
   }
   
    function get_user(role_id, class_id, user_id){

        if(role_id == ''){
            role_id = $('#role_id').val();
        }

        $.ajax({       
             type   : "POST",
             url    : "<?php echo site_url('ajax/get_user_by_role'); ?>",
             data   : { role_id : role_id , class_id: class_id, user_id:user_id},               
             async  : false,
             success: function(response){                                                   
                if(response)
                {
                    $('#user_id').html(response); 
                }
             }
         }); 
    }


    <?php if(isset($class_id) && isset($student_id)){ ?>
        get_student_by_class('<?php echo $class_id; ?>', '<?php echo $student_id; ?>');
    <?php } ?>
    
    function get_student_by_class(class_id, student_id){       
               
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_student_by_class'); ?>",
            data   : {class_id: class_id, student_id: student_id, is_all:true},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                  $('#student_id').html(response);
               }
            }
        });         
    }
 
</script>
<!-- Super admin js end -->


<!-- datatable with buttons -->
 <script type="text/javascript">
    $(document).ready(function() {
      $('#datatable-responsive').DataTable( {
          dom: 'Bfrtip',
          iDisplayLength: 15,
          buttons: [
              'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5',
              'pageLength'
          ],
          search: true,              
          responsive: true
      });
    });    
    
    $("#paidreceipt").validate();
    
</script>