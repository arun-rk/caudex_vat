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
var customer,statusx;
 function applyfilter(apply_filter) {
	 $(apply_filter).attr('disabled',true);
	 $(apply_filter).text('Filtering...');
	 statusx = $('#status').val();
	 customer = $('#customer').val();
	 table.ajax.reload(function () {
			$(apply_filter).attr('disabled',false);
			$(apply_filter).text('Filter Sales');
	   });
 }
    $(document).ready(function() {

        function status(x) {
            var paid = '<?= lang('paid'); ?>';
            var partial = '<?= lang('partial'); ?>';
            var due = '<?= lang('due'); ?>';
            if (x == 'paid') {
                return '<div class="text-center"><span class="sale_status label label-success">'+paid+'</span></div>';
            } else if (x == 'partial') {
                return '<div class="text-center"><span class="sale_status label label-primary">'+partial+'</span></div>';
            } else if (x == 'due') {
                return '<div class="text-center"><span class="sale_status label label-danger">'+due+'</span></div>';
            } else {
                return '<div class="text-center"><span class="sale_status label label-default">'+x+'</span></div>';
            }
        }

        table = $('#SLData').DataTable({

            'ajax' : { url: '<?=site_url('sales/get_sales');?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
                d.customer = customer;
                d.statusx = statusx;
            }},
            "buttons": [
            { extend: 'copyHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 5, 6, 7, 8, 9 ] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 5, 6, 7, 8, 9 ] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 5, 6, 7, 8, 9 ] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': true,
            exportOptions: { columns: [ 0, 1, 2, 3,  5, 6, 7, 8, 9 ] } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id"},
            { "data": "date", "render": hrld },
            { "data": "customer_name" },
            { "data": "total", "render": currencyFormat },
            { "data": "total_tax", "visible": false , "render": currencyFormat },
            { "data": "total_discount", "render": currencyFormat },
            { "data": "total_tax", "render": currencyFormat },
            { "data": "grand_total", "render": currencyFormat },
            { "data": "paid", "render": currencyFormat },
            { "data": "status", "render": status },
            { "data": "Actions", "searchable": false, "orderable": false,'render':function (data, type, row) {
							// console.log(row);
							// if(!row.sale_id){
							data = `<?="<div class='text-center' style='width: 147px;'><div class='btn-group'><a href='" . site_url('pos/view/`+data+`/1') . "' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-list'></i></a> <a href='".site_url('sales/payments/`+data+`')."' title='" . lang("view_payments") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-money'></i></a> <a href='".site_url('sales/add_payment/`+data+`')."' title='" . lang("add_payment") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-briefcase'></i></a> <a data-href='" . site_url('sales/returns/`+data+`') . "' onClick=\"returnsale('". lang('You are going to return sale, please click ok to return.') ."',this)\" title='".lang("Returns")."' class='tip btn btn-primary  btn-xs'><i class='fa fa-reply'></i></a><a href='" . site_url('pos/?edit=`+data+`') . "' title='".lang("edit_invoice")."' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('sales/delete/`+data+`') . "' onClick=\"return confirm('". lang('alert_x_sale') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>" ?>`;
							// }
							// else{
							// data = `<?="<div class='text-center' style='width: 147px;'><div class='btn-group'><a href='" . site_url('pos/view/`+data+`/1') . "' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-list'></i></a> <a href='".site_url('sales/payments/`+data+`')."' title='" . lang("view_payments") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-money'></i></a> <a href='".site_url('sales/add_payment/`+data+`')."' title='" . lang("add_payment") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-briefcase'></i></a> <a href='" . site_url('pos/?edit=`+data+`') . "' title='".lang("edit_invoice")."' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('sales/delete/`+data+`') . "' onClick=\"return confirm('". lang('alert_x_sale') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>" ?>`;
							// }
							return data;
						} }
            ],
            "footerCallback": function (  tfoot, data, start, end, display ) {
                var api = this.api(), data;
                $(api.column(3).footer()).html( cf(api.column(3).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(4).footer()).html( cf(api.column(4).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(5).footer()).html( cf(api.column(5).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(6).footer()).html( cf(api.column(6).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(7).footer()).html( cf(api.column(7).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                $(api.column(8).footer()).html( cf(api.column(8).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
                // $(api.column(9).footer()).html('asdsadsadsa' );
                // $(api.column(10).footer()).html('asdsadsadsa' );
            }

        });

        $('#search_table').on( 'keyup change', function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
                table.search( this.value ).draw();
            }
        });

        table.columns().every(function () {
            var self = this;
            $( 'input.datepicker', this.footer() ).on('dp.change', function (e) {
                self.search( this.value ).draw();
            });
            $( 'input:not(.datepicker)', this.footer() ).on('keyup change', function (e) {
                var code = (e.keyCode ? e.keyCode : e.which);
                if (((code == 13 && self.search() !== this.value) || (self.search() !== '' && this.value === ''))) {
                    self.search( this.value ).draw();
                }
            });
            $( 'select', this.footer() ).on( 'change', function (e) {
                self.search( this.value ).draw();
            });
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
								<div class="filter row" style=" margin: 15px; ">
									<div class="form-group col-md-4 col-xs-12">
											<label for="group">Customer</label>                            
											<select name="group" id="customer" data-placeholder="Select Customer" class="form-control input-tip select2" style="width:100%;">
												<option value="" selected="selected"></option>
												<?=LoadCombo("tec_customers","id","name","name","","")?>
											</select>
									</div>
									<div class="form-group  col-md-4 col-xs-12">
											<label for="group">Status</label>                            
											<select name="group" id="status" data-placeholder="Select Status" class="form-control input-tip select2" style="width:100%;">
												<option value=" " selected="selected">All</option>
												<option value="paid">Paid</option>
												<option value="partial">Partial</option>
												<option value="due">Due</option>
											</select>
									</div>
									<div class="form-group  col-md-4 col-xs-12">
                            <button type="button" style=" margin-top: 23px; " id="apply_filter" onclick="applyfilter(this)" value="Filter Products"  class="btn btn-primary" >Filter Products</button>
									</div>
								</div>	
                    <div class="table-responsive">
                        <table id="SLData" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th style="max-width:30px;"><?= lang("id"); ?></th>
                                    <th class="col-xs-2"><?= lang("date"); ?></th>
                                    <th><?= lang("customer"); ?></th>
                                    <th class="col-xs-1"><?= lang("total"); ?></th>
                                    <th class="col-xs-1"><?= lang("tax"); ?></th>
                                    <th class="col-xs-1"><?= lang("discount"); ?></th>
                                    <th class="col-xs-1"><?= lang("VAT"); ?></th>
                                    <th class="col-xs-1"><?= lang("grand_total"); ?></th>
                                    <th class="col-xs-1"><?= lang("paid"); ?></th>
                                    <th class="col-xs-1"><?= lang("status"); ?></th>
                                    <th style="min-width:115px; max-width:115px; text-align:center;"><?= lang("actions"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                   <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                               </tr>
                           </tbody>
                           <tfoot>
                            <tr class="active">
                                <th style="max-width:30px;"><input type="text" class="text_filter" placeholder="[<?= lang('id'); ?>]"></th>
                                <th class="col-sm-2"><span class="datepickercon"><input type="text" class="text_filter datepicker" placeholder="[<?= lang('date'); ?>]"></span></th>
                                <th class="col-sm-2"><input type="text" class="text_filter" placeholder="[<?= lang('customer'); ?>]"></th>
                                <th class="col-sm-1"><?= lang("total"); ?></th>
                                <th class="col-sm-1"><?= lang("tax"); ?></th>
                                <th class="col-sm-1"><?= lang("discount"); ?></th>   
                                <th class="col-xs-1"><?= lang("VAT"); ?></th>                            
                                <th class="col-sm-2"><?= lang("grand_total"); ?></th>
                                <th class="col-sm-1"><?= lang("paid"); ?></th>
                                <th class="col-sm-1">
                                    <select class="select2 select_filter"><option value=""><?= lang("all"); ?></option><option value="paid"><?= lang("paid"); ?></option><option value="partial"><?= lang("partial"); ?></option><option value="due"><?= lang("due"); ?></option></select>
                                </th>
                                    <!-- <th class="col-xs-1"><?= lang("status"); ?></th> -->
                                <th class="col-sm-1"><?= lang("actions"); ?></th>
                            </tr>
                            <tr>
                                <td colspan="11" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="<?= lang('type_hit_enter'); ?>" style="width:100%;"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <?php   
                //echo $name= cryptography("TGhOTlh0SHEvc3lPcjZpanh3MjdRQT09");
                ?>

                <div class="clearfix"></div>
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
    $(document).ready(function() {
        $(document).on('click', '.sale_status', function() {
			 
            var sale_id = $(this).closest('tr').children('td:first').text();
            var curr_status = $(this).text();
            var status = curr_status.toLowerCase();
            $('#status-id').text('( <?= lang('sale_id'); ?> '+sale_id+' )');
            $('#sale_id').val(sale_id);
            $('#status').val(status);
            $('#status').select2('val', status);
            $('#stModal').modal()
        });
    });
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
}
</style>
<div class="modal fade" id="returnsale" tabindex="-1" role="dialog" aria-labelledby="stModalLabel">
    <div class="modal-dialog" role="document" style=" position: fixed; width: 95%; margin:1px 2.5%; " >
        <div class="modal-content" >
								<div class="modal-header" style=" position: relative; ">
											<h4 class="modal-title" id="myModalLabel">Return Item</h4>
											<button type="button" style=" position: absolute; top: 50%; right: 30px; " class="close ar_close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
										</button>
								</div>
								<div class="modal-body" style="border: 1px solid #e4e4e4;padding: 0;" >
									<div class="table-responsive">
												<table id="product_return" class="table table-striped table-bordered table-condensed table-hover">
													<thead>
																	<tr class="active">
																			<th class="col-xs-1"><?= lang("Product"); ?></th>
																			<th class="col-xs-1"><?= lang("Price"); ?></th>
																			<th class="col-xs-1"><?= lang("Qty"); ?></th>
																			<!-- <th class="col-xs-1"><?= lang("Unit"); ?></th> -->
																			<th class="col-xs-1"><?= lang("DIS"); ?></th>
																			<!-- <th class="col-xs-1"><?= lang("VAT %	"); ?></th> -->
																			<th class="col-xs-1"><?= lang("Tax"); ?></th>
																			<th class="col-xs-1"><?= lang("STOTAL"); ?></th>
																			<th class="col-xs-1"><?= lang("R-QTY"); ?></th>
																	</tr>
														</thead>
														<tbody>
																	<tr>
																		<td colspan="9" style=" text-align: center; " class="dataTables_empty"><?= lang('no_data'); ?></td>
																</tr>
														</tbody>
														<!-- <tfoot>
															<tr class="active">
																	<th class="col-sm-1"><?= lang("Product"); ?></th>
																	<th class="col-sm-1"><?= lang("Price"); ?></th>
																	<th class="col-sm-1"><?= lang("Qty"); ?></th>   
																	<th class="col-sm-2"><?= lang("DIS"); ?></th>
																	<th class="col-sm-1"><?= lang("VAT %	"); ?></th>
																	<th class="col-sm-1"><?= lang("Tax"); ?></th>
																	<th class="col-sm-1"><?= lang("STOTAL"); ?></th>
																	<th class="col-xs-1"><?= lang("R-QTY"); ?></th>
															</tr>
														</tfoot> -->
											</table>
									</div>
								</div>
								<div class="modal-footer">
										<input type="button" value="Close" class="btn btn-default pull-left" data-dismiss="modal">
										<input type="submit" class="btn btn-primary pull-right" name="return_items" value="Return Items">
								</div>
        </div>
    </div>
</div>
<script>
// var sale_x;
// var tablx;
// $(document).ready(function(){

// 	tablx = $('#product_return').DataTable({
// 					'ajax' : { url: '<?=site_url('sales/get_return_product');?>', type: 'POST', "data": function ( d ) {
// 							d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
// 							d.sale_id = sale_x;
// 					}},
// 					'dom': 'tp',
//           "buttons": [],
// 					"columns": [
// 					{ "data": "name"},
// 					{ "data": "price"},
// 					{ "data": "quantity" },
// 					{ "data": "item_discount"  },
// 					// { "data": "name"},
// 					// { "data": "stock" },
// 					{ "data": "tax" },
// 					{ "data": "subtotal" },
// 					{ "data": "Actions", "searchable": false, "orderable": false }
// 					]
// 	});

// });
</script>
<style>
div.dataTables_wrapper div.dataTables_paginate {
    text-align: center;
}
</style>
<script>

function returnsale(txt,ele) { 
	
	ele = ele.parentNode.parentNode.parentNode.parentNode;
	sale_x = table.row( ele ).data().id;
	window.location = '<?=site_url("sales/return_page/")?>'+sale_x;
// 	// alert(txt);
// 	tablx
// 	tablx = $('#product_return').DataTable({
// 					'ajax' : { url: '<?=site_url('sales/get_return_product');?>', type: 'POST', "data": function ( d ) {
// 							d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
// 							d.sale_id = sale_x;
// 					}},
// 					'dom': 'tp',
//           "buttons": [],
// 					"columns": [
// 					{ "data": "name"},
// 					{ "data": "price"},
// 					{ "data": "quantity" },
// 					{ "data": "item_discount"  },
// 					// { "data": "name"},
// 					// { "data": "stock" },
// 					{ "data": "tax" },
// 					{ "data": "subtotal" },
// 					{ "data": "Actions", "searchable": false, "orderable": false }
// 					]
// 	});

// 	ele = ele.parentNode.parentNode.parentNode.parentNode;
// 	console.log(ele);
// 	sale_x = table.row( ele ).data().id;
// 	tablx.ajax.reload();
// 	console.log(table.row( ele ).data());
	
// 	var ahref = $(ele).data('href');
// 	// $('#returnsale .modal-content').html(txt);
// 	$('#returnsale').modal('show');
 }
</script>