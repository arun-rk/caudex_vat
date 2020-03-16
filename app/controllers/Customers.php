<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
{

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('customers_model');
    }

    function index() {

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('customers');
        $bc = array(array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/index', $this->data, $meta);
    }

    function get_customers() {

        $this->load->library('datatables');
        $this->datatables
        ->select("id, name, phone, email, cf1, cf2,vat_no,code")
        ->from("customers")
        ->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='" . site_url('customers/edit/$1') . "' class='tip btn btn-warning btn-xs' title='".$this->lang->line("edit_customer")."'><i class='fa fa-edit'></i></a> <a href='" . site_url('customers/delete/$1') . "' onClick=\"return confirm('". $this->lang->line('alert_x_customer') ."')\" class='tip btn btn-danger btn-xs' title='".$this->lang->line("delete_customer")."'><i class='fa fa-trash-o'></i></a></div></div>", "id")
        ->unset_column('id');

        echo $this->datatables->generate();

    }

    function get_customersData($id='') {
			echo json_encode($this->customers_model->getList($id));
    }

    function add() {

        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email|is_unique[tec_customers.email]');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'is_natural|required|is_unique[tec_customers.phone]');
        $this->form_validation->set_rules('cs_code', $this->lang->line("Code"), 'is_unique[tec_customers.code]|required');
        $this->form_validation->set_rules('vat_no', $this->lang->line("vat_no"), 'trim');
        $this->form_validation->set_rules('op_balance', $this->lang->line("op_balance"), 'numeric');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => ' ',
                'code' => $this->input->post('cs_code'),
                'vat_no' => $this->input->post('vat_no'),
                'reg_date' =>date("Y-m-d", strtotime( $this->input->post('reg_date'))),
                'credit_limit' => $this->input->post('cr_limit'),
                'opening_balance' => $this->input->post('op_balance'),
                'total_balance' => $this->input->post('op_balance'),
            );

        }

        if ( $this->form_validation->run() == true && $cid = $this->customers_model->addCustomer($data)) {
					// if($data['opening_balance']>0){
					// 	round($grand_total/5)*5;
					// 	$round_opening_balance = $data['opening_balance']*1000;
					// 	$round_opening_balance = round($round_opening_balance/5)*5;
					// 	$rounding = $data['opening_balance']*1000 - $round_opening_balance;
					// 	$rounding = $rounding/1000;
					// 	$round_opening_balance = $round_opening_balance/1000;
						
					// 	$sales = array('date' => date('Y-m-d H:i:s'),
					// 			'customer_id' => $cid,
					// 			'customer_name' => $data['name'],
					// 			'total' => $data['opening_balance'],
					// 			'product_discount' => 0,
					// 			'order_discount_id' => 0,
					// 			'order_discount' => 0,
					// 			'total_discount' => 0,
					// 			'product_tax' => 0,
					// 			'order_tax_id' => 0,
					// 			'order_tax' => 0,
					// 			'total_tax' => 0,
					// 			'grand_total' => $round_opening_balance,
					// 			'total_items' => 0,
					// 			'total_quantity' => 0,
					// 			'rounding' => $rounding,
					// 			'paid' => 0,
					// 			'status' => 'due',
					// 			'created_by' => 0,
					// 			'note' =>'',
					// 			'hold_ref' => ' ',
					// 			'doctor' => '',
					// 			'patient' => '',
					// 			'batchno' => '',
					// 			'gstno' => $data['vat_no'],
					// 	);
					// 	$x =  $this->customers_model->addSales($sales);
					// }
            if($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'success', 'msg' =>  $this->lang->line("customer_added"), 'id' => $cid, 'val' => $data['phone'].' - '.$data['name'].' ('.$data['code'].' )'));
                die();
            }
            $this->session->set_flashdata('message', $this->lang->line("customer_added"));
            redirect("customers");

        } else {
            if($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'failed', 'msg' => validation_errors())); die();
            }

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = lang('add_customer');
            $bc = array(array('link' => site_url('customers'), 'page' => lang('customers')), array('link' => '#', 'page' => lang('add_customer')));
            $meta = array('page_title' => lang('add_customer'), 'bc' => $bc);
            $this->page_construct('customers/add', $this->data, $meta);

        }
    }

    function edit($id = NULL) {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', $this->lang->line('access_denied'));
            redirect('pos');
        }
        if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
				$this->form_validation->set_rules('email', $this->lang->line("email_address"), 'valid_email');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'is_natural|required');


        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'credit_limit' => $this->input->post('cr_limit'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2')
            );

        }

        if ( $this->form_validation->run() == true && $this->customers_model->updateCustomer($id, $data)) {

            $this->session->set_flashdata('message', $this->lang->line("customer_updated"));
            redirect("customers");

        } else {

            $this->data['customer'] = $this->customers_model->getCustomerByID($id);
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['page_title'] = lang('edit_customer');
            $bc = array(array('link' => site_url('customers'), 'page' => lang('customers')), array('link' => '#', 'page' => lang('edit_customer')));
            $meta = array('page_title' => lang('edit_customer'), 'bc' => $bc);
            $this->page_construct('customers/edit', $this->data, $meta);

        }
    }

    function delete($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', $this->lang->line("disabled_in_demo"));
            redirect('pos');
        }

        if($this->input->get('id')) { $id = $this->input->get('id', TRUE); }

        if (!$this->Admin)
        {
            $this->session->set_flashdata('error', lang("access_denied"));
            redirect('pos');
        }

        if ( $this->customers_model->deleteCustomer($id) )
        {
            $this->session->set_flashdata('message', lang("customer_deleted"));
            redirect("customers");
        }

    }


}
