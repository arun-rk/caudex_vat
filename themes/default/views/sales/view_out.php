<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
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
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700&display=swap" rel="stylesheet">
</head>
<body>
<style>
	*{
		font-family: 'Roboto', sans-serif;
	}
	
	@page { size:  auto; margin: 0; }
	@media print {

html,
body {
	border: 1px solid white;
	/* height: 99%; */
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
div#buttons span {
    padding: 4px;
    /* left: 12px; */
}
</style>

	<div class="no-print">
		<?php if (@$message) { ?>
		<div class="alert alert-success">
			<button data-dismiss="alert" class="close" type="button">×</button>
			<?= is_array($message) ? print_r($message, true) : $message; ?>
		</div>
		<?php } ?>
	</div>
<style>
	/* 'storename storename storename' */
.maingrid{
	max-width: 300px;
    margin: auto;
	display:grid;
  grid-template-columns: 1fr 1fr 1fr;
	grid-template-areas: 
	'logo logo logo'
	'storename storename storename'
	'hl1 hl1 hl1 '
	'address1 address1 address1 '
	'address2 address2 address2 '
	' pos_vatno pos_vatno pos_vatno'
	' city phone email'
	'hl3  hl3 hl3 '
	'r_header r_header  r_header '
	'hl2 hl2 hl2 '
	'date date inv_no '
	'sales_person sales_person  vat_no '
	'tb_head  tb_head tb_head '
	'tb_body  tb_body tb_body '
	'hl4 hl4 hl4 '
	'total total total '
	'p_dis p_dis p_dis '
	'o_dis o_dis o_dis '
	'vat vat vat '
	'rounding rounding rounding '
	'g_total g_total g_total '
	'paid paid paid '
	'info info info '
	'thanku thanku thanku '
;
}
	.sales_person{
		text-align:left;
	}
 .tbl_head_item {
		font-size: 10px;
		
}
	.tb_head  .tbl_head_item {
    font-weight: 600;
}
	.tb_body_row  .tbl_head_item {
    font-weight: 500;
}
.tb_body_row{
	}
	table{
		
    border-collapse: collapse;
	}
.tb_head{
		border-collapse: collapse;
}
.tb_head .tbl_head_item{
	text-align:center;
}
.imag_tag{
	text-align: center;
	grid-area:logo
}
img{
    width: 64px;
}
.storename{
	font-size: 23px;
    text-align: center;
    font-weight: 500;
    grid-area: storename;
		text-transform: uppercase;
    margin-bottom: 5px;
}
.semi_text {
	/* color: #2d2d2d; */
    font-size: 11px;
    font-weight: 600;
}
.light_text {
    /* color: #444444; */
    font-size: 8.5px;
    font-weight: 400;
    text-transform: uppercase;
}
.align_center{
	text-align:center;
}
/* .align_left{
	text-align:left;
} */
.hl{
	border-bottom: 1px dotted black;
	margin:1px 0;
}
.inv_no,
.vat_no,
.customer
{
	text-align:end;
}
.tin,
.inv_no,
.date,
.sales_person,
.customer,
.address,
.delivery_place,
.vat_no
{
font-size:10px;
}
.total,
.o_dis,
.p_dis,
.vat,
.rounding,
.g_total
 {
	display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 2px 10px;
		font-size: 9px;
		font-weight: 800;
		border-left:none;
		border-right:none;
		/* border: 0.1px dashed; */
}
.total {
    /* padding: 4px 10px;
    margin: 4px 0; */
    font-size: 12px;
    font-weight: 800;
    /* border: 1px dashed; */
}
.g_total {
    /* padding: 4px 10px;
    margin: 4px 0; */
    font-size: 12px;
		font-weight: 800;
    border: 1px dashed;
}
.p_dis {
    padding: 2px 10px;
    font-size: 9px;
    font-weight: 400;
    border: none;
}
.o_dis {
    padding: 2px 10px;
    font-size: 9px;
    font-weight: 400;
    border: none;
}
.vat,.rounding {
    padding: 2px 10px;
    font-size: 9px;
    font-weight: 400;
    border: none;
}
</style>

<div class="maingrid">
<!-- <div style="grid-area:free"></div>
<div style="grid-area:free"></div> -->
	<?php
	if ($store) {
			echo '<div class="imag_tag" ><img   src="'.base_url('uploads/'.$store->logo).'" alt="'.cryptography($store->name).'"></div>';
			echo '<strong class="storename" >'.cryptography($store->name).'</strong>';
			// echo '<div class="hl" style="grid-area:hl1"></div>';
			echo " <div class='align_center' style='grid-area:address1'><span class='light_text' >".cryptography($store->address1)."</span></div>";
			echo " <div class='align_center' style='grid-area:address2'><span  class='light_text'>".cryptography($store->address2)."</span></div>";
			echo " <div class='align_center' style='grid-area:pos_vatno'><span class='light_text'>VAT no :223334091300002 </span></div>";
			// echo " <div class='align_center' style='grid-area:city'><span class='light_text'>".cryptography($store->city)."</span></div>";
			echo " <div class='align_center' style='grid-area:city'><span class='light_text'></span></div>";
			echo " <div class='align_center' style='grid-area:phone'><span class='light_text'>Tel. ".cryptography($store->phone)."</span></div>";
			// echo " <div class='align_center' style='grid-area:email'><span class='light_text'>".cryptography($store->email)."</span></div>";
			echo " <div class='align_center' style='grid-area:r_header'><span class='semi_text'> OUTSTANDING PAYMENT </span></div>";
			//echo '<p><b> INVOICE CASH / CREDIT </b></p>';
	}
	?>
<div class="hl" style="grid-area:hl2"></div>
<div class="hl" style="grid-area:hl3"></div>
<div class="hl" style="grid-area:hl4"></div>
<div class="date tiny_text" style='grid-area:date'> <?= lang("Customer","Customer").': '.$customer->name; ?> </div>
<div class="inv_no tiny_text" style='grid-area:inv_no'><?= lang('date','date').': '.date('d,M yy',strtotime($temp->date)); ?></div>
<div  class="sales_person tiny_text" style='grid-area:sales_person'><?= lang("Mail","Mail").': '. $customer->email; ?></div>
<div class="vat_no" style='grid-area:vat_no'><?= lang("Phone","Phone").': '.  $customer->phone;?></div>

<!-- <div class="tin tiny_text" style='grid-area:tin'><?= lang("TIN","TIN").': '.$Settings->gstin; ?> </div>
<div class="customer tiny_text" style='grid-area:customer'><?= lang("customer","customer").': '. $inv->customer_name; ?></div>
<div class="address tiny_text" style='grid-area:address'><?= lang("Address","Address").': '. $customer->cf1; ?> </div>
<div class="hl" style="grid-area:hl3"></div>
<div class="delivery_place" style='grid-area:delivery_place'><?= lang("Delivery Place","Delivery Place").': '. $inv->hold_ref; ?> </div> -->


<!-- <div class="total" style="grid-area:total">
<span>Payable to customer :</span>
<span><?=$this->tec->formatMoney($total )?></span>
</div> -->
<div class="total" style="grid-area:total">
<span>TOTAL OUTSTANDING:</span>
<span><?=$this->tec->formatMoney($dues)?></span>
</div>
<div class="p_dis" style="grid-area:p_dis">
<span>PAID AMOUNT :</span>
<span><?=$this->tec->formatMoney($temp->total_amount)?></span>
</div>
<div class="o_dis" style="grid-area:o_dis">
<span>PAID BY :</span>
<span><?=($temp->paid_by)?></span>
</div>
<div class="total" style="grid-area:vat">
<span>BALANCE DUE :</span>
<span><?=$this->tec->formatMoney($dues_now)?></span>
</div>
<!--  -->

<style>
.paid {
    display: grid;
    grid-gap: 8px;
    padding: 10px;
    margin-top: 12px;
    border: 1px solid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-areas:
        'paid_text paid_by paid_by'
        'paid_details paid_details paid_details'
        'amount_txt amount amount'
        'due_txt due due'
        'note note note'
        'balance_txt balance balance';
}
.paid_text,
.amount_txt,
.due_txt,
.balance_txt
 {
    font-size: 12px;
    font-weight: 600;
}
.paid_by,
.amount,
.due,
.balance
 {
		font-size: 11px;
		font-weight: 400;
		text-align:end;
}

.note
 {
		font-size: 11px;
		font-weight: 400;
		text-align:start;
}
.thanku {
	text-align:center;
	padding: 19px 0;
}
.thanku span{
	font-size: 17px;
    font-weight: 700;
}
.info *{
	font-size: 11px;
    font-weight: 400;
}
.paid_details{
		text-align:start;		
		font-size: 11px;
		font-weight: 400;
    padding-left: 12px;

}
.paid_details div {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>

<div class="info" style="grid-area:info">
<span>Exchange Policy Terms & Cond.</span>
<ol>
	<li>Any claims must be accompanied by official receipt</li>
	<li>New and unused merchandise can be exchanged within one week on the purchase.</li>
	<li>There Is no exchange or return for used or altered products.</li>
	<li>Items sold during sale and clearance neither, refundable nor exchangeable.</li>
</ol>
<span>Printed Time : <?=date('d-m-yy -g:i:s A')?></span>
<!-- Printed Time : 29-12-2019 -02:15:05 PM</span> -->
</div>

<div class="thanku" style="grid-area:thanku">
<span>--- THANK YOU VISIT AGAIN ---</span>
</div>

</div>	
<div style="
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
"><span>--</span>
</div>
<div id="buttons" style="padding: 7px;text-transform:uppercase;position: fixed;top: 0;background: #ececec;margin: 9px;border-radius: 5px;box-shadow: 0 0 2px #525252;" class="no-print">
<span class="col-xs-12">
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
<span class="col-xs-6"><a class="btn btn-block btn-success" href="#"
		id="email"><?= lang("email"); ?></a></span>
<span class="col-xs-6">
	<a class="btn btn-block btn-warning" href="<?= site_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
</span>

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
				<?php @include 'remote_printing.php'; ?>
				<!-- end -->
</body>
<script>
$(document).ready(function(){
	localStorage.spos_discount_all_products = 0
});
</script>
</html>