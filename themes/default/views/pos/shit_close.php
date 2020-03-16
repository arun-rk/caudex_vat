<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog">
    <div class="modal-content" id="modal-content">
		<style>
@media print{
	.ar-no-printx{
		display:none !important;
	}
	h4 {
    margin: 0 !important;
    font-weight: 400;
    font-size: 13px;
    letter-spacing: 0.5px;
}
}
.align_center {
    text-align: center;
}
.storename {
    text-align: center;
    display: block;
    font-size: 27px;
    font-weight: 500;
}
.light_text {
    font-size: 12px;
    letter-spacing: 1px;
    font-weight: 400;
}
.semi_text {
    font-size: 15px;
    display: block;
    margin: 10px 0;
    padding: 4px;
    border: 1px solid #969696;
    border-right: none;
    border-left: none;
}

</style>
        <div class="modal-header ar-no-printx">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <button type="button" class="close mr10" onclick="PrintElem('modal-content');"><i class="fa fa-print"></i></button>
            <!-- <button type="button" class="close mr10" onclick="window.print();"><i class="fa fa-print"></i></button> -->
            <h4 class="modal-title" id="myModalLabel"><?= lang('Shift Close').' ('.lang('opened_at').': '.$this->tec->hrld($shift->end).')'; ?></h4>
        </div>
        <div class="modal-body">
				<?php
	if ($store) {
			echo '<div class="imag_tag" style=" text-align: center; " ><img  style=" height: 90px; margin-top: 15px; margin-bottom: 11px; "  src="'.base_url('uploads/'.$store->logo).'" alt="'.cryptography($store->name).'"></div>';
			echo '<strong class="storename" >'.cryptography($store->name).'</strong>';
			// echo '<div class="hl" style="grid-area:hl1"></div>';
			echo " <div class='align_center' style='grid-area:address1'><span class='light_text' >".cryptography($store->address1)."</span></div>";
			echo " <div class='align_center' style='grid-area:address2'><span  class='light_text'>".cryptography($store->address2)."</span></div>";
			echo " <div class='align_center' style='grid-area:pos_vatno'><span class='light_text'>VAT no :223334091300002 </span></div>";
			// echo " <div class='align_center' style='grid-area:city'><span class='light_text'>".cryptography($store->city)."</span></div>";
			echo " <div class='align_center' style='grid-area:city'><span class='light_text'></span></div>";
			echo " <div class='align_center' style='grid-area:phone'><span class='light_text'>Tel. ".cryptography($store->phone)."</span></div>";
			// echo " <div class='align_center' style='grid-area:email'><span class='light_text'>".cryptography($store->email)."</span></div>";
			echo " <div class='align_center' style='grid-area:r_header'><span class='semi_text'> SHIFT CLOSE </span></div>";
			//echo '<p><b> INVOICE CASH / CREDIT </b></p>';
	}
	?>
<div class="hl" style="grid-area:hl2"></div>
            <!-- <table width="100%" class="stable" >
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_in_hand'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->tec->formatMoney($this->session->userdata('cash_in_hand')); ?></span></h4>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_sale'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->tec->formatMoney($cashsales->paid ? $cashsales->paid : '0.00') . ' (' . $this->tec->formatMoney($cashsales->total ? $cashsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('ch_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->tec->formatMoney($chsales->paid ? $chsales->paid : '0.00') . ' (' . $this->tec->formatMoney($chsales->total ? $chsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('gc_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->tec->formatMoney($gcsales->paid ? $gcsales->paid : '0.00') . ' (' . $this->tec->formatMoney($gcsales->total ? $gcsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid <?= (!isset($Settings->stripe)) ? '#DDD' : '#EEE'; ?>;"><h4><?= lang('cc_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid <?= (!isset($Settings->stripe)) ? '#DDD' : '#EEE'; ?>;"><h4>
                            <span><?= $this->tec->formatMoney($ccsales->paid ? $ccsales->paid : '0.00') . ' (' . $this->tec->formatMoney($ccsales->total ? $ccsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>

                <?php if (isset($Settings->stripe)) { ?>
                    <tr>
                        <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('stripe'); ?>:</h4></td>
                        <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                                <span><?= $this->tec->formatMoney($stripesales->paid ? $stripesales->paid : '0.00') . ' (' . $this->tec->formatMoney($stripesales->total ? $stripesales->total : '0.00') . ')'; ?></span>
                            </h4></td>
                    </tr>
                <?php } ?>

                <tr>
                    <td style="border-bottom: 1px solid #008d4c;"><h4><?= lang('other_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #008d4c;"><h4>
                            <span><?= $this->tec->formatMoney($other_sales->paid ? $other_sales->paid : '0.00') . ' (' . $this->tec->formatMoney($other_sales->total ? $other_sales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>

                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><?= lang('total_sales'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;"><h4>
                            <span><?= $this->tec->formatMoney($totalsales->paid ? $totalsales->paid : '0.00') . ' (' . $this->tec->formatMoney($totalsales->total ? $totalsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>

                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><?= lang('expenses'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;"><h4>
                            <span><?= $this->tec->formatMoney($expenses->total ? $expenses->total : '0.00'); ?></span>
                        </h4></td>
                </tr>
                <?php $total_cash = ($cashsales->paid ? $cashsales->paid + ($cash_in_hand ? $cash_in_hand : $this->session->userdata('cash_in_hand')) : (($cash_in_hand ? $cash_in_hand : $this->session->userdata('cash_in_hand'))));
                $total_cash -= ($expenses->total ? $expenses->total : 0.00);
                ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('total_cash'); ?></strong>:</h4>
                    </td>
                    <td style="text-align:right;"><h4>
                            <span><strong><?= $this->tec->formatMoney($total_cash); ?></strong></span>
                        </h4></td>
                </tr>
            </table> -->

                

            </div>
            <div class="modal-footer ar-no-printx">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?=lang('close')?></button>
                <?php
                if ( ! $Settings->remote_printing) {
                    echo '<a href="'.site_url('pos/print_register').'" class="btn btn-default" data-toggle="ajax2">'.lang("print").'</a>';
                } elseif ($Settings->remote_printing == 2) {
                    echo '<button id="print-register-details" class="btn btn-default">'.lang("print").'</button>';
                } else {
                    echo '<button type="button" onclick="window.print();" class="btn btn-default">'.lang('print').'</button>';
                }
                ?>
								
            </div>
        </div>
				
    </div>

</div>

<?php
if ($Settings->remote_printing == 2) {
?>
<script type="text/javascript">
    var socket = null;
    $(document).ready(function() {
        try {
            socket = new WebSocket('ws://127.0.0.1:6441');
            socket.onopen = function () {
                console.log('Connected');
                return;
            };
            socket.onclose = function () {
                console.log('Connection closed');
                return;
            };
        } catch (e) {
            console.log(e);
        }
        function printRegister(data) {
            if (socket.readyState == 1) {
                socket.send(JSON.stringify({
                    type: 'print-data',
                    data: data
                }));
                return false;
            } else {
                bootbox.alert('<?= lang('pos_print_error'); ?>');
                return false;
            }
        }
        $('#print-register-details').click(function(e) {
            e.preventDefault();
            $.get('<?= site_url('pos/print_register/2'); ?>', function(regData) {
                printRegister(regData);
                return false;
            });
            return false;
        });
    });
</script>
<?php
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({minimumResultsForSearch:6});
    });

function PrintElem(elem)
{
		var mywindow = window.open('', 'PRINT', 'height='+window.innerHeight+',width='+window.innerWidth);

		mywindow.document.write('<html><head><title>' + document.title  + '</title>');
		mywindow.document.write('</head><body >');
		// mywindow.document.write('<h1>' + document.title  + '</h1>');
		mywindow.document.write(document.getElementById(elem).innerHTML);
		mywindow.document.write('</body></html>');

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		mywindow.close();

		return true;
}
</script>