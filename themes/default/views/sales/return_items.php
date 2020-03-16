<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<style>

@page { 
   margin: 0%
max-width: 96% !important
}
.modal-dialog {
    width: 100%;
    margin: 30px auto;
}
</style>
  <style type="text/css" media="all">
                        body { color: #000; }
                        #wrapper { max-width: 650px; margin: 0 auto; padding-top: 20px; }
                        .btn { margin-bottom: 5px; }
                        .table { border-radius: 3px; }
                        .table th { background: #f5f5f5; }
                        .table th, .table td { vertical-align: middle !important; }
                        h3 { margin: 5px 0; }

                        @media print {
							
p { 
    font-weight: bold;
    font-size: 16px;

}
div {
    font-weight: bold;
    font-size: 16px;

}
.table { 
    font-weight: bold;
    font-size: 16px;
}
							.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th
{
	border: 3px solid #000 !important;
}
.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    border-top: 3px solid #000 !important;
}
.P_Right {
    float: right;
}
p.P_Left {
    float: left;
    width: 50%;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    border: 3px solid #000;
}
.table {
    border-radius: 3px;
    font-size: 13px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    border-top: 3px solid #000 !important ;
}
                            .no-print { display: none; }
                            #wrapper { width: auto;max-width: 96% !important;min-width: 96% !important;margin:2% !important;padding:0%; 
}
#receiptData{ width: auto;
    max-width: 96% !important;
    min-width: 96% !important;
margin:2% !important;padding:0%; }
                        }
                    </style>
              
<script type="text/javascript">
var table;
var sale_id ="<?=$sale_id?>";
var csrf_hash = "<?=$this->security->get_csrf_hash()?>";
    $(document).ready(function() {
				table =$('#product_return').DataTable({
								'ajax' : { url: '<?=site_url('sales/get_return_product');?>', type: 'POST', "data": function ( d ) {
										d.<?=$this->security->get_csrf_token_name();?> = csrf_hash;
										d.sale_id = sale_id;
								// },
								// complete: function(response) {
								// 	var data  = JSON.parse(response.responseText).data;
						
								// 	$.ajaxSetup({
								// 				data: CsrfSecret
								// 		});
								}},
								'dom': 't',
								"buttons": [],
								"columns": [
								{ "data": "name"},
								{ "data": "price"},
								{ "data": "quantity" },
								{ "data": "item_discount"  },
								// { "data": "name"},
								// { "data": "stock" },
								{ "data": "tax" },
								{ "data": "subtotal" },
								{ "data": "Actions", "searchable": false, "orderable": false },
								// { "data": "subtotal", "searchable": false, "orderable": false }
								]
				});
    });
</script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('list_results'); ?></h3>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
										<table id="product_return" class="table table-striped table-bordered table-condensed table-hover">
													<thead>
																	<tr class="active">
																			<th class="col-xs-1"><?= lang("Product"); ?></th>
																			<th class="col-xs-1"><?= lang("Price"); ?></th>
																			<th class="col-xs-1"><?= lang("Qty"); ?></th>
																			<th class="col-xs-1"><?= lang("DIS"); ?></th>
																			<th class="col-xs-1"><?= lang("Tax"); ?></th>
																			<th class="col-xs-1"><?= lang("STOTAL"); ?></th>
																			<th class="col-xs-1"><?= lang("R-QTY"); ?></th>
																			<!-- <th class="col-xs-1"><?= lang("R-TOTAL"); ?></th> -->
																	</tr>
														</thead>
														<tbody>
																	<tr>
																		<td colspan="7" style=" text-align: center; " class="dataTables_empty"><?= lang('no_data'); ?></td>
																</tr>
														</tbody>
														<tfoot>
															<tr class="active">
																	<th class="col-sm-1"><?= lang("Product"); ?></th>
																	<th class="col-sm-1"><?= lang("Price"); ?></th>
																	<th class="col-sm-1"><?= lang("Qty"); ?></th>   
																	<th class="col-sm-2"><?= lang("DIS"); ?></th>
																	<th class="col-sm-1"><?= lang("Tax"); ?></th>
																	<th class="col-sm-1"><?= lang("STOTAL"); ?></th>
																	<th class="col-xs-1"><?= lang("R-QTY"); ?></th>
																			<!-- <th class="col-xs-1"><?= lang("R-TOTAL"); ?></th> -->
															</tr>
														</tfoot>
											</table>
                </div>


                <?php   
                //echo $name= cryptography("TGhOTlh0SHEvc3lPcjZpanh3MjdRQT09");
                ?>

                <div class="clearfix"></div>
            </div>
						<div class="box-footer">
						<input type="submit" class="btn btn-primary pull-right" name="return_items" onclick="returnitems()" value="Return Items">
						</div>
        </div>
    </div>
</div>
</section>
<?php if ($Admin) { ?>
<div class="modal fade" id="stModal" tabindex="-1" role="dialog" aria-labelledby="stModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
                <h4 class="modal-title" id="stModalLabel"><?= lang('update_status'); ?> <span id="status-id"></span></h4>
            </div>
            <?= form_open('sales/status'); ?>
            <div class="modal-body">
                <input type="hidden" value="" id="sale_id" name="sale_id" />
                <div class="form-group">
                    <?= lang('status', 'status'); ?>
                    <?php $opts = array('paid' => lang('paid'), 'partial' => lang('partial'), 'due' => lang('due'))  ?>
                    <?= form_dropdown('status', $opts, set_value('status'), 'class="form-control select2 tip" id="status" required="required" style="width:100%;"'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?= lang('update'); ?></button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    // $(document).ready(function() {
    //     $(document).on('click', '.sale_status', function() {
			 
    //         var sale_id = $(this).closest('tr').children('td:first').text();
    //         var curr_status = $(this).text();
    //         var status = curr_status.toLowerCase();
    //         $('#status-id').text('( <?= lang('sale_id'); ?> '+sale_id+' )');
    //         $('#sale_id').val(sale_id);
    //         $('#status').val(status);
    //         $('#status').select2('val', status);
    //         $('#stModal').modal()
    //     });
    // });
</script>
<?php } ?>
<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/moment.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datetimepicker({format: 'YYYY-MM-DD', showClear: true, showClose: true, useCurrent: false, widgetPositioning: {horizontal: 'auto', vertical: 'bottom'}, widgetParent: $('.dataTable tfoot')});
    });
</script>
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
</style>
<style>
div.dataTables_wrapper div.dataTables_paginate {
    text-align: center;
}
</style>
<script>

function returnitems() { 
	// sale_id
	var returned_items={
		<?=$this->security->get_csrf_token_name();?> : "<?=$this->security->get_csrf_hash()?>",
		sale_id:sale_id,
		items:[]
	};
	if(confirm('Are you sure ?')){
		$.each($('input.rtn_qty'), function (index, value) { 
			console.log(value.value);	

			if(value.value > 0){
				var value2 = value.parentNode.parentNode;
				data = table.row( value2 ).data();
				data.return_qty = value.value;
				returned_items.items.push(data);
			}
		});
		$.ajax({
			type: "POST",
			url: "<?=base_url('sales/return_item_save')?>",
			data: returned_items,
			dataType: "json",
			success: function (response) {
				// alert(response);
				if(Number(response)){
				window.location.href = "<?=base_url('sales/return_print/')?>"+response;
				}
				else{
					alert('Oops !!, Somthing is wrong .Please try again later.');
				}
			}
		});
	}
	// get all items and sales id 
	// insert those into sale return table with old sales id
 }

//  $(function () { 
// 	// rtn_qty
// 	$('#product_return').on('scroll change','.rtn_qty',function(){
// 		alert();
// 	});
// 	$('#product_return .rtn_qty').onscroll(function(){
// 		alert();
// 	});
//   });
 
</script>