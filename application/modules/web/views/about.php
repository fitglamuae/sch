<div class="page-header-area">
    <div class="container">
        <div class="page-header-content">
            <h2 class="title"><span class="inner"><?php echo $this->lang->line('about_school'); ?></span></h2>
            <ul class="links">
                <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a></li>
                <li><a href="javascript:void(0);"><?php echo $this->lang->line('about_school'); ?></a></li>
            </ul>
        </div>
    </div>
</div>
<?php if(isset($this->setting->about_text) && !empty($this->setting->about_text)){ ?>
   <div class="welcome-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="welcome-banner">
                        <?php if(isset($this->setting->about_image) && !empty($this->setting->about_image)){ ?>
                        <img class="wb-banner" src="<?php echo UPLOAD_PATH; ?>about/<?php echo $this->setting->about_image; ?>" alt="">
                        <?php }else{ ?>
                            <img class="wb-banner" src="<?php echo IMG_URL; ?>about-image.jpg" alt="">
                        <?php } ?>  
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="welcome-content">
                        <h2 class="title"><?php echo $this->lang->line('welcome_to'); ?></h2>
                        <h2 class="title-2">
                            <?php if(isset($this->setting->school_name)){ ?>
                            <?php echo $this->setting->school_name; ?>
                            <?php }else{ ?>
                                  <?php echo $this->GSMS; ?>
                            <?php } ?>
                        </h2>
                        <p class="text">
                            <?php echo nl2br($this->setting->about_text); ?>  
                        </p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
