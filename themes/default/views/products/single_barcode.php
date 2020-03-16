<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<style>
.barcode {
    padding-left: 5mm;
    padding-right: 3mm;
    padding-top: 11mm;
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
    height: 38px;;
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
   padding: 4px;
    float: left;
    width: 40.4mm;
    text-align: center;
    height: 21.4mm;
   
    line-height: 0.9;
    padding-top: 7px;
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

<div id="ProductBarcode" class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header modal-primary no-print">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<button type="button" class="close mr10" onclick="window.print();"><i class="fa fa-print"></i></button>
			<h4 class="modal-title" id="myModalLabel">
				<?= $page_title; ?>
			</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
				 <div class="row no-print" style="margin-left: 20px;margin-top: 10px;">
                            <div class="col-md-4">

                             <div class="form-group">
                                    <?= lang('No of Barcode', 'No of Barcode'); ?>
                                    <?= form_input('barcount','', 'class="form-control tip" id="barcount"'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
							  <div class="form-group">
							   <?= lang(''); ?>
							<a style="    margin-top: 25px;" onclick="Barcode(<?= $product_id; ?>);"  id="Sub" title='".<?=lang('print_barcodes') ?>."'   class='btn btn-primary'  >Submit</a>
                                </div>
                            </div>
					</div>
					<div class="barcode" id="barcodedata">

						<?=$html?>

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
<script src="<?= $assets ?>dist/js/purchases.min.js" type="text/javascript"></script>
<script type="text/javascript">

function Barcode(ProductId){
	  
        $.ajax({
            type: "get",
            url: base_url+'products/single_barcode_ajax?product_id='+ProductId+'&count='+ $("#barcount").val(),
          
             dataType: "html",
            success: function (data) {
				 
                $("#barcodedata").empty();
				    $("#barcodedata").append(data);
				
            }
        });
	}
	
	
var Count='';
    $(function(){
       $("#count").on('change', function(){
       Count=   $(this).val().trim();
	   if(Count.trim()!=''){
		   $("#Sub").attr('href','products/single_barcode/<?= $product_id; ?>/'+Count);
	   }
       }); 
	   
	 
    });
	  
</script>