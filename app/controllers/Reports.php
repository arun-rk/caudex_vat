<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller
{

    function __construct() {
        parent::__construct();


        if ( ! $this->loggedIn) {
            redirect('login');
        }

        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->load->model('reports_model');
    }

    function daily_sales($year = NULL, $month = NULL) {
        if (!$year) { $year = date('Y'); }
        if (!$month) { $month = date('m'); }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->lang->load('calendar');
        $config = array(
            'show_next_prev' => TRUE,
            'next_prev_url' => site_url('reports/daily_sales'),
            'month_type' => 'long',
            'day_type' => 'long'
            );
        $config['template'] = '

        {table_open}<table border="0" cellpadding="0" cellspacing="0" class="table table-bordered table-calendar" style="min-width:522px;">{/table_open}

        {heading_row_start}<tr class="active">{/heading_row_start}

        {heading_previous_cell}<th><div class="text-center"><a href="{previous_url}">&lt;&lt;</div></a></th>{/heading_previous_cell}
        {heading_title_cell}<th colspan="{colspan}"><div class="text-center">{heading}</div></th>{/heading_title_cell}
        {heading_next_cell}<th><div class="text-center"><a href="{next_url}">&gt;&gt;</a></div></th>{/heading_next_cell}

        {heading_row_end}</tr>{/heading_row_end}

        {week_row_start}<tr>{/week_row_start}
        {week_day_cell}<td class="cl_equal"><div class="cl_wday">{week_day}</div></td>{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}

        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td>{/cal_cell_start}

        {cal_cell_content}{day}<br>{content}{/cal_cell_content}
        {cal_cell_content_today}<div class="highlight">{day}</div>{content}{/cal_cell_content_today}

        {cal_cell_no_content}{day}{/cal_cell_no_content}
        {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

        {cal_cell_blank}&nbsp;{/cal_cell_blank}

        {cal_cell_end}</td>{/cal_cell_end}
        {cal_row_end}</tr>{/cal_row_end}

        {table_close}</table>{/table_close}
        ';

        $this->load->library('calendar', $config);

        $sales = $this->reports_model->getDailySales($year, $month);
						$total = 0;
						$product_tax = 0;
						$total_tax = 0;
						$discount = 0;
						$grand_total = 0;
						$order_tax = 0;
						$paid = 0;
						$date = 0;
        if (!empty($sales)) {
            foreach ($sales as $sale) {
								$date += intval($sale->date);						
								$total += $sale->total;
								$product_tax += $sale->product_tax;
								$total_tax += $sale->total_tax;
								$discount += $sale->discount;
								$grand_total += $sale->grand_total;
								$paid += $sale->paid;
								$date = $sale->date;
							}
							$daily_sale[$date] = "<table class='table table-condensed table-striped' style='margin-bottom:0;'><tr>
							<td>".lang('total').
							"</td><td style='text-align:right;'>{$this->tec->formatMoney($total)}</td></tr><tr><td><br>".lang('tax').
							"</td>
							<td style='text-align:right;'><br>{$this->tec->formatMoney($total_tax)}</td>
							</tr><tr><td class='violet'>".lang('discount').
							"</td><td style='text-align:right;'>{$this->tec->formatMoney($discount)}</td></tr><tr><td class='violet'>".lang('grand_total').
							"</td><td style='text-align:right;' class='violet'>{$this->tec->formatMoney($grand_total)}</td></tr><tr><td class='green'>".lang('paid').
							"</td><td style='text-align:right;' class='green'>{$this->tec->formatMoney($paid)}</td></tr><tr><td class='orange'>".lang('balance').
							"</td><td style='text-align:right;' class='orange'>{$this->tec->formatMoney($grand_total - $paid)}</td></tr></table>";
							// $daily_sale[$sale->date] = "<table class='table table-condensed table-striped' style='margin-bottom:0;'><tr><td>".lang('total').
							// "</td><td style='text-align:right;'>{$this->tec->formatMoney($sale->total)}</td></tr><tr><td><span style='font-weight:normal;'>".lang('product_tax')."<br>".lang('order_tax')."</span><br>".lang('tax').
							// "</td><td style='text-align:right;'><span style='font-weight:normal;'>{$this->tec->formatMoney($sale->product_tax)}<br>{$this->tec->formatMoney($sale->order_tax)}</span><br>{$this->tec->formatMoney($sale->total_tax)}</td></tr><tr><td class='violet'>".lang('discount').
							// "</td><td style='text-align:right;'>{$this->tec->formatMoney($sale->discount)}</td></tr><tr><td class='violet'>".lang('grand_total').
							// "</td><td style='text-align:right;' class='violet'>{$this->tec->formatMoney($sale->grand_total)}</td></tr><tr><td class='green'>".lang('paid').
							// "</td><td style='text-align:right;' class='green'>{$this->tec->formatMoney($sale->paid)}</td></tr><tr><td class='orange'>".lang('balance').
							// "</td><td style='text-align:right;' class='orange'>{$this->tec->formatMoney($sale->grand_total - $sale->paid)}</td></tr></table>";
        } else {
            $daily_sale = array();
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['calender'] = $this->calendar->generate($year, $month, $daily_sale);

        $start = $year.'-'.$month.'-01 00:00:00';
        $end = $year.'-'.$month.'-'.days_in_month($month, $year).' 23:59:59';
        $this->data['total_purchases'] = $this->reports_model->getTotalPurchases($start, $end);
        $this->data['total_sales'] = $this->reports_model->getTotalSales($start, $end);
        $this->data['total_expenses'] = $this->reports_model->getTotalExpenses($start, $end);

        $this->data['page_title'] = $this->lang->line("daily_sales");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('daily_sales')));
        $meta = array('page_title' => lang('daily_sales'), 'bc' => $bc);
        $this->page_construct('reports/daily', $this->data, $meta);

    }


    function monthly_sales($year = NULL) {
        if(!$year) { $year = date('Y'); }
        $this->load->language('calendar');
        $this->lang->load('calendar');
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-12-31 23:59:59';
        $this->data['total_purchases'] = $this->reports_model->getTotalPurchases($start, $end);
        $this->data['total_sales'] = $this->reports_model->getTotalSales($start, $end);
        $this->data['total_expenses'] = $this->reports_model->getTotalExpenses($start, $end);
        $this->data['year'] = $year;
        $this->data['sales'] = $this->reports_model->getMonthlySales($year);
        $this->data['page_title'] = $this->lang->line("monthly_sales");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('monthly_sales')));
        $meta = array('page_title' => lang('monthly_sales'), 'bc' => $bc);
        $this->page_construct('reports/monthly', $this->data, $meta);
    }

    function index() {
        if ($this->input->post('customer')) {
            $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : NULL;
            $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : NULL;
            $user = $this->input->post('user') ? $this->input->post('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getTotalCustomerSales($this->input->post('customer'), $user, $start_date, $end_date);
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['page_title'] = $this->lang->line("sales_report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('sales_report')));
        $meta = array('page_title' => lang('sales_report'), 'bc' => $bc);
        $this->page_construct('reports/sales', $this->data, $meta);
    }

    function get_sales() {
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;

        $this->load->library('datatables');
        $this->datatables
        ->select("id, date, customer_name, total, total_tax, total_discount, grand_total, paid, (grand_total-paid) as balance, status")
        ->from('sales');
        if ($this->session->userdata('store_id')) {
            $this->datatables->where('store_id', $this->session->userdata('store_id'));
        }
        $this->datatables->unset_column('id');
        if($customer) { $this->datatables->where('customer_id', $customer); }
        if($user) { $this->datatables->where('created_by', $user); }
        if($start_date) { $this->datatables->where('date >=', $start_date); }
        if($end_date) { $this->datatables->where('date <=', $end_date); }

        echo $this->datatables->generate();
    }
	
	 function get_today_credit() {
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
				if(JSON_ENCODE($start_date)=='null')
				{
					$start_date=JSON_ENCODE(date('Y-m-d'));
				}
				else
				{
					$start_date= JSON_ENCODE($start_date);
				}
				if(JSON_ENCODE($end_date)=='null')
				{
					$end_date=JSON_ENCODE(date('Y-m-d'));
				}
				else
				{
					$end_date= JSON_ENCODE($end_date);
				}
				//  + sum(tec_sale_items.cgst_tax_val+tec_sale_items.sgst_tax_val)
				/* 
				select tec_sale_items.product_name as Item,
				sum(tec_sale_items.quantity) as SaleQty,
				sum(tec_purchase_items.cost) as PurchaseRate,
				tec_products.price  as SaleRate,
				sum(tec_sale_items.cgst_tax_val+tec_sale_items.sgst_tax_val) as vat,
				sum(tec_sale_items.quantity *tec_purchase_items.cost) as PurchaseValue,
				sum(tec_sale_items.quantity * tec_products.price )  as SaleValue,
				sum((tec_sale_items.quantity * tec_products.price)-(tec_sale_items.quantity * tec_purchase_items.cost)) as NetProfit
				from tec_sales
				LEFT OUTER join tec_sale_items on tec_sale_items.sale_id=tec_sales.id 
				LEFT OUTER join tec_purchases on tec_purchases.batch_no=tec_sales.batchno 
				LEFT OUTER join tec_purchase_items on tec_purchase_items.purchase_id=tec_sale_items.product_id 
				LEFT OUTER join tec_products on tec_products.id=tec_sale_items.product_id 
				*/
				$sql ="
					select tec_sale_items.product_name as Item,
					sum(tec_sale_items.quantity) as SaleQty,
					tec_purchase_items.cost as PurchaseRate,
					tec_products.price  as SaleRate,
					sum(tec_sale_items.cgst_tax_val+tec_sale_items.sgst_tax_val) as vat,
					sum(tec_sale_items.quantity *tec_purchase_items.cost) as PurchaseValue,
					sum(tec_sale_items.quantity * tec_products.price )  as SaleValue,
					sum((tec_sale_items.quantity * tec_products.price)-(tec_sale_items.quantity * tec_purchase_items.cost)) as NetProfit
					from tec_sales
					LEFT OUTER join tec_sale_items on tec_sale_items.sale_id=tec_sales.id 
					LEFT OUTER join tec_purchases on tec_purchases.batch_no=tec_sales.batchno 
					LEFT OUTER join tec_purchase_items on tec_purchase_items.product_id=tec_sale_items.product_id 
					LEFT OUTER join tec_products on tec_products.id=tec_sale_items.product_id 
					WHERE  DATE(tec_sales.DATE)=$start_date and tec_sales.status != 'paid' group by tec_sale_items.product_name AND
					tec_sales.created_by > 0 ";
        //date_format(tec_sales.date, '%Y-%m-%d') between ".$start_date." and ".$end_date."
        
				$query = $this->db->query($sql);
		 
				if(JSON_ENCODE($query)!="false") 
				{
					$jsonVal='{"draw":0,"recordsTotal":0,"recordsFiltered":0,"data":'.JSON_ENCODE($query->result()).'}';
					echo $jsonVal;
				}
				else
				{
					$jsonVal='{"draw":0,"recordsTotal":0,"recordsFiltered":0,"data":[]}';
					echo $jsonVal;
				}
						//echo JSON_ENCODE({"draw":0,"recordsTotal":1,"recordsFiltered":1,"data":);
					// echo json_encode($query->result());
		}
	
	
	 function get_today() {
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
				if(JSON_ENCODE($start_date)=='null')
				{
					$start_date=JSON_ENCODE(date('Y-m-d'));
				}
				else
				{
					$start_date= JSON_ENCODE($start_date);
				}
				if(JSON_ENCODE($end_date)=='null')
				{
					$end_date=JSON_ENCODE(date('Y-m-d'));
				}
				else
				{
					$end_date= JSON_ENCODE($end_date);
				}
				//  + sum(tec_sale_items.cgst_tax_val+tec_sale_items.sgst_tax_val)
				/* 
				select tec_sale_items.product_name as Item,
				sum(tec_sale_items.quantity) as SaleQty,
				sum(tec_purchase_items.cost) as PurchaseRate,
				tec_products.price  as SaleRate,
				sum(tec_sale_items.cgst_tax_val+tec_sale_items.sgst_tax_val) as vat,
				sum(tec_sale_items.quantity *tec_purchase_items.cost) as PurchaseValue,
				sum(tec_sale_items.quantity * tec_products.price )  as SaleValue,
				sum((tec_sale_items.quantity * tec_products.price)-(tec_sale_items.quantity * tec_purchase_items.cost)) as NetProfit
				from tec_sales
				LEFT OUTER join tec_sale_items on tec_sale_items.sale_id=tec_sales.id 
				LEFT OUTER join tec_purchases on tec_purchases.batch_no=tec_sales.batchno 
				LEFT OUTER join tec_purchase_items on tec_purchase_items.purchase_id=tec_sale_items.product_id 
				LEFT OUTER join tec_products on tec_products.id=tec_sale_items.product_id 
				*/
				$sql ="
					select tec_sale_items.product_name as Item,
					sum(tec_sale_items.quantity) as SaleQty,
					tec_purchase_items.cost as PurchaseRate,
					tec_products.price  as SaleRate,
					sum(tec_sale_items.cgst_tax_val+tec_sale_items.sgst_tax_val) as vat,
					sum(tec_sale_items.quantity *tec_purchase_items.cost) as PurchaseValue,
					sum(tec_sale_items.quantity * tec_products.price )  as SaleValue,
					sum((tec_sale_items.quantity * tec_products.price)-(tec_sale_items.quantity * tec_purchase_items.cost)) as NetProfit
					from tec_sales
					LEFT OUTER join tec_sale_items on tec_sale_items.sale_id=tec_sales.id 
					LEFT OUTER join tec_purchases on tec_purchases.batch_no=tec_sales.batchno 
					LEFT OUTER join tec_purchase_items on tec_purchase_items.product_id=tec_sale_items.product_id 
					LEFT OUTER join tec_products on tec_products.id=tec_sale_items.product_id 
					WHERE  DATE(tec_sales.DATE)=$start_date and tec_sales.status = 'paid' group by tec_sale_items.product_name AND
					tec_sales.created_by > 0 ";
        //date_format(tec_sales.date, '%Y-%m-%d') between ".$start_date." and ".$end_date."
        
				$query = $this->db->query($sql);
		 
				if(JSON_ENCODE($query)!="false") 
				{
					$jsonVal='{"draw":0,"recordsTotal":0,"recordsFiltered":0,"data":'.JSON_ENCODE($query->result()).'}';
					echo $jsonVal;
				}
				else
				{
					$jsonVal='{"draw":0,"recordsTotal":0,"recordsFiltered":0,"data":[]}';
					echo $jsonVal;
				}
						//echo JSON_ENCODE({"draw":0,"recordsTotal":1,"recordsFiltered":1,"data":);
					// echo json_encode($query->result());
		}
	
	
	    function get_gst() {
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;

        $this->load->library('datatables');
		
		   $this->datatables
        ->select("date(`date`) as Invoice_Date,
         count({$this->db->dbprefix('sales')}.Id) as Total_Inv,
        (tec_sale_items.cgst_tax + tec_sale_items.sgst_tax) AS VAT,
        (tec_sale_items.cgst_tax_val) AS VAT_Amtx,
		sum({$this->db->dbprefix('sale_items')}.item_discount) + sum({$this->db->dbprefix('sales')}.order_discount) as 'Discount',
		sum({$this->db->dbprefix('sales')}.order_discount) as 'xiscount',
		{$this->db->dbprefix('sale_items')}.cgst_tax as 'CGST',
		sum({$this->db->dbprefix('sale_items')}.cgst_tax_val)*2 as VAT_Amt, 
		{$this->db->dbprefix('sale_items')}.sgst_tax as 'SGST' ,
		sum({$this->db->dbprefix('sale_items')}.sgst_tax_val) as SGST_Amt")
        ->from('sales')
				->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
        ->group_by("date(`date`),{$this->db->dbprefix('sale_items')}.cgst_tax,{$this->db->dbprefix('sale_items')}.sgst_tax");
		      

		 
        if($customer) { $this->datatables->where('customer_id', $customer); }
        if($user) { $this->datatables->where('created_by', $user); }
        if($start_date) { $this->datatables->where('date >=', $start_date); }
        if($end_date) { $this->datatables->where('date <=', $end_date); }

        echo $this->datatables->generate();
    }
	function month_gst() {
	 
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;

		 if(!$start_date){
			 $start_date=date('Y-m-01');
		 }
		 	 if(!$end_date){
			 $end_date=date('Y-m-d');
		 }
        $this->load->library('datatables');
		
		  if($start_date) { 
		   $this->datatables
        ->select("Concat(Concat(MONTHNAME({$this->db->dbprefix('sales')}.date),' - '),YEAR({$this->db->dbprefix('sales')}.date)) as 'Date',
 	(SELECT sum(S.total) FROM `tec_sales` S where cast(date as date) >='".$start_date."' and cast(date as date) <='".$end_date."') as 'Total',
		CONCAT({$this->db->dbprefix('sale_items')}.cgst_tax, ' %') as 'GST',
		count(distinct({$this->db->dbprefix('sales')}.Id)) as 'Sales',
	sum( {$this->db->dbprefix('sale_items')}.sgst_tax_val) AS 'SGST', 
		sum({$this->db->dbprefix('sale_items')}.cgst_tax_val) as 'CGST',
(SELECT sum(S.grand_total) FROM `tec_sales` S where cast(date as date) >='".$start_date."' and cast(date as date) <='".$end_date."') as 'grandtotal'		")
        ->from('sales')
        ->join('sale_items', 'sale_items.sale_id=sales.id', 'inner')
        ->group_by("MONTHNAME({$this->db->dbprefix('sales')}.date),YEAR({$this->db->dbprefix('sales')}.date),{$this->db->dbprefix('sale_items')}.cgst_tax,{$this->db->dbprefix('sale_items')}.sgst_tax");
		      
		  }
		  else{
			     $this->datatables
        ->select("Concat(Concat(MONTHNAME({$this->db->dbprefix('sales')}.date),' - '),YEAR({$this->db->dbprefix('sales')}.date)) as 'Date',
 	(SELECT sum(S.total) FROM `tec_sales` S  ) as 'Total',
		CONCAT({$this->db->dbprefix('sale_items')}.cgst_tax, ' %') as 'GST',
		count(distinct({$this->db->dbprefix('sales')}.Id)) as 'Sales',
	sum( {$this->db->dbprefix('sale_items')}.sgst_tax_val) AS 'SGST', 
		sum({$this->db->dbprefix('sale_items')}.cgst_tax_val) as 'CGST',
(SELECT sum(S.grand_total) FROM `tec_sales` S )as 'grandtotal'		")
        ->from('sales')
        ->join('sale_items', 'sale_items.sale_id=sales.id', 'inner')
        ->group_by("MONTHNAME({$this->db->dbprefix('sales')}.date),YEAR({$this->db->dbprefix('sales')}.date),{$this->db->dbprefix('sale_items')}.cgst_tax,{$this->db->dbprefix('sale_items')}.sgst_tax");
		      
		  }
		 
        if($customer) { $this->datatables->where('customer_id', $customer); }
        if($user) { $this->datatables->where('created_by', $user); }
        if($start_date) { $this->datatables->where('cast(date as date) >=', $start_date); }
        if($end_date) { $this->datatables->where('cast(date as date) <=', $end_date); }

        echo $this->datatables->generate();
    }
	  function dayily_gst() {
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;

        $this->load->library('datatables');
		
		   $this->datatables
        ->select("cast({$this->db->dbprefix('sales')}.date as date) as 'Date',
 	GROUP_CONCAT(DISTINCT({$this->db->dbprefix('sales')}.id)) as 'BillNo',
		CONCAT({$this->db->dbprefix('sale_items')}.cgst_tax, ' %') as 'GST',
		count(distinct({$this->db->dbprefix('sales')}.Id)) as 'Sales',
	sum( {$this->db->dbprefix('sale_items')}.sgst_tax_val) AS 'SGST', 
		sum({$this->db->dbprefix('sale_items')}.cgst_tax_val) as 'CGST'  ")
        ->from('sales')
        ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
        ->group_by("date(`date`),{$this->db->dbprefix('sale_items')}.cgst_tax,{$this->db->dbprefix('sale_items')}.sgst_tax");
		      
		  
		 
        if($customer) { $this->datatables->where('customer_id', $customer); }
        if($user) { $this->datatables->where('created_by', $user); }
        if($start_date) { $this->datatables->where('cast(date as date) >=', $start_date); }
        if($end_date) { $this->datatables->where('cast(date as date) <=', $end_date); }

        echo $this->datatables->generate();
    }

	function gst() {
        if ($this->input->post('customer')) {
            $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : NULL;
            $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : NULL;
            $user = $this->input->post('user') ? $this->input->post('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getGSTReports($start_date, $end_date);
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['page_title'] = $this->lang->line("VAT Report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang("VAT Report")));
        $meta = array('page_title' => lang("VAT Report"), 'bc' => $bc);
        $this->page_construct('reports/gst', $this->data, $meta);
    }
	function monthlygst() {
        if ($this->input->post('customer')) {
            $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : NULL;
            $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : NULL;
            $user = $this->input->post('user') ? $this->input->post('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getGSTReports($start_date, $end_date);
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();
		
		$first_day_this_month = date('m-01-Y'); // hard-coded '01' for first day
$last_day_this_month  = date('m-t-Y');

		    $this->data['startdate'] = $first_day_this_month;
			    $this->data['enddate'] = $last_day_this_month;
        $this->data['page_title'] = $this->lang->line("Monthly GST Report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang("Monthly GST Report")));
        $meta = array('page_title' => lang("Monthly GST Report"), 'bc' => $bc);
        $this->page_construct('reports/monthlygst', $this->data, $meta);
    }

		function dailygst() {
        if ($this->input->post('customer')) {
            $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : NULL;
            $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : NULL;
            $user = $this->input->post('user') ? $this->input->post('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getGSTReports($start_date, $end_date);
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();
		
		$first_day_this_month = date('m-01-Y'); // hard-coded '01' for first day
$last_day_this_month  = date('m-t-Y');

		    $this->data['startdate'] = $first_day_this_month;
			    $this->data['enddate'] = $last_day_this_month;
        $this->data['page_title'] = $this->lang->line("Daily GST Report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang("Daily GST Report")));
        $meta = array('page_title' => lang("Daily GST Report"), 'bc' => $bc);
        $this->page_construct('reports/dailygst', $this->data, $meta);
    }

	
	function today() {
        if ($this->input->post('customer')) {
            $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : NULL;
            $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : NULL;
            $user = $this->input->post('user') ? $this->input->post('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getGSTReports($start_date, $end_date);
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['page_title'] = $this->lang->line("Today Sales Report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang("Today Sales ")));
        $meta = array('page_title' => lang("Today Sales "), 'bc' => $bc);
        $this->page_construct('reports/today', $this->data, $meta);
    }
	
    function products() {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['products'] = $this->reports_model->getAllProducts();
        $this->data['page_title'] = $this->lang->line("products_report");
        $this->data['page_title'] = $this->lang->line("products_report");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('products_report')));
        $meta = array('page_title' => lang('products_report'), 'bc' => $bc);
        $this->page_construct('reports/products', $this->data, $meta);
    }

    function get_products() {
        $product = $this->input->get('product') ? $this->input->get('product') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;
        //COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity)*".$this->db->dbprefix('products').".cost, 0) as cost,
        $this->load->library('datatables');
        $this->datatables
				->select("tec_products.id as id, tec_products.name,
				 	tec_products.code,
					COALESCE(sum(tec_sale_items.quantity), 0) as sold,
					COALESCE(sum(tec_sale_items.cgst_tax_val*2), 0) as tax, 
					COALESCE(sum(tec_sale_items.quantity)*tec_sale_items.cost, 0) as cost, 
					COALESCE(sum(tec_sale_items.subtotal), 0) as income")
					// (COALESCE(sum(tec_sale_items.subtotal), 0)) - COALESCE(sum(tec_sale_items.quantity)* tec_sale_items.cost, 0) -COALESCE(((sum(tec_sale_items.subtotal)*.tec_products.tax)/100), 0) as profit", FALSE)
        // ->select($this->db->dbprefix('products').".id as id, ".$this->db->dbprefix('products').".name, ".$this->db->dbprefix('products').".code, COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity), 0) as sold, ROUND(COALESCE(((sum(".$this->db->dbprefix('sale_items').".subtotal)*".$this->db->dbprefix('products').".tax)/100), 0), 2) as tax, COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity)*".$this->db->dbprefix('sale_items').".cost, 0) as cost, COALESCE(sum(".$this->db->dbprefix('sale_items').".subtotal), 0) as income, ROUND((COALESCE(sum(".$this->db->dbprefix('sale_items').".subtotal), 0)) - COALESCE(sum(".$this->db->dbprefix('sale_items').".quantity)*".$this->db->dbprefix('sale_items').".cost, 0) -COALESCE(((sum(".$this->db->dbprefix('sale_items').".subtotal)*".$this->db->dbprefix('products').".tax)/100), 0), 2)
        //     as profit", FALSE)
        ->from('sale_items')
        ->join('products', 'sale_items.product_id=products.id', 'left')
        ->join('sales', 'sale_items.sale_id=sales.id', 'left');
        if ($this->session->userdata('store_id')) {
            $this->datatables->where('sales.store_id', $this->session->userdata('store_id'));
        }
        $this->datatables->group_by('products.id');

        if($product) { $this->datatables->where('products.id', $product); }
        if($start_date) { $this->datatables->where('date >=', $start_date); }
        if($end_date) { $this->datatables->where('date <=', $end_date); }
        echo $this->datatables->generate();
    }

    function profit( $income, $cost, $tax) {
        return floatval($income)." - ".floatval($cost)." - ".floatval($tax);
    }

    function top_products() {
        $this->data['topProducts'] = $this->reports_model->topProducts();
        $this->data['topProducts1'] = $this->reports_model->topProducts1();
        $this->data['topProducts3'] = $this->reports_model->topProducts3();
        $this->data['topProducts12'] = $this->reports_model->topProducts12();
        $this->data['page_title'] = $this->lang->line("top_products");
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('top_products')));
        $meta = array('page_title' => lang('top_products'), 'bc' => $bc);
        $this->page_construct('reports/top', $this->data, $meta);
    }

    function registers() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['users'] = $this->reports_model->getAllStaff();
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('registers_report')));
        $meta = array('page_title' => lang('registers_report'), 'bc' => $bc);
        $this->page_construct('reports/registers', $this->data, $meta);
    }

    function get_register_logs() {
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;

        $this->load->library('datatables');
        if ($this->db->dbdriver == 'sqlite3') {
            $this->datatables->select("{$this->db->dbprefix('registers')}.id as id, date, closed_at, ({$this->db->dbprefix('users')}.first_name || ' ' || {$this->db->dbprefix('users')}.last_name || '<br>' || {$this->db->dbprefix('users')}.email) as user, cash_in_hand, (total_cc_slips || ' (' || total_cc_slips_submitted || ')') as cc_slips, (total_cheques || ' (' || total_cheques_submitted || ')') as total_cheques, (total_cash || ' (' || total_cash_submitted || ')') as total_cash, note", FALSE);
        } else {
            $this->datatables->select("{$this->db->dbprefix('registers')}.id as id, date, closed_at, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name, '<br>', " . $this->db->dbprefix('users') . ".email) as user, CAST(cash_in_hand AS DECIMAL(11,3)) as cash_in_hand , CONCAT(total_cc_slips, ' (', total_cc_slips_submitted, ')') as cc_slips, CONCAT(total_cheques, ' (', total_cheques_submitted, ')') as total_cheques, CONCAT(CAST(total_cash AS DECIMAL(11,3)), ' (', total_cash_submitted, ')') as total_cash, note", FALSE);
        }
        $this->datatables->from("registers")
        ->join('users', 'users.id=registers.user_id', 'left');

        if ($user) {
            $this->datatables->where('registers.user_id', $user);
        }
        if ($start_date) {
            $this->datatables->where('date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }
        if ($this->session->userdata('store_id')) {
            $this->datatables->where('registers.store_id', $this->session->userdata('store_id'));
        }

        echo $this->datatables->generate();


    }

    function payments() {
        if ($this->input->post('customer')) {
            $start_date = $this->input->post('start_date') ? $this->input->post('start_date') : NULL;
            $end_date = $this->input->post('end_date') ? $this->input->post('end_date') : NULL;
            $user = $this->input->post('user') ? $this->input->post('user') : NULL;
            $this->data['total_sales'] = $this->reports_model->getTotalCustomerSales($this->input->post('customer'), $user, $start_date, $end_date);
        }
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['users'] = $this->reports_model->getAllStaff();
        $this->data['customers'] = $this->reports_model->getAllCustomers();
        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => lang('payments_report')));
        $meta = array('page_title' => lang('payments_report'), 'bc' => $bc);
        $this->page_construct('reports/payments', $this->data, $meta);
    }

    function get_payments() {
        $user = $this->input->get('user') ? $this->input->get('user') : NULL;
        $ref = $this->input->get('payment_ref') ? $this->input->get('payment_ref') : NULL;
        $sale_id = $this->input->get('sale_no') ? $this->input->get('sale_no') : NULL;
        $customer = $this->input->get('customer') ? $this->input->get('customer') : NULL;
        $paid_by = $this->input->get('paid_by') ? $this->input->get('paid_by') : NULL;
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;

        $this->load->library('datatables');
        $this->datatables
        ->select("{$this->db->dbprefix('payments')}.id as id, {$this->db->dbprefix('payments')}.date,{$this->db->dbprefix('payments')}.note,  {$this->db->dbprefix('payments')}.reference as ref, {$this->db->dbprefix('sales')}.id as sale_no, paid_by, amount")
        ->from('payments')
        ->join('sales', 'payments.sale_id=sales.id', 'left')
        ->group_by('payments.id');

        if ($this->session->userdata('store_id')) {
            $this->datatables->where('payments.store_id', $this->session->userdata('store_id'));
        }
        if ($user) {
            $this->datatables->where('payments.created_by', $user);
        }
        if ($ref) {
            $this->datatables->where('payments.reference', $ref);
        }
        if ($paid_by) {
            $this->datatables->where('payments.paid_by', $paid_by);
        }
        if ($sale_id) {
            $this->datatables->where('sales.id', $sale_id);
        }
        if ($customer) {
            $this->datatables->where('sales.customer_id', $customer);
        }
        if ($start_date) {
            $this->datatables->where($this->db->dbprefix('payments').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        echo $this->datatables->generate();

    }

    function alerts() {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('stock_alert');
        $bc = array(array('link' => '#', 'page' => lang('stock_alert')));
        $meta = array('page_title' => lang('stock_alert'), 'bc' => $bc);
        $this->page_construct('reports/alerts', $this->data, $meta);

    }

    function get_alerts() {
        $this->load->library('datatables');
        $this->datatables->select($this->db->dbprefix('products').".id as pid, ".$this->db->dbprefix('products').".image as image, ".$this->db->dbprefix('products').".code as code, ".$this->db->dbprefix('products').".name as pname, type, ".$this->db->dbprefix('categories').".name as cname, (CASE WHEN psq.quantity IS NULL THEN 0 ELSE psq.quantity END) as quantity, alert_quantity, tax, tax_method, cost, (CASE WHEN psq.price > 0 THEN psq.price ELSE {$this->db->dbprefix('products')}.price END) as price", FALSE)
        ->from('products')
        ->join('categories', 'categories.id=products.category_id')
        ->join("( SELECT * from {$this->db->dbprefix('product_store_qty')} WHERE store_id = {$this->session->userdata('store_id')}) psq", 'products.id=psq.product_id', 'left')
        ->where("(CASE WHEN psq.quantity IS NULL THEN 0 ELSE psq.quantity END) < {$this->db->dbprefix('products')}.alert_quantity", NULL, FALSE)
        ->group_by('products.id');
        $this->datatables->add_column("Actions", "<div class='text-center'><a href='#' class='btn btn-xs btn-primary ap tip' data-id='$1' title='".lang('add_to_purcahse_order')."'><i class='fa fa-plus'></i></a></div>", "pid");
        $this->datatables->unset_column('pid');
        echo $this->datatables->generate();
    }

}
