<?php $arr = array($this->lang->line('sale_to').'/ '.$this->lang->line('student_copy'), $this->lang->line('school_copy'), $this->lang->line('bank_copy')); ?>   
<?php $count = 1; $total_count = count($arr);  foreach($arr as $key=>$value){ ?>   
   <section class="content invoice profile_img layout-box">
       <!-- title row -->
      <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 invoice-header_ text-center" style="text-align: center !important;">
               <?php if($this->gsms_setting->logo){ ?>
                  <img src="<?php echo UPLOAD_PATH; ?>logo/<?php echo $this->gsms_setting->logo; ?>" alt="" width="100" /> 
               <?php }else if($this->gsms_setting->front_logo){ ?>
                  <img src="<?php echo UPLOAD_PATH; ?>logo/<?php echo $this->gsms_setting->front_logo; ?>" alt="" width="100"/> 
               <?php }else{ ?>                                                        
                  <img src="<?php echo UPLOAD_PATH; ?>logo/<?php echo $this->gsms_setting->brand_logo; ?>" alt="" width="100"/>
               <?php } ?> 
          </div>
      </div>
      <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 invoice-header_" style="text-align: center !important;">
              <address>
                  <h3><?php echo $this->gsms_setting->school_name; ?></h3>
                   <?php echo $this->gsms_setting->address; ?>
                  <br><?php echo $this->lang->line('phone'); ?>: <?php echo $this->gsms_setting->phone; ?>,
                  <?php echo $this->lang->line('email'); ?>: <?php echo $this->gsms_setting->email; ?>
              </address>
          </div>            
      </div>

      <!-- info row -->
       <div class="row invoice-info">          
          <div class="col-md-6 col-sm-6 col-xs-6 invoice-col_ text-center"> 
            <address>
                <strong><?php echo $this->lang->line('student'); ?>/<?php echo $this->lang->line('sale_to'); ?>:</strong><br>              
               <?php
                    $user = get_user_by_role($receipt->role_id, $receipt->user_id);
                
                if(!empty($user)){    
                ?>
                    <strong><?php echo $this->lang->line('sale_to'); ?>:</strong> <?php echo  $user->name; ?> [<?php echo  $user->role; ?>]<br>                
                    <?php
                    if($receipt->role_id == STUDENT){
                        echo '<strong>'.$this->lang->line('class').':</strong> '.$user->class_name.', <strong>'. $this->lang->line('section').':</strong> '.$user->section.', <strong>'. $this->lang->line('roll_no'). ':</strong>'. $user->roll_no . ']<br>';
                    }
                    ?>
                    <strong><?php echo $this->lang->line('phone'); ?>:</strong> <?php echo $user->phone; ?> 
                
                <?php } ?>
              </address>
          </div>
          <!-- /.col -->
          <div class="col-md-6 col-sm-6 col-xs-6 invoice-col">
            <address>
                <strong><?php echo $this->lang->line('invoice'); ?>: </strong> #<?php echo $receipt->custom_invoice_id; ?>                                                     
                <br><strong><?php echo $this->lang->line('status'); ?>:</strong> <?php echo get_paid_status($receipt->paid_status); ?>
                <br><strong><?php echo $this->lang->line('date'); ?>:</strong> <?php echo date($this->gsms_setting->sms_date_format, strtotime($receipt->date)); ?>
            </address>
          </div>
          <!-- /.col -->
      </div>   
      <div class="row">
           <div class="col-md-12 col-sm-12 col-xs-12 invoice-header_" style="text-align: center !important;">
               <address>
                   <h4><?php echo $value; ?></h4>                    
               </address>
           </div>            
       </div>
   </section>    
   <section class="content invoice"> 
       <!-- Table row -->
       <div class="row">
           <div class="col-xs-12 table">
               
                <?php if($receipt->invoice_type == 'sale'){ ?>
                   <table class="table table-striped">
                   <thead>
                        <tr>
                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                            <th><?php echo $this->lang->line('category'); ?></th>
                            <th><?php echo $this->lang->line('product'); ?></th>
                            <th><?php echo $this->lang->line('quantity'); ?></th>
                            <th><?php echo $this->lang->line('unit_price'); ?></th>
                            <th><?php echo $this->lang->line('subtotal'); ?></th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php if(isset($receipt_items) && !empty($receipt_items)){ ?>
                            <?php $counter = 1; foreach($receipt_items as $item){ ?>
                            <tr>
                                <td  style="width:10%"><?php echo $counter++; ?></td>
                                <td> <?php echo $item->title; ?></td>
                                <td> <?php echo $item->category; ?></td>
                                <td> <?php echo $item->product; ?></td>
                                <td> <?php echo $item->qty; ?></td>
                                <td> <?php echo $item->unit_price; ?></td>
                                <td> <?php echo $this->gsms_setting->currency_symbol; ?><?php echo $item->net_amount; ?></td>
                            </tr> 
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                    </table>
                   <?php }else{ ?>                   
               
                  <table class="table table-striped"> 
                   <thead>
                       <tr>
                           <th><?php echo $this->lang->line('sl_no'); ?></th>
                           <th><?php echo $this->lang->line('fee_type'); ?></th>
                           <th><?php echo $this->lang->line('gross_amount'); ?></th>
                           <th><?php echo $this->lang->line('discount'); ?></th>
                           <th><?php echo $this->lang->line('net_amount'); ?></th>
                       </tr>
                   </thead>
                   <tbody> 
                       <?php if(isset($receipt_items) && !empty($receipt_items)){ ?>
                           <?php $counter = 1; foreach($receipt_items as $item){ ?>
                           <tr>
                               <td  style="width:10%"><?php echo $counter++; ?></td>
                               <td  style="width:25%"> <?php echo $item->title; ?></td>
                               <td> <?php echo $this->gsms_setting->currency_symbol; ?><?php echo $item->gross_amount; ?></td>
                               <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo round($item->discount,2); ?></td>
                               <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo round($item->net_amount, 2); ?></td>
                           </tr> 
                           <?php } ?>
                       <?php } ?>
                   </tbody>
                  </table>
                   <?php } ?>
           </div>
           <!-- /.col -->
       </div>

       <div class="row">
           <!-- accepted payments column -->
           <div class="col-xs-6">
               <p class="lead"><?php echo $this->lang->line('payment_method'); ?>:</p>
               <img src="<?php echo IMG_URL; ?>visa.png" alt="Visa">
               <img src="<?php echo IMG_URL; ?>mastercard.png" alt="Mastercard">
               <img src="<?php echo IMG_URL; ?>american-express.png" alt="American Express">
               <img src="<?php echo IMG_URL; ?>paypal.png" alt="Paypal">                       
           </div>
           <!-- /.col -->
           <div class="col-xs-6">
               <div class="table-responsive">
                   <table class="table">
                       <tbody>
                           <tr>
                               <th style="width:56%"><?php echo $this->lang->line('subtotal'); ?>:</th>
                               <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo number_format($receipt->gross_amount,2); ?></td>
                           </tr>
                           <tr>
                               <th><?php echo $this->lang->line('discount'); ?></th>
                               <td><?php echo $this->gsms_setting->currency_symbol; ?><?php  echo number_format($receipt->discount,2); ?></td>
                           </tr>
                           <tr>
                               <th><?php echo $this->lang->line('total'); ?>:</th>
                               <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo number_format($receipt->net_amount,2); ?></td>
                           </tr>
                           <tr>
                               <th><?php echo $this->lang->line('paid_amount'); ?>:</th>
                               <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo $paid_amount ? number_format($paid_amount,2) : 0.00; ?></td>
                           </tr>
                           <tr>
                               <th><?php echo $this->lang->line('due_amount'); ?>:</th>
                               <td><span class="btn-danger" style="padding: 5px;border-radius: 4px;"><?php echo $this->gsms_setting->currency_symbol; ?><?php echo number_format($receipt->net_amount-$paid_amount,2); ?></span></td>
                           </tr>                       
                       </tbody>
                   </table>
               </div>
           </div>
           <!-- /.col -->
       </div>
       <!-- /.row -->

        <div class="row">
            <div class="col-xs-12"></div>
        </div>
       <!-- /.row -->
       <div class="row">       
           <p class="white text-center">
                <?php if(isset($this->gsms_setting->footer) && $this->gsms_setting->footer != ''){ ?>
                   <?php echo $this->gsms_setting->footer; ?>
                <?php }else{ ?>
                   <?php echo 'Copyright © '. date('Y').' <a target="_blank" href="https://codecanyon.net/user/codetroopers">Codetroopers Team.</a> All rights reserved.'; ?> 
                <?php } ?>
           </p> 
       </div>
   </section>  
                                              
    <?php if($count < $total_count){ ?>
       <hr/><div class="pagebreak">&nbsp;</div> 
    <?php } ?>
 <?php $count++; } ?>                                               


<div class="row no-print">
    <div class="col-xs-12 text-right">
        <button class="btn btn-default " onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
    </div>
</div>
