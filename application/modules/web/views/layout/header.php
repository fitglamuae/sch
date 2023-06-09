<header class="no-print">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-8 col-12">
                    <div class="ht-left">
                        <a href="#" class="link"><?php echo $this->lang->line('have_any_question'); ?></a>
                        <?php if(isset($this->setting->phone) && !empty($this->setting->phone)){ ?>
                            <a href="tel:<?php echo $this->setting->phone; ?>" class="link"><span class="icon"><i class="fas fa-phone-volume"></i></span> <?php echo $this->setting->phone; ?></a>
                        <?php } ?> 
                        <?php if(isset($this->setting->email) && !empty($this->setting->email)){ ?>    
                            <a href="mailto:<?php echo $this->setting->email; ?>" class="link"><span class="icon"><i class="fas fa-envelope"></i></span> <?php echo $this->setting->email; ?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-5 col-md-4 col-12">
                    <div class="ht-right">
                        <?php if(isset($this->setting->enable_online_admission) && !empty($this->setting->enable_online_admission)){ ?>
                            <a href="<?php echo site_url('admission-online'); ?>" class="link"><?php echo $this->lang->line('admission'); ?> </a>
                        <?php } ?>
                        <?php if (logged_in_user_id()) { ?>  
                            
                            <a href="<?php echo site_url('dashboard'); ?>" class="link"><span class="icon"><i class="fas fa-dashcube"></i></span> <?php echo $this->lang->line('dashboard'); ?></a>
                            <a href="<?php echo site_url('logout'); ?>" class="link"><span class="icon"><i class="fas fa-unlock"></i></span> <?php echo $this->lang->line('logout'); ?></a>                            
                        <?php }else{ ?>  
                            <a href="<?php echo site_url('login'); ?>" class="link"><span class="icon"><i class="fas fa-lock"></i></span> <?php echo $this->lang->line('login'); ?></a>
                        <?php } ?>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom <?php if(!isset($is_home)){ ?> header-bottom-inner<?php } ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-6">
                    <div class="logo">
                        <a href="<?php echo site_url(); ?>">
                            <?php if(isset($this->setting->front_logo) && !empty($this->setting->front_logo)){ ?>                            
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->setting->front_logo; ?>" alt=""  />
                            <?php }elseif(isset($this->setting->logo) && !empty($this->setting->logo)){ ?>
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->setting->logo; ?>" alt=""  />
                            <?php }else{ ?>
                                <img src="<?php echo IMG_URL; ?>default-front-logo.png" alt=""  />
                            <?php } ?> 
                        </a>
                    </div>
                </div>
                <div class="col-lg-10 col-6">
                    <div class="stellarnav">
                        <ul>
                            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a></li>
                            <li><a href="#"><?php echo $this->lang->line('announcement'); ?></a>
                                <ul>
                                    <li><a href="<?php echo site_url('news'); ?>"><?php echo $this->lang->line('news'); ?></a></li>
                                    <li><a href="<?php echo site_url('notice'); ?>"><?php echo $this->lang->line('notice'); ?></a></li>
                                    <li><a href="<?php echo site_url('holiday'); ?>"><?php echo $this->lang->line('holiday'); ?></a></li>
                                </ul>                                
                            </li>
                            <li><a href="<?php echo site_url('events'); ?>"><?php echo $this->lang->line('event'); ?></a></li>
                            <li><a href="<?php echo site_url('galleries'); ?>"><?php echo $this->lang->line('gallery'); ?></a></li>
                            <li><a href="<?php echo site_url('teachers'); ?>"><?php echo $this->lang->line('teacher'); ?></a></li>
                            <li><a href="<?php echo site_url('staff'); ?>"><?php echo $this->lang->line('staff'); ?></a></li>
                            <li><a href="<?php echo site_url('faq'); ?>"><?php echo $this->lang->line('faq'); ?></a></li>
                            <li><a href="<?php echo site_url('contact'); ?>"><?php echo $this->lang->line('contact_us'); ?></a></li>
                            <?php if(isset($header_pages) && !empty($header_pages)){ ?>
                                <li><a href="javascript:void(0)"><?php echo $this->lang->line('page'); ?></a>
                                    <ul>
                                    <?php foreach($header_pages AS $obj ){ ?>
                                         <li><a href="<?php echo site_url('page/'.$obj->page_slug); ?>"><?php echo $obj->page_title; ?></a></li>
                                     <?php } ?> 
                                    </ul>                                
                                </li>    
                            <?php } ?> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>