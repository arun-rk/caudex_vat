<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $page_title . ' | ' . $Settings->site_name; ?></title>
	<link rel="shortcut icon" href="<?= $assets ?>images/icon.png" />
	<link href="<?= $assets ?>dist/css/styles.css" rel="stylesheet" type="text/css" />
	<link href="<?= $assets ?>dist/css/custom.css" rel="stylesheet" type="text/css" />
	<?= $Settings->rtl ? '<link href="' . $assets . 'dist/css/rtl.css" rel="stylesheet" />' : ''; ?>
	<script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<style>
	div#Invoices {
		padding: 20px;
	}

	.form-control {
		border-radius: 0 !important;
		box-shadow: none;
		border-color: #C5D0DC;
		border-radius: 5px !important;
	}

	.form-group label {
		float: left;
	}

	div#TableType .select2.select2-container {
		margin-top: 10px;
	}

	div#TableType .select2.select2-container {
		margin-top: 10px;
		text-align: left;
	}

	div#TableType label {
		margin-left: 90px;
		margin-top: 15px;
	}

	div#TableType {
		text-align: right;
	}

	div#TableType .select2-selection {

		border-top-right-radius: 5px !important;
		border-bottom-right-radius: 5px !important;
	}
	</style>
	<style type="text/css" media="all">
	body {
		color: #000;
	}

	body {
		color: #000;
	}

	#wrapper {
		max-width: 384px;
		margin: 0 auto;
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
		.no-print {
			display: none;
		}

		#wrapper {
			max-width: 384px;
			margin: 0;
			padding: 0;
		}
	}

	@page {
		max-width: 384px
	}

	table.table.table-striped.table-condensed {
		font-size: 14px;
	}

	@page {
		max-width: 384px
	}

	table {
		border: 2px solid #dddddd;
	}

	table td {
		border: 1px solid #dddddd;
	}
	</style>
</head>

<body class="skin-<?= $Settings->theme_style; ?> sidebar-collapse sidebar-mini pos">
	<div class="wrapper rtl rtl-inv no-print">

		<header class="main-header no-print">
			<a class="logo">
				<?php if ($store) { ?>
				<span class="logo-mini"><?= $store->code; ?></span>
				<span class="logo-lg"><?= $store->name == 'SimplePOS' ? 'Simple<b>POS</b>' : $store->name; ?></span>
				<?php 
              } else { ?>
				<span class="logo-mini">POS</span>
				<span
					class="logo-lg"><?= $Settings->site_name == 'SimplePOS' ? 'Simple<b>POS</b>' : $Settings->site_name; ?></span>
				<?php 
              } ?>
			</a>
			<nav class="navbar navbar-static-top" role="navigation">
				<ul class="nav navbar-nav pull-left">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img
								src="<?= $assets; ?>images/<?= $Settings->selected_language; ?>.png"
								alt="<?= $Settings->selected_language; ?>"></a>
						<ul class="dropdown-menu">
							<?php $scanned_lang_dir = array_map(function ($path) {
                              return basename($path);
                            }, glob(APPPATH . 'language/*', GLOB_ONLYDIR));
                            foreach ($scanned_lang_dir as $entry) { ?>
							<li><a href="<?= site_url('pos/language/' . $entry); ?>"><img
										src="<?= $assets; ?>images/<?= $entry; ?>.png" class="language-img">
									&nbsp;&nbsp;<?= ucwords($entry); ?></a></li>
							<?php 
                          } ?>
						</ul>
					</li>
				</ul>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li><a href="#" class="clock"></a></li>
						<li>
							<a href="#"><?php echo $this->session->userdata('CounterName'); ?></a>
						</li>

						<li>
							<a href="#">Session Start at (<b><?php echo $this->session->userdata('SessionCreatedDate'); ?></b>) </a>
						</li>
						<li style="display:none;"><a href="<?= site_url(); ?>"><i class="fa fa-dashboard"></i></a></li>
						<?php if ($Admin) { ?>
						<li style="display:none;"><a href="<?= site_url('settings'); ?>"><i class="fa fa-cogs"></i></a></li>
						<?php 
                      } ?>
						<?php if ($this->db->dbdriver != 'sqlite3') { ?>
						<li style="display:none;"><a href="<?= site_url('pos/view_bill'); ?>" target="_blank"><i
									class="fa fa-desktop"></i></a></li>
						<?php 
                      } ?>
						<li style="display:none;" class="hidden-xs hidden-sm"><a href="<?= site_url('pos/shortcuts'); ?>"
								data-toggle="ajax"><i class="fa fa-key"></i></a></li>
						<li style="display:none;"><a href="<?= site_url('pos/register_details'); ?>"
								data-toggle="ajax"><?= lang('register_details'); ?></a></li>
						<?php if ($Admin) { ?>
						<li style="display:none;"><a href="<?= site_url('pos/today_sale'); ?>"
								data-toggle="ajax"><?= lang('today_sale'); ?></a></li>
						<?php 
                      } ?>

						<?php if ($suspended_sales) { ?>
						<li style="display:none;" class="dropdown notifications-menu" id="suspended_sales">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell-o"></i>
								<span class="label label-warning"><?= sizeof($suspended_sales); ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">
									<input type="text" autocomplete="off" data-list=".list-suspended-sales" name="filter-suspended-sales"
										id="filter-suspended-sales" class="form-control input-sm kb-text clearfix"
										placeholder="<?= lang('filter_by_reference'); ?>">
								</li>
								<li>
									<ul class="menu">
										<li class="list-suspended-sales">
											<?php
                                            foreach ($suspended_sales as $ss) {
                                              echo '<a href="' . site_url('pos/?hold=' . $ss->id) . '" class="load_suspended">' . $this->tec->hrld($ss->date) . ' (' . $ss->customer_name . ')<br><div class="bold">' . $ss->hold_ref . '</div></a>';
                                            }
                                            ?>
										</li>
									</ul>
								</li>
								<li class="footer"><a href="<?= site_url('sales/opened'); ?>"><?= lang('view_all'); ?></a></li>
							</ul>
						</li>
						<?php 
                      } ?>
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img
									src="<?= base_url('uploads/avatars/thumbs/' . ($this->session->userdata('avatar') ? $this->session->userdata('avatar') : $this->session->userdata('gender') . '.png')) ?>"
									class="user-image" alt="Avatar" />
								<span><?= $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'); ?></span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">
									<img
										src="<?= base_url('uploads/avatars/' . ($this->session->userdata('avatar') ? $this->session->userdata('avatar') : $this->session->userdata('gender') . '.png')) ?>"
										class="img-circle" alt="Avatar" />
									<p>
										<?= $this->session->userdata('email'); ?>
										<small><?= lang('member_since') . ' ' . $this->session->userdata('created_on'); ?></small>
									</p>
								</li>
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>"
											class="btn btn-default btn-flat"><?= lang('profile'); ?></a>
									</div>
									<div class="pull-right">
										<a href="<?= site_url('logout'); ?>"
											class="btn btn-default btn-flat<?= $this->session->userdata('register_id') ? ' sign_out' : ''; ?>"><?= lang('sign_out'); ?></a>
									</div>
								</li>
							</ul>
						</li>
						<li>
							<a href="#" data-toggle="control-sidebar" class="sidebar-icon"><i
									class="fa fa-folder sidebar-icon"></i></a>
						</li>
						<li><a href="<?= site_url('pos/pos_session'); ?>">Close</a></li>
					</ul>
				</div>
			</nav>
		</header>

		<aside class="main-sidebar no-print" style="display:none;">
			<section class="sidebar">
				<ul class="sidebar-menu">
					<li class="mm_welcome"><a href="<?= site_url(); ?>"><i class="fa fa-dashboard"></i>
							<span><?= lang('dashboard'); ?></span></a></li>
					<?php if ($Settings->multi_store && !$this->session->userdata('store_id')) { ?>
					<li class="mm_stores"><a href="<?= site_url('stores'); ?>"><i class="fa fa-building-o"></i>
							<span><?= lang('stores'); ?></span></a></li>
					<?php 
                  } ?>
					<li class="mm_pos"><a href="<?= site_url('pos'); ?>"><i class="fa fa-th"></i>
							<span><?= lang('pos'); ?></span></a></li>

					<?php if ($Admin) { ?>
					<li class="treeview mm_products">
						<a href="#">
							<i class="fa fa-barcode"></i>
							<span><?= lang('products'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="products_index"><a href="<?= site_url('products'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_products'); ?></a></li>
							<li id="products_add"><a href="<?= site_url('products/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_product'); ?></a></li>
							<li id="products_import"><a href="<?= site_url('products/import'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('import_products'); ?></a></li>


						</ul>
					</li>
					<li class="treeview mm_categories">
						<a href="#">
							<i class="fa fa-folder"></i>
							<span><?= lang('categories'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="categories_index"><a href="<?= site_url('categories'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_categories'); ?></a></li>
							<li id="categories_add"><a href="<?= site_url('categories/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_category'); ?></a></li>
							<li id="categories_import"><a href="<?= site_url('categories/import'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('import_categories'); ?></a></li>
							<li class="divider"></li>
							<li id="categories_index"><a href="<?= site_url('departments'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('List Department'); ?></a></li>
							<li id="categories_add"><a href="<?= site_url('departments/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('Add Department'); ?></a></li>
							<li class="divider"></li>
							<li id="categories_index"><a href="<?= site_url('tables'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('List Tables'); ?></a></li>
							<li id="categories_add"><a href="<?= site_url('tables/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('Add Table'); ?></a></li>
						</ul>
					</li>
					<?php if ($this->session->userdata('store_id')) { ?>
					<li class="treeview mm_sales">
						<a href="#">
							<i class="fa fa-shopping-cart"></i>
							<span><?= lang('sales'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="sales_index"><a href="<?= site_url('sales'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_sales'); ?></a></li>
							<li id="sales_opened"><a href="<?= site_url('sales/opened'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_opened_bills'); ?></a></li>
							<li id="sales_return_index"><a href="<?= site_url('sales/returns'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('List Sales Returns'); ?></a></li>
						</ul>
					</li>
					<li class="treeview mm_purchases">
						<a href="#">
							<i class="fa fa-plus"></i>
							<span><?= lang('purchases'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="purchases_index"><a href="<?= site_url('purchases'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_purchases'); ?></a></li>
							<li id="purchases_add"><a href="<?= site_url('purchases/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_purchase'); ?></a></li>
							<li class="divider"></li>
							<li id="purchases_expenses"><a href="<?= site_url('purchases/expenses'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('list_expenses'); ?></a></li>
							<li id="purchases_add_expense"><a href="<?= site_url('purchases/add_expense'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('add_expense'); ?></a></li>
						</ul>
					</li>
					<?php 
                  } ?>
					<li class="treeview mm_gift_cards">
						<a href="#">
							<i class="fa fa-credit-card"></i>
							<span><?= lang('gift_cards'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="gift_cards_index"><a href="<?= site_url('gift_cards'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_gift_cards'); ?></a></li>
							<li id="gift_cards_add"><a href="<?= site_url('gift_cards/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_gift_card'); ?></a></li>
						</ul>
					</li>

					<li class="treeview mm_auth mm_customers mm_suppliers">
						<a href="#">
							<i class="fa fa-users"></i>
							<span><?= lang('people'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="auth_users"><a href="<?= site_url('users'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_users'); ?></a></li>
							<li id="auth_add"><a href="<?= site_url('users/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_user'); ?></a></li>
							<li class="divider"></li>
							<li id="customers_index"><a href="<?= site_url('customers'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_customers'); ?></a></li>
							<li id="customers_add"><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_customer'); ?></a></li>
							<li class="divider"></li>
							<li id="suppliers_index"><a href="<?= site_url('suppliers'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_suppliers'); ?></a></li>
							<li id="suppliers_add"><a href="<?= site_url('suppliers/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_supplier'); ?></a></li>
						</ul>
					</li>

					<li class="treeview mm_settings">
						<a href="#">
							<i class="fa fa-cogs"></i>
							<span><?= lang('settings'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="settings_index"><a href="<?= site_url('settings'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('settings'); ?></a></li>
							<li class="divider"></li>

							<?php if ($Settings->multi_store) { ?>
							<li id="settings_stores"><a href="<?= site_url('settings/stores'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('stores'); ?></a></li>
							<li id="settings_add_store"><a href="<?= site_url('settings/add_store'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('add_store'); ?></a></li>
							<li class="divider"></li>
							<?php 
                          } ?>
							<li id="settings_printers"><a href="<?= site_url('settings/printers'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('printers'); ?></a></li>
							<li id="settings_add_printer"><a href="<?= site_url('settings/add_printer'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('add_printer'); ?></a></li>
							<li class="divider"></li>
							<?php if ($this->db->dbdriver != 'sqlite3') { ?>
							<li id="settings_backups"><a href="<?= site_url('settings/backups'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('backups'); ?></a></li>
							<?php 
                          } ?>
							<!-- <li id="settings_updates"><a href="<?= site_url('settings/updates'); ?>"><i class="fa fa-circle-o"></i> <?= lang('updates'); ?></a></li> -->
						</ul>
					</li>
					<li class="treeview mm_reports">
						<a href="#">
							<i class="fa fa-bar-chart-o"></i>
							<span><?= lang('reports'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="reports_gst"><a href="<?= site_url('reports/gst'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('GST'); ?></a></li>
							<li id="reports_daily_sales"><a href="<?= site_url('reports/daily_sales'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('daily_sales'); ?></a></li>
							<li id="reports_monthly_sales"><a href="<?= site_url('reports/monthly_sales'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('monthly_sales'); ?></a></li>
							<li id="reports_index"><a href="<?= site_url('reports'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('sales_report'); ?></a></li>
							<li class="divider"></li>
							<li id="reports_payments"><a href="<?= site_url('reports/payments'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('payments_report'); ?></a></li>
							<li class="divider"></li>
							<li id="reports_registers"><a href="<?= site_url('reports/registers'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('registers_report'); ?></a></li>
							<li class="divider"></li>
							<li id="reports_top_products"><a href="<?= site_url('reports/top_products'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('top_products'); ?></a></li>
							<li id="reports_products"><a href="<?= site_url('reports/products'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('products_report'); ?></a></li>
						</ul>
					</li>
					<?php 
                  } else { ?>
					<li class="mm_products"><a href="<?= site_url('products'); ?>"><i class="fa fa-barcode"></i>
							<span><?= lang('products'); ?></span></a></li>
					<li class="mm_categories"><a href="<?= site_url('categories'); ?>"><i class="fa fa-folder-open"></i>
							<span><?= lang('categories'); ?></span></a></li>
					<?php if ($this->session->userdata('store_id')) { ?>
					<li class="treeview mm_sales">
						<a href="#">
							<i class="fa fa-shopping-cart"></i>
							<span><?= lang('sales'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="sales_index"><a href="<?= site_url('sales'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_sales'); ?></a></li>
							<li id="sales_opened"><a href="<?= site_url('sales/opened'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_opened_bills'); ?></a></li>
						</ul>
					</li>
					<li class="treeview mm_purchases">
						<a href="#">
							<i class="fa fa-plus"></i>
							<span><?= lang('expenses'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="purchases_expenses"><a href="<?= site_url('purchases/expenses'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('list_expenses'); ?></a></li>
							<li id="purchases_add_expense"><a href="<?= site_url('purchases/add_expense'); ?>"><i
										class="fa fa-circle-o"></i> <?= lang('add_expense'); ?></a></li>
						</ul>
					</li>
					<?php 
                  } ?>
					<li class="treeview mm_gift_cards">
						<a href="#">
							<i class="fa fa-credit-card"></i>
							<span><?= lang('gift_cards'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="gift_cards_index"><a href="<?= site_url('gift_cards'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_gift_cards'); ?></a></li>
							<li id="gift_cards_add"><a href="<?= site_url('gift_cards/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_gift_card'); ?></a></li>
						</ul>
					</li>
					<li class="treeview mm_customers">
						<a href="#">
							<i class="fa fa-users"></i>
							<span><?= lang('customers'); ?></span>
							<i class="fa fa-angle-left pull-right"></i>
						</a>
						<ul class="treeview-menu">
							<li id="customers_index"><a href="<?= site_url('customers'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('list_customers'); ?></a></li>
							<li id="customers_add"><a href="<?= site_url('customers/add'); ?>"><i class="fa fa-circle-o"></i>
									<?= lang('add_customer'); ?></a></li>
						</ul>
					</li>
					<?php 
                  } ?>
				</ul>
			</section>
		</aside>

		<div class="content-wrapper no-print" style="    margin-left: 0 !important;">

			<div class="col-lg-12 alerts no-print">
				<?php if ($error) { ?>
				<div class="alert alert-danger alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<h4><i class="icon fa fa-ban"></i> <?= lang('error'); ?></h4>
					<?= $error; ?>
				</div>
				<?php 
              }
              if ($message) { ?>
				<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<h4><i class="icon fa fa-check"></i> <?= lang('Success'); ?></h4>
					<?= $message; ?>
				</div>
				<?php 
              } ?>
			</div>
			<div class="col-sm-12">
				<div class="tabbable ">
					<ul class="nav nav-tabs no-print" id="myTab">
						<li class="active" id="Order_LI">
							<a data-toggle="tab" href="#Order">

								Orders
							</a>
						</li>

						<li id="Current_LI">
							<a data-toggle="tab" href="#Current">
								Current

							</a>
						</li>
						<li id="Booking_LI">
							<a data-toggle="tab" href="#Book">
								Booking

							</a>
						</li>
						<li id="Customer_LI">
							<a data-toggle="tab" onclick="LoadCustomerOrders();" href="#Customer">
								Customer Order

							</a>
						</li>
						<li id="Draft_LI">
							<a data-toggle="tab" href="#Draft">
								Draft

							</a>
						</li>
						<li id="Invoices_LI">
							<a onclick="LoadInvoice();" data-toggle="tab" href="#Invoices">
								Invoices

							</a>
						</li>


					</ul>

					<div class="tab-content ">
						<div id="Order" class="tab-pane fade in active">
							<table style="width:100%;" class="layout-table">
								<tr>
									<td style="width: 682px;">


										<div id="pos">
											<?= form_open('pos/pos_screen', 'id="pos-sale-form" novalidate'); ?>
											<div class="OrderTab" id="leftdiv">
												<div id="lefttop" style="margin-bottom:5px;">

												</div>
												<div id="printhead" class="print">
													<?= $Settings->header; ?>
													<p><?= lang('date'); ?>: <?= date($Settings->dateformat) ?></p>
												</div>

												<div id="botbuttons" class="col-xs-6 text-center">
													<div class="span3 row-fluid dine-legend">
														<div class="span4 text-center">
															<span id="Available" class="badge badge-success">0</span> <span
																class="dineInLegendName">Available</span>
														</div>
														<div class="span4 text-center">
															<span id="Occupied" class="badge ">0</span> <span class="dineInLegendName">Occupied</span>
														</div>
														<div class="span4 text-center">
															<span id="DoneSoon" class="badge badge-warning">0</span> <span
																class="dineInLegendName">Done Soon</span>
														</div>
													</div>
													<div class="col-xs-6 col-lg-6">
														<div class="form-group" id="TableType">
															<?= lang('Type', 'Type'); ?>
															<?php $tm = array(1 => lang('Outdoor'), 0 => lang('Indoor')); ?>
															<?= form_dropdown('type', $tm, set_value('type'), 'class="form-control tip select2" id="type"  required="required" style="width:50%;margin-top:50px;"'); ?>
														</div>
													</div>
													<div class="row" id="TablesList">

													</div>

												</div>
												<div id="botbuttons" class="col-xs-6 text-center">
													<div class="row" id="OrderList">

													</div>

												</div>
												<div class="clearfix"></div>

											</div>
											<?= form_close(); ?>
										</div>

									</td>

								</tr>
							</table>


						</div>

						<div id="Current" class="tab-pane fade">

							<?= form_open('pos/pos_screen', 'id="pos-sale-form"'); ?>
							<table style="width:100%;" class="layout-table" id="Step_1">
								<tr>
									<td style="width: 699px;">
										<div id="Pos_Button" class="col-xs-12 text-center">
											<div class="row" style="padding-left: 0px;padding-right: 3px;margin-top: 16px;margin-bottom: 0;">
												<div class="col-xs-3" style="padding: 0 5px;">
													<div class="btn-group-vertical btn-block">
														<button type="button" class="btn bg-purple btn-block btn-flat" id="Beverage_print_order"><i
																class="fa fa-print" aria-hidden="true"></i> <?= lang('Beverage') ?></button>

													</div>

												</div>
												<div class="col-xs-3" style="padding: 0 5px;">

													<div class="btn-group-vertical btn-block">
														<button type="button" class="btn bg-purple btn-block btn-flat" id="Food_print_order"><i
																class="fa fa-print" aria-hidden="true"></i> <?= lang('Food') ?></button>

													</div>
												</div>
												<div class="col-xs-2" style="padding: 0 5px;">
													<div class="btn-group-vertical btn-block">

														<button type="button" class="btn bg-navy btn-block btn-flat" id="print_bill"><i
																class="fa fa-print" aria-hidden="true"></i> <?= lang('Bill') ?></button>
													</div>
												</div>
												<div class="col-xs-2" style="padding: 0 5px;">
													<div class="btn-group-vertical btn-block">
														<button style="display:none;" type="button" class="btn btn-warning btn-block btn-flat"
															id="suspend"><?= lang('hold'); ?></button>
														<button type="button" class="btn btn-danger btn-block btn-flat" id="reset"><i
																class="fa fa-ban" aria-hidden="true"></i> <?= lang('Cancel') ?></button>


													</div>

												</div>
												<div class="col-xs-2" style="padding: 0 5px;">
													<div class="btn-group-vertical btn-block">

														<button type="button" class="btn btn-success btn-block btn-flat pos-settle"
															id="<?= $eid ? 'submit-sale' : 'payment'; ?>"> <i class="fa fa-file-text-o"
																aria-hidden="true"></i> <?= lang('Pay') ?></button>
														<button type="button" class="btn btn-success btn-block btn-flat pos-order"
															id="OrderKitchen"> <i class="fa fa-shopping-cart" aria-hidden="true"></i>
															<?= lang('Order') ?></button>
														<input type="hidden" value="0" id="OrderStatus" name="OrderStatus" />

													</div>

												</div>


											</div>

										</div>

										<div id="pos">

											<div id="leftdiv">
												<div id="lefttop" style="margin-bottom:5px;">
													<div class="row">
														<div class="col-xs-8" style="    padding-right: 0 !important;">
															<div class="form-group" style="margin-bottom:5px;">
																<div class="input-group">
																	<?php foreach ($customers as $customer) {
                                                                      $cus[$customer->id] = $customer->name;
                                                                    } ?>
																	<?= form_dropdown('customer_id', $cus, set_value('customer_id', $Settings->default_customer), 'id="spos_customer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control select2" style="width:100%;position:absolute;"'); ?>
																	<div class="input-group-addon no-print" style="padding: 2px 5px;">
																		<a href="#" id="add-customer" class="external" data-toggle="modal"
																			data-target="#myModal"><i class="fa fa-2x fa-plus-circle" id="addIcon"></i></a>
																	</div>
																</div>
																<div style="clear:both;"></div>
															</div>

														</div>
														<div class="col-xs-4">
															<div class="form-group" style="margin-bottom:5px;">
																<input type="text" name="orderid" value="" id="orderid" class="form-control kb-pad"
																	placeholder="<?= lang('Order Id') ?>" />
															</div>

														</div>
														<div class="col-xs-1" style="display:none;">
															<a role="button" data-toggle="modal" data-target="#notesaleModal">
																<i class="fa fa-comment"></i>
															</a>

														</div>

													</div>
													<?php if ($eid && $Admin) { ?>
													<div class="form-group" style="margin-bottom:5px;">
														<?= form_input('date', set_value('date', $sale->date), 'id="date" required="required" class="form-control"'); ?>
													</div>
													<?php 
                                                  } ?>
													<div class="form-group" style="margin-bottom:5px;">
														<input type="text" name="gstno" value="" id="gstno" class="form-control kb-text"
															placeholder="<?= lang('GST No') ?>" />
													</div>
													<div class="form-group" style="margin-bottom:5px;">
														<input type="text" name="code" id="add_item" class="form-control"
															placeholder="<?= lang('search__scan') ?>" />
													</div>

												</div>
												<div id="printhead" class="print">
													<?= $Settings->header; ?>
													<p><?= lang('date'); ?>: <?= date($Settings->dateformat) ?></p>
												</div>
												<div id="print" class="fixed-table-container">

													<div id="list-table-div">
														<div class="fixed-table-header" style="border: 1px solid #d2d6de">
															<table class="table table-striped table-condensed table-hover list-table"
																style="margin:0;">
																<thead>
																	<tr style="background:#fff;     font-size: 13px;   ">

																		<th style="width:30%;text-align:center;">Product</th>
																		<th style="width: 15%;text-align:center;">Price</th>
																		<th style="width: 10%;text-align:center;">Qty</th>
																		<th style="width: 10%;text-align:center;"><?= lang('CGST %') ?></th>
																		<th style="width: 10%;text-align:center;"><?= lang('Tax') ?></th>
																		<th style="width: 10%;text-align:center;"><?= lang('SGST %') ?></th>
																		<th style="width: 10%;text-align:center;"><?= lang('Tax') ?></th>
																		<th style="width: 15%;text-align:center;">Total</th>
																		<th style="width: 5%;" class="satu"> </th>

																	</tr>
																</thead>
															</table>
														</div>
														<table id="posTable" class="table table-striped table-condensed table-hover list-table"
															style="margin:0px;" data-height="100">
															<thead>
																<tr class="success">
																	<th style="width:30%;text-align:center;">Product</th>
																	<th style="width: 15%;text-align:center;">Price</th>
																	<th style="width: 10%;text-align:center;">Qty</th>
																	<th style="width: 10%;text-align:center;"><?= lang('CGST %') ?></th>
																	<th style="width: 10%;text-align:center;"><?= lang('Tax') ?></th>
																	<th style="width: 10%;text-align:center;"><?= lang('SGST %') ?></th>
																	<th style="width: 10%;text-align:center;"><?= lang('Tax') ?></th>
																	<th style="width: 15%;text-align:center;">Total</th>
																	<th style="width: 5%;" class="satu"> </th>
																</tr>
															</thead>
															<tbody></tbody>
														</table>
													</div>
													<div style="clear:both;"></div>

													<div id="totaldiv">
														<table id="totaltbl" class="table table-condensed totals" style="margin-bottom:10px;">
															<tbody>
																<tr style="background:#fff;">
																	<td width="25%;font-weight:bold;"><?= lang('total_items') ?></td>
																	<td class="text-right" style="padding-right:10px;"><span id="count">0</span></td>
																	<td width="25%"><?= lang('total') ?></td>
																	<td class="text-right" colspan="3"><span id="total">0</span></td>
																</tr>
																<tr style="background:#fff;">
																	<td width="25%"><a href="#" id="add_discount"><?= lang('discount') ?></a></td>
																	<td class="text-right" style="padding-right:10px;"><span id="ds_con"></span></td>
																	<td width="10%"><?= lang('CGST') ?></td>
																	<td class="text-right"><span id="CGST">0</span></td>
																	<td width="10%"><?= lang('SGST') ?></td>
																	<td class="text-right"><span id="SGST">0</span></td>
																</tr>
																<tr style="background:#fff">
																	<td colspan="2" style="font-weight:bold;">
																		<?= lang('total_payable') ?>
																		<a role="button" data-toggle="modal" data-target="#noteModal">
																			<i class="fa fa-comment"></i>
																		</a>
																	</td>
																	<td class="text-right" colspan="4" style="font-weight:bold;"><span
																			id="total-payable">0</span></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>

												<div class="clearfix"></div>
												<span id="hidesuspend"></span>
												<span id="hideOrderKitchen"></span>
												<input type="hidden" name="spos_note" value="" id="spos_note">

												<div id="payment-con">
													<input type="hidden" name="amount" id="amount_val" value="<?= $eid ? $sale->paid : ''; ?>" />
													<input type="hidden" name="common_tag" id="common_tag" value="NONE" />
													<input type="hidden" name="balance_amount" id="balance_val" value="" />
													<input type="hidden" name="paid_by" id="paid_by_val" value="cash" />
													<input type="hidden" name="cc_no" id="cc_no_val" value="" />
													<input type="hidden" name="paying_gift_card_no" id="paying_gift_card_no_val" value="" />
													<input type="hidden" name="cc_holder" id="cc_holder_val" value="" />
													<input type="hidden" name="cheque_no" id="cheque_no_val" value="" />
													<input type="hidden" name="cc_month" id="cc_month_val" value="" />
													<input type="hidden" name="cc_year" id="cc_year_val" value="" />
													<input type="hidden" name="cc_type" id="cc_type_val" value="" />
													<input type="hidden" name="cc_cvv2" id="cc_cvv2_val" value="" />
													<input type="hidden" name="balance" id="balance_val" value="" />
													<input type="hidden" name="hd_is_del" id="hd_is_del_val" value="0" />
													<input type="hidden" name="hd_sale_type" id="hd_sale_type_val" value="Immediate" />
													<input type="hidden" name="payment_note" id="payment_note_val" value="" />
												</div>
												<input type="hidden" name="customer" id="customer" value="<?= $Settings->default_customer ?>" />
												<input type="hidden" name="order_tax" id="tax_val" value="" />
												<input type="hidden" name="order_discount" id="discount_val" value="" />
												<input type="hidden" name="count" id="total_item" value="" />
												<input type="hidden" name="Last_Pay" id="Last_Pay" value="" />
												<input type="hidden" name="cancel" id="cancel" value="" />
												<input type="hidden" name="did" id="is_delete" value="<?= $sid; ?>" />
												<input type="hidden" name="eid" id="is_delete" value="<?= $eid; ?>" />
												<input type="hidden" name="total_items" id="total_items" value="0" />
												<input type="hidden" name="table_id" id="table_id" value="0" />
												<input type="hidden" name="total_quantity" id="total_quantity" value="0" />
												<input type="submit" id="submit" value="Submit Sale" style="display: none;" />
											</div>

										</div>

									</td>
									<td>

										<div class="contents" id="right-col">
											<div id="category-sidebar-menu" style=margin-left: 10px;height: 100px;width: 800px;margin-left:
												auto;margin-right: auto;">
												<ul class="control-sidebar-menu">
													<?php
                                                    foreach ($categories as $category) {
                                                      echo '<li class="C_List"><a href="#" class="category' . ($category->id == $Settings->default_category ? ' active' : '') . '" id="' . $category->id . '">';
                                                      if ($category->image) {
                                                        echo '<div class="menu-icon"><img src="' . base_url('uploads/thumbs/' . $category->image) . '" alt="" class="img-thumbnail img-responsive"></div>';
                                                      } else {
                                                        echo '<i class="menu-icon fa fa-folder-open bg-red"></i>';
                                                      }
                                                      echo '<div class="menu-info"><h4 class="control-sidebar-subheading">' . $category->code . '</h4><p>' . $category->name . '</p></div>
										</a></li>';
                                                    }
                                                    ?>
												</ul>
											</div>
											<div id="item-list">
												<div class="form-group" style="padding: 6px;    margin-bottom: 0px;">
													<input type="text" name="code" id="add_item_search" class="form-control"
														placeholder="<?= lang('search items') ?>" />
												</div>
												<div class="items">

													<?php echo $products; ?>
												</div>
											</div>
											<div class="product-nav">
												<div class="btn-group btn-group-justified">
													<div class="btn-group">
														<button style="z-index:10002;" class="btn btn-warning pos-tip btn-flat" type="button"
															id="previous"><i class="fa fa-chevron-left"></i></button>
													</div>
													<div class="btn-group">
														<button style="z-index:10003;" class="btn btn-success pos-tip btn-flat" type="button"
															id="sellGiftCard"><i class="fa fa-credit-card" id="addIcon"></i>
															<?= lang('sell_gift_card') ?></button>
													</div>
													<div class="btn-group">
														<button style="z-index:10004;" class="btn btn-warning pos-tip btn-flat" type="button"
															id="next"><i class="fa fa-chevron-right"></i></button>
													</div>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<table style="width:100%;display:none;" class="layout-table" id="Step_2">
								<tr>
									<td style="width: 682px;    padding: 15px;">

										<div class="modal-content">
											<div class="modal-header" style="padding: 5px;padding-left: 17px;">

												<h4 class="modal-title" id="payModalLabel" style="font-size: 15px;font-weight: bold;">
													<?= lang('payment') ?>
												</h4>
											</div>
											<div class="modal-body">
												<div class="row">
													<div class="col-xs-9">
														<label style="margin-right: 13px;    font-weight: normal;">
															<input checked="checked" id="radioImm" name="saleType" type="radio" value="immediate"
																class="ng-untouched ng-pristine ng-valid saleType">
															Immediate Sale </label>
														<label style="font-weight: normal;">
															<input id="radioBoo" name="saleType" type="radio" value="booking"
																class="ng-untouched ng-pristine ng-valid saleType">
															Booking </label>
													</div>
													<div class="col-xs-9">

														<div class="font16">
															<table class="table table-bordered table-condensed" style="margin-bottom: 0;">
																<tbody>
																	<tr>

																	</tr>
																	<tr>
																		<td width="20%"
																			style="border-right-color:     font-size: 14px;#FFF !important;font-weight: normal;">
																			<?= lang("total_items"); ?></td>
																		<td width="30%" class="text-right" style="    font-size: 14px;"><span
																				id="item_count">0.00</span></td>
																		<td width="25%"
																			style="    font-size: 14px;border-right-color: #FFF !important;font-weight: normal;">
																			<?= lang("total_payable"); ?></td>
																		<td width="25%" class="text-right" style="    font-size: 14px;"><span
																				id="twt">0.00</span></td>
																	</tr>
																	<tr>
																		<td
																			style="    font-size: 14px;border-right-color: #FFF !important;font-weight: normal;">
																			<?= lang("total_paying"); ?></td>
																		<td class="text-right" style="    font-size: 14px;"><span
																				id="total_paying">0.00</span></td>
																		<td
																			style="    font-size: 14px;border-right-color: #FFF !important;font-weight: normal;">
																			<?= lang("balance"); ?></td>
																		<td class="text-right" style="    font-size: 14px;"><span id="balance">0.00</span>
																		</td>
																	</tr>
																	<tr>
																		<td
																			style="font-size: 14px;border-right-color: #FFF !important;font-weight: normal;">
																			<?= lang("Discount"); ?></td>
																		<td class="text-right" style="    font-size: 14px;"><span
																				id="total_Discount">0.00</span></td>
																		<td
																			style="    font-size: 14px;border-right-color: #FFF !important;font-weight: normal;">
																			<?= lang("Tax"); ?></td>
																		<td class="text-right" style="    font-size: 14px;"><span id="total_Tax">0.00</span>
																		</td>
																	</tr>
																</tbody>
															</table>
															<div class="clearfix"></div>
														</div>
														<div class="row booking">
															<div class="col-xs-12">
																<div class="form-group">
																	<?= lang('Delivery Date & Time'); ?>
																	<input name="deliveryDate" type="text" id="deliveryDate"
																		class="form-control datetimepicker" />

																</div>
															</div>
														</div>


														<div class="row">
															<div class="col-xs-12">
																<div class="form-group">
																	<?= lang('note'); ?>
																	<textarea name="note" id="note" class="pa form-control kb-text"></textarea>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-xs-6">
																<div class="form-group">
																	<?= lang("amount"); ?>
																	<input name="amount" type="text" id="amount" class="pa form-control kb-pad amount" />
																</div>
															</div>
															<div class="col-xs-6">
																<div class="form-group">
																	<?= lang("paying_by"); ?>
																	<select id="paid_by" class="form-control paid_by select2" style="width:100%;">
																		<option value="cash"><?= lang("cash"); ?></option>
																		<option value="CC"><?= lang("Debit / Credit Card"); ?></option>
																		<option value="Paytm"><?= lang("Paytm"); ?></option>
																		<option value="Complimentary"><?= lang("Complimentary"); ?></option>
																		<!--<option value="cheque"><?= lang("cheque"); ?></option>
                                        <option value="gift_card"><?= lang("gift_card"); ?></option>
                                        <?= isset($Settings->stripe) ? '<option value="stripe">' . lang("stripe") . '</option>' : ''; ?>
                                        <option value="other"><?= lang("other"); ?></option>-->
																	</select>
																</div>
															</div>
														</div>
														<div class="row immediate">
															<div class="col-xs-12">
																<div class="form-group gc" style="display: none;">
																	<?= lang("gift_card_no", "gift_card_no"); ?>
																	<input type="text" id="gift_card_no"
																		class="pa form-control kb-pad gift_card_no gift_card_input" />

																	<div id="gc_details"></div>
																</div>
																<div class="pcc" style="display:none;">
																	<div class="form-group">
																		<input type="text" id="swipe" class="form-control swipe swipe_input"
																			placeholder="<?= lang('focus_swipe_here') ?>" />
																	</div>
																	<div class="row">
																		<div class="col-xs-6">
																			<div class="form-group">
																				<input type="text" id="pcc_no" class="form-control kb-pad"
																					placeholder="<?= lang('cc_no') ?>" />
																			</div>
																		</div>
																		<div class="col-xs-6">
																			<div class="form-group">

																				<input type="text" id="pcc_holder" class="form-control kb-text"
																					placeholder="<?= lang('cc_holder') ?>" />
																			</div>
																		</div>
																		<div class="col-xs-3">
																			<div class="form-group">
																				<select id="pcc_type" class="form-control pcc_type select2"
																					placeholder="<?= lang('card_type') ?>">
																					<option value="Visa"><?= lang("Visa"); ?></option>
																					<option value="MasterCard"><?= lang("MasterCard"); ?></option>
																					<option value="Amex"><?= lang("Amex"); ?></option>
																					<option value="Discover"><?= lang("Discover"); ?></option>
																				</select>
																			</div>
																		</div>
																		<div class="col-xs-3">
																			<div class="form-group">
																				<input type="text" id="pcc_month" class="form-control kb-pad"
																					placeholder="<?= lang('month') ?>" />
																			</div>
																		</div>
																		<div class="col-xs-3">
																			<div class="form-group">

																				<input type="text" id="pcc_year" class="form-control kb-pad"
																					placeholder="<?= lang('year') ?>" />
																			</div>
																		</div>
																		<div class="col-xs-3">
																			<div class="form-group">

																				<input type="text" id="pcc_cvv2" class="form-control kb-pad"
																					placeholder="<?= lang('cvv2') ?>" />
																			</div>
																		</div>
																	</div>
																</div>
																<div class="pcheque" style="display:none;">
																	<div class="form-group"><?= lang("cheque_no", "cheque_no"); ?>
																		<input type="text" id="cheque_no" class="form-control cheque_no kb-text" />
																	</div>
																</div>
																<div class="pcash">
																	<div class="form-group"><?= lang("payment_note"); ?>
																		<input type="text" id="payment_note" class="form-control payment_note kb-text" />
																	</div>

																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-xs-12">
																<div class="form-group">

																	}
																	<label><input id="isDoorDelivery" name="isDoorDelivery" type="checkbox"
																			class="ng-valid ng-dirty ng-touched"> <?= lang(" Is Door Delivery?"); ?>
																	</label>
																</div>
															</div>

														</div>
														<button style="float: left;width: 20%;" type="button"
															class="btn btn-danger btn-block btn-flat" id="GoBack">Go Back</button>
														<button type="button" class="btn btn-success btn-block btn-flat"
															style=" margin-top: 0px; display: block;float: left;width: 20%;margin-bottom:10px;margin-left: 15px;"
															id="submit-sale">Payment</button>




													</div>
													<div class="col-xs-3 text-center">
														<!-- <span style="font-size: 1.2em; font-weight: bold;"><?= lang('quick_cash'); ?></span> -->

														<div class="btn-group btn-group-vertical" style="width:100%;">
															<button type="button" class="btn btn-info btn-block quick-cash" id="quick-payable">0.00
															</button>
															<?php
                                                            foreach (lang('quick_cash_notes') as $cash_note_amount) {
                                                              echo '<button type="button" class="btn btn-block btn-warning quick-cash">' . $cash_note_amount . '</button>';
                                                            }
                                                            ?>
															<button type="button" class="btn btn-block btn-danger"
																id="clear-cash-notes"><?= lang('clear'); ?></button>
														</div>

													</div>
												</div>
											</div>

										</div>

									</td>
									<td style="width: 682px;    padding: 15px;">
										<div>

											<div class="modal-content">
												<div class="modal-header" style="padding: 5px;padding-left: 17px;">

													<h4 class="modal-title" id="cModalLabel" style="font-size: 15px;font-weight: bold;">
														<?= lang('Customer') ?>
													</h4>
												</div>


												<div class="modal-body">
													<div id="c-alert" class="alert alert-danger" style="display:none;"></div>
													<input type="hidden" value="0" id="hdnCustId" />
													<div class="row">
														<div class="col-xs-12">
															<div class="form-group">
																<label class="control-label" for="phone" style="font-weight: normal;">
																	<?= lang("phone"); ?>
																</label>
																<input type="text" name="phone_p" id="cphone_p" class="form-control"
																	placeholder="<?= lang('search customer by phone number') ?>" />

															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-6">

															<div class="form-group">
																<label class="control-label" for="code" style="font-weight: normal;">
																	<?= lang("name"); ?>
																</label>
																<?= form_input('name_p', '', 'class="form-control input-sm kb-text"  id="cname_p"'); ?>
															</div>
														</div>
														<div class="col-xs-6">

															<div class="form-group">
																<label class="control-label" for="cemail" style="font-weight: normal;">
																	<?= lang("email_address"); ?>
																</label>
																<?= form_input('email_p', '', 'class="form-control input-sm kb-text"  id="cemail_p"'); ?>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-6">
															<div class="form-group">
																<label class="control-label" for="cf1" style="font-weight: normal;">
																	<?= lang("Address"); ?>
																</label>
																<?= form_input('cf1_p', '', 'class="form-control input-sm kb-text"  id="cf1_p"'); ?>
															</div>
														</div>
														<div class="col-xs-6">
															<div class="form-group">
																<label class="control-label" for="cf2" style="font-weight: normal;">
																	<?= lang("cf2"); ?>
																</label>
																<?= form_input('cf2_p', '', 'class="form-control input-sm kb-text"  id="cf2_p"'); ?>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-xs-12">
															<div class="form-group">
																<label class="control-label" for="cf1" style="font-weight: normal;">
																	<?= lang("Shipping Address"); ?>
																</label>
																<?= form_input('shipping_address', '', 'class="form-control input-sm kb-text"  id="shipping_address"'); ?>
															</div>
														</div>

													</div>
													<div class="row">
														<div class="col-xs-6">
															<div class="form-group">
																<label class="control-label" for="cf1" style="font-weight: normal;">
																	<?= lang("City"); ?>
																</label>
																<?= form_input('city', '', 'class="form-control input-sm kb-text"  id="city"'); ?>
															</div>
														</div>




														<div class="col-xs-6">
															<div class="form-group">
																<label class="control-label" for="cf1" style="font-weight: normal;">
																	<?= lang("Pin Code"); ?>
																</label>
																<?= form_input('pin_code', '', 'class="form-control input-sm kb-text"  id="pin_code"'); ?>
															</div>
														</div>



													</div>

												</div>


											</div>
									</td>
								</tr>


							</table>

							<?= form_close(); ?>
						</div>

						<!-----------------------------------start booking---------------------------------------->
						<div id="Book" class="tab-pane fade" style="padding: 15px;">
							<section class="content" style="text-align:center;">
								<div class="row">
									<div class="col-sm-12">
										<div class="tabbable">
											<ul class="nav nav-tabs" id="myTab">
												<li class="active" id="nbook">
													<a data-toggle="tab" href="#newbook">

														New Booking
													</a>
												</li>

												<li id="lbook">
													<a data-toggle="tab" href="#listbook">
														List Booking

													</a>
												</li>
											</ul>

											<div class="tab-content" style="height: auto;">
												<div id="newbook" class="tab-pane fade in active">
													<div class="row">
														<div class="col-xs-12">
															<div class="box box-primary">
																<div class="box-header">
																	<h3 class="box-title"><?= lang('enter_info'); ?></h3>
																</div>
																<div class="box-body">

																	<div class="col-md-6">
																		<?= form_open_multipart("pos/add_booking"); ?>
																		<div class="form-group">
																			<?= lang("Delivery_Date & Time", "delivery_datetime"); ?>
																			<input name="delivery_datetime" id="delivery_datetime" value=""
																				class="form-control datetimepicker" required="required" />
																		</div>
																		<div class="form-group">
																			<?= lang("Content", "content"); ?>
																			<input name="content" type="text" id="content" value=""
																				class="pa form-control kb-pad item" required="required" /></div>
																		<div class="form-group">
																			<?= lang("Product", "cake"); ?>
																			<input type="text" name="code" id="cake" class="form-control"
																				onclick="cakesearch();" placeholder="<?= lang('search__scan') ?>" />
																		</div>

																		<div class="form-group">
																			<?= lang("Quantity", "quantity"); ?>
																			<input name="quantity" type="number" id="quantity" value=""
																				class="pa form-control kb-pad qty" placeholder="in Kg" required="required" />
																		</div>
																		<div class="form-group" style="display:none;">
																			<?= lang("No.Of Cakes", "no_of_cake"); ?>
																			<input name="no_of_cake" type="number" id="no_of_cake" value=""
																				class="pa form-control kb-pad no_of_cake" />
																		</div>




																		<div class="form-group" class="photoedit">
																			<?= lang("Do You Want Photo Edit ?", "is_photoedit"); ?>
																			<input type="checkbox" id="is_photoedit" value="" />
																		</div>
																		<div id="aut1" style="display: none">
																			<div class="form-group">
																				<?= lang("Photo", "photo") ?>
																				<input id="photo" type="file" name="photo" data-show-upload="false"
																					data-show-preview="false" class="form-control file">
																			</div>
																		</div>


																	</div>
																	<div class="col-md-6">
																		<div class="form-group" class="deliver">
																			<?= lang("Do You Want Home Delivery ?", "is_deliver"); ?>
																			<input type="checkbox" id="is_deliver" value="" />
																		</div>
																		<div id="aut2" style="display: none">
																			<div class="form-group">
																				<?= lang("Delivery Address", "deladdress"); ?>
																				<input type="text" name="deladdress" id="deladdress" class="form-control">
																			</div>
																			<div class="form-group">
																				<?= lang("Delivery Cost", "cost"); ?>
																				<input type="number" name="cost" id="cost" class="form-control">
																			</div>
																		</div>
																		<div class="form-group">
																			<?= lang("Discount", "discount"); ?>
																			<input name="discount" type="number" id="discount" value=""
																				class="pa form-control kb-pad discount" placeholder="0" />
																		</div>
																		<div class="form-group">
																			<?= lang("Advance_Payment", "advance"); ?>
																			<input name="advance" type="number" id="advance" value=""
																				class="pa form-control kb-pad adv" required="required" />
																		</div>
																		<div class="form-group" style="display:none;">
																			<?= lang("Balance", "balance"); ?>
																			<input name="balance" type="number" id="balance" value=""
																				class="pa form-control kb-pad bal" />
																		</div>
																		<input type="hidden" name="is_photoedit1" id="is_photoedit1" value="0" />
																		<input type="hidden" name="is_deliver1" id="is_deliver1" value="0" />
																		<input type="hidden" id="prod_id" name="prod_id" />
																		<div class="form-group">
																			<?php echo form_submit('book', lang('BOOK'), 'class="btn btn-primary"'); ?>
																		</div>
																	</div>
																	<?php echo form_close(); ?>
																</div>
																<div class="clearfix"></div>
															</div>
														</div>
													</div>
												</div>

												<div id="listbook" class="tab-pane fade">
													<div class="row">
														<div class="col-xs-12 pad-10" style="text-align:center">
															<div class="row dflex">
																<div class="col-xs-4">
																	<div class="form-group">
																		<!-- <label class="control-label" for="start_date"><?= lang("start_date"); ?></label> -->
																		<input type="text" name="daterange"
																			value="<?php echo (isset($daterange))?$daterange:date('m/d/YYYY').' - '.date('m/d/YYYY'); ?>"
																			class="form-control dateinput" readonly />
																	</div>
																</div>
																<div class="col-xs-4">
																	<button type="button" class="btn btn-primary"
																		id="senddata"><?= lang("submit"); ?></button>
																	<!-- <button type="button" onclick="arprint();" class=" btn btn-primary">Print</button> -->
																	<button type="button" onclick="printDiv('ar_print');"
																		class=" btn btn-primary">Print</button>
																	<!-- <button type="submit" class="btn btn-primary datesearch"><i class="fa fa-chevron-right" aria-hidden="true"></i></button> -->

																</div>
															</div>
															<!-- <form action="" method="post" class="pad-10 row">
                              <input type="text" name="daterange" value="<?php echo (isset($daterange))?$daterange:date('m/d/YYYY').' - '.date('m/d/YYYY'); ?>" class="col-xs-3 dateinput" readonly />
                              <button type="submit"  class="col-xs-3 btn btn-primary  ">Print</button>
                              <button type="button" onclick="printDiv('ar_print');" class="col-xs-3  btn btn-primary">Print</button>
                            </form> -->
														</div>
														<div class="col-xs-12">
															<table>
																<th
																	style="text-align:center; width: 7%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Date/time</th>
																<th
																	style="text-align:center; width: 40%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Content</th>
																<th
																	style="text-align:center; width: 8%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Product</th>
																<th
																	style="text-align:center; width: 20%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	qty</th>
																<th
																	style="text-align:center; width: 20%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Image</th>
																</tr>
																<tbody id="bookinglist1">
																	<tr>
																		<td colspan="5">non data found</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/moment.min.js" type="text/javascript">
						</script>
						<script src="<?= $assets ?>plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
							type="text/javascript"></script>
						<script type="text/javascript">
						$(document).ready(function() {
							$('#is_photoedit').on('ifChecked', function(event) {
								$("#aut1").show();
								$("#photo").attr("required", true);
								$("#is_photoedit1").val("1");
							});
							$('#is_photoedit').on('ifUnchecked', function(event) {
								$("#aut1").hide();
								$("#photo").attr("required", false);
								$("#is_photoedit1").val("0");
							});
							$('#is_deliver').on('ifChecked', function(event) {
								$("#aut2").show();
								$("#deladdress").attr("required", true);
								$("#cost").attr("required", true);
								$("#is_deliver1").val("1");
							});
							$('#is_deliver').on('ifUnchecked', function(event) {
								$("#aut2").hide();
								$("#deladdress").attr("required", false);
								$("#cost").attr("required", false);
								$("#is_deliver1").val("0");
							});

						});

						$(function() {
							$('#delivery_datetime').datetimepicker({
								format: 'YYYY-MM-DD hh:mm A'
							});
						});

						function cakesearch() {
							$("#cake").autocomplete({
								source: base_url + 'pos/cakesearch',
								minLength: 1,
								autoFocus: false,
								delay: 200,
								response: function(event, ui) {
									ui.content[0];
									if ($(this).val().length >= 16 && ui.content[0].id == 0) {
										bootbox.alert(lang.no_match_found, function() {
											$('#cake').focus();
										});
										$(this).val('');
									} else if (ui.content.length == 1 && ui.content[0].id != 0) {
										ui.item = ui.content[0];
										console.log(ui.content[0]);
									} else if (ui.content.length == 1 && ui.content[0].id == 0) {
										bootbox.alert(lang.no_match_found, function() {
											$('#cake').focus();
										});
										$(this).val('');
									}
								},
								select: function(event, ui) {
									event.preventDefault();
									if (ui.item.id !== 0) {
										var row = $("#prod_id").val(ui.item);
										$p_id = ui.item;

										$prodid = $p_id['item_id'];
										console.log($prodid);
										$("#prod_id").val($p_id['item_id']);
										if (row)
											$(this).val($p_id['label']);
									} else {
										bootbox.alert(lang.no_match_found);
									}
								}
							});
						}

						// function showbalance()
						// {

						// $.post("<?php echo site_url('pos/add_booking') ?>",{<?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash() ?>"},function(data){
						// alert(data);
						// $("#balance").val(data);
						// })
						// }

						// }
						</script>
						<!------------------------------------end booking------------------------------------------------>

						<div id="Customer" class="tab-pane fade">
							<table style="width:100%;" class="layout-table">
								<tr>
									<td style="width: 682px;">


										<div id="pos">
											<?= form_open('pos/pos_screen', 'id="pos-sale-form" novalidate'); ?>
											<div class="OrderTab" id="leftdiv">
												<div id="lefttop" style="margin-bottom:5px;">

												</div>
												<div id="printhead" class="print">
													<?= $Settings->header; ?>
													<p><?= lang('date'); ?>: <?= date($Settings->dateformat) ?></p>
												</div>

												<div id="botbuttons" class="col-xs-6 text-center">
													<div class="span3 row-fluid dine-legend">
														<div class="span4 text-center">
															<span id="Available" class="badge badge-success">0</span> <span
																class="dineInLegendName">Available</span>
														</div>
														<div class="span4 text-center">
															<span id="Occupied" class="badge ">0</span> <span class="dineInLegendName">Occupied</span>
														</div>
														<div class="span4 text-center">
															<span id="DoneSoon" class="badge badge-warning">0</span> <span
																class="dineInLegendName">Done Soon</span>
														</div>
													</div>
													<div class="col-xs-6 col-lg-6">
														<div class="form-group" id="TableType">
															<?= lang('Type', 'Type'); ?>
															<?php $tm = array(1 => lang('Outdoor'), 0 => lang('Indoor')); ?>
															<?= form_dropdown('type', $tm, set_value('type'), 'class="form-control tip select2" id="type_Cusomer"  required="required" style="width:50%;margin-top:50px;"'); ?>
														</div>
													</div>
													<div class="row" id="TablesList_Customer">

													</div>

												</div>
												<div id="botbuttons" class="col-xs-6 text-center">
													<div class="row" id="OrderList_Customer">

													</div>

												</div>
												<div class="clearfix"></div>

											</div>
											<?= form_close(); ?>
										</div>

									</td>

								</tr>
							</table>


						</div>

						<div id="Draft" class="tab-pane fade" style="padding: 15px;">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table id="OBData" class="table table-striped table-bordered table-condensed table-hover"
											style="margin-bottom:5px;">
											<thead>
												<tr class="active">
													<th style="max-width:30px;"><?= lang("id"); ?></th>
													<th class="col-xs-2"><?= lang("date"); ?></th>
													<th class="col-xs-2"><?= lang("customer"); ?></th>
													<th><?= lang("reference_note"); ?></th>
													<th class="col-xs-1"><?= lang("total_items"); ?></th>
													<th class="col-xs-1"><?= lang("grand_total"); ?></th>
													<th style="width:85px; text-align:center;"><?= lang("actions"); ?></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
												</tr>
											</tbody>
											<tfoot>
												<tr class="active">
													<th style="max-width:30px;"><input type="text" class="text_filter"
															placeholder="[<?= lang('id'); ?>]"></th>
													<th class="col-sm-2"><span class="datepickercon"><input type="text"
																class="text_filter datepicker" placeholder="[<?= lang('date'); ?>]"></span></th>
													<th class="col-sm-2"><input type="text" class="text_filter"
															placeholder="[<?= lang('customer'); ?>]"></th>
													<th><input type="text" class="text_filter" placeholder="[<?= lang('reference_note'); ?>]">
													</th>
													<th><input type="text" class="text_filter" placeholder="[<?= lang('total_items'); ?>]"></th>
													<th class="col-xs-1"><?= lang("grand_total"); ?></th>
													<th style="width:85px; text-align:center;"><?= lang("actions"); ?></th>
												</tr>
												<tr>
													<td colspan="7" class="p0"><input type="text" class="form-control b0" name="search_table"
															id="search_table" placeholder="<?= lang('type_hit_enter'); ?>" style="width:100%;"></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div id="Invoices" class="tab-pane fade">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table id="SLRData" class="table table-striped table-bordered table-condensed table-hover">
											<thead>
												<tr class="active">
													<th style="max-width:30px;"><?= lang("id"); ?></th>
													<th class="col-sm-2"><?= lang("date"); ?></th>
													<th class="col-sm-2"><?= lang("Sale Type"); ?></th>
													<th class="col-sm-1"><?= lang("total"); ?></th>
													<th class="col-sm-1"><?= lang("tax"); ?></th>
													<!-- <th class="col-sm-1"><?= lang("discount"); ?></th> -->
													<th class="col-sm-2"><?= lang("grand_total"); ?></th>
													<th class="col-sm-1"><?= lang("paid"); ?></th>
													<th class="col-sm-1"><?= lang("balance"); ?></th>
													<th class="col-sm-1"><?= lang("status"); ?></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
												</tr>
											</tbody>
											<tfoot>
												<tr class="active">
													<th style="max-width:30px;"><input type="text" class="text_filter"
															placeholder="[<?= lang('id'); ?>]"></th>
													<th class="col-sm-2"><span class="datepickercon"><input type="text"
																class="text_filter datepicker" placeholder="[<?= lang('date'); ?>]"></span></th>
													<th class="col-sm-2"><input type="text" class="text_filter"
															placeholder="[<?= lang('customer'); ?>]"></th>
													<th class="col-sm-1"><?= lang("total"); ?></th>
													<th class="col-sm-1"><?= lang("tax"); ?></th>
													<!--  <th class="col-sm-1"><?= lang("discount"); ?></th> -->
													<th class="col-sm-2"><?= lang("grand_total"); ?></th>
													<th class="col-sm-1"><?= lang("paid"); ?></th>
													<th class="col-sm-1"><?= lang("balance"); ?></th>
													<th class="col-sm-1">
														<select class="select2 select_filter">
															<option value=""><?= lang("all"); ?></option>
															<option value="paid"><?= lang("paid"); ?></option>
															<option value="partial"><?= lang("partial"); ?></option>
															<option value="due"><?= lang("due"); ?></option>
														</select>
													</th>
												</tr>
												<tr>
													<td colspan="10" class="p0"><input type="text" class="form-control b0" name="search_table"
															id="search_table" placeholder="<?= lang('type_hit_enter'); ?>" style="width:100%;"></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>


		</div>
	</div>


	<!-- prinbt -->
	<section class="content" id="ar_print" style="display:none;">
		<div id="wrapper">
			<div id="receiptData" style="width: auto; max-width: 384px; min-width: 384px; margin: 0 0;">
				<div id="receipt-data">
					<div>
						<div style="text-align:center;">
							<div class="row">
								<div class="col-xs-12">
									<div>
										<div class="box-body">
											<div class="form-group">
												<div class="row" style="margin-bottom: 10px;">
													<div class="col-md-12" style="margin-top: 26px;">
														<table>
															<tr>
																<th colspan="4">
																	<h3>Booking Orders</h3>
																	</td>
															</tr>
															<tr>
																<th
																	style="text-align:center; width: 7%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Date/time</th>
																<th
																	style="text-align:center; width: 40%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Content</th>
																<th
																	style="text-align:center; width: 8%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Product</th>
																<th
																	style="text-align:center; width: 20%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	qty</th>
																<th
																	style="text-align:center; width: 20%;font-weight: bold; border-bottom: 2px solid #ddd;">
																	Image</th>
															</tr>
															<tbody id="bookinglist">
																<tr>
																	<td colspan="5" style="text-align: center;">No data found</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
												<div class="row" style="float: right;margin-right: 3px;"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
							<hr>

							<div class="btn-group btn-group-justified" role="group" aria-label="...">
								<div class="btn-group" role="group">
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- prinbt -->


	<aside class="control-sidebar control-sidebar-dark no-print" id="categories-list">
		<div class="tab-content sb">
			<div class="tab-pane active sb" id="control-sidebar-home-tab">
				<div id="filter-categories-con">
					<input type="text" autocomplete="off" data-list=".control-sidebar-menu" name="filter-categories"
						id="filter-categories" class="form-control sb col-xs-12 kb-text"
						placeholder="<?= lang('filter_categories'); ?>" style="margin-bottom: 20px;">
				</div>
				<div class="clearfix sb"></div>
				<div id="category-sidebar-menu">
					<ul class="control-sidebar-menu">
						<?php
                        foreach ($categories as $category) {
                          echo '<li><a href="#" class="category' . ($category->id == $Settings->default_category ? ' active' : '') . '" id="' . $category->id . '">';
                          if ($category->image) {
                            echo '<div class="menu-icon"><img src="' . base_url('uploads/thumbs/' . $category->image) . '" alt="" class="img-thumbnail img-responsive"></div>';
                          } else {
                            echo '<i class="menu-icon fa fa-folder-open bg-red"></i>';
                          }
                          echo '<div class="menu-info"><h4 class="control-sidebar-subheading">' . $category->code . '</h4><p>' . $category->name . '</p></div>
                            </a></li>';
                        }
                        ?>
					</ul>
				</div>
			</div>
		</div>
	</aside>
	<div class="control-sidebar-bg sb"></div>
	</div>
	</div>
	<div id="order_tbl" style="display:none;"><span id="order_span"></span>
		<table id="order-table" class="prT table table-striped table-condensed" style="width:100%;margin-bottom:0;"></table>
	</div>
	<div id="bill_tbl" style="display:none;"><span id="bill_span"></span>
		<table id="bill-table" width="100%" class="prT table table-striped table-condensed"
			style="width:100%;margin-bottom:0;"></table>
		<table id="bill-total-table" width="100%" class="prT table table-striped table-condensed"
			style="width:100%;margin-bottom:0;"></table>
	</div>
	<div style="width:500px;background:#FFF;display:block">
		<div id="order-data" style="display:none;" class="text-center">
			<h1><?= $store->name; ?></h1>
			<h2><?= lang('order'); ?></h2>
			<div id="preo" class="text-left"></div>
		</div>
		<div id="bill-data" style="display:none;" class="text-center">
			<h1><?= $store->name; ?></h1>
			<h2><?= lang('bill'); ?></h2>
			<div id="preb" class="text-left"></div>
		</div>
	</div>

	<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>

	<div class="modal" data-easein="flipYIn" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="myModalLabel"><?= lang('sell_gift_card'); ?></h4>
				</div>
				<div class="modal-body">
					<p><?= lang('enter_info'); ?></p>

					<div class="alert alert-danger gcerror-con" style="display: none;">
						<button data-dismiss="alert" class="close" type="button">×</button>
						<span id="gcerror"></span>
					</div>
					<div class="form-group">
						<?= lang("card_no", "gccard_no"); ?> *
						<div class="input-group">
							<?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
							<div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#" id="genNo"><i
										class="fa fa-cogs"></i></a></div>
						</div>
					</div>
					<input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname" />
					<div class="form-group">
						<?= lang("value", "gcvalue"); ?> *
						<?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
					</div>
					<div class="form-group">
						<?= lang("price", "gcprice"); ?> *
						<?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?>
					</div>
					<div class="form-group">
						<?= lang("expiry_date", "gcexpiry"); ?>
						<?php echo form_input('gcexpiry', '', 'class="form-control" id="gcexpiry"'); ?>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close') ?></button>
					<button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" data-easein="flipYIn" id="dsModal" tabindex="-1" role="dialog" aria-labelledby="dsModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="dsModalLabel"><?= lang('discount_title'); ?></h4>
				</div>
				<div class="modal-body">
					<input type='text' class='form-control input-sm kb-pad' id='get_ds' onClick='this.select();' value=''>

					<label class="checkbox" for="apply_to_order">
						<input type="radio" name="apply_to" value="order" id="apply_to_order" checked="checked" />
						<?= lang('apply_to_order') ?>
					</label>
					<label class="checkbox" for="apply_to_products">
						<input type="radio" name="apply_to" value="products" id="apply_to_products" />
						<?= lang('apply_to_products') ?>
					</label>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm pull-left"
						data-dismiss="modal"><?= lang('close') ?></button>
					<button type="button" id="updateDiscount" class="btn btn-primary btn-sm"><?= lang('update') ?></button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" data-easein="flipYIn" id="tsModal" tabindex="-1" role="dialog" aria-labelledby="tsModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="tsModalLabel"><?= lang('tax_title'); ?></h4>
				</div>
				<div class="modal-body">
					<input type='text' class='form-control input-sm kb-pad' id='get_ts' onClick='this.select();' value=''>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm pull-left"
						data-dismiss="modal"><?= lang('close') ?></button>
					<button type="button" id="updateTax" class="btn btn-primary btn-sm"><?= lang('update') ?></button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" data-easein="flipYIn" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="noteModalLabel"><?= lang('note'); ?></h4>
				</div>
				<div class="modal-body">
					<textarea name="snote" id="snote" class="pa form-control kb-text"></textarea>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm pull-left"
						data-dismiss="modal"><?= lang('close') ?></button>
					<button type="button" id="update-note" class="btn btn-primary btn-sm"><?= lang('update') ?></button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" data-easein="flipYIn" id="notesaleModal" tabindex="-1" role="dialog"
		aria-labelledby="noteModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="noteModalLabel"><?= lang('note'); ?></h4>
				</div>
				<div class="modal-body">
					<textarea name="hold_ref" id="hold_ref" class="form-control kb-text"
						placeholder="<?= lang('reference_note') ?>"
						class="pa form-control kb-text"><?= $reference_note; ?></textarea>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm pull-left"
						data-dismiss="modal"><?= lang('close') ?></button>
					<button type="button" id="Saleupdate-note" class="btn btn-primary btn-sm"><?= lang('update') ?></button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" data-easein="flipYIn" id="splitModal" tabindex="-1" role="dialog" aria-labelledby="proModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" style="width: 800px;">
			<div class="modal-content">
				<div class="modal-header modal-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="proModalLabel">
						<?= lang('Split Table') ?>
					</h4>
				</div>
				<div class="modal-body">
					<table id="posTable_SPLIT" class="table table-striped table-condensed table-hover list-table"
						style="margin:0px;" data-height="100">
						<thead>
							<tr class="success">
								<th style="width: 5%;text-align:center;"></th>
								<th style="width: 25%;text-align:center;"><?= lang('product') ?></th>
								<th style="width: 25%;text-align:center;"><?= lang('price') ?></th>
								<th style="width: 15%;text-align:center;"><?= lang('qty') ?></th>
								<th style="width: 15%;text-align:center;"><?= lang('Tax') ?></th>
								<th style="width: 15%;text-align:center;"><?= lang('subtotal') ?></th>

							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>

				</div>
			</div>
		</div>
	</div>


	<div class="modal" data-easein="flipYIn" id="proModal" tabindex="-1" role="dialog" aria-labelledby="proModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header modal-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="proModalLabel">
						<?= lang('payment') ?>
					</h4>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-striped">
						<tr>
							<th style="width:25%;"><?= lang('net_price'); ?></th>
							<th style="width:25%;"><span id="net_price"></span></th>
							<th style="width:25%;"><?= lang('product_tax'); ?></th>
							<th style="width:25%;"><span id="pro_tax"></span> <span id="pro_tax_method"></span></th>
						</tr>
					</table>
					<input type="hidden" id="row_id" />
					<input type="hidden" id="item_id" />
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<?= lang('unit_price', 'nPrice') ?>
								<input type="text" class="form-control input-sm kb-pad" id="nPrice" onClick="this.select();"
									placeholder="<?= lang('new_price') ?>">
							</div>
							<div class="form-group" style="display:none;">
								<?= lang('discount', 'nDiscount') ?>
								<input type="text" class="form-control input-sm kb-pad" id="nDiscount" onClick="this.select();"
									placeholder="<?= lang('discount') ?>">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<?= lang('quantity', 'nQuantity') ?>
								<input type="text" class="form-control input-sm kb-pad" id="nQuantity" onClick="this.select();"
									placeholder="<?= lang('current_quantity') ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<?= lang('comment', 'nComment') ?>
								<textarea class="form-control kb-text" id="nComment"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close') ?></button>
					<button class="btn btn-success" id="editItem"><?= lang('update') ?></button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" data-easein="flipYIn" id="susModal" tabindex="-1" role="dialog" aria-labelledby="susModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="susModalLabel"><?= lang('suspend_sale'); ?></h4>
				</div>
				<div class="modal-body">
					<p><?= lang('type_reference_note'); ?></p>

					<div class="form-group">
						<?= lang("reference_note", "reference_note"); ?>
						<?php echo form_input('reference_note', $reference_note, 'class="form-control kb-text" id="reference_note"'); ?>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> <?= lang('close') ?> </button>
					<button type="button" id="suspend_sale" class="btn btn-primary"><?= lang('submit') ?></button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal" data-easein="flipYIn" id="OrderKitchen_Conf" tabindex="-1" role="dialog"
		aria-labelledby="susModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="susModalLabel"><?= lang('Order Confirm'); ?></h4>
				</div>
				<div class="modal-body">
					<p><?= lang('type_reference_note'); ?></p>

					<div class="form-group">
						<?= lang("reference_note", "reference_note"); ?>
						<?php echo form_input('reference_note_kit', $reference_note, 'class="form-control kb-text" id="reference_note_kit"'); ?>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> <?= lang('close') ?> </button>
					<button type="button" id="OrderKitchen_sale" class="btn btn-primary"><?= lang('submit') ?></button>
				</div>
			</div>
		</div>
	</div>



	<div class="modal" data-easein="flipYIn" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalLabel"
		aria-hidden="true"></div>
	<div class="modal" data-easein="flipYIn" id="opModal" tabindex="-1" role="dialog" aria-labelledby="opModalLabel"
		aria-hidden="true"></div>

	<div class="modal" data-easein="flipYIn" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel"
		aria-hidden="true">

	</div>
	<div class="modal" data-easein="flipYIn" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="cModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header modal-primary">
					<button type="button" class="close" onClick="CloseCustomerModel()" aria-hidden="true"><i
							class="fa fa-times"></i></button>
					<h4 class="modal-title" id="cModalLabel">
						<?= lang('add_customer') ?>
					</h4>
				</div>

				<?= form_open('pos/add_customer', 'id="customer-form"'); ?>
				<div class="modal-body">
					<div id="c-alert" class="alert alert-danger" style="display:none;"></div>
					<input type="hidden" value="0" id="hdnCustId" />
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label class="control-label" for="phone">
									<?= lang("phone"); ?>
								</label>
								<input required type="text" name="phone" id="cphone" class="form-control"
									placeholder="<?= lang('search customer by phone number') ?>" />

							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">

							<div class="form-group">
								<label class="control-label" for="code">
									<?= lang("name"); ?>
								</label>
								<?= form_input('name', '', 'class="form-control input-sm kb-text" required id="cname"'); ?>
							</div>
						</div>
						<div class="col-xs-6">

							<div class="form-group">
								<label class="control-label" for="cemail">
									<?= lang("email_address"); ?>
								</label>
								<?= form_input('email', '', 'class="form-control input-sm kb-text" required id="cemail"'); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label" for="cf1">
									<?= lang("Address"); ?>
								</label>
								<?= form_input('cf1', '', 'class="form-control input-sm kb-text" required id="cf1"'); ?>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label" for="cf2">
									<?= lang("cf2"); ?>
								</label>
								<?= form_input('cf2', '', 'class="form-control input-sm kb-text" required id="cf2"'); ?>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer" style="margin-top:0;">
					<button type="button" class="btn btn-default pull-left" id="customerModalClose"
						onClick="CloseCustomerModel()"> <?= lang('close') ?> </button>
					<button type="submit" class="btn btn-primary" id="add_customer"> <?= lang('add_customer') ?> </button>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	<div class="modal" data-easein="flipYIn" id="posModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		aria-hidden="true"></div>
	<div class="modal" data-easein="flipYIn" id="posModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2"
		aria-hidden="true"></div>

	<script type="text/javascript">
	var table;
	var table_new;

	function LoadInvoice() {

		if (table_new) {
			table_new.destroy();
		}

		table_new = $('#SLRData').DataTable({

			'ajax': {
				url: '<?= site_url('reports/get_sales_pos/' . $v); ?>',
				type: 'POST',
				"data": function(d) {
					d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";
				}
			},
			"buttons": [{
					extend: 'copyHtml5',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					}
				},
				{
					extend: 'excelHtml5',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					}
				},
				{
					extend: 'csvHtml5',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					}
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A4',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					}
				},
				{
					extend: 'colvis',
					text: 'Columns'
				},
			],
			"columns": [{
					"data": "id"
				},
				{
					"data": "date",
					"render": hrld
				},
				{
					"data": "name"
				},
				{
					"data": "total",
					"render": currencyFormat
				},
				{
					"data": "order_tax",
					"render": currencyFormat
				},
				// { "data": "total_discount", "render": currencyFormat },
				{
					"data": "grand_total",
					"render": currencyFormat
				},
				{
					"data": "paid",
					"render": currencyFormat
				},
				{
					"data": "balance",
					"render": currencyFormat
				},
				{
					"data": "status",
					"render": status
				}
			],
			"footerCallback": function(tfoot, data, start, end, display) {
				var api = this.api(),
					data;
				$(api.column(3).footer()).html(cf(api.column(3).data().reduce(function(a, b) {
					return pf(a) + pf(b);
				}, 0)));
				$(api.column(4).footer()).html(cf(api.column(4).data().reduce(function(a, b) {
					return pf(a) + pf(b);
				}, 0)));
				$(api.column(5).footer()).html(cf(api.column(5).data().reduce(function(a, b) {
					return pf(a) + pf(b);
				}, 0)));
				$(api.column(6).footer()).html(cf(api.column(6).data().reduce(function(a, b) {
					return pf(a) + pf(b);
				}, 0)));
				$(api.column(7).footer()).html(cf(api.column(7).data().reduce(function(a, b) {
					return pf(a) + pf(b);
				}, 0)));
				$(api.column(8).footer()).html(cf(api.column(8).data().reduce(function(a, b) {
					return pf(a) + pf(b);
				}, 0)));
			}

		});

	}


	var base_url = '<?= base_url(); ?>',
		assets = '<?= $assets ?>';
	var dateformat = '<?= $Settings->dateformat; ?>',
		timeformat = '<?= $Settings->timeformat ?>';
	<?php unset($Settings->protocol, $Settings->smtp_host, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->smtp_crypto, $Settings->mailpath, $Settings->timezone, $Settings->setting_id, $Settings->default_email, $Settings->version, $Settings->stripe, $Settings->stripe_secret_key, $Settings->stripe_publishable_key); ?>
	var Settings = <?= json_encode($Settings); ?>;
	var sid = false,
		username = '<?= $this->session->userdata('username'); ?>',
		spositems = {};
	$(window).load(function() {
		$('#mm_<?= $m ?>').addClass('active');
		$('#<?= $m ?>_<?= $v ?>').addClass('active');
	});
	var pro_limit = <?= $Settings->pro_limit ?>,
		java_applet = 0,
		count = 1,
		total = 0,
		an = 1,
		p_page = 0,
		page = 0,
		cat_id = <?= $Settings->default_category ?>,
		tcp = <?= $tcp ?>;
	var gtotal = 0,
		order_discount = 0,
		order_tax = 0,
		protect_delete = <?= ($Admin) ? 0 : ($Settings->pin_code ? 1 : 0); ?>;
	var order_data = {},
		bill_data = {};
	var csrf_hash = '<?= $this->security->get_csrf_hash(); ?>';
	<?php
        if ($Settings->remote_printing == 2) {

          ?>
	var ob_store_name = "<?= printText($store->name, (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n";
	order_data.store_name = ob_store_name;
	bill_data.store_name = ob_store_name;

	ob_header = "";
	ob_header +=
		"<?= printText($store->name . ' (' . $store->code . ')', (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n";
	<?php
        if ($store->address1) { ?>
	ob_header += "<?= printText($store->address1, (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n";
	<?php

      }
      if ($store->address2) { ?>
	ob_header += "<?= printText($store->address2, (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n";
	<?php

      }
      if ($store->city) { ?>
	ob_header += "<?= printText($store->city, (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n";
	<?php

      } ?>
	ob_header +=
		"<?= printText(lang('tel') . ': ' . $store->phone, (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n\r\n";
	ob_header +=
		"<?= printText(str_replace(array("\n", "\r"), array("\\n", "\\r"), $store->receipt_header), (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n\r\n";

	order_data.header = ob_header +
		"<?= printText(lang('order'), (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n\r\n";
	bill_data.header = ob_header +
		"<?= printText(lang('bill'), (!empty($printer) ? $printer->char_per_line : '')); ?>\r\n\r\n";
	order_data.totals = '';
	order_data.payments = '';
	bill_data.payments = '';
	order_data.footer = '';
	bill_data.footer = "<?= lang('merchant_copy'); ?> \n";
	<?php

      }
      ?>
	var lang = new Array();
	lang['code_error'] = '<?= lang('code_error'); ?>';
	lang['r_u_sure'] = '<?= lang('r_u_sure'); ?>';
	lang['please_add_product'] = '<?= lang('please_add_product'); ?>';
	lang['paid_less_than_amount'] = '<?= lang('paid_less_than_amount'); ?>';
	lang['x_suspend'] = '<?= lang('x_suspend'); ?>';
	lang['discount_title'] = '<?= lang('discount_title'); ?>';
	lang['update'] = '<?= lang('update'); ?>';
	lang['tax_title'] = '<?= lang('tax_title'); ?>';
	lang['leave_alert'] = '<?= lang('leave_alert'); ?>';
	lang['close'] = '<?= lang('close'); ?>';
	lang['delete'] = '<?= lang('delete'); ?>';
	lang['no_match_found'] = '<?= lang('no_match_found'); ?>';
	lang['wrong_pin'] = '<?= lang('wrong_pin'); ?>';
	lang['file_required_fields'] = '<?= lang('file_required_fields'); ?>';
	lang['enter_pin_code'] = '<?= lang('enter_pin_code'); ?>';
	lang['incorrect_gift_card'] = '<?= lang('incorrect_gift_card'); ?>';
	lang['card_no'] = '<?= lang('card_no'); ?>';
	lang['value'] = '<?= lang('value'); ?>';
	lang['balance'] = '<?= lang('balance'); ?>';
	lang['unexpected_value'] = '<?= lang('unexpected_value'); ?>';
	lang['inclusive'] = '<?= lang('inclusive'); ?>';
	lang['exclusive'] = '<?= lang('exclusive'); ?>';
	lang['total'] = '<?= lang('total'); ?>';
	lang['total_items'] = '<?= lang('total_items'); ?>';
	lang['order_tax'] = '<?= lang('order_tax'); ?>';
	lang['order_discount'] = '<?= lang('order_discount'); ?>';
	lang['total_payable'] = '<?= lang('total_payable'); ?>';
	lang['rounding'] = '<?= lang('rounding'); ?>';
	lang['grand_total'] = '<?= lang('grand_total'); ?>';
	lang['register_open_alert'] = '<?= lang('register_open_alert'); ?>';
	lang['discount'] = '<?= lang('discount'); ?>';
	lang['order'] = '<?= lang('order'); ?>';
	lang['bill'] = '<?= lang('bill'); ?>';
	lang['merchant_copy'] = '<?= lang('merchant_copy'); ?>';

	$(document).ready(function() {
		<?php if ($this->session->userdata('rmspos')) { ?>
		if (get('spositems')) {
			remove('spositems');
		}
		if (get('spos_discount')) {
			remove('spos_discount');
		}
		if (get('spos_tax')) {
			remove('spos_tax');
		}
		if (get('spos_note')) {
			remove('spos_note');
		}
		if (get('spos_customer')) {
			remove('spos_customer');
		}
		if (get('amount')) {
			remove('amount');
		}
		<?php $this->tec->unset_data('rmspos');
          } ?>

		if (get('rmspos')) {
			if (get('spositems')) {
				remove('spositems');
			}
			if (get('spos_discount')) {
				remove('spos_discount');
			}
			if (get('spos_tax')) {
				remove('spos_tax');
			}
			if (get('spos_note')) {
				remove('spos_note');
			}
			if (get('spos_customer')) {
				remove('spos_customer');
			}
			if (get('amount')) {
				remove('amount');
			}
			remove('rmspos');
		}
		<?php if ($sid) { ?>

		store('spositems', JSON.stringify(<?= $items; ?>));
		store('spos_discount', '<?= $suspend_sale->order_discount_id; ?>');
		store('spos_tax', '<?= $suspend_sale->order_tax_id; ?>');
		store('spos_customer', '<?= $suspend_sale->customer_id; ?>');
		$('#spos_customer').select2().select2('val', '<?= $suspend_sale->customer_id; ?>');
		store('rmspos', '1');
		$('#tax_val').val('<?= $suspend_sale->order_tax_id; ?>');
		$('#discount_val').val('<?= $suspend_sale->order_discount_id; ?>');
		<?php 
          } elseif ($eid) { ?>
		$('#date').inputmask("y-m-d h:s:s", {
			"placeholder": "YYYY/MM/DD HH:mm:ss"
		});
		store('spositems', JSON.stringify(<?= $items; ?>));
		store('spos_discount', '<?= $sale->order_discount_id; ?>');
		store('spos_tax', '<?= $sale->order_tax_id; ?>');
		store('spos_customer', '<?= $sale->customer_id; ?>');
		store('sale_date', '<?= $sale->date; ?>');
		$('#spos_customer').select2().select2('val', '<?= $sale->customer_id; ?>');
		$('#date').val('<?= $sale->date; ?>');
		store('rmspos', '1');
		$('#tax_val').val('<?= $sale->order_tax_id; ?>');
		$('#discount_val').val('<?= $sale->order_discount_id; ?>');
		<?php 
          } else { ?>
		if (!get('spos_discount')) {
			store('spos_discount', '<?= $Settings->default_discount; ?>');
			$('#discount_val').val('<?= $Settings->default_discount; ?>');
		}
		if (!get('spos_tax')) {
			store('spos_tax', '<?= $Settings->default_tax_rate; ?>');
			$('#tax_val').val('<?= $Settings->default_tax_rate; ?>');
		}
		<?php 
          } ?>

		if (ots = get('spos_tax')) {
			$('#tax_val').val(ots);
		}
		if (ods = get('spos_discount')) {
			$('#discount_val').val(ods);
		}
		bootbox.addLocale('bl', {
			OK: '<?= lang('ok'); ?>',
			CANCEL: '<?= lang('no'); ?>',
			CONFIRM: '<?= lang('yes'); ?>'
		});
		bootbox.setDefaults({
			closeButton: false,
			locale: "bl"
		});
		<?php if ($eid) { ?>
		$('#suspend').attr('disabled', true);
		$('#print_order').attr('disabled', true);
		$('#print_bill').attr('disabled', true);
		<?php 
          } ?>
	});
	</script>

	<script type="text/javascript">
	var socket = null;
	<?php
        if ($Settings->remote_printing == 2) {
          ?>
	try {
		socket = new WebSocket('ws://127.0.0.1:6441');
		socket.onopen = function() {
			console.log('Connected');
			return;
		};
		socket.onclose = function() {
			console.log('Connection closed');
			return;
		};
	} catch (e) {
		console.log(e);
	}
	<?php

      }
      ?>

	function printBill(bill) {
		if (Settings.remote_printing == 1) {
			Popup_POS($('#bill_tbl').html());
		} else if (Settings.remote_printing == 2) {
			if (socket.readyState == 1) {
				var socket_data = {
					'printer': <?= $Settings->local_printers ? "''" : json_encode($printer); ?>,
					'logo': '<?= !empty($store->logo) ? base_url('uploads/' . $store->logo) : ''; ?>',
					'text': bill
				};
				socket.send(JSON.stringify({
					type: 'print-receipt',
					data: socket_data
				}));
				return false;
			} else {
				bootbox.alert('<?= lang('pos_print_error'); ?>');
				return false;
			}
		}
	}
	var order_printers = <?= $Settings->local_printers ? "''" : json_encode($order_printers); ?>;

	function printOrder(order) {
		if (Settings.remote_printing == 1) {
			Popup_POS($('#order_tbl').html());
		} else if (Settings.remote_printing == 2) {
			if (socket.readyState == 1) {
				if (order_printers == '') {

					var socket_data = {
						'printer': false,
						'order': true,
						'logo': '<?= !empty($store->logo) ? base_url('uploads/' . $store->logo) : ''; ?>',
						'text': order
					};
					socket.send(JSON.stringify({
						type: 'print-receipt',
						data: socket_data
					}));

				} else {

					$.each(order_printers, function() {
						var socket_data = {
							'printer': this,
							'logo': '<?= !empty($store->logo) ? base_url('uploads/' . $store->logo) : ''; ?>',
							'text': order
						};
						socket.send(JSON.stringify({
							type: 'print-receipt',
							data: socket_data
						}));
					});

				}
				return false;
			} else {
				bootbox.alert('<?= lang('pos_print_error'); ?>');
				return false;
			}
		}
	}

	function Popup_POS(data) {
		var mywindow = window.open('', 'spos_print', 'height=1000,width=1000');
		mywindow.document.write('<html style="max-width: 384px;  margin:0;padding-left:5px;"><head><title>Print</title>');
		mywindow.document.write(
			'<link rel="stylesheet" href="<?= $assets ?>bootstrap/css/bootstrap.min.css" type="text/css" />');
		mywindow.document.write(
			'<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Inconsolata" />');

		mywindow.document.write(
			'<style type="text/css" media="all">body { color: #000; }#wrapper { max-width: 384px;  margin:0 auto;  }.btn { margin-bottom: 5px; }.table { border-radius: 3px; }.table th { background: #f5f5f5; }.table th, .table td { vertical-align: middle !important; }h3 { margin: 5px 0; }@media print {.no-print { display: none; }#wrapper { max-width: 384px;  margin:0;padding:0; }}@page {max-width: 384px}h1 {font-family: Inconsolata;font-size: 14px;font-style: normal;font-variant: normal;font-weight: 500;line-height: 15.4px;}h3 {font-family: Inconsolata;font-size: 14px;font-style: normal;font-variant: normal;font-weight: 500;line-height: 15.4px;}p {font-family: Inconsolata;font-size: 14px;font-style: normal;font-variant: normal;font-weight: 400;line-height: 20px;}blockquote {font-family: Inconsolata;font-size: 21px;font-style: normal;font-variant: normal;font-weight: 400;line-height: 30px;}pre {font-family: Inconsolata;font-size: 13px;font-style: normal;font-variant: normal;font-weight: 400;line-height: 18.5714px;}#receiptData{font-family: Inconsolata;font-size: 13px;font-style: normal;font-variant: normal;font-weight: 400;color: #555555;}</style>'
		);
		mywindow.document.write('</head><body>');
		mywindow.document.write(
			'<div id="wrapper"><div id="receiptData" style="width: auto; max-width: 384px; min-width: 384px; margin: 0 auto;">'
		);

		mywindow.document.write(data);
		mywindow.document.write('</div></div>');
		mywindow.document.write('</body></html>');
		setTimeout(function() {
			mywindow.print();
			mywindow.close();
		}, 500);

		return true;
	}
	</script>
	<?php
    if (isset($print) && !empty($print)) {
      /* include FCPATH.'themes'.DIRECTORY_SEPARATOR.$Settings->theme.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'pos'.DIRECTORY_SEPARATOR.'remote_printing.php'; */
      include 'remote_printing.php';
    }
    ?>

	<script src="<?= $assets ?>dist/js/libraries.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>dist/js/scripts.min.js" type="text/javascript"></script>
	<script src="<?= $assets ?>dist/js/pos.min.js" type="text/javascript"></script>
	<?php if ($Settings->remote_printing != 1 && $Settings->print_img) { ?>
	<script src="<?= $assets ?>dist/js/htmlimg.js"></script>
	<?php 
  } ?>


	<script type="text/javascript">
	$(document).ready(function() {

		function status(x) {
			var paid = '<?= lang('paid'); ?>';
			var partial = '<?= lang('partial'); ?>';
			var due = '<?= lang('due'); ?>';
			if (x == 'paid') {
				return '<div class="text-center"><span class="sale_status label label-success">' + paid + '</span></div>';
			} else if (x == 'partial') {
				return '<div class="text-center"><span class="sale_status label label-primary">' + partial +
					'</span></div>';
			} else if (x == 'due') {
				return '<div class="text-center"><span class="sale_status label label-danger">' + due + '</span></div>';
			} else {
				return '<div class="text-center"><span class="sale_status label label-default">' + x + '</span></div>';
			}
		}

		var table = $('#OBData').DataTable({

			'ajax': {
				url: '<?= site_url('pos/get_opened_list'); ?>',
				type: 'POST',
				"data": function(d) {
					d.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash() ?>";
				}
			},
			"buttons": [{
					extend: 'copyHtml5',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					}
				},
				{
					extend: 'excelHtml5',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					}
				},
				{
					extend: 'csvHtml5',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					}
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A4',
					'footer': true,
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
					}
				},
				{
					extend: 'colvis',
					text: 'Columns'
				},
			],
			"columns": [{
					"data": "id",
					"visible": false
				},
				{
					"data": "date",
					"render": hrld
				},
				{
					"data": "customer_name"
				},
				{
					"data": "hold_ref"
				},
				{
					"data": "items"
				},
				{
					"data": "grand_total",
					"render": currencyFormat
				},
				{
					"data": "Actions",
					"searchable": false,
					"orderable": false
				}
			],
			"footerCallback": function(tfoot, data, start, end, display) {
				var api = this.api(),
					data;
				$(api.column(5).footer()).html(cf(api.column(5).data().reduce(function(a, b) {
					return pf(a) + pf(b);
				}, 0)));
			}

		});


		$('#search_table').on('keyup change', function(e) {
			var code = (e.keyCode ? e.keyCode : e.which);
			if (((code == 13 && table.search() !== this.value) || (table.search() !== '' && this.value === ''))) {
				table.search(this.value).draw();
			}
		});

		table.columns().every(function() {
			var self = this;
			$('input.datepicker', this.footer()).on('dp.change', function(e) {
				self.search(this.value).draw();
			});
			$('input:not(.datepicker)', this.footer()).on('keyup change', function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if (((code == 13 && self.search() !== this.value) || (self.search() !== '' && this.value === ''))) {
					self.search(this.value).draw();
				}
			});
			$('select', this.footer()).on('change', function(e) {
				self.search(this.value).draw();
			});
		});

	});
	</script>

	<script type="text/javascript">
	$(document).ready(function() {
		$('#form').hide();
		$('.toggle_form').click(function() {
			$("#form").slideToggle();
			return false;
		});
	});
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
	</script>
	<script type="text/javascript">
	$(function() {
		$(".immediate").css("display", "block");
		$(".booking").css("display", "none");
		$('.datetimepicker').datetimepicker({
			format: 'YYYY-MM-DD hh:mm A'
		});
		$('.datepicker').datetimepicker({
			format: 'YYYY-MM-DD',
			showClear: true,
			showClose: true,
			useCurrent: false,
			widgetPositioning: {
				horizontal: 'auto',
				vertical: 'bottom'
			},
			widgetParent: $('.dataTable tfoot')
		});
		$('input').on('ifChecked', function(event) {


			if ($(this).val() == 'booking') {
				$("#hd_sale_type_val").val("Booking");
				$("#radioBoo").prop("checked", true);
				$("#radioImm").prop("checked", false);
				$(".immediate").css("display", "none");
				$(".booking").css("display", "block");
			} else if ($(this).val() == 'on') {
				$("#isDoorDelivery").prop("checked", true);
				$("#hd_is_del_val").val("1");
			} else {
				$("#hd_sale_type_val").val("Immediate");
				$("#radioBoo").prop("checked", false);
				$("#radioImm").prop("checked", true);
				$(".immediate").css("display", "block");
				$(".booking").css("display", "none");
			}
		});
		$('input').on('ifUnchecked', function(event) {

			if ($(this).val() == 'on') {
				$("#isDoorDelivery").prop("checked", false);
				$("#hd_is_del_val").val("0");
			}


		});

		$('#senddata').on('click', function() {
			var daterange = $('input[name="daterange"]').val();
			console.log(daterange);
			$.ajax({
				url: '<?= site_url('pos/getBookingOrders'); ?>',
				dataType: 'json',
				type: 'POST',
				data: {
					<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash() ?>",
					dr: daterange
				},
				success: function(res) {
					console.log(res);
					$('#bookinglist').html(res.data);
					$('#bookinglist1').html(res.data);
				},
				error: function(params) {
					console.log('err');
				}
			});
		});

	});
	</script>

	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	<script type="text/javascript">
	function printDiv(divName) {
		// var printContents = document.getElementById(divName).innerHTML;
		// var originalContents = document.body.innerHTML;

		// document.body.innerHTML = printContents;

		// window.print();

		// document.body.innerHTML = originalContents;
		var printContent = document.getElementById(divName);
		var WinPrint = window.open('', '', 'width=900,height=650');
		WinPrint.document.write(printContent.innerHTML);
		WinPrint.document.close();
		WinPrint.focus();
		WinPrint.print();
		WinPrint.close();
	}

	$(function() {
		$('input[name="daterange"]').daterangepicker({
			dateFormat: 'dd-mm-yy'
		});
	});

	function arprint() {
		$('#ar_print').show();
		window.print();
		// $('#ar_print').hide();
	}
	</script>

</body>

</html>