<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="col-md-3">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="col-md-5 ">
                <div class="school-name"><?php echo $this->gsms_setting->school_name; ?></div>
            </div>
            <div class="col-md-4">
                <ul class="nav navbar-nav <?php echo $this->gsms_setting->enable_rtl ? 'navbar-left' : 'navbar-right'; ?>">
                    <li class="fn_for_online_exam">
                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <?php
                                $photo = $this->session->userdata('photo');
                                $role_id = $this->session->userdata('role_id');
                                $path = '';
                                if($role_id == STUDENT){ $path = 'student'; }
                                elseif($role_id == GUARDIAN){ $path = 'guardian'; }
                                elseif($role_id == TEACHER){ $path = 'teacher'; }
                                else{ $path = 'employee'; }
                            ?>
                            <?php if ($photo != '') { ?>                                        
                                <img src="<?php echo UPLOAD_PATH; ?>/<?php echo $path; ?>-photo/<?php echo $photo; ?>" alt="" width="60" /> 
                            <?php } else { ?>
                                <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="60" /> 
                            <?php } ?>                            
                            <?php echo $this->session->userdata('name'); ?>
                            <span class=" fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-usermenu pull-left">
                            <li><a href="<?php echo site_url('profile/index'); ?>"> <?php echo $this->lang->line('profile'); ?></a></li>
                            <li><a href="<?php echo site_url('profile/password'); ?>"><?php echo $this->lang->line('reset_password'); ?></a></li>
                            <li><a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-sign-out pull-left"></i> <?php echo $this->lang->line('logout'); ?></a></li>
                        </ul>
                    </li>
                    <?php $messages = get_inbox_message(); ?>
                    <?php if(isset($messages) && !empty($messages)){ ?>
                    <li role="presentation" class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-green"><?php echo count($messages); ?></span>
                        </a>
                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                            
                           <?php foreach($messages as $obj){ ?> 
                            <li>
                                <?php $user = get_user_by_id($obj->sender_id);  ?>
                                 <a href="<?php echo site_url('message/view/'.$obj->id); ?>">
                                    <span class="image">
                                        <img src="<?php echo IMG_URL; ?>default-user.png" alt="Profile Image" />
                                    </span>
                                    <span>
                                        <span><?php echo $user->name; ?></span>
                                        <span class="time"><?php echo get_nice_time($obj->created_at); ?></span>
                                    </span>
                                    <span class="message">
                                        <?php echo $obj->subject; ?>
                                    </span>
                                </a>
                            </li>                    
                            <?php } ?>
                            <li>
                                <div class="text-center">
                                    <a href="<?php echo site_url('message/inbox'); ?>">
                                        <strong><?php echo $this->lang->line('read_more'); ?></strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->gsms_setting->enable_frontend){ ?>
                        <li>
                            <a href="<?php echo site_url(); ?>"><i class="fa fa-globe"></i> <?php echo $this->lang->line('web'); ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    </div>
</div>

<?php if(has_permission(VIEW, 'setting', 'globalsearch') || has_permission(VIEW, 'setting', 'sessionchange')){ ?> 

    <div class="<?php echo $this->gsms_setting->enable_rtl ? 'left_col' : 'right_col'; ?> fn_header_global no-print">  
    <div class="x_panel" style="padding-bottom: 2px;margin: 0px;">             
        <div class="x_content"> 
            <div class="row">               
               <?php if(has_permission(VIEW, 'setting', 'globalsearch')){ ?> 
                <div class="col-md-5 col-sm-5 col-xs-12">  
                    <div class="row">                         
                        <div class="col-md-2 col-sm-2 col-xs-12"></div>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="item form-group">                                
                                <input type="text"  class="form-control col-md-7 col-xs-12"  name="global_search"  id="global_search" onkeyup="get_search(this.value);" placeholder="<?php echo $this->lang->line('global_search'); ?>: <?php echo $this->lang->line('student'); ?>, <?php echo $this->lang->line('guardian'); ?>, <?php echo $this->lang->line('teacher'); ?>, <?php echo $this->lang->line('employee'); ?>" required="required">
                            </div>
                        </div>
                    </div>                       
                </div>
               <?php } ?>
                                
                <?php if(has_permission(VIEW, 'setting', 'sessionchange')){ ?> 
                 <div class="col-md-1 col-sm-1 col-xs-1 header-form-sep"> |</div>
                
                 <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row">  
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <div class="item form-group">                                 
                                <select  class="form-control col-md-7 col-xs-12 "  name="academic_year_id"  id="academic_year_id" required="required">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php foreach ($this->academic_years as $obj) { ?>
                                    <?php $running = $obj->is_running ? ' ['.$this->lang->line('running_year').']' : ''; ?>
                                    <option value="<?php echo $obj->id; ?>" <?php if($this->academic_year_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->session_year; echo $running; ?></option>
                                    <?php } ?>                                                               
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <button  type="submit" class="btn btn-success" onclick="update_academic_year();"><?php echo $this->lang->line('update'); ?></button>
                            </div>
                        </div>
                    </div>          
                </div> 
                <?php } ?>
                
            </div>
            
            
            <!-- ====================START ======================= -->
            <div class="search_result_container"  style="position: absolute; padding-top: 1px; z-index: 999; background: #000;width: 100%; display: none;">
                <div class="row search_result" style="background: #fff; margin:0px 25px 10px  25px;">                    
                </div>
            </div>
            <!-- ====================END ======================= -->
            
        </div>
    </div>
</div>

<script type="text/javascript">
    
    //================ SEARCH ======================================================
      function get_search(keyword){        
         
        if(!keyword){
            $('.search_result').html(''); 
            $('.search_result_container').hide(); 
            return false;
        }
        
        $('.search_result_container').show();  
        $('.search_result').html('<p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" style="width: 40px;" /></p>');
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('dashboard/get_search'); ?>",
            data   : { keyword : keyword},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {                                                      
                   $('.search_result_container').show();                                     
                   $('.search_result').html(response);                                     
               }else{
                   $('.search_result_container').hide(); 
               }
            }
        }); 
    }
        
    
    //======================== ACADEMIC YEAR ==================================
    function update_academic_year(){
    
       var academic_year_id = $('#academic_year_id').val();
               
       if(!academic_year_id){
           toastr.error('<?php echo $this->lang->line('session_year'); ?>');           
           return false;
        }
        
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('administrator/year/update_academic_year'); ?>",
            data   : { academic_year_id : academic_year_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                   toastr.success('<?php echo $this->lang->line('update_success') ?>');                   
                   location.reload();
               }else{
                   toastr.error('<?php echo $this->lang->line('update_failed') ?>'); 
               }
            }
        });        
    }

 
</script>

 <?php } ?>