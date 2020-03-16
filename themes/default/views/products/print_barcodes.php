<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<style>
.barcode {
    padding-left: 5mm;
    padding-right: 3mm;
    padding-top: 11mm;
}

.barcodex {
    padding-left: 4px !important;
    padding-right: 4px !important;
    padding-top: 1px !important;
}
#ProductBarcode
{
	width:210mm !important;
	margin-top: 0px !important;
	height:287mm !important;
}
.main {
      width: 100%;
    float: left;
    padding-left: 0mm !important;
    padding-top: 0mm !important;
    padding-right: 0mm !important;
	    border: none;
}
.sub h4 {
    font-size: 12px;
    margin: 0;
 position: relative;
    top: -4px;
}
.sub strong {
   font-size: 9px;
    margin: 0;
    width: 100% !important;
    position: relative;
    top: -8px;
    font-weight: 500;
	 white-space: nowrap; 
 
    overflow: hidden;
    text-overflow: clip;
}
.sub img {
       position: relative;
    top: -6px;
    width: 137px;
		height: 35px;
		
}
.price{
    font-size: 10px;
    margin: 0;
    position: relative;
    top: -4px;
    float: right;
	    margin-right: 16px;
}
.code{
    font-size: 10px;
    margin: 0;
    position: relative;
    top: -4px;
    float: left;
	    margin-left: 16px;
}
.sub {
   /* padding: 4px; */
    float: left;
    width: 40.4mm;
    text-align: center;
		
    height: 20mm;
    /* height: 21.4mm; */
		margin: 0px 18px;
    line-height: 0.9;    
		/* padding-top: 4px; */
}
.modal-body {
   
        padding-left: 0mm !important;
    padding-top: 0mm !important;
    padding-right: 0mm !important;
} 
#ProductBarcode{
    page-break-after: avoid;

}
@media print {
    html, body {
        height: 90%;    
    }
}
 #ProductBarcode {
            border: none;
            page-break-after: always;
        }
</style>

<div id="ProductBarcode"  class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header modal-primary no-print">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<button type="button" class="close mr10" onclick="window.print();"><i class="fa fa-print"></i></button>
			<h4 class="modal-title" id="myModalLabel">
				<?= $page_title; ?>
			</h4>
		</div>
		 
		           
		<div class="modal-body">
			<div class="row" style="margin: 0px !important;">
		 
	 
	 
				<div class="col-md-12" >
				<!-- <div class="filter row" style=" margin: 15px; ">
									<div class="form-group col-md-3 col-xs-12">
											<label for="group">Category</label>                            
											<select name="group" id="category" data-placeholder="Select Category" class="form-control input-tip select2" style="width:100%;">
												<option value="" selected="selected"></option>
												<?=LoadCombo("tec_categories","id","name","name","","")?>
											</select>
									</div>
									<div class="form-group  col-md-3 col-xs-12">
											<label for="group">Supplier</label>                            
											<select name="group" id="supplier" data-placeholder="Select Supplier" class="form-control input-tip select2" style="width:100%;">
												<option value="" selected="selected"></option>
												<?=LoadCombo("tec_suppliers","id","name","name","","")?>
											</select>
									</div>
									<div class="form-group  col-md-3 col-xs-12">
											<label for="group">Vat%</label>                            
											<select name="group" id="vat_p" data-placeholder="Select Vat%" class="form-control input-tip select2" style="width:100%;">
												<option value="" selected="selected"></option>
												<?=LoadCombo("tec_gst_groups","id","name","name","","")?>
											</select>
									</div>
									<div class="form-group  col-md-3 col-xs-12">
                            <button type="button" style=" margin-top: 23px; " id="apply_filter" onclick="applyfilter(this)" value="Filter Products"  class="btn btn-primary" >Filter Products</button>
									</div>
								</div>	 -->
				</div>
				<div class="col-md-12" style="    padding: 0px !important;">
					<div class="barcode">

						<?=$html?>
						<div class="no-print text-center"><?= $links ? $links : ''; ?></div>

					</div>
				</div>

			</div>
		</div>
		<div class="modal-footer no-print">
			<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close'); ?></button>
			<button class="btn btn-primary" href="javascript:void();" onclick="window.print();"><i class="fa fa-print"></i> <?= lang('print'); ?></button>
		</div>
	</div>
</div>
