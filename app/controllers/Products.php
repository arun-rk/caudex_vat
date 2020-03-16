<?php defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('products_model');
    }

    public function index()
    {

        $stores = $this->site->getAllStores();
        if ($this->input->get('store_id') && !$this->session->userdata('has_store_id')) {
            $this->data['store'] = $this->site->getStoreByIDgetStoreByID($this->input->get('store_id', true));
        } elseif ($this->session->userdata('store_id')) {
            $this->data['store'] = $this->site->getStoreByID($this->session->userdata('store_id'));
        } else {
            $this->data['store'] = current($stores);
        }
        $this->data['stores'] = $stores;
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('products');
        $bc = array(array('link' => '#', 'page' => lang('products')));
        $meta = array('page_title' => lang('products'), 'bc' => $bc);
        $this->page_construct('products/index', $this->data, $meta);

    }

    public function get_products($store_id)
    {
				$category = $this->input->post('category');
				$supplier = $this->input->post('supplier');
				$vat_p = $this->input->post('vat_p');
        $this->load->library('datatables');
        if ($this->Admin) {
            $this->datatables->select($this->db->dbprefix('products') . ".id as pid, " . $this->db->dbprefix('products') . ".image as image, " . $this->db->dbprefix('products') . ".code as code, " . $this->db->dbprefix('products') . ".hsncode as hsn, " . $this->db->dbprefix('products') . ".name as pname, type, " . $this->db->dbprefix('categories') . ".name as cname, psq.quantity, tax, tax_method, cost, (CASE WHEN psq.price > 0 THEN psq.price ELSE {$this->db->dbprefix('products')}.price END) as price, barcode_symbology", false);
        } else {
            $this->datatables->select($this->db->dbprefix('products') . ".id as pid, " . $this->db->dbprefix('products') . ".image as image, " . $this->db->dbprefix('products') . ".code as code,  " . $this->db->dbprefix('products') . ".hsncode as hsn," . $this->db->dbprefix('products') . ".name as pname, type, " . $this->db->dbprefix('categories') . ".name as cname, psq.quantity, tax, tax_method, (CASE WHEN psq.price > 0 THEN psq.price ELSE {$this->db->dbprefix('products')}.price END) as price, barcode_symbology", false);
        }

        $this->datatables->from('products')
            ->join('categories', 'categories.id=products.category_id', 'left')
        // ->join('product_store_qty', 'product_store_qty.product_id=products.id', 'left')
            ->join("( SELECT * from {$this->db->dbprefix('product_store_qty')} WHERE store_id = {$store_id}) psq", 'products.id=psq.product_id', 'left')
        // ->where('product_store_qty.store_id', $store_id)
						->group_by('products.id');
				if($category) { $this->datatables->where('products.category_id',$category); }
				if($supplier) { $this->datatables->where('products.supplier_id',$supplier); }
				if($vat_p) { $this->datatables->where('products.gst_id',$vat_p); }
				
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='" . site_url('products/view/$1') . "' title='" . lang("view") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-file-text-o'></i></a>  <a id='$4 ($3)' href='" . site_url('products/gen_barcode/$3/$5') . "' title='" . lang("view_barcode") . "' class='barcode barcodex tip btn btn-primary btn-xs'><i class='fa fa-barcode'></i></a> <a class='tip image btn btn-primary btn-xs' id='$4 ($3)' href='" . base_url('uploads/$2') . "' title='" . lang("view_image") . "'><i class='fa fa-picture-o'></i></a> <a href='" . site_url('products/edit/$1') . "' title='" . lang("edit_product") . "' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('products/delete/$1') . "' onClick=\"return confirm('" . lang('alert_x_product') . "')\" title='" . lang("delete_product") . "' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "pid, image, code, pname, barcode_symbology");

        // $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'><a href='".site_url('products/view/$1')."' title='" . lang("view") . "' class='tip btn btn-primary btn-xs' data-toggle='ajax'><i class='fa fa-file-text-o'></i></a><a href='".site_url('products/single_barcode/$1')."' title='".lang('print_barcodes')."' class='tip btn btn-default btn-xs' data-toggle='ajax-modal'><i class='fa fa-print'></i></a> <a href='".site_url('products/single_label/$1')."' title='".lang('print_labels')."' class='tip btn btn-default btn-xs' data-toggle='ajax-modal'><i class='fa fa-print'></i></a> <a id='$4 ($3)' href='" . site_url('products/gen_barcode/$3/$5') . "' title='" . lang("view_barcode") . "' class='barcode barcodex tip btn btn-primary btn-xs'><i class='fa fa-barcode'></i></a> <a class='tip image btn btn-primary btn-xs' id='$4 ($3)' href='" . base_url('uploads/$2') . "' title='" . lang("view_image") . "'><i class='fa fa-picture-o'></i></a> <a href='" . site_url('products/edit/$1') . "' title='" . lang("edit_product") . "' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('products/delete/$1') . "' onClick=\"return confirm('" . lang('alert_x_product') . "')\" title='" . lang("delete_product") . "' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "pid, image, code, pname, barcode_symbology");

        $this->datatables->edit_column('cost','$10','cost');
        $this->datatables->edit_column('price','$10','price');
        $this->datatables->unset_column('pid')->unset_column('barcode_symbology');
        echo $this->datatables->generate();

    }

    public function view($id = null)
    {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $product = $this->site->getProductByID($id);
        $this->data['product'] = $product;
        $this->data['category'] = $this->site->getCategoryByID($product->category_id);
        $this->data['combo_items'] = $product->type == 'combo' ? $this->products_model->getComboItemsByPID($id) : null;
        $this->load->view($this->theme . 'products/view', $this->data);

    }

    public function barcode($product_code = null)
    {
        if ($this->input->get('code')) {
            $product_code = $this->input->get('code');
        }
        $data['product_details'] = $this->products_model->getProductByCode($product_code);
        $data['img'] = "<img src='" . base_url() . "index.php?products/gen_barcode&code={$product_code}' alt='{$product_code}' />";
        $this->load->view('barcode', $data);

    }

    public function product_barcode($product_code = null, $bcs = 'code128', $height = 60)
    {
        if ($this->input->get('code')) {
            $product_code = $this->input->get('code');
        }
        return "<img src='" . base_url() . "products/gen_barcode/{$product_code}/{$bcs}/{$height}' alt='{$product_code}' />";
    }

    public function gen_barcode($product_code = null, $bcs = 'code128', $height = 60, $text = 0)
    {
        $drawText = ($text != 1) ? false : true;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $imageResource = Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        return $imageResource;
    }

    public function print_barcodes()
    {
        $limit = 40;
        // $limit = 65;
        $this->load->helper('pagination');
        $page = $this->input->get('page');
        $total = $this->products_model->products_count();
        $info = ['page' => $page, 'total' => ceil($total / $limit)];
        $pagination = pagination('products/print_barcodes', $total, $limit, true);

        $products = $this->products_model->fetch_products($limit, (!empty($page) ? (($page - 1) * $limit) : 0));

        $r = 1;
        $html = "";
        //   $html .= '<table class="table table-bordered table-centered mb0">
        //   <tbody><tr>';
        //   foreach ($products as $pr) {
        //       if ($r == 1) {
        //            $r = 5;
        //           $html .= $rw ? '</tr><tr>' : '';
        //       }
        //       $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 60) . '<br><span class="price">'.lang('price') .': ' .$this->Settings->currency_prefix. ' ' . $pr->price . '</span></td>';
        //       $r++;
        //   }
        //   $html .= '</tr></tbody></table>';

        $html .= '<div class="main">';

        foreach ($products as $pr) {
            $qty = $this->products_model->getStoreQuantity($pr->id);

            if ((int) $qty->quantity != 0) {

                for ($i = 1; $i <= (int) $qty->quantity; $i++) {

                    if ($r <= 4) {
                        $html .= '<div class="sub"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 60) . '<br><span class="code">' . $pr->code . '</span><span class="price">Rs. ' . $pr->price . '</span></div>';
                        $r++;

                    } else {
                    }
                    if ($r > 4) {
                        $r = 1;

                        $html .= '</div><div class="main">';

                    }
                }

            }

        }
        $html .= '</div>';

        $this->data['links'] = $pagination;
        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'products/print_barcodes', $this->data);
		}
		
		public function print_barcodes_2($id='')
    {
			if ($this->input->is_ajax_request()) {
				// $limit = 100;
				$pageSize = $this->input->get('pageSize');
				$pageNumber = $this->input->get('pageNumber');
				$data = array();
				if($this->input->get('category')) {$data['category_id'] = $this->input->get('category'); }
				if($this->input->get('supplier')) {$data['supplier_id'] = $this->input->get('supplier'); }
				if($this->input->get('search')) {$data['name'] = $this->input->get('search'); }
				// $data['supplier_id'] = $this->input->get('supplier');
				// $data['name'] = $this->input->get('search');
        // // $limit = 65;
        // $this->load->helper('pagination');
        // $page = $this->input->get('page');
        // $total = $this->products_model->products_count();
        // $info = ['page' => $page, 'total' => ceil($total / $limit)];
        // $pagination = pagination('products/print_barcodes', $total, $limit, true);
				$products = $this->products_model->fetch_products_2($pageSize,$pageNumber,'',$data);
				$total = $this->products_model->fetch_products_total($data);
				$total_p = array();
				$total = (int) $total;
				if($products){
					foreach ($products as &$itemx) {
						// $itemx = new stdClass;
						// $itemx = $item;
						$itemx->barcode = $this->product_barcode($itemx->code, $itemx->barcode_symbology, 60);
						$itemx->qty = $this->products_model->getStoreQuantity($itemx->id);
						
						// if ((int) $qty->quantity != 0) {
	
						// 	for ($i = 1; $i <= (int) $qty->quantity; $i++) {
						// 		array_push($total_p,$itemx);
						// 		$total++;
						// 	}
						// }
						// $this->product_barcode($pr->code, $pr->barcode_symbology, 60)
					}
				}
				else{
					$products = array();
				}
				// echo '('.json_encode(array('items'=>$products,'total'=>300)).')';
				echo  json_encode(array('items'=>$products,'total'=>$total)) ;
			}
			else{
							$bc = array(array('link' => '#', 'page' => lang('Barcodes')));
							$meta = array('page_title' => lang('Barcodes'), 'bc' => $bc);
							$this->page_construct('products/barcodes', $this->data, $meta);
			}
        // $limit = 100;
        // // $limit = 65;
        // $this->load->helper('pagination');
        // $page = $this->input->get('page');
        // $total = $this->products_model->products_count();
        // $info = ['page' => $page, 'total' => ceil($total / $limit)];
        // $pagination = pagination('products/print_barcodes', $total, $limit, true);

        // $products = $this->products_model->fetch_products($limit, (!empty($page) ? (($page - 1) * $limit) : 0));
				// echo json_encode($products);
        // $r = 1;
        // $html = "";
        // //   $html .= '<table class="table table-bordered table-centered mb0">
        // //   <tbody><tr>';
        // //   foreach ($products as $pr) {
        // //       if ($r == 1) {
        // //            $r = 5;
        // //           $html .= $rw ? '</tr><tr>' : '';
        // //       }
        // //       $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 60) . '<br><span class="price">'.lang('price') .': ' .$this->Settings->currency_prefix. ' ' . $pr->price . '</span></td>';
        // //       $r++;
        // //   }
        // //   $html .= '</tr></tbody></table>';

        // $html .= '<div class="main">';

        // foreach ($products as $pr) {
        //     $qty = $this->products_model->getStoreQuantity($pr->id);

        //     if ((int) $qty->quantity != 0) {

        //         for ($i = 1; $i <= (int) $qty->quantity; $i++) {

        //             if ($r <= 4) {
        //                 $html .= '<div class="sub"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 60) . '<br><span class="code">' . $pr->code . '</span><span class="price">Rs. ' . $pr->price . '</span></div>';
        //                 $r++;

        //             } else {
        //             }
        //             if ($r > 4) {
        //                 $r = 1;

        //                 $html .= '</div><div class="main">';

        //             }
        //         }

        //     }

        // }
        // $html .= '</div>';

        // $this->data['links'] = $pagination;
        // $this->data['html'] = $html;
        // $this->data['page_title'] = lang("print_barcodes");
        // $this->load->view($this->theme . 'products/print_barcodes', $this->data);
    }
		
		public function print_barcodes__byid($id='')
    {
			$id = $this->input->get('id');
			// printbarcode.php
			if (!$this->input->is_ajax_request()) {
				
        $this->load->view($this->theme . 'products/printbarcode', $this->data);
			}
			else{
						$pageSize = 0;
						$pageNumber = 0;
						$products = $this->products_model->fetch_products_2($pageSize,$pageNumber,$id);
						$total_p = array();
						$products->barcode = $this->product_barcode($products->code, $products->barcode_symbology, 60);
						$qty = $this->products_model->getStoreQuantity($products->id);
						if ((int) $qty->quantity != 0) {
	
							for ($i = 1; $i <= (int) $qty->quantity; $i++) {
								array_push($total_p,$products);
							}
						}
						// $this->product_barcode($pr->code, $pr->barcode_symbology, 60)
					// echo '('.json_encode(array('items'=>$products,'total'=>300)).')';
					echo  json_encode(array('items'=>$total_p )) ;
			}
			
			
    }


    public function print_labels()
    {
        $limit = 10;
        $this->load->helper('pagination');
        $page = $this->input->get('page');
        $total = $this->products_model->products_count();
        $info = ['page' => $page, 'total' => ceil($total / $limit)];
        $pagination = pagination('products/print_labels', $total, $limit, true);
        $products = $this->products_model->fetch_products($limit, 1);
        $html = "";
        foreach ($products as $pr) {
            $html .= '<div class="text-center labels break-after"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 25) . '<br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $pr->price . '</span></div>';
        }
        $this->data['links'] = $pagination;
        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_labels");
        $this->load->view($this->theme . 'products/print_labels', $this->data);
    }

    public function single_barcode($product_id = null, $count = null)
    {

        $product = $this->site->getProductByID($product_id);

        $limit = 10;
        $this->load->helper('pagination');
        $page = $this->input->get('page');
        $total = $this->products_model->products_count();
        $info = ['page' => $page, 'total' => ceil($total / $limit)];
        $pagination = pagination('products/print_barcodes', $total, $limit, true);

        $products = $this->products_model->fetch_productsById($limit, $limit, $product_id);

        $r = 1;
        $html = "";

        $html .= '<div class="main">';
        for ($i = 0; $i < $limit; $i++) {
            foreach ($products as $pr) {
                if ($r <= 5) {
                    $html .= '<div class="sub"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 60) . '<br><span class="code">' . $pr->code . '</span><span class="price">Rs. ' . $pr->price . '</span></div>';
                    $r++;

                } else {
                }
                if ($r > 5) {
                    $r = 1;

                    $html .= '</div><div class="main">';

                }

            }
        }
        $html .= '</div>';

        $this->data['links'] = $pagination;
        $this->data['html'] = $html;
        $this->data['product_id'] = $product_id;
        $this->data['page_title'] = lang("print_barcodes") . ' (' . $product->name . ')';
        $this->load->view($this->theme . 'products/single_barcode', $this->data);

    }

    public function single_barcode_ajax($product_id = null, $count = null)
    {

        $product_id = $this->input->get('product_id');
        $count_Val = (int) $this->input->get('count');
        $product = $this->site->getProductByID($product_id);
        $limit = $count_Val;
        $this->load->helper('pagination');
        $page = $this->input->get('page');
        $total = $this->products_model->products_count();
        $info = ['page' => $page, 'total' => ceil($total / $limit)];
        $pagination = pagination('products/print_barcodes', $total, $limit, true);

        $products = $this->products_model->fetch_productsById($limit, $limit, $product_id);

        $r = 1;
        $html = "";

        $html .= '<div class="main">';
        for ($i = 0; $i < $limit; $i++) {
            foreach ($products as $pr) {
                if ($r <= 5) {
                    $html .= '<div class="sub"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 60) . '<br><span class="code">' . $pr->code . '</span><span class="price">Rs. ' . $pr->price . '</span></div>';
                    $r++;

                } else {
                }
                if ($r > 5) {
                    $r = 1;

                    $html .= '</div><div class="main">';

                }

            }
        }
        $html .= '</div>';

        echo $html;

    }

    public function single_label($product_id = null, $warehouse_id = null)
    {

        $product = $this->site->getProductByID($product_id);
        $html = "";
        if ($product->quantity > 0) {
            for ($r = 1; $r <= $product->quantity; $r++) {
                $html .= '<div class="text-center labels"><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 25) . ' <br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $product->price . '</span></div>';
            }
        } else {
            for ($r = 1; $r <= 10; $r++) {
                $html .= '<div class="text-center labels"><strong>' . $product->name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 25) . ' <br><span class="price">' . lang('price') . ': ' . $this->Settings->currency_prefix . ' ' . $product->price . '</span></div>';
            }
        }
        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_labels") . ' (' . $product->name . ')';
        $this->load->view($this->theme . 'products/single_label', $this->data);

    }

    public function add()
    {
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
        $this->form_validation->set_rules('uom_id', lang("Unit of measeure"), 'required');
        $this->form_validation->set_rules('product_tax', lang("product_tax"), 'required|is_numeric');
        $this->form_validation->set_rules('alert_quantity', lang("alert_quantity"), 'is_numeric');

        if ($this->form_validation->run() == true) {
            if ($this->input->post('price_margin')) {
                $price_margin = $this->input->post(price_margin);
            } else {
                $price_margin = 0;
            }
            $data = array(
                'type' => $this->input->post('type'),
                'code' => $this->input->post('code'),
                'pcode' => $this->input->post('code'),
                'hsncode' => $this->input->post('hsn'),
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category'),
                'local_barcode' => $this->products_model->get_latest_local_barcode(),
                'uom_id' => $this->input->post('uom_id'),
                'gst_id' => $this->input->post('gst_groups'),
                'price' => $this->input->post('price'),
                'cost' => $this->input->post('cost'),
                'tax' => $this->input->post('product_tax'),
                'tax_method' => $this->input->post('tax_method'),
                'alert_quantity' => $this->input->post('alert_quantity'),
                'details' => $this->input->post('details'),
                'barcode_symbology' => $this->input->post('barcode_symbology'),
                'price_margin' => $price_margin,
                'supplier_id' =>$this->input->post('supplier_id'),
                'pkg_qty' =>$this->input->post('pkg_qty'),
            );

            if ($this->Settings->multi_store) {
                $stores = $this->site->getAllStores();
                foreach ($stores as $store) {
                    $store_quantities[] = array(
                        'store_id' => $store->id,
                        'quantity' => $this->input->post('quantity' . $store->id),
                        'price' => $this->input->post('price' . $store->id),
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
                            'quantity' => $_POST['combo_item_quantity'][$r],
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
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/add");
                }

                $photo = $this->upload->file_name;
                $data['image'] = $photo;

                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/' . $photo;
                $config['new_image'] = 'uploads/thumbs/' . $photo;
                $config['maintain_ratio'] = true;
                $config['width'] = 110;
                $config['height'] = 110;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    $this->session->set_flashdata('error', $this->image_lib->display_errors());
                    redirect("products/add");
                }

            }
            // $this->tec->print_arrays($data, $items);
        }

        if ($this->form_validation->run() == true && $this->products_model->addProduct($data, $store_quantities, $items)) {

            $this->session->set_flashdata('message', lang("product_added"));
            redirect('products');

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['stores'] = $this->site->getAllStores();
            $this->data['gst_groupss'] = $this->site->getAllGst();
            $this->data['page_title'] = lang('add_product');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_product')));
            $meta = array('page_title' => lang('add_product'), 'bc' => $bc);
            $this->page_construct('products/add', $this->data, $meta);

        }
    }

    public function edit($id = null)
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $pr_details = $this->site->getProductByID($id);
        if ($this->input->post('code') != $pr_details->code) {
            $this->form_validation->set_rules('code', lang("product_code"), 'is_unique[products.code]');
        }
        $this->form_validation->set_rules('code', lang("product_code"), 'trim|min_length[2]|max_length[50]|required|alpha_numeric');
        $this->form_validation->set_rules('name', lang("product_name"), 'required');
        $this->form_validation->set_rules('category', lang("category"), 'required');
        $this->form_validation->set_rules('price', lang("product_price"), 'required|is_numeric');
        $this->form_validation->set_rules('cost', lang("product_cost"), 'required|is_numeric');
        $this->form_validation->set_rules('uom_id', lang("Unit of measeure"), 'required');
        $this->form_validation->set_rules('product_tax', lang("product_tax"), 'required|is_numeric');
        $this->form_validation->set_rules('alert_quantity', lang("alert_quantity"), 'is_numeric');

        if ($this->form_validation->run() == true) {

            $data = array(
                'type' => $this->input->post('type'),
                'code' => $this->input->post('code'),
                'hsncode' => $this->input->post('hsn'),
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category'),
                'gst_id' => $this->input->post('gst_groups'),
                'price' => $this->input->post('price'),
                'uom_id' => $this->input->post('uom_id'),
                'cost' => $this->input->post('cost'),
                'tax' => $this->input->post('product_tax'),
                'tax_method' => $this->input->post('tax_method'),
                'alert_quantity' => $this->input->post('alert_quantity'),
                'details' => $this->input->post('details'),
                'barcode_symbology' => $this->input->post('barcode_symbology'),
                'price_margin' => $this->input->post('price_margin'),
                'supplier_id' => $this->input->post('supplier_id'),
                'pkg_qty' => $this->input->post('pkg_qty'),
            );

            if ($this->Settings->multi_store) {
                $stores = $this->site->getAllStores();
                foreach ($stores as $store) {
                    $store_quantities[] = array(
                        'store_id' => $store->id,
                        'quantity' => $this->input->post('quantity' . $store->id),
                        'price' => $this->input->post('price' . $store->id),
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
                            'quantity' => $_POST['combo_item_quantity'][$r],
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
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/edit/" . $id);
                }

                $photo = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/' . $photo;
                $config['new_image'] = 'uploads/thumbs/' . $photo;
                $config['maintain_ratio'] = true;
                $config['width'] = 110;
                $config['height'] = 110;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    $this->session->set_flashdata('error', $this->image_lib->display_errors());
                    redirect("products/edit/" . $id);
                }

            } else {
                $photo = null;
            }

        }

        if ($this->form_validation->run() == true && $this->products_model->updateProduct($id, $data, $store_quantities, $items, $photo)) {

            $this->session->set_flashdata('message', lang("product_updated"));
            redirect("products");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $product = $this->site->getProductByID($id);
            if ($product->type == 'combo') {
								$combo_items = $this->products_model->getComboItemsByPID($id);
								$items = array();
								if($combo_items){
									foreach ($combo_items as $combo_item) {
											$cpr = $this->site->getProductByID($combo_item->id);
											$cpr->qty = $combo_item->qty;
											$items[] = array('id' => $cpr->id, 'row' => $cpr);
									}
								}
                $this->data['items'] = $items;
            }
            $this->data['product'] = $product;
            $this->data['stores'] = $this->site->getAllStores();
            $this->data['stores_quantities'] = $this->Settings->multi_store ? $this->products_model->getStoresQuantity($id) : $this->products_model->getStoreQuantity($id);
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['gst_groupss'] = $this->site->getAllGst();
            $this->data['page_title'] = lang('edit_product');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_product')));
            $meta = array('page_title' => lang('edit_product'), 'bc' => $bc);
            $this->page_construct('products/edit', $this->data, $meta);

        }
    }

    // public function import()
    // {
    //     if (!$this->Admin) {
    //         $this->session->set_flashdata('error', lang('access_denied'));
    //         redirect('pos');
    //     }
    //     $this->load->helper('security');
    //     $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

    //     if ($this->form_validation->run() == true) {
    //         if (DEMO) {
    //             $this->session->set_flashdata('warning', lang("disabled_in_demo"));
    //             redirect('pos');
    //         }

    //         if (isset($_FILES["userfile"])) {

    //             $this->load->library('upload');

    //             $config['upload_path'] = 'uploads/';
    //             $config['allowed_types'] = 'csv';
    //             $config['max_size'] = '1000';
    //             $config['overwrite'] = true;

    //             $this->upload->initialize($config);

    //             if (!$this->upload->do_upload()) {
    //                 $error = $this->upload->display_errors();
    //                 $this->session->set_flashdata('error', $error);
    //                 redirect("products/import");
    //             }

    //             $csv = $this->upload->file_name;

    //             $arrResult = array();
    //             $handle = fopen("uploads/" . $csv, "r");
    //             if ($handle) {
    //                 while (($row = fgetcsv($handle, 1000, ",")) !== false) {
    //                     $arrResult[] = $row;
    //                 }
    //                 fclose($handle);
    //             }
		// 						array_shift($arrResult);
		// 						echo '<pre>';
		// 						var_dump($arrResult);
		// 						die();
    //             $keys = array('code', 'name', 'hsn', 'gst', 'cost', 'margin', 'price', 'category','supplier_id','barcode');

    //             $final = array();
    //             foreach ($arrResult as $key => $value) {
    //                 $final[] = array_combine($keys, $value);
    //             }

    //             if (sizeof($final) > 1001) {
    //                 $this->session->set_flashdata('error', lang("more_than_allowed"));
    //                 redirect("products/import");
    //             }

    //             foreach ($final as $csv_pr) {
    //                 if ($this->products_model->getProductByCode($csv_pr['code'])) {
    //                     $this->session->set_flashdata('error', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("code_already_exist"));
    //                     redirect("products/import");
    //                 }
    //                 if (!is_numeric($csv_pr['gst'])) {
		// 									$this->session->set_flashdata('error', lang("check_product_tax") . " (" . $csv_pr['gst'] . "). " . lang("tax_not_numeric"));
		// 									redirect("products/import");
    //                 }
    //                 if (!($category = $this->site->getCategoryByCode($csv_pr['category']))) {
		// 									$this->session->set_flashdata('error', lang("check_category") . " (" . $csv_pr['category'] . "). " . lang("category_x_exist"));
		// 									redirect("products/import");
    //                 }
		// 								if (!$this->products_model->getSupplierByid($csv_pr['supplier_id'])) {
		// 										$this->session->set_flashdata('error', lang("check_suppiler_id") . " (" . $csv_pr['supplier_id'] . "). " . lang("suppiler_not_exist"));
		// 										redirect("products/import");
		// 								}
    //                 $data[] = array(
    //                     'type' => 'standard',
    //                     'code' => $csv_pr['code'],
    //                     'hsncode' => $csv_pr['hsn'],
    //                     'name' => $csv_pr['name'],
    //                     'cost' => $csv_pr['cost'],
    //                     'gst_id' => $csv_pr['gst'],
    //                     'price' => $csv_pr['price'],
    //                     'price_margin' => $csv_pr['margin'],
    //                     'category_id' => $category->id,
    //                     'supplier_id' => $csv_pr['supplier_id'],
    //                     'code' => $csv_pr['barcode'],
    //                     'local_barcode' => $this->products_model->get_latest_local_barcode(),
    //                 );
    //             }
    //             //print_r($data); die();
    //         }

    //     }

    //     if ($this->form_validation->run() == true && $this->products_model->add_products($data)) {

    //         $this->session->set_flashdata('message', lang("products_added"));
    //         redirect('products');

    //     } else {

    //         $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
    //         $this->data['categories'] = $this->site->getAllCategories();
    //         $this->data['page_title'] = lang('import_products');
    //         $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('import_products')));
    //         $meta = array('page_title' => lang('import_products'), 'bc' => $bc);
    //         $this->page_construct('products/import', $this->data, $meta);

    //     }
    // }

    public function import()
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

				$data = array();
        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect('pos');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '10000';
                $config['overwrite'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/import");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("uploads/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
								array_shift($arrResult);
								$data = array();
                $keys = array('category', 'subcategory', 'pcode', 'supplier_c_f', 'supplier_name', 'hsn', 'barcode', 'product_name','brand','pack_id','um_unit','uom','price_level','price','cost','tax','price_withtax');
                // $keys = array('code', 'name', 'hsn', 'gst', 'cost', 'margin', 'price', 'category','supplier_id','barcode');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
								}

                foreach ($final as $csv_pr) {

                    $data[] = array(
                        'type' => 'standard',
                        'pcode' => trim($csv_pr['pcode']),
                        'pack_id' => trim($csv_pr['pack_id']),
                        'um_unit' => trim($csv_pr['um_unit']),
                        'price_level' => trim($csv_pr['price_level']),
                        'price_notax' => trim($csv_pr['price']),
                        'hsncode' => trim($csv_pr['hsn']),
                        'name' => trim($csv_pr['product_name']),
                        'cost' => trim($csv_pr['cost']),
                        'tax' => 0,
                        'alert_quantity' => 2,
                        'gst_id' => $this->products_model->get_gst_id(trim($csv_pr['tax'])),
                        'price' => trim($csv_pr['price']),
                        'price_margin' => trim($csv_pr['price_withtax']),
                        'brand_id' => $this->products_model->get_brand_id(trim($csv_pr['brand'])),
                        'subcategory_id' => $this->products_model->get_subcategory_id(trim($csv_pr['subcategory'])),
                        'uom_id' => $this->products_model->get_uom_id(trim($csv_pr['uom'])),
                        'category_id' => $this->products_model->get_category_id(trim($csv_pr['category'])),
                        'supplier_id' =>  $this->products_model->get_supplier_id(trim($csv_pr['supplier_name']),trim($csv_pr['supplier_c_f'])),
                        'code' => trim($csv_pr['barcode']),
                        'local_barcode' => $this->products_model->get_latest_local_barcode(),
                    );
                }
            }

        }
				$res= $this->products_model->add_products($data);
        if ($this->form_validation->run() == true && $res ) {

            $this->session->set_flashdata('message', lang("products_added"));
            redirect('products');

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['page_title'] = lang('import_products');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('import_products')));
            $meta = array('page_title' => lang('import_products'), 'bc' => $bc);
            $this->page_construct('products/import', $this->data, $meta);

        }
    }

    public function delete($id = null)
    {
        if (DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        if ($this->products_model->deleteProduct($id)) {
            $this->session->set_flashdata('message', lang("product_deleted"));
            redirect('products');
        }

    }

    public function suggestions()
    {
        $term = $this->input->get('term', true);

        $rows = $this->products_model->getProductNames($term);
        if ($rows) {
            foreach ($rows as $row) {
                $row->qty = 1;
                $gst = $this->site->getGstByID($row->gst_id);
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'gst' => $gst);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

}
