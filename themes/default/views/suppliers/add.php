<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?= lang('enter_info'); ?></h3>
				</div>
				<div class="box-body">
					<?php echo form_open("suppliers/add");?>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="code"><?= $this->lang->line("name"); ?></label>
							<?= form_input('name', set_value('name'), 'class="form-control input-sm" id="name"'); ?>
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
							<label class="control-label" for="cf1"><?= $this->lang->line("scf1"); ?></label>
							<?= form_input('cf1', set_value('cf1'), 'class="form-control input-sm" id="cf1"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="cf2"><?= $this->lang->line("scf2"); ?></label>
							<?= form_input('cf2', set_value('cf2'), 'class="form-control input-sm" id="cf2"');?>
						</div>
						<div class="form-group">
							<label class="control-label" for="gstin"><?= $this->lang->line("VATIN"); ?></label>
							<?= form_input('gstin', set_value('gstin'), 'class="form-control input-sm" id="gstin"');?>
						</div>
						<div class="form-group">
							<label class="control-label" for="sup_name"><?= $this->lang->line("Supplier Name"); ?></label>
							<?= form_input('sup_name', set_value('sup_name'), 'class="form-control input-sm" id="sup_name"');?>
						</div>
						<div class="form-group">
							<label class="control-label" for="code"><?= $this->lang->line("Supplier Code"); ?></label>
							<?= form_input('code', set_value('code'), 'class="form-control input-sm" id="code"');?>
						</div>
						<div class="form-group">
							<label class="control-label" for="vat"><?= $this->lang->line("VAT No"); ?></label>
							<?= form_input('vat', set_value('vat'), 'class="form-control input-sm" id="vat"');?>
						</div>
						<div class="form-group">
						<label class="control-label" for="country"><?= $this->lang->line("Country"); ?></label>
						<select class="form-control" name="country" id="country" onchange="filter_datatable(this.value)">
								<option value="0">Select Country</option>
								<?php LoadCombo("tec_country","country_id","Country_name",""," ","");  ?>
							</select>
						</div>
						<div class="form-group">
						<label>Registration Date</label>
						<input type="date" class="form-control" name="date" id="date">
						</div>

						<div class="form-group">
							<?php echo form_submit('add_supplier', $this->lang->line("add_supplier"), 'class="btn btn-primary"');?>
						</div>
					</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</section>
