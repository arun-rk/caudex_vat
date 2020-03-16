<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }
        if ( ! $this->session->userdata('store_id')) {
            $this->session->set_flashdata('warning', lang("please_select_store"));
            redirect('stores');
        }
        $this->load->library('form_validation');
        $this->load->model('sales_model');

        $this->digital_file_types = 'zip|pdf|doc|docx|xls|xlsx|jpg|png|gif';

    }

    function index() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('sales');
        $bc = array(array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('sales/index', $this->data, $meta);
    }

    function get_sales() {
				$customer = $this->input->post('customer');
				$status = $this->input->post('statusx');
				$status  = trim($status);
        $this->load->library('datatables');
        if ($this->db->dbdriver == 'sqlite3') {
            $this->datatables->select("tec_sales.id, strftime('%Y-%m-%d %H:%M', tec_sales.date) as date, tec_sales.customer_name, tec_sales.total, tec_sales.total_tax, tec_sales.total_discount, tec_sales.grand_total, tec_sales.paid, tec_sales.status,tec_sales_returns.sale_id");
        } else {
            $this->datatables->select("tec_sales.id, DATE_FORMAT(tec_sales.date, '%Y-%m-%d %H:%i') as date, tec_sales.customer_name, tec_sales.total, tec_sales.total_tax, tec_sales.total_discount, tec_sales.grand_total, tec_sales.paid, tec_sales.status,tec_sales_returns.sale_id");
        }
        $this->datatables->from('tec_sales');
        $this->datatables->join('tec_sales_returns','tec_sales_returns.sale_id = tec_sales.id','left');
        if (!$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('tec_sales.created_by', $this->session->userdata('user_id'));
        }
        if($customer) { $this->datatables->where('tec_sales.customer_id', $customer); } 
        if($status) { $this->datatables->where('tec_sales.status', $status); } 
        $this->datatables->where('tec_sales.store_id', $this->session->userdata('store_id'));
        $this->datatables->group_by('tec_sales.id');
        $this->datatables->add_column("Actions", '$1', "id");
        // $this->datatables->add_column("Actions", "<div class='text-center' style='width: 147px;'><div class='btn-group'><a href='" . site_url('pos/view/$1/1') . "' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-list'></i></a> <a href='".site_url('sales/payments/$1')."' title='" . lang("view_payments") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-money'></i></a> <a href='".site_url('sales/add_payment/$1')."' title='" . lang("add_payment") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-briefcase'></i></a> <a data-href='" . site_url('sales/returns/$1') . "' onClick=\"returnsale('". lang('You are going to return sale, please click ok to return.') ."',this)\" title='".lang("Returns")."' class='tip btn btn-primary  btn-xs'><i class='fa fa-reply'></i></a><a href='" . site_url('pos/?edit=$1') . "' title='".lang("edit_invoice")."' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('sales/delete/$1') . "' onClick=\"return confirm('". lang('alert_x_sale') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");
        // $this->datatables->unset_column('id');
				// $x= $this->db->error();
				echo $this->datatables->generate();
				// $x= $this->db->error();
			}

    function opened() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('opened_bills');
        $bc = array(array('link' => '#', 'page' => lang('opened_bills')));
        $meta = array('page_title' => lang('opened_bills'), 'bc' => $bc);
        $this->page_construct('sales/opened', $this->data, $meta);
    }
	 function quotation() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Quotations');
        $bc = array(array('link' => '#', 'page' => lang('Quotations')));
        $meta = array('page_title' => lang('Quotations'), 'bc' => $bc);
        $this->page_construct('sales/quotation', $this->data, $meta);
    }
	function salesreturns() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Returns Bills');
        $bc = array(array('link' => '#', 'page' => lang('Returns Bills')));
        $meta = array('page_title' => lang('Returns Bills'), 'bc' => $bc);
        $this->page_construct('sales/salesreturns', $this->data, $meta);
    }
	function salesitemreturns() {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('Return Item Bills');
        $bc = array(array('link' => '#', 'page' => lang('Return Item Bills')));
        $meta = array('page_title' => lang('Return Item Bills'), 'bc' => $bc);
        $this->page_construct('sales/salesitemreturns', $this->data, $meta);
    }

    function get_opened_list() {

        $this->load->library('datatables');
        if ($this->db->dbdriver == 'sqlite3') {
            $this->datatables->select("id, date, customer_name, hold_ref, (total_items || ' (' || total_quantity || ')') as items, grand_total", FALSE);
        } else {
            $this->datatables->select("id, date, customer_name, hold_ref, CONCAT(total_items, ' (', total_quantity, ')') as items, grand_total", FALSE);
        }
        $this->datatables->from('suspended_sales');
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
            $this->datatables->where('created_by', $user_id);
        }
        $this->datatables->where('store_id', $this->session->userdata('store_id'));
        $this->datatables->add_column("Actions",
            "<div class='text-center'><div class='btn-group'><a href='" . site_url('pos/?hold=$1') . "' title='".lang("click_to_add")."' class='tip btn btn-info btn-xs'><i class='fa fa-th-large'></i></a>
            <a href='" . site_url('sales/delete_holded/$1') . "' onClick=\"return confirm('". lang('alert_x_holded') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id")
        ->unset_column('id');

        echo $this->datatables->generate();

    }
	 function get_quotation_list() {

        $this->load->library('datatables');
        if ($this->db->dbdriver == 'sqlite3') {
            $this->datatables->select("id, date, customer_name, hold_ref, (total_items || ' (' || total_quantity || ')') as items, grand_total", FALSE);
        } else {
            $this->datatables->select("id, date, customer_name, hold_ref, CONCAT(total_items, ' (', total_quantity, ')') as items, grand_total", FALSE);
        }
        $this->datatables->from('estimate_sales');
        if(!$this->Admin) {
            $user_id = $this->session->userdata('user_id');
            $this->datatables->where('created_by', $user_id);
        }
        $this->datatables->where('store_id', $this->session->userdata('store_id'));
        $this->datatables->add_column("Actions",
            "<div class='text-center'><div class='btn-group'><a href='" . site_url('pos/?qut=$1') . "' title='".lang("click_to_add")."' class='tip btn btn-info btn-xs'><i class='fa fa-th-large'></i></a>
			<a href='".site_url('pos/quta/$1')."' title='".lang('print_barcodes')."' class='tip btn btn-default btn-xs'><i class='fa fa-print'></i></a> 
            <a href='" . site_url('sales/delete_quotation/$1') . "' onClick=\"return confirm('". lang('You are going to delete quotation bill, please click ok to delete.') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id")
        ->unset_column('id');

        echo $this->datatables->generate();

    }

	 function get_return_list() {
				
		$customer = $this->input->post('customer');
		$status = $this->input->post('statusx');
		$status  = trim($status);
        $this->load->library('datatables');
        if ($this->db->dbdriver == 'sqlite3') {
            $this->datatables->select("id, strftime('%Y-%m-%d %H:%M', date) as date, customer_name, total, total_tax, total_discount, grand_total, paid, status");
        } else {
            $this->datatables->select("id, DATE_FORMAT(date, '%Y-%m-%d %H:%i') as date, customer_name, total, total_tax, total_discount, grand_total, paid, status");
        }
        $this->datatables->from('sales_returns');
        if (!$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
				}
				
        if($customer) { $this->datatables->where('sales_returns.customer_id', $customer); } 
        if($status) { $this->datatables->where('sales_returns.status', $status); } 
        $this->datatables->where('store_id', $this->session->userdata('store_id'));
        $this->datatables->add_column("Actions", "<div class='text-center' style='width: 147px;'><div class='btn-group'><a href='" . site_url('pos/returnview/$1/1') . "' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-list'></i></a><a href='" . site_url('sales/delete_return/$1') . "' onClick=\"return confirm('". lang('alert_x_sale') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");

        // $this->datatables->unset_column('id');
        echo $this->datatables->generate(); 
        
        
       
    }

	 function get_return_item_list() {

        $this->load->library('datatables');
        if ($this->db->dbdriver == 'sqlite3') {
            $this->datatables->select("id, strftime('%Y-%m-%d %H:%M', date) as date, customer_name, total, total_tax, total_discount, grand_total, paid, status");
        } else {
            $this->datatables->select("id, DATE_FORMAT(date, '%Y-%m-%d %H:%i') as date, customer_name, total, total_tax, total_discount, grand_total, paid, status");
        }
        $this->datatables->from('tec_sale_items_returns');
        if (!$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }
        $this->datatables->where('store_id', $this->session->userdata('store_id'));
        $this->datatables->add_column("Actions", "<div class='text-center' style='width: 147px;'><div class='btn-group'><a href='" . site_url('pos/returnview/$1/1') . "' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-list'></i></a><a href='" . site_url('sales/delete_return/$1') . "' onClick=\"return confirm('". lang('alert_x_sale') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");

        // $this->datatables->unset_column('id');
        echo $this->datatables->generate(); 
        
        
       
    }



    function delete($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }

        if($this->input->get('id')){ $id = $this->input->get('id'); }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('sales');
        }

        if ( $this->sales_model->deleteInvoice($id) ) {
            $this->session->set_flashdata('message', lang("invoice_deleted"));
            redirect('sales');
        }

    }
  function delete_return($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }

        if($this->input->get('id')){ $id = $this->input->get('id'); }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('sales/salesreturns');
        }

        if ( $this->sales_model->deleteReturnInvoice($id) ) {
            $this->session->set_flashdata('message', lang("Return Invoice Deleted"));
            redirect('sales/salesreturns');
        }

    }
	function returns($id = NULL) {
       

        if($this->input->get('id')){ $id = $this->input->get('id'); }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('sales');
        }
		
        $sales = $this->sales_model->getSaleByID($id);
        $salesitems = $this->sales_model->getSaleItemsBySaleID($id);
		   $salepayments = $this->sales_model->getPaymentsBySaleID($id);
	 
		 
		     $Sel_sales = array(
                        'id' => $sales->id,
                        'date' => $sales->date,
                        'customer_id' => $sales->customer_id,
                        'customer_name' => $sales->customer_name,
                        'total' => $sales->total,
                        'product_discount' => $sales->product_discount,
                        'order_discount_id' => $sales->order_discount_id,
                        'order_discount' => $sales->order_discount,
						  'total_discount' => $sales->total_discount,
                         'product_tax' => $sales->product_tax,
						  'order_tax_id' => $sales->order_tax_id,
                        'order_tax' => $sales->order_tax,
                        'total_tax' => $sales->total_tax,
                        'grand_total' => $sales->grand_total,
                        'total_items' => $sales->total_items,
                        'total_quantity' => $sales->total_quantity,
						'paid' => $sales->paid,
						  'created_by' => $sales->created_by,
                         'updated_by' => $sales->updated_by,
						  'updated_at' => $sales->updated_at,
                        'note' => $sales->note,
                        'status' =>'Return',
                        'rounding' => $sales->rounding,
                        'store_id' => $sales->store_id,
                        'hold_ref' => $sales->hold_ref,
						);
					 
					 
						
						   $Sel_salepayments = array(
                        'id' => $salepayments->id,
                        'date' => $salepayments->date,
                        'sale_id' => $salepayments->sale_id,
                        'customer_id' => $salepayments->customer_id,
                        'transaction_id' => $salepayments->transaction_id,
                        'paid_by' => $salepayments->paid_by,
                        'cheque_no' => $salepayments->cheque_no,
                        'cc_no' => $salepayments->cc_no,
						  'cc_holder' => $salepayments->cc_holder,
                         'cc_month' => $salepayments->cc_month,
						  'cc_year' => $salepayments->cc_year,
                        'cc_type' => $salepayments->cc_type,
                        'amount' => $salepayments->amount,
                        'currency' => $salepayments->currency,
                        'created_by' => $salepayments->created_by,
                        'attachment' => $salepayments->attachment,
						'note' => $salepayments->note,
						  'pos_paid' => $salepayments->pos_paid,
                         'pos_balance' => $salepayments->pos_balance,
						  'gc_no' => $salepayments->gc_no,
						  'reference' => $salepayments->reference,
						  'updated_by' => $salepayments->updated_by,
                         'updated_at' => $salepayments->updated_at,
						  'store_id' => $salepayments->store_id,
						);
						
			 
		  if ( $this->sales_model->AddReturnInvoice($Sel_sales,$Sel_salepayments))
		  {
			    
		   foreach ($salesitems as $salesitem) {
			   
			  // echo $salesitem->product_id;
							     $Sel_salesitems = array(
                        'id' => $salesitem->id,
                        'sale_id' => $salesitem->sale_id,
                        'product_id' => $salesitem->product_id,
                        'quantity' => $salesitem->quantity,
                        'unit_price' => $salesitem->unit_price,
                        'net_unit_price' => $salesitem->net_unit_price,
                        'discount' => $salesitem->discount,
                        'item_discount' => $salesitem->item_discount,
						  'cgst_tax' => $salesitem->cgst_tax,
                         'sgst_tax' => $salesitem->sgst_tax,
						  'cgst_tax_val' => $salesitem->cgst_tax_val,
                        'sgst_tax_val' => $salesitem->sgst_tax_val,
                        'tax' => $salesitem->tax,
                        'item_tax' => $salesitem->item_tax,
                        'subtotal' => $salesitem->subtotal,
                        'real_unit_price' => $salesitem->real_unit_price,
						'cost' => $salesitem->cost,
						  'product_code' => $salesitem->product_code,
                         'product_name' => $salesitem->product_name,
						  'comment' => $salesitem->comment,
						);
						 $this->sales_model->AddReturnItems($Sel_salesitems);
					 }
					 
        if ( $this->sales_model->deleteInvoice($id) ) {
		
            $this->session->set_flashdata('message', lang("Sale returned"));
           redirect('sales');
        }
		  }
		  else{
			   $this->session->set_flashdata('error', lang("Error !"));
           redirect('sales');
		  }
		 
			 
  

    }

    function delete_holded($id = NULL) {

        if($this->input->get('id')){ $id = $this->input->get('id'); }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('sales/opened');
        }

        if ( $this->sales_model->deleteOpenedSale($id) ) {
            $this->session->set_flashdata('message', lang("opened_bill_deleted"));
            redirect('sales/opened');
        }

    }
	 function delete_quotation($id = NULL) {

        if($this->input->get('id')){ $id = $this->input->get('id'); }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('sales/opened');
        }

        if ( $this->sales_model->deletequotation($id) ) {
            $this->session->set_flashdata('message', lang("Quotation deleted"));
            redirect('sales/quotation');
        }

    }

    /* -------------------------------------------------------------------------------- */

    function payments($id = NULL) {
        $this->data['payments'] = $this->sales_model->getSalePayments($id);
        $this->load->view($this->theme . 'sales/payments', $this->data);
    }

    function payment_note($id = NULL) {
        $payment = $this->sales_model->getPaymentByID($id);
        $inv = $this->sales_model->getSaleByID($payment->sale_id);
        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
        $this->data['inv'] = $inv;
        $this->data['payment'] = $payment;
        $this->data['page_title'] = $this->lang->line("payment_note");

        $this->load->view($this->theme . 'sales/payment_note', $this->data);
    }

    function add_payment($id = NULL, $cid = NULL) {
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Admin) {
                $date = $this->input->post('date');
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $payment = array(
                'date' => $date,
                'sale_id' => $id,
                'customer_id' => $cid,
                'reference' => $this->input->post('reference'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'gc_no' => $this->input->post('gift_card_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata('user_id'),
                'store_id' => $this->session->userdata('store_id'),
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = 2048;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            // $this->tec->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            $this->tec->dd();
        }


        if ($this->form_validation->run() == true && $this->sales_model->addPayment($payment)) {
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $sale = $this->sales_model->getSaleByID($id);
            $this->data['inv'] = $sale;
            $this->load->view($this->theme . 'sales/add_payment', $this->data);
        }
    }

    function edit_payment($id = NULL, $sid = NULL) {

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            $payment = array(
                'sale_id' => $sid,
                'reference' => $this->input->post('reference'),
                'amount' => $this->input->post('amount-paid'),
                'paid_by' => $this->input->post('paid_by'),
                'cheque_no' => $this->input->post('cheque_no'),
                'gc_no' => $this->input->post('gift_card_no'),
                'cc_no' => $this->input->post('pcc_no'),
                'cc_holder' => $this->input->post('pcc_holder'),
                'cc_month' => $this->input->post('pcc_month'),
                'cc_year' => $this->input->post('pcc_year'),
                'cc_type' => $this->input->post('pcc_type'),
                'note' => $this->input->post('note'),
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            if ($this->Admin) {
                $payment['date'] = $this->input->post('date');
            }

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'files/';
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = 2048;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->tec->print_arrays($payment);

        } elseif ($this->input->post('edit_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            $this->tec->dd();
        }


        if ($this->form_validation->run() == true && $this->sales_model->updatePayment($id, $payment)) {
            $this->session->set_flashdata('message', lang("payment_updated"));
            redirect("sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $payment = $this->sales_model->getPaymentByID($id);
            if($payment->paid_by != 'cash') {
                $this->session->set_flashdata('error', lang('only_cash_can_be_edited'));
                $this->tec->dd();
            }
            $this->data['payment'] = $payment;
            $this->load->view($this->theme . 'sales/edit_payment', $this->data);
        }
    }

    function delete_payment($id = NULL) {

        if($this->input->get('id')){ $id = $this->input->get('id'); }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ( $this->sales_model->deletePayment($id) ) {
            $this->session->set_flashdata('message', lang("payment_deleted"));
            redirect('sales');
        }
    }

    public function status() {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('sales');
        }
        $this->form_validation->set_rules('sale_id', lang('sale_id'), 'required');
        $this->form_validation->set_rules('status', lang('status'), 'required');

        if ($this->form_validation->run() == true) {

            $this->sales_model->updateStatus($this->input->post('sale_id', TRUE), $this->input->post('status', TRUE));
            $this->session->set_flashdata('message', lang('status_updated'));
            redirect('sales');

        } else {

            $this->session->set_flashdata('error', validation_errors());
            redirect('sales');

        }
		}
		
		function add_outstanding_payment() {
			$this->load->helper('security');
			if ($this->input->get('id')) {
					$id = $this->input->get('id');
			}
      // get the total amount and methord 
			$data = new stdClass();
			$data->total_amount = $this->input->post('amount-paid');
			$data->paid_by = $this->input->post('paid_by');
			$data->date = $this->input->post('date');
			$data->note = $this->input->post('note');
			$data->reference = $this->input->post('reference');
			$data->cus_id = $this->input->post('ar_cus_id');
			$data->cash_amount_val = $this->input->post('cash_amount_val');
			$data->card_amount_val = $this->input->post('card_amount_val');
			$data->cheque_amount_val = $this->input->post('cheque_amount_val');
			$temp = clone $data;
			$dues = $this->sales_model->get_customer_dues($data->cus_id);
			$customer = $this->sales_model->getCustomerByID($data->cus_id);
			// var_dump($_POST);
			// die();
			// if(!isset($data->cus_id)){
			// 	redirect('pos');
			// }
			$payment = array(
				'date' => $data->date,
				'sale_id' => 0,
				'customer_id' => $data->cus_id ,
				'reference' => $data->reference,
				'amount' => $dues,
				'paid_by' => 	$data->paid_by,
				'cheque_no' => $this->input->post('cheque_no'),
				'gc_no' => $this->input->post('gift_card_no'),
				'cc_no' => $this->input->post('pcc_no'),
				'cc_holder' => $this->input->post('pcc_holder'),
				'cc_month' => $this->input->post('pcc_month'),
				'cc_year' => $this->input->post('pcc_year'),
				'cc_type' => $this->input->post('pcc_type'),
				'note' => $data->note,
				'created_by' => '',
				'store_id' => '',
			);

			if ($_FILES['userfile']['size'] > 0) {
					$this->load->library('upload');
					$config['upload_path'] = 'files/';
					$config['allowed_types'] = $this->digital_file_types;
					$config['max_size'] = 2048;
					$config['overwrite'] = FALSE;
					$config['encrypt_name'] = TRUE;
					$this->upload->initialize($config);
					if (!$this->upload->do_upload()) {
							$error = $this->upload->display_errors();
							$this->session->set_flashdata('error', $error);
							redirect($_SERVER["HTTP_REFERER"]);
					}
					$photo = $this->upload->file_name;
					$payment['attachment'] = $photo;
			}
			// $x = $this->sales_model->addPayment($payment);
			if ( $this->sales_model->addPayment($payment)) {
				$this->session->set_flashdata('message', lang("payment_added"));
		} else {
				$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
				$this->load->view($this->theme . 'pos', $this->data);
		}
				// $this->db->where;
				$res = $this->db->update('tec_customers',
				array('total_balance'=>($dues-$data->total_amount )),
				array('id'=>$data->cus_id));
				$x = $this->db->error();
				if(!$res){
					$this->data['error'] = 'Somthing is wrong ! Please try again later !';
					$this->load->view($this->theme . 'pos', $this->data);
				}
				$d_n =$this->sales_model->get_customer_dues($data->cus_id);
				$this->data['dues'] = $dues;
				$this->data['temp'] =  $temp ;
				$this->data['customer'] =  $customer ;
				$this->data['dues_now'] =  $d_n;
				$this->load->view($this->theme . 'sales/view_out', $this->data);
			}
// die();
// 			$this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
// 			$this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
// 			$this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
			// if ($this->form_validation->run() == true) {
			// 		if ($this->Admin) {
			// 				$date = $this->input->post('date');
			// 		} else {
			// 				$date = date('Y-m-d H:i:s');
			// 		}
			// 		$payment = array(
			// 				'date' => $date,
			// 				'sale_id' => $id,
			// 				'customer_id' => $cid,
			// 				'reference' => $this->input->post('reference'),
			// 				'amount' => $this->input->post('amount-paid'),
			// 				'paid_by' => $this->input->post('paid_by'),
			// 				'cheque_no' => $this->input->post('cheque_no'),
			// 				'gc_no' => $this->input->post('gift_card_no'),
			// 				'cc_no' => $this->input->post('pcc_no'),
			// 				'cc_holder' => $this->input->post('pcc_holder'),
			// 				'cc_month' => $this->input->post('pcc_month'),
			// 				'cc_year' => $this->input->post('pcc_year'),
			// 				'cc_type' => $this->input->post('pcc_type'),
			// 				'note' => $this->input->post('note'),
			// 				'created_by' => $this->session->userdata('user_id'),
			// 				'store_id' => $this->session->userdata('store_id'),
			// 		);

			// 		if ($_FILES['userfile']['size'] > 0) {
			// 				$this->load->library('upload');
			// 				$config['upload_path'] = 'files/';
			// 				$config['allowed_types'] = $this->digital_file_types;
			// 				$config['max_size'] = 2048;
			// 				$config['overwrite'] = FALSE;
			// 				$config['encrypt_name'] = TRUE;
			// 				$this->upload->initialize($config);
			// 				if (!$this->upload->do_upload()) {
			// 						$error = $this->upload->display_errors();
			// 						$this->session->set_flashdata('error', $error);
			// 						redirect($_SERVER["HTTP_REFERER"]);
			// 				}
			// 				$photo = $this->upload->file_name;
			// 				$payment['attachment'] = $photo;
			// 		}

			// 		// $this->tec->print_arrays($payment);

			// } elseif ($this->input->post('add_payment')) {
			// 		$this->session->set_flashdata('error', validation_errors());
			// 		$this->tec->dd();
			// }


			// if ($this->form_validation->run() == true && $this->sales_model->addPayment($payment)) {
			// 		$this->session->set_flashdata('message', lang("payment_added"));
			// 		redirect($_SERVER["HTTP_REFERER"]);
			// } else {
			// 		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			// 		$sale = $this->sales_model->getSaleByID($id);
			// 		$this->data['inv'] = $sale;
			// 		$this->load->view($this->theme . 'sales/add_payment', $this->data);
			// }
	// }
	 public function return_page($sale_id)
	 {
		 $this->data['sale_id'] = $sale_id;
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = lang('Return Items');
		$bc = array(array('link' => '#', 'page' => lang('Return Items')));
		$meta = array('page_title' => lang('Return Items'), 'bc' => $bc);
		$this->page_construct('sales/return_items', $this->data, $meta);
		// }
	 }

		function get_return_product() {
			$sale_id =  $this->input->post('sale_id');
			$this->load->library('datatables');
			// $this->datatables->select("  tec_sale_items.product_id as name,tec_sale_items.product_id as price,tec_sale_items.quantity,tec_sale_items.item_discount,tec_sale_items.subtotal ,(tec_sale_items.cgst_tax_val+tec_sale_items.cgst_tax_val) as tax  ");
			$this->datatables->select("  tec_products.name,tec_products.price,(tec_sale_items.quantity - tec_sale_items.return_quantity) as quantity,tec_sale_items.item_discount,tec_sale_items.subtotal ,(tec_sale_items.cgst_tax_val+tec_sale_items.cgst_tax_val) as tax ,tec_sale_items.id ");
			$this->datatables->from('tec_sale_items');
			$this->datatables->join('tec_products','tec_products.id  = tec_sale_items.product_id','inner');
			// $this->datatables->join('tec_sale_items_returns','tec_sale_items_returns.sale_id  = tec_sale_items.id','left');
			if($sale_id){
				$this->datatables->where('tec_sale_items.sale_id', $sale_id);
			}
			$this->datatables->add_column("Actions", "<input class='rtn_qty' type='number' value='0'  min='0' max='$2' onkeydown='return false' onscroll='return false' > ", "id,quantity");

			// $this->datatables->add_column("Actions", "<div class='text-center' style='width: 147px;'><div class='btn-group'><a href='" . site_url('pos/view/$1/1') . "' title='".lang("view_invoice")."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-list'></i></a> <a href='".site_url('sales/payments/$1')."' title='" . lang("view_payments") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-money'></i></a> <a href='".site_url('sales/add_payment/$1')."' title='" . lang("add_payment") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-briefcase'></i></a> <a data-href='" . site_url('sales/returns/$1') . "' onClick=\"returnsale('". lang('You are going to return sale, please click ok to return.') ."',this)\" title='".lang("Returns")."' class='tip btn btn-primary  btn-xs'><i class='fa fa-reply'></i></a><a href='" . site_url('pos/?edit=$1') . "' title='".lang("edit_invoice")."' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('sales/delete/$1') . "' onClick=\"return confirm('". lang('alert_x_sale') ."')\" title='".lang("delete_sale")."' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");
			// $x =  $this->datatables->generate();
			echo json_encode($this->datatables->generate('raw'));
	}

	
	function get_csv_test() {
		$this->load->library('datatables');
		$this->load->dbutil();
		$this->datatables->select("*");
		$this->datatables->from('tec_products');
		$x=  $this->datatables->get_display_result();
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="asdsad.csv";');
		echo $this->dbutil->csv_from_result($x);
}


	public function return_item_save()
	{
		$sale_id = $this->input->post('sale_id');
		$items = $this->input->post('items');
		$ids = array_column($items, 'id');
		// get sale of id sale_id
		// var_dump($items);
		// die();
		$sale = $this->sales_model->getSaleByID($sale_id);
		$customer_id = $sale->customer_id;
		if(!$sale) { echo 'false1'; exit; }
		$sale_items = $this->sales_model->getSaleItemsBySaleID($sale_id,$ids);
		if(!$sale_items) { echo 'false2'; exit; }

		//insert it into return sael with extra colum sale id
		$sale->sale_id = $sale_id;
		$sale->doctor = ' ';
		$sale->patient = ' ';
		$org_sale_id = $sale->id;
		unset($sale->id);
		unset($sale->batchno);
		unset($sale->gstno);
		unset($sale->cash_amount);
		unset($sale->card_amount);
		unset($sale->cheque_amount);
		unset($sale->return_amount);
		// minus quantiy
		$return = $this->sales_model->AddReturnSale($sale);
		// var_dump($this->db->error());
		// die();
		if(!$return) { echo 'false3'; exit; }
		// get items from items id  and insert into retuned ites colum 
		// AddReturnItems
		$total_return  = 0;
		foreach ($sale_items as $sale_item) {
			$temp = array_search($sale_item->id, array_column($items, 'id'));
			$total_return  += ($sale_item->subtotal/$sale_item->quantity)*$items[$temp]['return_qty'];
			$sale_item->quantity =$items[$temp]['return_qty']; 
			$sale_item->subtotal =  ($sale_item->quantity*$sale_item->net_unit_price)+ ($sale_item->cgst_tax_val*2) ;
			$sale_item->net_unit_price += $sale_item->discount;
			$sale_item->return_sale_id= $return;
			unset($sale_item->return_quantity);
			$sale_item->sale_id= $org_sale_id;
			$rq = $this->db->get_where('tec_sale_items',array('id'=>$sale_item->id))->row()->return_quantity;
			$xq1 = $this->db->update('tec_sale_items',array('return_quantity'=>($sale_item->quantity+$rq)),array('id'=>$sale_item->id));
			unset($sale_item->id);
			if(!$xq1){
				echo 'false4'; exit;
			}
			$prod = $this->sales_model->AddProductQty($sale_item->product_id,$sale_item->quantity);
			if(!$prod) { echo 'false5'; exit; }
			$r_sale = $this->sales_model->AddReturnItems($sale_item);
			if(!$r_sale) { echo 'false6'; exit; }
		}
		
		$return_amount = $this->db->get_where('tec_sales',array('id'=>$sale_id))->row()->return_amount;
		$xq2 = $this->db->update('tec_sales',array('return_amount'=>($total_return+$return_amount)),array('id'=>$sale_id));
		$res = $this->sales_model->update_customer_balance(array('id'=>$customer_id,'total_balance'=>($total_return+$return_amount)),FALSE);
		$x = $this->db->last_query();
		if(!$xq2){
			echo 'false7'; exit;
		}
		// add retuen qty to the product
		// if all success then true
		echo $return;
	}

	public function return_print($sale_id)
	{
		$this->data['sale_items'] = $this->sales_model->getReturnItemsBySaleID($sale_id);
		$this->data['customer'] = $this->sales_model->getCustomerBySaleID($sale_id);
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['page_title'] = lang('Return Items');
		$bc = array(array('link' => '#', 'page' => lang('Return Items')));
		$meta = array('page_title' => lang('Return Items'), 'bc' => $bc);
		$this->load->view($this->theme .'sales/view', $this->data, $meta);
	}

}
