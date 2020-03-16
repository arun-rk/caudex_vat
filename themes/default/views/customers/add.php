<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?= lang('enter_info'); ?></h3>
				</div>
				<div class="box-body">
					<?php echo form_open("customers/add");?>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="code"><?= $this->lang->line("name"); ?></label>
							<?= form_input('name', set_value('name'), 'class="form-control input-sm" id="name"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="code"><?= $this->lang->line("code"); ?></label>
							<?= form_input('cs_code', set_value('cs_code'), 'class="form-control input-sm" id="cs_code"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="email_address"><?= $this->lang->line("email_address"); ?></label>
							<?= form_input('email', set_value('email'), 'class="form-control input-sm" id="email_address"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="phone"><?= $this->lang->line("phone"); ?></label>
							<?= form_input('phone', set_value('phone'), 'class="form-control input-sm" id="phone"');?>
						</div>

						<div class="form-group">
							<label class="control-label" for="vat_no"><?= $this->lang->line("VAT NO"); ?></label>
							<?= form_input('vat_no', set_value('vat_no'), 'class="form-control input-sm" id="vat_no"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="cf1"><?= $this->lang->line("Address"); ?></label>
							<?= form_input('cf1', set_value('cf1'), 'class="form-control input-sm" id="cf1"');?>
						</div>
						<!-- <div class="form-group">
							<label class="control-label" for="cf2"><?= $this->lang->line("ccf2"); ?></label>
							<?= form_input('cf2', set_value('cf2'), 'class="form-control input-sm" id="cf2"');?>
						</div> -->
						<div class="form-group">
							<label class="control-label" for="op_balance"><?= $this->lang->line("Out standing balance"); ?></label>
							<?= form_input('op_balance', set_value('op_balance'), 'class="form-control input-sm" id="op_balance"');?>
						</div>

						


						<div class="form-group">
						<label>Registration Date</label>
						<input type="date" class="form-control" name="reg_date" id="reg_date">
						</div>
						<div class="form-group">
						<label>Credit Limt</label>
						  <input type="number" class="form-control" name="cr_limit" id="cr_limit">
						</div>
						<!-- <div class="form-group">
						<label>Opening Balance</label>
						  <input type="number" class="form-control" name="op_balance" id="op_balance">
						</div> -->


						<div class="form-group">
							<?php echo form_submit('add_customer', $this->lang->line("add_customer"), 'class="btn btn-primary"');?>
						</div>
					</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
$('form').submit(function(){
    $(this.add_customer).attr('disabled', true);
});

</script>