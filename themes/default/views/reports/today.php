<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<?php
$v = "?v=1";

if ($this->input->post('customer')){
    $v .= "&customer=".$this->input->post('customer');
}
if ($this->input->post('user')){
    $v .= "&user=".$this->input->post('user');
}
if ($this->input->post('start_date')){
    $v .= "&start_date=".$this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=".$this->input->post('end_date');
}

?>
<style>
td {
    text-align: center !important;
}
</style>

<script type="text/javascript">
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

        var table = $('#SLRData').DataTable({

            'ajax' : { url: '<?=site_url('reports/get_today_credit/'. $v);?>', type: 'POST', "data": function ( d ) {
			 
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', 'footer': true, exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': true,
            exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "Item"},
           { "data": "SaleQty" },
          { "data": "PurchaseRate", "render": currencyFormat },
            { "data": "SaleRate" , "render": currencyFormat},
            { "data": "PurchaseValue", "render": currencyFormat },
            { "data": "SaleValue", "render": currencyFormat },
            { "data": "vat" , "render": function(data){
							// console.log(data);
							return 'BHD. '+data;
						}},
            // { "data": "vat" , "render": currencyFormat},
            { "data": "NetProfit" , "render": currencyFormat}
            /*  { "data": "balance", "render": currencyFormat },
            { "data": "status", "render": status } */
            ] ,
            "footerCallback": function (  tfoot, data, start, end, display ) {
                var api = this.api(), data;
              
				    $(api.column(6).footer()).html( cf(api.column(6).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
               
            }

        });

        var crdsales = $('#crdsales').DataTable({

            'ajax' : { url: '<?=site_url('reports/get_today/'. $v);?>', type: 'POST', "data": function ( d ) {
			 
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', 'footer': true, exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': true,
            exportOptions: { columns: [ 0,1,2,3,4,5,6 ] } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "Item"},
           { "data": "SaleQty" },
          { "data": "PurchaseRate", "render": currencyFormat },
            { "data": "SaleRate" , "render": currencyFormat},
            { "data": "PurchaseValue", "render": currencyFormat },
            { "data": "SaleValue", "render": currencyFormat },
            { "data": "vat" , "render": function(data){
							// console.log(data);
							return 'BHD. '+data;
						}},
            // { "data": "vat" , "render": currencyFormat},
            { "data": "NetProfit" , "render": currencyFormat}
            /*  { "data": "balance", "render": currencyFormat },
            { "data": "status", "render": status } */
            ] ,
            "footerCallback": function (  tfoot, data, start, end, display ) {
                var api = this.api(), data;
              
				    $(api.column(6).footer()).html( cf(api.column(6).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
               
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
        crdsales.columns().every(function () {
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#form').hide();
        $('.toggle_form').click(function(){
            $("#form").slideToggle();
            return false;
        });
    });
</script>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <a href="#" class="btn btn-default btn-sm toggle_form pull-right"><?= lang("show_hide"); ?></a>
                    <h3 class="box-title"><?= lang('customize_report'); ?></h3>
                </div>
                <div class="box-body">
                    <div id="form" class="panel panel-warning">
                        <div class="panel-body">
                            <?= form_open("reports/today");?>

                            <div class="row">
                             
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label" for="start_date"><?= lang("start_date"); ?></label>
                                        <?= form_input('start_date', set_value('start_date'), 'class="form-control datepicker" id="start_date"');?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label" for="end_date"><?= lang("end_date"); ?></label>
                                        <?= form_input('end_date', set_value('end_date'), 'class="form-control datepicker" id="end_date"');?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary"><?= lang("submit"); ?></button>
                                </div>
                            </div>
                            <?= form_close();?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                   

										<div class="nav-tabs-custom">
											<ul class="nav nav-tabs">
												<li class="active"><a href="#tab_1" data-toggle="tab">Today Sales</a></li>
												<li><a href="#tab_2" data-toggle="tab">Credit Sales</a></li>
												<!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
											</ul>
											<div class="tab-content">
												<div class="tab-pane active" id="tab_1"> 
													<div class="row">
													<div class="col-sm-12">
															<div class="table-responsive">
																	<table id="SLRData" class="table table-striped table-bordered table-condensed table-hover">
																			<thead>
			
																					<tr class="active">
																							<th class="col-sm-2"><?= lang("Item"); ?></th>
																							<th class="col-sm-1"><?= lang("Sale Qty"); ?></th>
																							<th class="col-sm-2"><?= lang("Purchase Rate"); ?></th>
																							<th class="col-sm-2"><?= lang("Sale Rate"); ?></th>
																							<th class="col-sm-2"><?= lang("Purchase Value"); ?></th>
																							<th class="col-sm-2"><?= lang("Sale Value"); ?></th>
																							<th class="col-sm-1"><?= lang("Vat"); ?></th>
																							<th class="col-sm-1"><?= lang("Net Profit"); ?></th>
																											
																					</tr>
																			</thead>
																			<tbody>
																					<tr>
																							<td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
																					</tr>
																			</tbody>
																			<tfoot>
																					<tr class="active">
																						<th class="col-sm-2"><?= lang("Item"); ?></th>
																						<th class="col-sm-1"><?= lang("Sale Qty"); ?></th>
																						<th class="col-sm-1"><?= lang("Purchase Rate"); ?></th>
																						<th class="col-sm-1"><?= lang("Sale Rate"); ?></th>
																						<th class="col-sm-2"><?= lang("Purchase Value"); ?></th>
																						<th class="col-sm-1"><?= lang("Sale Value"); ?></th>
																						<th class="col-sm-1"><?= lang("Vat"); ?></th>
																						<th class="col-sm-3"><?= lang("Net Profit"); ?></th>
																					</tr>
																					<tr>
																							<td colspan="10" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="<?= lang('type_hit_enter'); ?>" style="width:100%;"></td>
																					</tr>
																			</tfoot>
																	
																	</table>
															</div>
													</div>
											</div>

												</div> 

												<div class="tab-pane" id="tab_2"> 
													<div class="row">
													<div class="col-sm-12">
															<div class="table-responsive">
																	<table id="crdsales" class="table table-striped table-bordered table-condensed table-hover">
																			<thead>
			
																					<tr class="active">
																							<th class="col-sm-2"><?= lang("Item"); ?></th>
																							<th class="col-sm-1"><?= lang("Sale Qty"); ?></th>
																							<th class="col-sm-2"><?= lang("Purchase Rate"); ?></th>
																							<th class="col-sm-2"><?= lang("Sale Rate"); ?></th>
																							<th class="col-sm-2"><?= lang("Purchase Value"); ?></th>
																							<th class="col-sm-2"><?= lang("Sale Value"); ?></th>
																							<th class="col-sm-1"><?= lang("Vat"); ?></th>
																							<th class="col-sm-1"><?= lang("Net Profit"); ?></th>
																											
																					</tr>
																			</thead>
																			<tbody>
																					<tr>
																							<td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
																					</tr>
																			</tbody>
																			<tfoot>
																					<tr class="active">
																						<th class="col-sm-2"><?= lang("Item"); ?></th>
																						<th class="col-sm-1"><?= lang("Sale Qty"); ?></th>
																						<th class="col-sm-1"><?= lang("Purchase Rate"); ?></th>
																						<th class="col-sm-1"><?= lang("Sale Rate"); ?></th>
																						<th class="col-sm-2"><?= lang("Purchase Value"); ?></th>
																						<th class="col-sm-1"><?= lang("Sale Value"); ?></th>
																						<th class="col-sm-1"><?= lang("Vat"); ?></th>
																						<th class="col-sm-3"><?= lang("Net Profit"); ?></th>
																					</tr>
																					<tr>
																							<td colspan="10" class="p0"><input type="text" class="form-control b0" name="search_table" id="search_table" placeholder="<?= lang('type_hit_enter'); ?>" style="width:100%;"></td>
																					</tr>
																			</tfoot>
																	
																	</table>
															</div>
													</div>
											</div>

												</div> 

											</div>
										</div>



                    <?php if ($this->input->post('customer')) { ?>
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn bg-purple btn-lg btn-block" style="cursor:default;">
                                <strong><?= $this->tec->formatMoney($total_sales->number, 0); ?></strong>
                                <?= lang("sales"); ?>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-lg btn-block" style="cursor:default;">
                                <strong><?= $this->tec->formatMoney($total_sales->amount); ?></strong>
                                <?= lang("amount"); ?>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-lg btn-block" style="cursor:default;">
                                <strong><?= $this->tec->formatMoney($total_sales->paid); ?></strong>
                                <?= lang("paid"); ?>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning btn-lg btn-block" style="cursor:default;">
                                <strong><?= $this->tec->formatMoney($total_sales->amount-$total_sales->paid); ?></strong>
                                <?= lang("due"); ?>
                            </button>
                        </div>
                    </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/moment.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });
        $('.datepicker').datetimepicker({format: 'YYYY-MM-DD', showClear: true, showClose: true, useCurrent: false, widgetPositioning: {horizontal: 'auto', vertical: 'bottom'}, widgetParent: $('.dataTable tfoot')});
    });
</script>
