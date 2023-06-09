<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title no-print">
                <h3 class="head-title"><i class="fa fa-barcode"></i><small> <?php echo $this->lang->line('generate_teacher_id_card'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
              
            <div class="x_content quick-link no-print">
                <?php $this->load->view('quick-link'); ?>              
            </div>
            
            <div class="x_content no-print"> 
                <?php echo form_open_multipart(site_url('card/teacher/index'), array('name' => 'generate', 'id' => 'generate', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="item form-group"> 
                            <div><?php echo $this->lang->line('teacher'); ?> <span class="required">*</span></div>
                            <select  class="form-control col-md-7 col-xs-12 "  name="teacher_id"  id="teacher_id">
                                <option value="0"><?php echo $this->lang->line('all_teacher'); ?></option>  
                                <?php if (isset($teachers) && !empty($teachers)) { ?>
                                    <?php foreach ($teachers as $obj) { ?>
                                        <option value="<?php echo $obj->id; ?>" <?php if (isset($teacher_id) && $teacher_id == $obj->id) { echo 'selected="selected"'; } ?>><?php echo $obj->name; ?></option>
                                    <?php } ?> 
                                <?php } ?> 
                            </select>
                            <div class="help-block"><?php echo form_error('teacher_id'); ?></div>
                        </div>
                    </div>                
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('generate'); ?></button>
                        </div>
                    </div>

                </div>
                <?php echo form_close(); ?>
            </div>

            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">                    
                    <ul  class="nav nav-tabs bordered no-print">                 
                        <li  class="active"><a href="#tab_teacher_list" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa fa-users"></i> <?php echo $this->lang->line('teacher'); ?></a></li>                          
                    </ul>
                    <br/>
                    <div class="tab-content">
                        <div  class="tab-pane fade in active" id="tab_teacher_list">
                            <div class="x_content">
                                
                               <div class="row">
                                   
                                   <?php if(isset($cards) && !empty($cards)){ ?>
                                        <?php $counter = 1; foreach($cards as $obj){ ?>  

                                            <div class="card-block">
                                                <div class="card-top">
                                                    <div class="card-logo">
                                                        <?php  if($setting->school_logo != ''){ ?>
                                                           <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $setting->school_logo; ?>" alt="" /> 
                                                        <?php }else if($this->gsms_setting->logo){ ?>
                                                           <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->gsms_setting->logo; ?>" alt="" /> 
                                                        <?php }else if($this->gsms_setting->frontend_logo){ ?>
                                                           <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->gsms_setting->frontend_logo; ?>" alt="" /> 
                                                        <?php }else{ ?>                                                        
                                                           <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->gsms_setting->brand_logo; ?>" alt=""  />
                                                        <?php } ?>                                                          
                                                    </div>
                                                    <div class="card-school">
                                                        <h2><?php echo isset($setting->school_name) && $setting->school_name != '' ? $setting->school_name : $this->gsms_setting->school_name; ?></h2>
                                                        <p><?php echo isset($setting->school_address) && $setting->school_address != '' ? $setting->school_address : $this->gsms_setting->address;; ?></p>
                                                        <p><?php echo $this->lang->line('phone'); ?>: <?php echo isset($setting->phone) && $setting->phone != '' ? $setting->phone : $this->gsms_setting->phone;; ?></p>
                                                    </div>
                                                </div>
                                                <div class="std-id">
                                                    <h3><span><?php echo $this->lang->line('teacher_id'); ?>: <?php echo $obj->user_id; ?></span> </h3>
                                                </div>
                                                <div class="card-main">
                                                    <div class="card-photo">
                                                        <?php  if($obj->photo != ''){ ?>
                                                        <img src="<?php echo UPLOAD_PATH; ?>/teacher-photo/<?php echo $obj->photo; ?>" alt="" /> 
                                                        <?php }else{ ?>
                                                        <img src="<?php echo IMG_URL; ?>/default-user.png" alt=""  /> 
                                                        <?php } ?>
                                                    </div>
                                                    <div class="card-info">
                                                        <p><span class="card-title"><?php echo $this->lang->line('teacher_name'); ?></span><span class="card-value">: <?php echo $obj->name; ?></span></p>
                                                        <p><span class="card-title"><?php echo $this->lang->line('department'); ?></span><span class="card-value">: <?php echo $obj->title; ?></span></p>
                                                        <p><span class="card-title"><?php echo $this->lang->line('blood_group'); ?></span><span class="card-value">: <?php echo $this->lang->line($obj->blood_group); ?></span></p>
                                                        <p><span class="card-title"><?php echo $this->lang->line('birth_date'); ?></span><span class="card-value">: <?php echo date($this->gsms_setting->sms_date_format, strtotime($obj->dob)); ?></span></p>
                                                        <p><span class="card-title"><?php echo $this->lang->line('join_date'); ?></span><span class="card-value">: <?php echo date($this->gsms_setting->sms_date_format, strtotime($obj->joining_date)); ?></span></p>
                                                    </div>
                                                </div>
                                                <div class="card-bottom">
                                                    <p><?php echo isset($setting->bottom_text) ? $setting->bottom_text : ''; ?></p>
                                                </div>
                                            </div>

                                       <?php if($counter%4 == 0){ ?>                                               
                                              <div class="pagebreak">&nbsp;</div>                                              
                                        <?php } ?>

                                       <?php $counter++; } ?>
                                   <?php } ?>
                                    
                               </div>
                                
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-print"></div>
            <div class="ln_solid no-print"></div>
            <div class="row no-print">
                <div class="col-xs-12 text-right">
                    <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
            
        </div>
    </div>
</div>

<link href="<?php echo CSS_URL; ?>card.css" rel="stylesheet">
<?php $this->load->view('layout/card-css'); ?>   


<script type="text/javascript">
    $("#generate").validate();
</script> 
