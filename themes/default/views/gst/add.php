<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('enter_info'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">

                        <?php echo form_open_multipart("gst/add", 'class="validation"'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
								 
                                    <?= lang('name', 'name'); ?>
                                    <?= form_input('name', set_value('name'), 'class="form-control tip" id="name"  required="required"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang('CGST', 'CGST'); ?>
                                    <?= form_input('cgst', set_value('cgst'), 'class="form-control tip" id="cgst"  required="required"'); ?>
                                </div>
                               <div class="form-group">
                                    <?= lang('SGST', 'SGST'); ?>
                                    <?= form_input('sgst', set_value('sgst'), 'class="form-control tip" id="sgst"  required="required"'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= form_submit('gst_category', lang('Add_GST'), 'class="btn btn-primary"'); ?>
                        </div>

                        <?php echo form_close();?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
