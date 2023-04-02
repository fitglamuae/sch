<footer>
    <div class="pull-right">
        <?php if(isset($this->gsms_setting->footer) && !empty($this->gsms_setting->footer)){ ?>                            
            <?php echo $this->gsms_setting->footer; ?>                
        <?php }else{ ?>  
            ©<?php date('Y'); ?> Global - Single School Management System Pro | Developed by <a class="blue" target="_blank" href="https://codecanyon.net/user/codetroopers">Codetroopers</a>
        <?php } ?>
    </div>
    <div class="clearfix"></div>
</footer>