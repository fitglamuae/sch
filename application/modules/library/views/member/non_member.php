<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-book"></i><small> <?php echo $this->lang->line('manage_library_member'); ?></small></h3>
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
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="<?php echo site_url('library/member/index'); ?>"   aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('member'); ?></a> </li>
                        <?php if(has_permission(ADD, 'library', 'member')){ ?>
                            <li  class="<?php if(isset($non_list)){ echo 'active'; }?>"><a href="<?php echo site_url('library/member/add'); ?>"  aria-expanded="false"><i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('non_member'); ?></a> </li>                          
                        <?php } ?>
                    </ul>
                    <br/>
                    
                    <div class="tab-content">                       
                        <div  class="tab-pane fade in <?php if(isset($non_list)){ echo 'active'; }?>" id="tab_non_member_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>
                                        <th><?php echo $this->lang->line('photo'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('roll_no'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($non_members) && !empty($non_members)){ ?>
                                        <?php foreach($non_members as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td>
                                               <?php  if($obj->photo != ''){ ?>
                                                <img src="<?php echo UPLOAD_PATH; ?>/student-photo/<?php echo $obj->photo; ?>" alt="" width="70" /> 
                                                <?php }else{ ?>
                                                <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="70" /> 
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $obj->name; ?></td>
                                            <td><?php echo $obj->class_name; ?></td>
                                            <td><?php echo $obj->section; ?></td>
                                            <td><?php echo $obj->roll_no; ?></td>
                                            <td>
                                                <?php if(has_permission(ADD, 'library', 'member')){ ?>
                                                    <a href="javascript:void(0);" id="<?php echo $obj->user_id; ?>" class="btn btn-success btn-xs fn_add_to_library"><i class="fa fa-reply"></i> <?php echo $this->lang->line('add_to_library'); ?></a>
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

 <script type="text/javascript">
     
      $(document).ready(function(){
          
        $('.fn_add_to_library').click(function(){
           
          var user_id  = $(this).attr('id');         
          
          $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('library/member/add_to_library'); ?>",
            data   : { user_id : user_id},               
            async  : false,
            success: function(response){ 
                if(response){
                    toastr.success('Success');
                    location.reload();
                }else{
                    toastr.error('Error'); 
                }
            }
        }); 
                      
       });       
   });
</script>
 <script type="text/javascript">
        $(document).ready(function() {
          $('#datatable-responsive, .datatable-responsive').DataTable( {
              dom: 'Bfrtip',
              iDisplayLength: 15,
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength'
              ],
              search: true
          } );
        } );
</script>