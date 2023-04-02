<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa fa-desktop"></i><small> <?php echo $this->lang->line('about_school'); ?></small></h3>
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
                        <li  class="active"><a href="#tab_edit_about"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('about_school'); ?></a> </li>                          
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                       
                        <div class="tab-pane fade in active" id="tab_edit_about">
                            <div class="x_content"> 
                               <?php echo form_open_multipart(site_url('frontend/about/edit/'), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                                                                 
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="about_text"><?php echo $this->lang->line('about_school'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <textarea  class="form-control col-md-7 col-xs-12"  name="about_text"  id="edit_about_text" placeholder="<?php echo $this->lang->line('about_school'); ?>"><?php echo isset($setting->about_text) ?  $setting->about_text : ''; ?></textarea>
                                        <div class="help-block"><?php echo form_error('about_text'); ?></div>
                                    </div>
                                </div>                                                         
                                                                
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('image'); ?></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" name="prev_about_image" id="prev_about_image" value="<?php echo $setting->about_image; ?>" />
                                        <?php if($setting->about_image){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/about/<?php echo $setting->about_image; ?>" alt="" width="250" /><br/><br/>
                                        <?php } ?>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                            <input  class="form-control col-md-7 col-xs-12"  name="about_image"  id="about_image" type="file">
                                        </div>
                                        <div class="text-info"><?php echo $this->lang->line('dimension'); ?>:- Max-W: 600px, Max-H: 600px</div>    
                                        <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                        <div class="help-block"><?php echo form_error('about_image'); ?></div>
                                    </div>
                                </div>
                                                         
                                                                                             
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" value="<?php echo isset($setting) ? $setting->id : $id; ?>" name="id" />
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('update'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>                         
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
    
    $("#edit").validate();  
  </script> 

  
      