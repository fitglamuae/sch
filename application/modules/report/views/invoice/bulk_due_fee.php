<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('due_fee_report'); ?></small></h3>                
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <?php $this->load->view('quick_report'); ?>   
            
             <div class="x_content filter-box no-print"> 
                <?php echo form_open_multipart(site_url('report/bulkduefee'), array('name' => 'duefee', 'id' => 'duefee', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">                    
                   <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <?php echo $this->lang->line('academic_year'); ?>
                            <select  class="form-control col-md-7 col-xs-12 " name="academic_year_id" id="academic_year_id">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php foreach ($academic_years as $obj) { ?>
                                <?php $running = $obj->is_running ? ' ['.$this->lang->line('running_year').']' : ''; ?>
                                <option value="<?php echo $obj->id; ?>" <?php if(isset($academic_year_id) && $academic_year_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->session_year; echo $running; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('class'); ?> <span class="required">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12 " name="class_id" id="class_id" required="required"  onchange="get_student_by_class(this.value, '');">
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                <?php foreach ($classes as $obj) { ?>
                                <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                   <div class="col-md-3 col-sm-3 col-xs-12 display_">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('student'); ?></div>
                            <select  class="form-control col-md-7 col-xs-12 " name="student_id" id="student_id" >                                
                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                            </select>
                        </div>
                    </div> 
                
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

            <?php if(isset($students) && !empty($students)){ ?> 
            <?php foreach($students as $std){ ?>
            
                <?php $due_fees = get_student_due_fees($academic_year_id, $class_id, $std->id); ?>
                
                <?php if(!empty($due_fees)){ ?>                
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-6 col-xs-6  col-sm-offset-3 col-xs-offset-3 layout-box">                       
                            <div><img   src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->gsms_setting->logo; ?>" alt="" width="70" /></div>
                            <h4><?php echo $this->gsms_setting->school_name; ?></h4>
                            <p><?php echo $this->gsms_setting->address; ?></p>                                                                
                                 <p>
                                     <strong><?php echo $this->lang->line('student'); ?> :</strong> <?php echo $std->name; ?>, 
                                     <strong><?php echo $this->lang->line('father'); ?>:</strong> <?php echo $std->father_name; ?>
                                 </p>
                                <p>                                            
                                    <strong><?php echo $this->lang->line('class'); ?>:</strong> <?php echo $std->class_name; ?>,  
                                    <strong><?php echo $this->lang->line('roll_no'); ?>:</strong> <?php echo $std->roll_no; ?>,  
                                    <strong><?php echo date('d/m/Y'); ?></strong>
                                </p>                                                       
                            <h3 class="head-title ptint-title"><i class="fa fa-bar-chart"></i><small> <?php echo $this->lang->line('due_fee_report'); ?></small></h3> <br/>               
                        </div>
                    </div>            

                     <div class="row">
                        <table id="datatable-keytable" class="datatable-responsive table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                         <thead>
                             <tr>
                                 <th><?php echo $this->lang->line('sl_no'); ?></th>                                       
                                 <th><?php echo $this->lang->line('month'); ?></th>
                                 <th><?php echo $this->lang->line('title'); ?></th>
                                 <th><?php echo $this->lang->line('net_amount'); ?></th>                                        
                                 <th><?php echo $this->lang->line('paid_amount'); ?></th>                                        
                                 <th><?php echo $this->lang->line('due_fee'); ?></th>
                             </tr>
                         </thead>
                         <tbody>   
                             <?php 
                             $total_balance = 0; 
                             $count = 1; 
                             ?>
                            <?php foreach($due_fees as $obj ){ ?>
                            <tr>
                                <td><?php echo $count++; ?></td>  
                                <td><?php echo $obj->month; ?></td>
                                <td><?php echo $obj->head; ?></td>
                                <td class="blue"><?php echo $obj->net_amount; ?></td>
                                <td class="green"><?php echo $obj->paid_amount ? $obj->paid_amount : 0; ?></td>
                                <td class="red"><?php echo $obj->net_amount - $obj->paid_amount; $total_balance += $obj->net_amount - $obj->paid_amount; ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="5"><strong><?php echo $this->lang->line('total_due_fee'); ?> (-)</strong></td>
                                <td class="red"><strong><?php echo number_format($total_balance,2); ?></strong></td>                                           
                            </tr>                             
                        </tbody>                                               
                    </table>
                </div>               
                </div>
                <div class="x_content">
                   <div class="row"><div class="pagebreak">&nbsp;</div></div>
                </div>
            <?php } ?>
            
            <?php } ?>
            
            <?php }else{ ?>  
               <div class="x_content">
                   <div class="row">
                       <div class="col-sm-12 col-xs-12 text-center"><?php echo $this->lang->line('no_data_found'); ?></div>
                   </div>
               </div>
            <?php } ?>
            
             <div class="row no-print">
                <div class="col-xs-12 text-right">
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
            
        </div>
    </div>
</div>
 <script type="text/javascript">

    $("#duefee").validate(); 
    
    <?php if(isset($class_id) && isset($student_id)){ ?>
        get_student_by_class('<?php echo $class_id; ?>', '<?php echo $student_id; ?>');
    <?php } ?>
      function get_student_by_class(class_id, student_id){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_student_by_class'); ?>",
            data   : {class_id: class_id, student_id:student_id, bulk:true},               
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