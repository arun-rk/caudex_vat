<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases extends MY_Controller
{

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
        $this->load->model('purchases_model');
        $this->allowed_types = 'gif|jpg|png|pdf|doc|docx|xls|xlsx|zip';
    }

    function index() {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['page_title'] = lang('purchases');
        $bc = array(array('link' => '#', 'page' => lang('purchases')));
        $meta = array('page_title' => lang('purchases'), 'bc' => $bc);
        $this->page_construct('purchases/index', $this->data, $meta);

    }

    function get_purchases() {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->load->library('datatables');
        $this->datatables->select("id, batch_no,date, reference,cgst_Total,sgst_Total, total,grand_total, note, attachment ");
        $this->datatables->from('purchases');
        if (!$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }
        $this->datatables->where('store_id', $this->session->userdata('store_id'));
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='".site_url('purchases/view/$1')."' title='".lang('view_purchase')."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-file-text-o'></i></a>       <a   title='".lang('Return Purchase')."' class='tip btn btn-primary btn-xs  return_purchase'  ><i class='fa fa-reply'></i></a>          <a href='" . site_url('purchases/edit/$1') . "' title='" . lang("edit_purchase") . "' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('purchases/delete/$1') . "' onClick=\"return confirm('" . lang('alert_x_purchase') . "')\" title='" . lang("delete_purchase") . "' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");

        $this->datatables->unset_column('id');
        echo $this->datatables->generate();

    }

    function view($id = NULL) {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->data['purchase'] = $this->purchases_model->getPurchaseByID($id);
        $this->data['items'] = $this->purchases_model->getAllPurchaseItems($id);
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['page_title'] = lang('view_purchase');
        $this->load->view($this->theme.'purchases/view', $this->data);

    }

    function add() {
        if ( ! $this->session->userdata('store_id')) {
            $this->session->set_flashdata('warning', lang("please_select_store"));
            redirect('stores');
        }
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->form_validation->set_rules('date', lang('date'), 'required');

        if ($this->form_validation->run() == true) {
            $total = 0;
            $quantity = "quantity";
            $product_id = "product_id";
            $unit_cost = "cost";
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
				 
                $item_qty = $_POST['quantity'][$r];
                $item_cost = $_POST['cost'][$r];
                $expiry_date = $_POST['expiry_date'][$r];
				 
				 $sgst = $_POST['sgst'][$r];
				  $cgst = $_POST['cgst'][$r];
				  
				  $sgst_Tax = $_POST['sgst_Tax'][$r];
				  $cgst_Tax = $_POST['cgst_Tax'][$r];
				  $supplier_id = $_POST['supplier'];
				  
                if( $item_id && $item_qty && $unit_cost ) {

                    if(!$this->purchases_model->getProductByID($item_id)) {
                        $this->session->set_flashdata('error', $this->lang->line("product_not_found")." ( ".$item_id." ).");
                        redirect('purchases/add');
                    }

                    $products[] = array(
                        'product_id' => $item_id,
						   
                        'cost' => $item_cost,
						
						'expiry_date' => $expiry_date,
						'cgst' => $cgst,
						'cgst_Tax' => $cgst_Tax,
						'sgst' => $sgst,
						'sgst_Tax' => $sgst_Tax,
						
                        'quantity' => $item_qty,
                        'subtotal' => (($item_qty  * $item_cost) + ((($item_qty* $item_cost)* $cgst)/100)+((($item_qty* $item_cost)*$sgst)/100))
                        );
					@$cgst_Total+=$cgst_Tax;
					@$sgst_Total+=$sgst_Tax;
					
					  $total += $item_cost;
                    @$grand_total += (($item_qty  * $item_cost) + ((($item_qty* $item_cost)* $cgst)/100)+((($item_qty* $item_cost)*$sgst)/100));

                }
            }

            if (!isset($products) || empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
				 $this->form_validation->set_rules('batch_no', lang("batch no"), 'required');
				
            } else {
                krsort($products);
            }

            $data = array(
	 
                        'date' =>date("Y-m-d", strtotime($this->input->post('date'))),
                        'reference' => $this->input->post('reference'),
						  'batch_no' => $this->input->post('batch_no'),
                        'note' => $this->input->post('note', TRUE),
                        'total' => $total,
						    'grand_total' => $grand_total,
						 'cgst_Total' => $cgst_Total,
						  'sgst_Total' => $sgst_Total,
							'supplier_id' => $supplier_id,
                        'created_by' => $this->session->userdata('user_id'),
                        'store_id' => $this->session->userdata('store_id'),
                    );

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("purchases/add");
                }

                $data['attachment'] = $this->upload->file_name;

            }
            // $this->tec->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->addPurchase($data, $products)) {

            $this->session->set_userdata('remove_spo', 1);
            $this->session->set_flashdata('message', lang('purchase_added'));
			echo JSON_ENCODE($data);
        redirect("purchases");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['suppliers'] = $this->site->getAllSuppliers();
			
		 
            $this->data['stores'] = $this->site->getAllStores();
            $this->data['gst_groupss'] = $this->site->getAllGst();
			
			
            $this->data['page_title'] = lang('add_purchase');
            $bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('add_purchase')));
            $meta = array('page_title' => lang('add_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/add', $this->data, $meta);

        }
    }

	  function addproduct() {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->form_validation->set_rules('code', lang("product_code"), 'trim|is_unique[products.code]|min_length[2]|max_length[50]|required|alpha_numeric');
        $this->form_validation->set_rules('name', lang("product_name"), 'required');
        $this->form_validation->set_rules('category', lang("category"), 'required');
        $this->form_validation->set_rules('price', lang("product_price"), 'required|is_numeric');
        if ($this->input->post('type') != 'service') {
            $this->form_validation->set_rules('cost', lang("product_cost"), 'required|is_numeric');
        }
        $this->form_validation->set_rules('product_tax', lang("product_tax"), 'required|is_numeric');
        $this->form_validation->set_rules('alert_quantity', lang("alert_quantity"), 'is_numeric');

        if ($this->form_validation->run() == true) {
if($this->input->post('price_margin'))
{
	$price_margin=$this->input->post(price_margin);
}
else{
	$price_margin=0;
}
            $data = array(
                'type' => $this->input->post('type'),
                'code' => $this->input->post('code'),
				  'hsncode' => $this->input->post('hsn'),
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category'),
				 'gst_id' => $this->input->post('gst_groups'),
                'price' => $this->input->post('price'),
                'cost' => $this->input->post('cost'),
                'tax' => $this->input->post('product_tax'),
                'tax_method' => $this->input->post('tax_method'),
                'alert_quantity' => $this->input->post('alert_quantity'),
                'details' => $this->input->post('details'),
                'barcode_symbology' => $this->input->post('barcode_symbology'),
				'price_margin' => $price_margin,
                );

            if ($this->Settings->multi_store) {
                $stores = $this->site->getAllStores();
                foreach ($stores as $store) {
                    $store_quantities[] = array(
                        'store_id' => $store->id,
                        'quantity' => $this->input->post('quantity'.$store->id),
                        'price' => $this->input->post('price'.$store->id)
                        );
                }
            } else {
                $store_quantities[] = array(
                    'store_id' => 1,
                    'quantity' => $this->input->post('quantity'),
                    'price' => $this->input->post('price'),
                    );
            }

            if ($this->input->post('type') == 'combo') {
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r])) {
                        $items[] = array(
                            'item_code' => $_POST['combo_item_code'][$r],
                            'quantity' => $_POST['combo_item_quantity'][$r]
                        );
                    }
                }
            } else {
                $items = array();
            }

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '500';
                $config['max_width'] = '800';
                $config['max_height'] = '800';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("purchases/add");
                }

                $photo = $this->upload->file_name;
                $data['image'] = $photo;

                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/' . $photo;
                $config['new_image'] = 'uploads/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 110;
                $config['height'] = 110;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    $this->session->set_flashdata('error', $this->image_lib->display_errors());
                    redirect("purchases/add");
                }

            }
            // $this->tec->print_arrays($data, $items);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->addProduct($data, $store_quantities, $items)) {

            $this->session->set_flashdata('message', lang("product_added"));
            redirect('purchases/add');

        }  
		else{
			  $this->session->set_flashdata('message', lang("Product add failed"));
            redirect('purchases/add');
		}
    }

	
    function edit($id = NULL) {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('date', lang('date'), 'required');

        if ($this->form_validation->run() == true) {
            $total = 0;
            $quantity = "quantity";
            $product_id = "product_id";
			 
            $unit_cost = "cost";
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                
				   $item_qty = $_POST['quantity'][$r];
                $item_cost = $_POST['cost'][$r];
				
				 $sgst = $_POST['sgst'][$r];
				  $cgst = $_POST['cgst'][$r];
				  
				  $sgst_Tax = $_POST['sgst_Tax'][$r];
				  $cgst_Tax = $_POST['cgst_Tax'][$r];
				  
                if( $item_id && $item_qty && $unit_cost ) {

                    if(!$this->site->getProductByID($item_id)) {
                        $this->session->set_flashdata('error', $this->lang->line("product_not_found")." ( ".$item_id." ).");
                        redirect('purchases/edit/'.$id);
                    }

                   
					
   $products[] = array(
                        'product_id' => $item_id,
						   
                        'cost' => $item_cost,
						
						'cgst' => $cgst,
						'cgst_Tax' => $cgst_Tax,
						'sgst' => $sgst,
						'sgst_Tax' => $sgst_Tax,
						
                        'quantity' => $item_qty,
                        'subtotal' => (($item_qty  * $item_cost) + ((($item_qty* $item_cost)* $cgst)/100)+((($item_qty* $item_cost)*$sgst)/100))
                        );
					$cgst_Total+=$cgst_Tax;
					$sgst_Total+=$sgst_Tax;
					
					  $total += $item_cost;
                    $grand_total += (($item_qty  * $item_cost) + ((($item_qty* $item_cost)* $cgst)/100)+((($item_qty* $item_cost)*$sgst)/100));

                }
            }

            if (!isset($products) || empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

			 $data = array(
	 
                        'date' =>date("Y-m-d", strtotime($this->input->post('date'))),
                        'reference' => $this->input->post('reference'),
						  'batch_no' => $this->input->post('batch_no'),
                        'note' => $this->input->post('note', TRUE),
                        'total' => $total,
						    'grand_total' => $grand_total,
						 'cgst_Total' => $cgst_Total,
						  'sgst_Total' => $sgst_Total,
                        'created_by' => $this->session->userdata('user_id'),
                        'store_id' => $this->session->userdata('store_id'),
                    );
			

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->upload->set_flashdata('error', $error);
                    redirect("purchases/add");
                }

                $data['attachment'] = $this->upload->file_name;

            }
            // $this->tec->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->updatePurchase($id, $data, $products)) {

            $this->session->set_userdata('remove_spo', 1);
            $this->session->set_flashdata('message', lang('purchase_updated'));
            redirect("purchases");

        } else {

            $this->data['purchase'] = $this->purchases_model->getPurchaseByID($id);
            $inv_items = $this->purchases_model->getAllPurchaseItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
				    
                $row->qty = $item->quantity;
                $row->cost = $item->cost;
                $ri = $this->Settings->item_addition ? $row->id : $c;
				$gst = $this->site->getGstByID($row->gst_id);
                $pr[$ri] = array('id' => $ri, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row,'gst' => $gst);
                $c++;
            }

            $this->data['items'] = json_encode($pr);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['suppliers'] = $this->site->getAllSuppliers();
            $this->data['page_title'] = lang('edit_purchase');
            $bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('edit_purchase')));
            $meta = array('page_title' => lang('edit_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/edit', $this->data, $meta);

        }
    }

    function delete($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->purchases_model->deletePurchase($id)) {
            $this->session->set_flashdata('message', lang("purchase_deleted"));
            redirect('purchases');
        }
    }

    function suggestions($id = NULL) {
        if($id) {
            $row = $this->site->getProductByID($id);
            $row->qty = 1;
            $pr = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
            echo json_encode($pr);
            die();
        }
        $term = $this->input->get('term', TRUE);
        $rows = $this->purchases_model->getProductNames($term);
        if ($rows) {
            foreach ($rows as $row) {
                $row->qty = 1;
				 $gst = $this->site->getGstByID($row->gst_id);
					
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row,'gst' => $gst);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

     /* ----------------------------------------------------------------- */

     function expenses($id = NULL) {

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('expenses');
        $bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('expenses')));
        $meta = array('page_title' => lang('expenses'), 'bc' => $bc);
        $this->page_construct('purchases/expenses', $this->data, $meta);

    }

    function get_expenses($user_id = NULL) {

        $detail_link = anchor('purchases/expense_note/$1', '<i class="fa fa-file-text-o"></i> ' . lang('expense_note'), 'data-toggle="modal" data-target="#myModal2"');
        $edit_link = anchor('purchases/edit_expense/$1', '<i class="fa fa-edit"></i> ' . lang('edit_expense'), 'data-toggle="modal" data-target="#myModal"');
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("delete_expense") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('purchases/delete_expense/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_expense') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            <li>' . $edit_link . '</li>
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';

        $this->load->library('datatables');
        if ($this->db->dbdriver == 'sqlite3') {
            $this->datatables->select($this->db->dbprefix('expenses') . ".id as id, date, reference, amount, note, (" . $this->db->dbprefix('users') . ".first_name || ' ' || " . $this->db->dbprefix('users') . ".last_name) as user, attachment", FALSE);
        } else {
            $this->datatables->select($this->db->dbprefix('expenses') . ".id as id, date, reference, amount, note, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as user, attachment", FALSE);
        }
        $this->datatables->from('expenses')
            ->join('users', 'users.id=expenses.created_by', 'left')
            ->group_by('expenses.id');

        if (!$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }
        $this->datatables->where('expenses.store_id', $this->session->userdata('store_id'));
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='".site_url('purchases/expense_note/$1')."' title='".lang('expense_note')."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-file-text-o'></i></a> <a href='" . site_url('purchases/edit_expense/$1') . "' title='" . lang("edit_expense") . "' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('purchases/delete_expense/$1') . "' onClick=\"return confirm('" . lang('alert_x_expense') . "')\" title='" . lang("delete_expense") . "' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");
        $this->datatables->unset_column('id');
        echo $this->datatables->generate();
    }

    function expense_note($id = NULL) {
        if ( ! $this->Admin) {
            if($expense->created_by != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('error', lang('access_denied'));
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'pos');
            }
        }

        $expense = $this->purchases_model->getExpenseByID($id);
        $this->data['user'] = $this->site->getUser($expense->created_by);
        $this->data['expense'] = $expense;
        $this->data['page_title'] = $this->lang->line("expense_note");
        $this->load->view($this->theme . 'purchases/expense_note', $this->data);

    }

    function add_expense() {
        if ( ! $this->session->userdata('store_id')) {
            $this->session->set_flashdata('warning', lang("please_select_store"));
            redirect('stores');
        }
        $this->load->helper('security');

        $this->form_validation->set_rules('amount', lang("amount"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Admin) {
                $date = trim($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data = array(
                'date' => $date,
               // 'reference' => $this->input->post('reference') ? $this->input->post('reference') : $this->site->getReference('ex'),
               'reference' => $this->input->post('reference') ? $this->input->post('reference') : "",
               'amount' => $this->input->post('amount'),
                'created_by' => $this->session->userdata('user_id'),
                'store_id' => $this->session->userdata('store_id'),
                'note' => $this->input->post('note', TRUE)
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            //$this->tec->print_arrays($data);

        } elseif ($this->input->post('add_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->addExpense($data)) {

            $this->session->set_flashdata('message', lang("expense_added"));
            redirect('purchases/expenses');

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('add_expense');
            $bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => site_url('purchases/expenses'), 'page' => lang('expenses')), array('link' => '#', 'page' => lang('add_expense')));
            $meta = array('page_title' => lang('add_expense'), 'bc' => $bc);
            $this->page_construct('purchases/add_expense', $this->data, $meta);

        }
    }

    function edit_expense($id = NULL) {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference', lang("reference"), 'required');
        $this->form_validation->set_rules('amount', lang("amount"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == true) {
            if ($this->Admin) {
                $date = trim($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data = array(
                'date' => $date,
                'reference' => $this->input->post('reference'),
                'amount' => $this->input->post('amount'),
                'note' => $this->input->post('note', TRUE)
            );
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }

            //$this->tec->print_arrays($data);

        } elseif ($this->input->post('edit_expense')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }


        if ($this->form_validation->run() == true && $this->purchases_model->updateExpense($id, $data)) {
            $this->session->set_flashdata('message', lang("expense_updated"));
            redirect("purchases/expenses");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['expense'] = $this->purchases_model->getExpenseByID($id);
            $this->data['page_title'] = lang('edit_expense');
            $bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => site_url('purchases/expenses'), 'page' => lang('expenses')), array('link' => '#', 'page' => lang('edit_expense')));
            $meta = array('page_title' => lang('edit_expense'), 'bc' => $bc);
            $this->page_construct('purchases/edit_expense', $this->data, $meta);

        }
    }

    function delete_expense($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $expense = $this->purchases_model->getExpenseByID($id);
        if ($this->purchases_model->deleteExpense($id)) {
            if ($expense->attachment) {
                unlink($this->upload_path . $expense->attachment);
            }
            $this->session->set_flashdata('message', lang("expense_deleted"));
            redirect('purchases/expenses');
        }
		}
		
		
     /* -----------------------Purchase returns------------------------------------------ */
		 
     function returnp($id = NULL) {
			 if($this->input->is_ajax_request()){
				$purchase_id = $this->input->post('purchase_id');
				$items = $this->input->post('items');
				$purchases = $this->purchases_model->getPurchaseByID($purchase_id);
				$purchases =(array) $purchases;
				if($purchases){
					$purchases['purchase_id'] = $purchases['id'];
					unset($purchases['id']);
				}else{
					echo json_encode(array(
						'code'=>2,
						'data'=>''
					));
					exit;					
				}
				$purchase_items = $this->purchases_model->UpdatePurchaseItems($items);
				if($purchase_items){
					$purchase_items = array_map(function($tag) {
						$tag['purchase_item_id'] = $tag['id'];
						$tag['purchase_return_id'] = $tag['purchase_id'];
						$tag['subtotal'] = $tag['returned'] * ($tag['cost'] +$tag['cgst_Tax'] + $tag['sgst_Tax']);
						unset($tag['id']);
						unset($tag['purchase_id']);
						return $tag;
				}, $purchase_items);
				}
				else{
					echo json_encode(array(
						'code'=>4,
						'data'=>''
					));
					exit;			
				}
				$purchases['grand_total'] = array_sum(array_column($purchase_items,'subtotal'));
				$res = $this->purchases_model->returnPurchase($purchases,$purchase_items);
				if(!$res){
					echo json_encode(array(
						'code'=>3,
						'data'=>''
					));
					exit;			
				}
				echo json_encode(array(
					'code'=>1,
					'data'=>site_url('purchases/debitnote/'.$res)
				));
				// redirect();

				// var_dump($purchases);
				// var_dump($purchase_items);

				// 	print_r($items);
				// 	get purchase data and items datas
				// 	update items in purchase_item 
				// 	insert into purchase return and purchase item return 

			 }
			 else{
				 
			 $this->data['purchase_id'] = $id;
			 $this->data['purchase_items'] = $this->purchases_model->getAllPurchaseItems($id);
			 $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			 $this->data['page_title'] = lang('Return');
			 $bc = array(
				 array('link' => site_url('purchases'), 'page' => lang('purchases')),
					array('link' => '#', 'page' => lang('Return'))
				);
			 $meta = array('page_title' => lang('Return'), 'bc' => $bc);
			 $this->page_construct('purchases/return', $this->data, $meta); 
			 }
			}

    function debitnote($id) {
			//  echo $id;			 
			$this->data['purchases']= $this->purchases_model->getReturnPurchaseByID($id);
			$this->data['purchase_items']= $this->purchases_model->getAllPurchaseReturnItems($id);
			$bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('Purchase return')));
			$meta = array('page_title' => lang('Purchase return'), 'bc' => $bc);
			$this->load->view($this->theme .'purchases/debit_note', $this->data, $meta);
		}

    //  function purchase_return($id = NULL) {
		// 	 $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		// 	 $this->data['page_title'] = lang('Purchase return');
		// 	 $bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('Purchase return')));
		// 	 $meta = array('page_title' => lang('Purchase return'), 'bc' => $bc);
		// 	 $this->page_construct('purchases/purchase_return', $this->data, $meta);
			 
		// 	}


			
			function purchase_return() {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['page_title'] = lang('Purchase Return');
        $bc = array(
					array('link' => '#', 'page' => lang('purchases')),
					array('link' => '#', 'page' => lang('Purchase Return')),
				);
        $meta = array('page_title' => lang('Purchase Return'), 'bc' => $bc);
        $this->page_construct('purchases/return_index', $this->data, $meta);

    }

    function get_returned_purchases() {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->load->library('datatables');
        $this->datatables->select("id, batch_no,date, reference,cgst_Total,sgst_Total, total,grand_total, note, attachment ");
        $this->datatables->from('purchases_return');
        if (!$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        }
        $this->datatables->where('store_id', $this->session->userdata('store_id'));
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='".site_url('purchases/return_view/$1')."' title='".lang('view_purchase')."' class='tip btn btn-primary btn-xs' data-toggle='ajax-modal'><i class='fa fa-file-text-o'></i></a>             <a href='" . site_url('purchases/return_delete/$1') . "' onClick=\"return confirm('" . lang('alert_x_purchase') . "')\" title='" . lang("delete_purchase") . "' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id");
// <a href='" . site_url('purchases/return_edit/$1') . "' title='" . lang("edit_purchase") . "' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a>
        $this->datatables->unset_column('id');
        echo $this->datatables->generate();

		}
		
    function return_view($id = NULL) {
			if ( ! $this->Admin) {
					$this->session->set_flashdata('error', lang('access_denied'));
					redirect('pos');
			}
			$this->data['purchase'] = $this->purchases_model->getReturnPurchaseByID($id);
			$this->data['items'] = $this->purchases_model->getAllPurchaseReturnItems($id);
			$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			$this->data['page_title'] = lang('view_purchase');
			$this->load->view($this->theme.'purchases/view', $this->data);

	}
// todo
    function return_edit($id = NULL) {
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('date', lang('date'), 'required');

        if ($this->form_validation->run() == true) {
            $total = 0;
            $quantity = "quantity";
            $product_id = "product_id";
            $unit_cost = "cost";
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            for ($r = 0; $r < $i; $r++) {
								$item_id = $_POST['product_id'][$r];

								$item_qty = $_POST['quantity'][$r];
								$item_cost = $_POST['cost'][$r];

								$sgst = $_POST['sgst'][$r];
								$cgst = $_POST['cgst'][$r];

								$sgst_Tax = $_POST['sgst_Tax'][$r];
								$cgst_Tax = $_POST['cgst_Tax'][$r];

                if( $item_id && $item_qty && $unit_cost ) {

                    if(!$this->site->getProductByID($item_id)) {
                        $this->session->set_flashdata('error', $this->lang->line("product_not_found")." ( ".$item_id." ).");
                        redirect('purchases/edit/'.$id);
                    }

                   
					
										$products[] = array(
													'product_id' => $item_id,
													'cost' => $item_cost,
													'cgst' => $cgst,
													'cgst_Tax' => $cgst_Tax,
													'sgst' => $sgst,
													'sgst_Tax' => $sgst_Tax,
													'quantity' => $item_qty,
													'subtotal' => (($item_qty  * $item_cost) + ((($item_qty* $item_cost)* $cgst)/100)+((($item_qty* $item_cost)*$sgst)/100))
															);
										$cgst_Total+=$cgst_Tax;
										$sgst_Total+=$sgst_Tax;
										$total += $item_cost;
										$grand_total += (($item_qty  * $item_cost) + ((($item_qty* $item_cost)* $cgst)/100)+((($item_qty* $item_cost)*$sgst)/100));

                }
            }

            if (!isset($products) || empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($products);
            }

			 					$data = array(	 
									'date' =>date("Y-m-d", strtotime($this->input->post('date'))),
									'reference' => $this->input->post('reference'),
									'batch_no' => $this->input->post('batch_no'),
									'note' => $this->input->post('note', TRUE),
									'total' => $total,
									'grand_total' => $grand_total,
									'cgst_Total' => $cgst_Total,
									'sgst_Total' => $sgst_Total,
									'created_by' => $this->session->userdata('user_id'),
									'store_id' => $this->session->userdata('store_id'),
                );
			

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');
                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = '2000';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->upload->set_flashdata('error', $error);
                    redirect("purchases/return_add");
                }

                $data['attachment'] = $this->upload->file_name;

            }
            // $this->tec->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->purchases_model->updatePurchase($id, $data, $products)) {

            $this->session->set_userdata('remove_spo', 1);
            $this->session->set_flashdata('message', lang('purchase_updated'));
            redirect("purchases/purchase_return");

        } else {

            $this->data['purchase'] = $this->purchases_model->getReturnPurchaseByID($id);
            $inv_items = $this->purchases_model->getAllPurchaseReturnItems($id);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $row = $this->site->getProductByID($item->product_id);
				    
                $row->qty = $item->quantity;
                $row->cost = $item->cost;
                $ri = $this->Settings->item_addition ? $row->id : $c;
				$gst = $this->site->getGstByID($row->gst_id);
                $pr[$ri] = array('id' => $ri, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row,'gst' => $gst);
                $c++;
            }

            $this->data['items'] = json_encode($pr);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['suppliers'] = $this->site->getAllSuppliers();
            $this->data['page_title'] = lang('edit_purchase');
            $bc = array(array('link' => site_url('purchases'), 'page' => lang('purchases')), array('link' => '#', 'page' => lang('edit_purchase')));
            $meta = array('page_title' => lang('edit_purchase'), 'bc' => $bc);
            $this->page_construct('purchases/edit', $this->data, $meta);

        }
    }

    function return_delete($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if ( ! $this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->purchases_model->deleteReturnedPurchase($id)) {
            $this->session->set_flashdata('message', lang("purchase_deleted"));
            redirect('purchases/purchase_return');
        }
    }
// todo end

			/* -----------------------------Purchase returns------------------------------------------ */


}
