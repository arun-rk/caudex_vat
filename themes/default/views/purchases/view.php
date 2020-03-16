<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<div class="modal-dialog" style="width: 930px;">
    <div class="modal-content">
        <div class="modal-header modal-primary">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <button type="button" class="close mr10" onclick="window.print();"><i class="fa fa-print"></i></button>
            <h4 class="modal-title" id="myModalLabel">
                <?= lang('purchase').' # '.$purchase->id; ?>
            </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="col-xs-2"><?= lang('date'); ?></td>
                                    <td class="col-xs-10"><?= $this->tec->hrld($purchase->date); ?></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-2"><?= lang('reference'); ?></td>
                                    <td class="col-xs-10"><?= $purchase->reference; ?></td>
                                </tr>
                                <?php
                                if ($purchase->attachment) {
                                    ?>
                                    <tr>
                                        <td class="col-xs-2"><?= lang('attachment'); ?></td>
                                        <td class="col-xs-10"><a href="<?=base_url('uploads/'.$purchase->attachment);?>"><?= $purchase->attachment; ?></a></td>
                                    </tr>
                                    <?php
                                }
                                if ($purchase->note) {
                                    ?>
                                    <tr>
                                        <td class="col-xs-2"><?= lang('note'); ?></td>
                                        <td class="col-xs-10"><?= $purchase->note; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" style="margin-bottom:0;">
                                <thead>
                                    <tr class="active">
                                        <th><?= lang('product'); ?></th>
										 <th><?= lang('category'); ?></th>
										
                                        <th class="col-xs-2"><?= lang('quantity'); ?></th>
                                        <th class="col-xs-2"><?= lang('unit_cost'); ?></th>
										<th class="col-xs-2"><?= lang('Product Cost'); ?></th>
										<th class="col-xs-2"><?= lang('Product Price'); ?></th>
											<!-- <th class="col-xs-2"><?= lang('CGST'); ?></th>
												<th class="col-xs-2"><?= lang('Tax'); ?></th>
													<th class="col-xs-2"><?= lang('SGST'); ?></th> -->
														<th class="col-xs-2"><?= lang('Vat %'); ?></th>
														<th class="col-xs-2"><?= lang('Tax'); ?></th>
                                        <th class="col-xs-2"><?= lang('subtotal'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($items) {
                                        foreach ($items as $item) {
                                            echo '<tr>';
                                            echo '<td>'.$item->product_name.' ('.$item->product_code.')</td>';
											echo '<td>'.$item->category.'</td>';
                                            echo '<td class="text-center">'.$this->tec->formatQuantity($item->quantity).'</td>';
                                            echo '<td class="text-right">'.$this->tec->formatMoney($item->cost).'</td>';
											  echo '<td class="text-right">'.$this->tec->formatMoney($item->product_cost).'</td>';
											    echo '<td class="text-right">'.$this->tec->formatMoney($item->product_price).'</td>';
												//  echo '<td class="text-right">'.$this->tec->formatMoney($item->cgst).'</td>';
												//   echo '<td class="text-right">'.$this->tec->formatMoney($item->cgst_Tax).'</td>';
												//    echo '<td class="text-right">'.$this->tec->formatMoney($item->sgst).'</td>';
												    echo '<td class="text-right">'.($item->cgst+$item->sgst).'%</td>';
												    echo '<td class="text-right">'.$this->tec->formatMoney($item->cgst_Tax+$item->sgst_Tax).'</td>';
                                            echo '<td class="text-right">'.$this->tec->formatMoney($item->subtotal).'</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <thead>
                                    <tr class="active">
                                        <td><?= lang('total'); ?></td>
                                        <td class="col-xs-1"></td>
                                        <td class="col-xs-1"></td>
										<td class="col-xs-1"></td>
                                        <td class="col-xs-1"></td>
										<td class="col-xs-1"></td>
                                        <td class="col-xs-1"></td>
										<td class="col-xs-1"></td>
                                        <!-- <td class="col-xs-1"></td> -->
										<!-- <td class="col-xs-1"></td> -->
                                        
                                        <td class="col-xs-4 text-right"><?=$this->tec->formatMoney($purchase->grand_total);?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>