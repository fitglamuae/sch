<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-mouse-pointer"></i><small> <?php echo $this->lang->line('manage_exam_result'); ?> </small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content quick-link">
                <?php $this->load->view('quick-link'); ?>              
            </div>
            
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs nav-tab-find bordered">
                   
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('onlineexam/takeexam/index'); ?>"  aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('list'); ?></a> </li>
                                    
                        <li class="li-class-list">
                            <?php echo form_open(site_url('onlineexam/takeexam/result'), array('name' => 'add', 'id' => 'add', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                <select  class="form-control col-md-7 col-xs-12 " name="class_id" id="class_id" onchange="get_subject_by_class_filter(this.value, '');" style="width: auto;">
                                    <?php if($this->session->userdata('role_id') != STUDENT){ ?>
                                       <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                    <?php } ?>        
                                    <?php foreach($classes as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT){ ?>
                                            <?php if ($obj->id != $this->session->userdata('class_id')){ continue; } ?>
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_class_id) && $filter_class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php }else{ ?> 
                                            <option value="<?php echo $obj->id; ?>" <?php if(isset($filter_class_id) && $filter_class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                                                                     
                                    <?php } ?>                                            
                                </select> 
                                <select  class="form-control col-md-7 col-xs-12 " name="subject_id" id="subject_id" style="width: auto;">                                
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option> 
                                    <?php foreach($subjects as $obj ){ ?>
                                        <?php if($this->session->userdata('role_id') == STUDENT){ ?>                                       
                                            <option value="<?php $obj->id; ?>" <?php if(isset($subject_id) && $subject_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $obj->name; ?></option>
                                        <?php } ?>                                                                                     
                                    <?php } ?>                                            
                                </select> 
                                <input type="submit" name="find" value="<?php echo $this->lang->line('find'); ?>"  class="btn btn-info btn-sm"/>
                            <?php echo form_close(); ?>
                        </li>
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_exam_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>   
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('exam_title'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>  
                                    
                                    <?php $count = 1; if(isset($exam_results) && !empty($exam_results)){ ?>
                                        <?php foreach($exam_results as $obj){ ?>                                        
                                        <tr>
                                            <td><?php echo $count++; ?></td>                                           
                                            <td><?php echo $obj->student_name; ?></td>
                                            <td><?php echo $obj->exam_title; ?></td>
                                            <td><?php echo $obj->class_name; ?></td>
                                            <td><?php echo $obj->section; ?></td>
                                            <td><?php echo $obj->subject; ?></td>
                                            <td><?php echo $obj->result_status == 'passed' ? $this->lang->line('passed') : $this->lang->line('failed'); ?></td>
                                            <td>
                                                <?php if(has_permission(VIEW, 'onlineexam', 'takeexam')){ ?>
                                                    <a  onclick="get_result_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-result-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                <?php } ?>                                                    
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


<div class="modal fade bs-result-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('detail_information'); ?></h4>
        </div>
        <div class="modal-body fn_result_data">
            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_result_modal(result_id){
         
        $('.fn_result_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('onlineexam/takeexam/get_single_result'); ?>",
          data   : {result_id : result_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_result_data').html(response);
             }
          }
       });
    }
</script>


<!-- datatable with buttons -->
<script type="text/javascript">
    
    <?php if(isset($filter_class_id) && $filter_class_id != '' && isset($filter_subject_id)){ ?>
        get_subject_by_class_filter('<?php echo $filter_class_id; ?>', '<?php echo $filter_subject_id; ?>');
    <?php } ?>
        
    function get_subject_by_class_filter(class_id, subject_id){       
           
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_subject_by_class'); ?>",
            data   : { class_id : class_id , subject_id : subject_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                  
                   $('#subject_id').html(response);                  
               }
            }
        });                  
        
   }
    
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

</script>
<style type="text/css">
    .nav-tabs>li {
        margin-bottom: -6px;
    }
</style>
 