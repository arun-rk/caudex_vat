<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Inconsolata" />
<style>
.table>tbody>tr>td,
.table>tbody>tr>th,
.table>tfoot>tr>td,
.table>tfoot>tr>th,
.table>thead>tr>td,
.table>thead>tr>th {
	border: 1px dotted #000 !important;
}

.table>tbody>tr>td,
.table>tbody>tr>th,
.table>tfoot>tr>td,
.table>tfoot>tr>th,
.table>thead>tr>td,
.table>thead>tr>th {
	border-top: 1px dotted #000 !important;
}

.P_Right {
	float: right;
}

p.P_Left {
	float: left;
	width: 50%;
}

.table>tbody>tr>td,
.table>tbody>tr>th,
.table>tfoot>tr>td,
.table>tfoot>tr>th,
.table>thead>tr>td,
.table>thead>tr>th {
	border: 1px dotted #000;
}

.table {
	border-radius: 3px;
	font-size: 13px;
}

.table>tbody>tr>td,
.table>tbody>tr>th,
.table>tfoot>tr>td,
.table>tfoot>tr>th,
.table>thead>tr>td,
.table>thead>tr>th {
	border-top: 1px dotted #000 !important;
}

@page {
	margin: 0% max-width: 96% !important size: 21.59cm 13.97cm;
}

p {

	font-size: 12px;

}

div {

	font-size: 12px;

}

.table {

	font-size: 12px;
}

#ItemsList {
	border: none !important;
}

#ItemsRow {
	border-left: 1px dotted #000 !important;
	border-right: 1px dotted #000 !important;
}

.table-condensed>tbody>tr>td,
.table-condensed>tbody>tr>th,
.table-condensed>tfoot>tr>td,
.table-condensed>tfoot>tr>th,
.table-condensed>thead>tr>td,
.table-condensed>thead>tr>th {
	padding: 0px !important;

	padding-right: 1px !important;
	padding-left: 1px !important;
}

.P_Left label {
	margin: 0px;
}

.P_Right label {
	margin: 0px;
}
</style>
<?php
if ($modal) {
    ?>

<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-body">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
			<?php
            } else {
                ?>
			<!doctype html>
			<html>

			<head>
				<meta charset="utf-8">
				<title><?= $page_title . " " . lang("no") . " " . $inv->billno; ?></title>
				<base href="<?= base_url() ?>" />
				<meta http-equiv="cache-control" content="max-age=0" />
				<meta http-equiv="cache-control" content="no-cache" />
				<meta http-equiv="expires" content="0" />
				<meta http-equiv="pragma" content="no-cache" />
				<link rel="shortcut icon" href="<?= $assets ?>images/icon.png" />
				<link href="<?= $assets ?>dist/css/styles.css" rel="stylesheet" type="text/css" />
				<style type="text/css" media="all">
				body {
					color: #000;
				}

				#wrapper {
					max-width: 650px;
					margin: 0 auto;
					padding-top: 20px;
				}

				.btn {
					margin-bottom: 5px;
				}

				.table {
					border-radius: 3px;
				}

				.table th {
					background: #f5f5f5;
				}

				.table th,
				.table td {
					vertical-align: middle !important;
				}

				h3 {
					margin: 5px 0;
				}

				@media print {

					html,
					body {
						border: 1px solid white;
						height: 99%;
						page-break-after: avoid;
						page-break-before: avoid;
					}

					.no-print {
						display: none;
					}

					#wrapper {
						width: auto;
						max-width: 96% !important;
						min-width: 96% !important;
						margin: 2% !important;
						padding: 0%;
					}

					#receiptData {
						width: auto;
						max-width: 96% !important;
						min-width: 96% !important;
						margin: 2% !important;
						padding: 0%;
					}
				}
				</style>
			</head>

			<body>
				<?php
                }
				?>

				<div id="wrapper" style="max-width: 100%;margin: 0;">
					<div id="receiptData" style="width: auto; max-width: 90%; min-width: 90%; margin: 0 auto;">
						<div class="no-print">
							<?php if ($message) { ?>
							<div class="alert alert-success">
								<button data-dismiss="alert" class="close" type="button">×</button>
								<?= is_array($message) ? print_r($message, true) : $message; ?>
							</div>
							<?php } ?>
						</div>
						<div id="receipt-data">
							<div>
								<div style="text-align:center;">
									<?php
									$phone=$store->phone;
									$phone=cryptography($phone);
                                    if ($store) {
                                     //   echo '<img src="'.base_url('uploads/'.$store->logo).'" alt="'.$store->name.'">';
                                        echo '<p style="text-align:center;margin: 0;">';
                                        echo '<strong style="font-size: 25px;">'.cryptography($store->name).'</strong><br>';
                                        echo cryptography($store->address1).'<br>'.cryptography($store->address2);
										echo " ".cryptography($store->city).'<br>';
										echo "$phone";
										  echo '<br>'.cryptography($store->email);
                                        echo '</p>';
                                      //  echo '<p><b>'.nl2br($store->receipt_header).'</b></p>';
																			 echo '<p><b> INVOICE CASH / CREDIT </b></p>';
                                    }
                                    ?>
								</div>

								<p class="P_Left" style="padding: 0px !important;margin: 0px !important;">
									<?= lang("TIN","TIN").': '.$Settings->gstin; ?> <br>

									<?= lang('Inv No','Inv No').': '.$inv->id; ?><br>

								</p>
								<div class="P_Right" style="padding: 0px !important;margin: 0px !important;">
									<div style="float:right;"> <?= lang("date","date").': '.$this->tec->hrld($inv->date); ?> </div><br>



									<?= lang("sales_person","sales_person").': '. $created_by->first_name." ".$created_by->last_name; ?>
									<br>
								</div>
								<div style="float: left;width: 100%;padding: 0px !important;margin: 0px !important;">
									<p class="P_Left" style="padding: 0px !important;margin: 0px !important;">
										<?= lang("customer","customer").': '. $inv->customer_name; ?> <br>

									</p>
									<div class="P_Right" style="padding: 0px !important;margin: 0px !important;">
										<?= lang("Delivery Place","Delivery Place").': '. $inv->hold_ref; ?> <br>
									</div>
								</div>

								<div style="float: left;width: 100%;padding: 0px !important;margin: 0px !important;">
									<p class="P_Left" style="padding: 0px !important;margin: 0px !important;">
										<?= lang("Address","Address").': '. $customer->cf1; ?> <br>

									</p>
									<p class="P_Right" style="padding: 0px !important;margin: 0px !important;">
										<?= lang("VAT No","VAT No").': '. ""; ?> <br>
<!-- <?= lang("VAT No","VAT No").': '. $inv->gstno; ?> <br> -->
									</p>
								</div>

								<div style="clear:both;"></div>
								<table class="table table-striped table-condensed" border="1" style="margin-bottom:0px !important">

									<tbody>
										<tr>
											<th style="text-align:center; width: 5%; border-bottom: 1px solid #000;"><?=lang('Sl No');?></th>
											<th style="text-align:center; width: 30%; border-bottom: 1px solid #000;"><?=lang('Item');?></th>
											<th style="text-align:center; width:7%; border-bottom: 1px solid #000;"><?=lang('HSN Code');?>
											</th>
											<th style="text-align:center; width: 9%; border-bottom: 1px solid #000;"><?=lang('price');?></th>
											<th style="text-align:center; width: 7%; border-bottom: 1px solid #000;"><?=lang('quantity');?>
											</th>
											<th style="text-align:center; width: 3%; border-bottom: 1px solid #000;"><?=lang('Unit');?>
											</th>
											<th style="text-align:center; width: 7%; border-bottom: 1px solid #000;"><?=lang('Discount');?>
											</th>
											<th style="text-align:center; width: 7%; border-bottom: 1px solid #000;display:none"><?=lang('CGST %');?></th>
											<th style="text-align:center; width: 7%; border-bottom: 1px solid #000;display:none"><?=lang('Tax');?></th>
											<th style="text-align:center; width: 7%; border-bottom: 1px solid #000;display:none"><?=lang('SGST %');?></th>
											<th style="text-align:center; width: 6%; border-bottom: 1px solid #000;display:none"><?=lang('Tax');?></th>

											<th style="text-align:center; width: 7%; border-bottom: 1px solid #000;"><?=lang('VAT %');?></th>
											<th style="text-align:center; width: 6%; border-bottom: 1px solid #000;"><?=lang('Tax');?></th>

											<th style="text-align:center; width: 15%; border-bottom: 1px solid #000;"><?=lang('subtotal');?>
											</th>

										</tr>
										<?php
                                        $tax_summary = array();
										$SlNo=1;
										$CGST=0;
										$SGST=0;
										$chk=0;
										$Inclusive_Tax_Amount=0;
										//print_r($rows);
										$sttl = 0;
                                        foreach ($rows as $row) {
											if($row->hsn) $hsn=$row->hsn; else $hsn="";

                                            echo '<tr id="ItemsRow"><td id="ItemsList" style="text-align:center;">' . $SlNo.'</td>';
											   echo '<td  id="ItemsList"  style="text-align:left;">' . $row->product_name .' ('. $row->product_code .')</td>';
											  echo '<td  id="ItemsList"  style="text-align:center;">' .$hsn . '</td>';
											echo '<td  id="ItemsList"  style="text-align:right;">';
											// $row->hsn
											$vat=$row->sgst_tax+$row->cgst_tax;

											if($row->tax_method == "0")
											{

												$Inclusive_Tax_Amount=($row->net_unit_price /(100+(5))*(5));

												$find=($row->net_unit_price)/(100+$vat);
                                                $find=round($find*$vat, 3);

											    $pr_price_found=$row->net_unit_price-$Inclusive_Tax_Amount;
												$pr_price=$this->tec->formatMoney($pr_price_found);

												//echo $this->tec->formatMoney(($row->net_unit_price + ($row->item_tax / $row->quantity))-(($row->cgst_tax_val+$row->sgst_tax_val)/$row->quantity)) . '</td>';
												echo $pr_price. '</td>';

												//$sttl += (($row->net_unit_price + ($row->item_tax / $row->quantity))-(($row->cgst_tax_val+$row->sgst_tax_val)/$row->quantity)) * $row->quantity;
												$sttl += (($pr_price_found)) * $row->quantity;
												$vat=$row->sgst_tax+$row->cgst_tax;
												$vatval=$find*$row->quantity;

											}
											else{

												echo $this->tec->formatMoney($row->net_unit_price + ($row->item_tax / $row->quantity)) . '</td>';
												$sttl += ($row->net_unit_price + ($row->item_tax / $row->quantity)) *$row->quantity;

												$vat=$row->sgst_tax+$row->cgst_tax;
											    $vatval=$row->sgst_tax_val+$row->cgst_tax_val;

											}

											if(@!$row->unit){
												$row->unit = ' - ';
											}
                                            echo '<td  id="ItemsList"  style="text-align:center;">' . $this->tec->formatQuantity($row->quantity) . '</td>';
																						echo '<td  id="ItemsList"  style="text-align:center;">' . $row->unit.'</td>';
																						echo '<td  id="ItemsList"  style="text-align:center;">' . $row->discount .'</td>';
											echo '<td  id="ItemsList"  style="text-align:center;display:none">' . $row->cgst_tax .'</td>';
											echo '<td  id="ItemsList"  style="text-align:center;display:none">' . $row->cgst_tax_val .'</td>';
											echo '<td  id="ItemsList" style="text-align:center;display:none">' . $row->sgst_tax .'</td>';
											echo '<td  id="ItemsList"  style="text-align:center;display:none">' . $row->sgst_tax_val .'</td>';

											echo '<td  id="ItemsList" style="text-align:center;">' . $vat .'</td>';
											echo '<td  id="ItemsList"  style="text-align:center;">' . $vatval .'</td>';

											echo '<td id="ItemsList" style="text-align:right;">' . $this->tec->formatMoney($row->subtotal) . '</td></tr>';
											$SlNo=$SlNo+1;
										/*	$CGST=$CGST+$row->cgst_tax_val;
											$SGST=$SGST+ $row->sgst_tax_val;*/
											$CGST=$CGST+($vatval/2);
											$SGST=$SGST+($vatval/2);
											$chk +=$row->subtotal;
																				}
																				$inv->grand_total = $sttl+$CGST+$SGST;
																				if($inv->order_discount){
																					$inv->grand_total -= $inv->order_discount;
																				}
                                        ?>
										<tr>
											<th colspan="7" style="text-align:left;"><?= lang("Total :"); ?></th>
											<th colspan="3" style="text-align:right;"><?= $this->tec->formatMoney($sttl ); ?></th>
											<!-- <th colspan="2" style="text-align:right;">xx<?= $this->tec->formatMoney($inv->total ); ?></th> -->
										</tr>
										<tr>
											<th colspan="7" style="text-align:left;"><?= lang("Product Discount :"); ?></th>
											<th colspan="3" style="text-align:right;"><?= $this->tec->formatMoney($inv->product_discount); ?>
											</th>
										</tr>
										<tr>
											<th colspan="7" style="text-align:left;"><?= lang("Order Discount :"); ?></th>
											<th colspan="3" style="text-align:right;"><?= $this->tec->formatMoney($inv->order_discount); ?>
											</th>
										</tr>
										<?php
										 echo '<tr style="display:none"><th colspan="7" style="text-align:left;>' . lang("SGST :") . '</th><th colspan="3" style="text-align:right;">' . $this->tec->formatMoney($SGST) . '</th></tr>';
										   echo '<tr style="display:none"><th colspan="7" style="text-align:left;">' . lang("CGST :") . '</th><th colspan="3" style="text-align:right;">' . $this->tec->formatMoney($CGST). '</th></tr>';
											 echo '<tr><th colspan="7" style="text-align:left;">' . lang("VAT :") . '</th><th colspan="3" style="text-align:right;">' . $this->tec->formatMoney($CGST+$SGST). '</th></tr>';

										 if ($inv->order_tax != 0) {

												}
												if ($inv->total_discount != 0) {
													//  echo '<tr><th colspan="1" style="text-align:left;">' . lang("order_discount") . '</th><th colspan="2" style="text-align:right;">' . $this->tec->formatMoney($inv->total_discount) . '</th></tr>';
												}

												if ($Settings->rounding) {
														// $round_total = $this->tec->roundNumber($sttl+$CGST+$SGST, $Settings->rounding);
														$round_total = $this->tec->roundNumber($inv->grand_total, $Settings->rounding);
														$rounding = $this->tec->formatDecimal($round_total - $inv->grand_total);
														?>
										<?php if($chk != $inv->grand_total) : ?>
										<tr style="display:none">
											<th colspan="9" style="text-align:left;"><?= lang("Kerala Flood Cess (1%):"); ?></th>
											<th colspan="3" style="text-align:right;">
												<?php echo $inv->grand_total-$chk ;?></th>
										</tr>
										<?php endif ?>
										<tr>
											<th colspan="7" style="text-align:left;"><?= lang("Grand Total :"); ?></th>
											<th colspan="3" style="text-align:right;">
												<?= $this->tec->formatMoney($inv->grand_total + $rounding); ?></th>
										</tr>
										<?php
												} else {
														$round_total = $inv->grand_total;
														?>
										<?php if($chk != $round_total ) : ?>
										<tr style="display:none">
											<th colspan="7" style="text-align:left;"><?= lang("Kerala Flood Cess (1%):"); ?></th>
											<th colspan="3" style="text-align:right;">
												<?php echo  $this->tec->formatMoney($round_total - $chk) ;?></th>
										</tr>
										<?php endif ?>

										<tr>
											<th colspan="7" style="text-align:left;"><?= lang("grand_total"); ?></th>
										 <!-- <th colspan="2" style="text-align:right;"><?= $this->tec->formatMoney($sttl); ?></th>  -->
										 <!-- <th colspan="2" style="text-align:right;"><?= $this->tec->formatMoney($sttl+$CGST+$SGST); ?></th>  -->
											<th colspan="3" style="text-align:right;"><?= $this->tec->formatMoney($inv->grand_total); ?></th>
										</tr>
										<?php
																				}
																				$ar_temp1 = (float)($inv->paid);
																				$round_total = (float)($round_total);
																				$ar_temp1 = (string)($ar_temp1);
																				$round_total = (string)($round_total);
																				$ar_temp2 = $ar_temp1 - $round_total;
                                        if ($ar_temp2 < 0) { ?>
										<tr>
											<th colspan="7" style="text-align:left;"><?= lang("paid_amount"); ?></th>
											<th colspan="3" style="text-align:right;"><?= $inv->paid?></th>
										</tr>
										<tr>
											<th colspan="7" style="text-align:left;"><?= lang("due_amount"); ?></th>
											<th colspan="3" style="text-align:right;">
												<?= $this->tec->formatMoney($inv->grand_total - floatval($inv->paid)); ?></th>
										</tr>
										<?php } ?>
									</tbody>

								</table>
								<?php
									if ($payments) {
											echo '<table class="table table-striped table-condensed" style="margin-top:0px;"><tbody>';
											foreach ($payments as $payment) {
													echo '<tr>';

													if ($payment->paid_by == 'cash' && $payment->pos_paid) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("change") . ': ' . ($payment->pos_balance > 0 ? $this->tec->formatMoney($payment->pos_balance) : 0) . '</td>';
													}
													if ($payment->paid_by == 'cash_card' && $payment->pos_paid) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ':  Cash & Card</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) .'( Cash : '. $inv->cash_amount.' / Card :'. $inv->card_amount.')</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("change") . ': ' . ($payment->pos_balance > 0 ? $this->tec->formatMoney($payment->pos_balance) : 0) . '</td>';
													}
													if ($payment->paid_by == 'cheque_cash' && $payment->pos_paid) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ':  Cheque & Cash </td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) .'( Cheque : '. $inv->cheque_amount.' / Cash :'. $inv->cash_amount.')</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("change") . ': ' . ($payment->pos_balance > 0 ? $this->tec->formatMoney($payment->pos_balance) : 0) . '</td>';
													}
													if ($payment->paid_by == 'card_cheque' && $payment->pos_paid) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ':  Card & Cheque</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) .'( Card : '. $inv->card_amount.' / Cheque :'. $inv->cheque_amount.')</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("change") . ': ' . ($payment->pos_balance > 0 ? $this->tec->formatMoney($payment->pos_balance) : 0) . '</td>';
													}
													if (($payment->paid_by == 'CC' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("no") . ': ' . 'xxxx xxxx xxxx ' . substr($payment->cc_no, -4) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("name") . ': ' . $payment->cc_holder . '</td>';
													}
													if ($payment->paid_by == 'Cheque' || $payment->paid_by == 'cheque' && $payment->cheque_no) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("cheque_no") . ': ' . $payment->cheque_no . '</td>';
													}
													if ($payment->paid_by == 'gift_card' && $payment->pos_paid) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("no") . ': ' . $payment->gc_no . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("balance") . ': ' . ($payment->pos_balance > 0 ? $this->tec->formatMoney($payment->pos_balance) : 0) . '</td>';
													}
													if ($payment->paid_by == 'other' && $payment->amount) {
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("paid_by") . ': ' . lang($payment->paid_by) . '</td>';
															echo '<td style="padding-left:15px;border-top: none !important;">' . lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . '</td>';
															echo $payment->note ? '</tr><td colspan="2">' . lang("payment_note") . ': ' . $payment->note . '</td>' : '';
													}
													echo '</tr>';
											}
											echo '</tbody></table>';
									}

                                ?>

								<?= $inv->note ? '<p style="margin-top:10px; text-align: center;">' . $this->tec->decode_html($inv->note) . '</p>' : ''; ?>
								<?php if (!empty($store->receipt_footer)) { ?>
								<div style="margin-top:20px;">
									<div style="text-align: right;"><?= nl2br($store->receipt_footer); ?></div>
								</div>
								<?php } ?>
							</div>
							<div style="clear:both;"></div>
						</div>

						<!-- start -->
						<div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
							<hr>
							<?php if ($modal) { ?>
							<div class="btn-group btn-group-justified" role="group" aria-label="...">
								<div class="btn-group" role="group">
									<?php
                                    if ( ! $Settings->remote_printing) {
                                        echo '<a href="'.site_url('pos/print_receipt/'.$inv->id.'/0').'" id="print" class="btn btn-block btn-primary">'.lang("print").'</a>';
                                    } elseif ($Settings->remote_printing == 1) {
                                        echo '<button onclick="window.print();" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                    } else {
                                        echo '<button onclick="return printReceipt()" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                    }
                                    ?>
								</div>
								<div class="btn-group" role="group">
									<a class="btn btn-block btn-success" href="#" id="email"><?= lang("email"); ?></a>
								</div>
								<div class="btn-group" role="group">
									<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
								</div>
							</div>
							<?php } else { ?>
							<span class="pull-right col-xs-12">
								<?php
                                if ( ! $Settings->remote_printing) {
                                    echo '<a href="'.site_url('pos/print_receipt/'.$inv->id.'/1').'" id="print" class="btn btn-block btn-primary">'.lang("print").'</a>';
                                    echo '<a href="'.site_url('pos/open_drawer/').'" class="btn btn-block btn-default">'.lang("open_cash_drawer").'</a>';
                                } elseif ($Settings->remote_printing == 1) {
                                    echo '<button onclick="window.print();" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                } else {
                                    echo '<button onclick="return printReceipt()" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                    echo '<button onclick="return openCashDrawer()" class="btn btn-block btn-default">'.lang("open_cash_drawer").'</button>';
                                }
                                ?>
							</span>
							<span class="pull-left col-xs-12"><a class="btn btn-block btn-success" href="#"
									id="email"><?= lang("email"); ?></a></span>
							<span class="col-xs-12">
								<a class="btn btn-block btn-warning" href="<?= site_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
							</span>
							<?php } ?>
							<div style="clear:both;"></div>
						</div>
						<!-- end -->
					</div>
				</div>
				<!-- start -->
				<?php
                if (!$modal) {
                    ?>
				<script type="text/javascript">
				var base_url = '<?=base_url();?>';
				var site_url = '<?=site_url();?>';
				var dateformat = '<?=$Settings->dateformat;?>',
					timeformat = '<?= $Settings->timeformat ?>'; <?php unset($Settings->protocol, $Settings->smtp_host, $Settings->smtp_user, $Settings->smtp_pass,$Settings->smtp_port, $Settings->smtp_crypto, $Settings->mailpath, $Settings->timezone, $Settings->setting_id, $Settings->default_email, $Settings->version, $Settings->stripe, $Settings->stripe_secret_key, $Settings->stripe_publishable_key); ?>
				var Settings = <?= json_encode($Settings); ?> ;
				</script>
				<script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
				<script src="<?= $assets ?>dist/js/libraries.min.js" type="text/javascript"></script>
				<script src="<?= $assets ?>dist/js/scripts.min.js" type="text/javascript"></script>
				<?php
                }
                ?>
				<script type="text/javascript">
				$(document).ready(function() {
					$('#print').click(function(e) {
						e.preventDefault();
						var link = $(this).attr('href');
						$.get(link);
						return false;
					});
					$('#email').click(function() {
						bootbox.prompt({
							title: "<?= lang("email_address "); ?>",
							inputType: 'email',
							value: "<?= $customer->email; ?>",
							callback: function(email) {
								if (email != null) {
									$.ajax({
										type: "post",
										url: "<?= site_url('pos/email_receipt') ?>",
										data: {
											<?= $this->security->get_csrf_token_name(); ?> : "<?= $this->security->get_csrf_hash(); ?>",
											email : email,
											id: <?php echo $inv->id; ?>
										},
										dataType: "json",
										success: function(data) {
											bootbox.alert({
												message: data.msg,
												size: 'small'
											});
										},
										error: function() {
											bootbox.alert({
												message: '<?= lang('ajax_request_failed '); ?>',
												size: 'small'
											});
											return false;
										}
									});
								}
							}
						});
						return false;
					});
				});
				</script>
				<?php /* include FCPATH.'themes'.DIRECTORY_SEPARATOR.$Settings->theme.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'pos'.DIRECTORY_SEPARATOR.'remote_printing.php'; */ ?>
				<?php include 'remote_printing.php'; ?>
				<?php
                if ($modal) {
                    ?>
		</div>
	</div>
</div>
<?php
    } else {
        ?>
<!-- end -->
</body>
<script>
$(document).ready(function(){
	localStorage.spos_discount_all_products = 0
});
</script>
</html>
<?php
}
?>
