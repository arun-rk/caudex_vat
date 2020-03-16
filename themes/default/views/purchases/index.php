<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<style>
.al-end{
	
	text-align: end;
}
</style>
<script type="text/javascript">
var table;
    $(document).ready(function() {

        if (get('remove_spo')) {
            if (get('spoitems')) {
                remove('spoitems');
            }
            remove('remove_spo');
        }
        <?php
        if ($this->session->userdata('remove_spo')) {
            ?>
            if (get('spoitems')) {
                remove('spoitems');
            }
            <?php
            $this->tec->unset_data('remove_spo');
        }
        ?>
        function attach(x) {
            if (x !== null) {
                return '<a href="<?=base_url();?>uploads/'+x+'" target="_blank" class="btn btn-primary btn-block btn-xs"><i class="fa fa-chain"></i></a>';
            }
            return '';
        }

        table = $('#purData').DataTable({

            'ajax' : { url: '<?=site_url('purchases/get_purchases');?>', type: 'POST', "data": function ( d ) {
                d.<?=$this->security->get_csrf_token_name();?> = "<?=$this->security->get_csrf_hash()?>";
            }},
            "buttons": [
            { extend: 'copyHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5,6 ,7] } },
            { extend: 'excelHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5,6 ,7] } },
            { extend: 'csvHtml5', 'footer': true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ,6,7] } },
            { extend: 'pdfHtml5', orientation: 'landscape', pageSize: 'A4', 'footer': true,
            exportOptions: { columns: [ 0, 1, 2, 3, 4, 5 ,6,7] } },
            { extend: 'colvis', text: 'Columns'},
            ],
            "columns": [
            { "data": "id", "visible": false },
			{ "data": "batch_no" },
            { "data": "date", "render": hrsd },
            { "data": "reference" },
			//  { "data": "cgst_Total", "render": currencyFormat },
			// { "data": "sgst_Total" , "render": currencyFormat },
						{ "data": "vat" ,"class":"al-end", "render": 
						function( data, type, row, meta ){
							// console.log(Number(row.sgst_Total));
							// console.log(Number(row.cgst_Total));
							vat = Number(row.sgst_Total)+Number(row.cgst_Total);
							// return "BHD. "+currencyFormat(vat);
							return currencyFormat(vat);
						}
						},
            { "data": "total", "render": currencyFormat },
			 { "data": "grand_total", "render": currencyFormat },
            { "data": "note" },
            { "data": "attachment", "render": attach, "searchable": false, "orderable": false },
            { "data": "Actions", "searchable": false, "orderable": false }
            ],
            "footerCallback": function (  tfoot, data, start, end, display ) {
                var api = this.api(), data;
                $(api.column(3).footer()).html( cf(api.column(3).data().reduce( function (a, b) { return pf(a) + pf(b); }, 0)) );
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

<style type="text/css">.table td:nth-child(3) { text-align: right; }</style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('list_results'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="purData" class="table table-striped table-bordered table-condensed table-hover" style="margin-bottom:5px;">
                            <thead>
                                <tr class="active">
                                    <th style="max-width:10px;"><?= lang("id"); ?></th>
									<th class="col-xs-1"><?= lang('Batch No'); ?></th>
                                    <th class="col-xs-2"><?= lang('date'); ?></th>
                                    <th class="col-xs-1"><?= lang('reference'); ?></th>
									 <!-- <th class="col-xs-2"><?= lang('CGST'); ?></th> -->
                                    <!-- <th class="col-xs-2"><?= lang('SGST'); ?></th> -->
                                    <th class="col-xs-2"><?= lang('VAT'); ?></th>
                                    <th class="col-xs-1"><?= lang('total'); ?></th>
									  <th class="col-xs-1"><?= lang('Grand Total'); ?></th>
                                    <th><?= lang('note'); ?></th>
                                    <th style="width:25px; padding-right:5px;"><i class="fa fa-chain"></i></th>
                                    <th style="width:100px;"><?= lang('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                               
                            </tfoot>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/moment.min.js" type="text/javascript"></script>
<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datetimepicker({format: 'YYYY-MM-DD', showClear: true, showClose: true, useCurrent: false, widgetPositioning: {horizontal: 'auto', vertical: 'bottom'}, widgetParent: $('.dataTable tfoot')});
			
				$('#purData').on('click','.return_purchase',function(){
					var data = table.row( $(this).closest('tr') ).data();
					window.location = "<?=site_url('purchases/returnp/')?>"+data.id;
				});

		});
</script>
