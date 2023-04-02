<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-calculator"></i><small> <?php echo $this->lang->line('print_multi_invoice'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content quick-link no-print">
                <?php $this->load->view('quick-link'); ?>              
            </div>
            
            <div class="x_content">
                
                <div class="x_content filter-box no-print"> 
                <?php echo form_open_multipart(site_url('accounting/invoice/multi'), array('name' => 'multi', 'id' => 'multi', 'class' => 'form-horizontal form-label-left'), ''); ?>
                <div class="row">  
                       
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="item form-group"> 
                                <div><?php echo $this->lang->line('class'); ?> </div>
                                <select  class="form-control col-md-7 col-xs-12 " name="class_id" id="class_id">
                                    <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                    <?php foreach ($classes as $obj) { ?>
                                    <option value="<?php echo $obj->id; ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="item form-group"> 
                                <div><?php echo $this->lang->line('month'); ?> <span class="required">*</span></div>
                                <input  class="form-control col-md-7 col-xs-12"  name="month"  id="month" value="<?php echo isset($month) ?  $month : ''; ?>" placeholder="<?php echo $this->lang->line('month'); ?>" required="required" type="text" autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="form-group"><br/>
                            <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('find'); ?></button>
                        </div>
                    </div>
                                        
                </div>
                <?php echo form_close(); ?>
            </div>

                
                <?php if(isset($invoices) && !empty($invoices)){ ?>
                <?php foreach($invoices AS $obj){ ?> 
                
                    <section class="content invoice profile_img text-left">                    
                         <!-- title row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-header  invoice-col">
                                <h1><?php echo $this->lang->line('invoice'); ?></h1>
                            </div>
                            <div class="col-sm-4 invoice-header  invoice-col">&nbsp;</div>
                            <div class="col-sm-4 invoice-header  invoice-col">
                                <img src="<?php echo UPLOAD_PATH; ?>/logo/<?php echo $this->gsms_setting->logo; ?>" alt="" width="70" />
                            </div>
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-4 col-md-4 invoice-col">
                                <strong><?php echo $this->lang->line('school'); ?>:</strong>
                                <address>
                                    <?php echo $this->gsms_setting->school_name; ?>
                                    <br><?php echo $this->gsms_setting->address; ?>
                                    <br><?php echo $this->lang->line('phone'); ?>: <?php echo $this->gsms_setting->phone; ?>
                                    <br><?php echo $this->lang->line('email'); ?>: <?php echo $this->gsms_setting->email; ?>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 col-md-4 invoice-col">
                                <strong><?php echo $this->lang->line('student'); ?>/ <?php echo $this->lang->line('user'); ?>:</strong>
                                <address>
                                    <?php  if($obj->invoice_type == 'sale'){ ?>
                                        <?php
                                            $user = get_user_by_role($obj->role_id, $obj->user_id);
                                         ?>
                                        <strong><?php echo $this->lang->line('sale_to_user'); ?>:</strong> <?php echo  $user->name; ?> [<?php echo  $user->role; ?>]<br>                
                                        <?php
                                        if($user->role_id == STUDENT){
                                                echo '<strong>'.$this->lang->line('class').':</strong> '.$user->class_name.', <strong>'. $this->lang->line('section').':</strong> '.$user->section.', <strong>'. $this->lang->line('roll_no'). ':</strong>'. $user->roll_no . ']';
                                            }
                                        ?>
                                        <br><strong><?php echo $this->lang->line('phone'); ?>:</strong> <?php echo $user->phone; ?> 
                                    <?php }else{ ?>
                                        <?php echo $obj->student_name; ?> [<?php echo $this->lang->line('student'); ?>]
                                        <br><?php echo $obj->present_address; ?>
                                        <br><?php echo $this->lang->line('class'); ?>: <?php echo $obj->class_name; ?>
                                        <br><?php echo $this->lang->line('phone'); ?>: <?php echo $obj->phone; ?>
                                    <?php } ?>    
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 col-md-4 invoice-col">
                                <b><?php echo $this->lang->line('invoice'); ?> #<?php echo $obj->custom_invoice_id; ?></b>                                                     
                                <br>
                                <b><?php echo $this->lang->line('payment_status'); ?>:</b> <span class="btn-success"><?php echo get_paid_status($obj->paid_status); ?></span>
                                <br>
                                <b><?php echo $this->lang->line('date'); ?>:</b> <?php echo date($this->gsms_setting->sms_date_format, strtotime($obj->date)); ?>
                            </div>
                            <!-- /.col -->
                        </div>                       
                </section>
                <section class="content invoice">
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table">
                            <table class="table table-striped">
                                <thead>
                                    <?php  if($obj->invoice_type == 'sale'){ ?>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                                            <th><?php echo $this->lang->line('category'); ?></th>
                                            <th><?php echo $this->lang->line('product'); ?></th>
                                            <th><?php echo $this->lang->line('quantity'); ?></th>
                                            <th><?php echo $this->lang->line('unit_price'); ?></th>
                                            <th><?php echo $this->lang->line('subtotal'); ?></th>
                                        </tr>
                                    <?php }else{ ?>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl_no'); ?></th>
                                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                                            <th><?php echo $this->lang->line('gross_amount'); ?></th>
                                            <th><?php echo $this->lang->line('discount'); ?></th>
                                            <th><?php echo $this->lang->line('net_amount'); ?></th>
                                        </tr>
                                    <?php } ?>
                                </thead>
                                <tbody> 
                                    <?php $invoice_items =  get_invoice_items($obj->inv_id, $obj->invoice_type); ?>
                                    <?php if(isset($invoice_items) && !empty($invoice_items)){ ?>
                                        <?php $counter = 1; foreach($invoice_items as $item){ ?>
                                            <?php  if($obj->invoice_type == 'sale'){ ?>
                                                <tr>
                                                    <td  style="width:10%"><?php echo $counter++; ?></td>
                                                    <td> <?php echo $item->title; ?></td>
                                                    <td> <?php echo $item->category; ?></td>
                                                    <td> <?php echo $item->product; ?></td>
                                                    <td> <?php echo $item->qty; ?></td>
                                                    <td> <?php echo $item->unit_price; ?></td>
                                                    <td> <?php echo $this->gsms_setting->currency_symbol; ?><?php echo $item->net_amount; ?></td>
                                                </tr>
                                            <?php }else{ ?>
                                                <tr>
                                                    <td  style="width:10%"><?php echo $counter++; ?></td>
                                                    <td  style="width:25%"> <?php echo $item->title; ?></td>
                                                    <td> <?php echo $this->gsms_setting->currency_symbol; ?><?php echo $item->gross_amount; ?></td>
                                                    <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo round($item->discount,2); ?></td>
                                                    <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo round($item->net_amount, 2); ?></td>
                                                </tr>                                             
                                             <?php } ?>
                                            
                                        <?php } ?>
                                    <?php } ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                            <!--<p class="lead"><?php echo $this->lang->line('method'); ?>:</p>-->
                            <img src="<?php echo IMG_URL; ?>visa.png" alt="Visa">
                            <img src="<?php echo IMG_URL; ?>mastercard.png" alt="Mastercard">
                            <img src="<?php echo IMG_URL; ?>american-express.png" alt="American Express">
                            <img src="<?php echo IMG_URL; ?>paypal.png" alt="Paypal">                         
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                            <?php $paid_amount =  get_invoice_paid_amount($obj->inv_id); ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width:50%"><?php echo $this->lang->line('subtotal'); ?>:</th>
                                            <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo $obj->gross_amount; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('discount'); ?></th>
                                            <td><?php echo $this->gsms_setting->currency_symbol; ?><?php  echo $obj->inv_discount; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('total'); ?>:</th>
                                            <td><?php echo $this->gsms_setting->currency_symbol; ?><?php echo $obj->net_amount; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('paid_amount'); ?>:</th>
                                            <td><?php echo $this->gsms_setting->currency_symbol; ?><?php $pamount = isset($paid_amount) ? $paid_amount->paid_amount : 0.00; echo $pamount; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('due_amount'); ?>:</th>
                                            <td><span class="btn-danger" style="padding: 5px;"><?php echo $this->gsms_setting->currency_symbol; ?><?php echo $obj->net_amount-$pamount; ?></span></td>
                                        </tr>
                                        <?php if($obj->paid_status == 'paid'){ ?>
                                            <tr>
                                                <th><?php echo $this->lang->line('date'); ?>:</th>
                                                <td><?php echo date($this->gsms_setting->sms_date_format, strtotime($obj->date)); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </section>
                <section>
                    <hr>
                    <div class="pagebreak">&nbsp;</div>
                </section>
                <?php } ?>
                <section>                    
                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                        </div>
                    </div>
                </section>
                
                <?php } ?>
                
            </div>
        </div>
    </div>
</div>

  <!-- bootstrap-datetimepicker -->
 <link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 
 <script type="text/javascript">
      $("#month").datepicker( {
            format: "mm-yyyy",
            startView: "months", 
            minViewMode: "months"
        });
    $("#multi").validate();  
</script>
