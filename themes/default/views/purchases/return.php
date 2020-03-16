<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header row">
                    <h3 class="box-title col-md-10"><?= lang('list_results'); ?></h3>
                    <div class="col-md-2" ><button onclick="returnpurchases(this,<?=$purchase_id?>)" class=" btn btn-primary">
										<i class='fa fa-reply'></i> Return 
										</button></div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="expData" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr class="active">
                                    <th style="max-width:30px;"><?= lang("id"); ?></th>
                                    <th class="col-xs-2"><?= lang("product_name"); ?></th>
                                    <th class="col-xs-2"><?= lang("code"); ?></th>
                                    <th class="col-xs-1"><?= lang("Price"); ?></th>
                                    <th class="col-xs-1"><?= lang("Gst"); ?></th>
                                    <th class="col-xs-2"><?= lang("Expity_date"); ?></th>
                                    <th class="col-xs-1"><?= lang("Qty"); ?></th>
                                    <th class="col-xs-1"><?= lang("Retuned"); ?></th>
                                    <th class="col-xs-4"><?= lang("actions"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
																		<?php if($purchase_items) : ?>
																		<?php $i = 1; ?>
																		<?php foreach( $purchase_items as $purchase_item) : ?>
                                		<tr>
																			<td> <?=$i?> </td>
																			<td> <?=$purchase_item->product_name?> </td>
																			<td> <?=$purchase_item->product_code?> </td>
																			<td> <?=$purchase_item->product_price?> </td>
																			<td> <?=$purchase_item->cgst_Tax+$purchase_item->sgst_Tax?> </td>
																			<td> <?=$purchase_item->expiry_date?> </td>
																			<td> <?=$purchase_item->quantity?> </td>
																			<td> <?=$purchase_item->returned?> </td>
																			<td> <input class="rtn_qty" type="number" value="0" min="0" max="<?=$purchase_item->quantity-$purchase_item->returned?>" data-id="<?=$purchase_item->id?>" onkeydown="return false" onscroll="return false"></td>
                                		</tr>
																		<?php $i++; ?>
																		<?php endforeach ?>
																		<?php else: ?>
                                <tr>
                                    <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                </tr>
																		<?php endif ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
table th, table td{
  white-space: nowrap !important;
}
.rtn_qty{
    width: 60px;
    padding: 0px 7px;
    border: none;
    border-bottom: 2px solid #888888;
}
.rtn_qty:focus{
    border-bottom: 2px solid #3c8dbc;
}
div.dataTables_wrapper div.dataTables_paginate {
    text-align: center;
}
</style>

<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/moment.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">

function returnpurchases(ele,purchase_id) { 
	// sale_id
	var returned_purchases={
		<?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash()?>",
		purchase_id:purchase_id,
		items:[]
	};
	if(confirm('Are you sure ?')){
		$.each($('input.rtn_qty'), function (index, value) { 
			console.log(value.value);	
			if(value.value > 0){
				var data={returned:0,id:0};
				var value2 =$(value).data('id');
				data.returned = value.value;
				data.id = value2;
				returned_purchases.items.push(data);
			}
		});
	}
	console.log(returned_purchases);
	
		$.ajax({
			type: "POST",
			url: "<?=site_url('purchases/returnp')?>",
			data: returned_purchases,
			dataType: "json",
			success: function (response) {
				// alert(response);
				if(Number(response.code)==1){
				window.location.href = response.data;
				}
				else{
					alert('Oops !!, Somthing is wrong .Please try again later.');
				}
			}
		});
	}
	// var table;
	// $(document).ready(function() {
	// 	table = $('#expData').DataTable();
	// });
</script>
