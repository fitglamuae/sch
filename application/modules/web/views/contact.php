<div class="page-header-area">
    <div class="container">
        <div class="page-header-content">
            <h2 class="title"><span class="inner"><?php echo $this->lang->line('contact_us'); ?></span></h2>
            <ul class="links">
                <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a></li>
                <li><a href="javascript:void(0);"><?php echo $this->lang->line('contact_us'); ?></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="page-contact-info-area_">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <?php $this->load->view('layout/message'); ?> 
            </div>
        </div>
    </div>
</div>

<div class="page-contact-info-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                <div class="pcia-info">
                    <div class="title-wrapper">
                        <h2 class="title">
                            <img src="<?php echo IMG_URL; ?>front/icon/heading-<?php echo $theme->slug; ?>.png" alt="">
                            <?php echo $this->lang->line('contact_us'); ?>
                        </h2>
                    </div>
                    <div class="row">
                        <?php if(isset($this->setting->phone) && !empty($this->setting->phone)){ ?>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="pcia-info-box">
                                    <div class="icon">
                                        <span class="box"><i class="fas fa-phone-volume"></i></span>
                                    </div>
                                    <p class="text"><?php echo $this->lang->line('phone'); ?>: <?php echo $this->setting->phone; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($this->setting->school_fax) && !empty($this->setting->school_fax)){ ?>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="pcia-info-box">
                                    <div class="icon">
                                        <span class="box"><i class="fas fa-fax"></i></span>
                                    </div>
                                    <p class="text"><?php echo $this->lang->line('school_fax'); ?>: <?php echo $this->setting->school_fax; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($this->setting->email) && !empty($this->setting->email)){ ?>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="pcia-info-box">
                                    <div class="icon">
                                        <span class="box"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <p class="text"><?php echo $this->lang->line('email'); ?>: <?php echo $this->setting->email; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <?php if(isset($this->setting->address) && !empty($this->setting->address)){ ?>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="pcia-info-box">
                                    <div class="icon">
                                        <span class="box"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <p class="text"><?php echo $this->lang->line('address'); ?>: <?php echo $this->setting->address; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="contact-form">
                    <form action="<?php echo site_url('contact'); ?>" method="post" id="contact_us" name="contact_us" >
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-box">
                                    <div class="icon-box">
                                        <span class="icon"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" name="name" id="name" required="required" placeholder="<?php echo $this->lang->line('name'); ?> *">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-box">
                                    <div class="icon-box">
                                        <span class="icon"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" name="email" id="email" required="required"  placeholder="<?php echo $this->lang->line('email'); ?> *">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-box">
                                    <div class="icon-box">
                                        <span class="icon"><i class="fas fa-phone-volume"></i></span>
                                    </div>
                                    <input  type="text" name="phone" id="phone" placeholder="<?php echo $this->lang->line('phone'); ?>"  minlength="6" maxlength="20">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-box">
                                    <div class="icon-box">
                                        <span class="icon"><i class="far fa-file-alt"></i></span>
                                    </div>
                                    <input type="text" name="subject" id="subject"  placeholder="<?php echo $this->lang->line('subject'); ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-box">
                                    <div class="icon-box">
                                        <span class="icon"><i class="fas fa-pencil-alt"></i></span>
                                    </div>
                                    <textarea name="message" id="message" required="required"  placeholder="<?php echo $this->lang->line('message'); ?> *"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-box submit text-right">
                                    <input type="submit" name="submit" id="submit" value="<?php echo $this->lang->line('submit'); ?>">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(isset($this->setting->google_map) && !empty($this->setting->google_map)){ ?>
<div class="map-area">
    <?php echo $this->setting->google_map; ?>    
</div>
<?php }?>

<script type="text/javascript">
     $('#contact_us').validate();
</script>