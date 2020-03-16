<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GST extends MY_Controller
{

    function __construct() {
        parent::__construct();


        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('gst_model');
    }

    function index() {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['gst_groups'] = $this->site->getAllGst();
        $this->data['page_title'] = lang('VAT');
        $bc = array(array('link' => '#', 'page' => lang('VAT')));
        $meta = array('page_title' => lang('VAT'), 'bc' => $bc);
        $this->page_construct('gst/index', $this->data, $meta);

    }

    function get_gst_groups() {

        $this->load->library('datatables');
        $this->datatables->select("id, name, cgst, sgst, (cgst+sgst) as vat");
        $this->datatables->from('gst_groups');
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'> <a href='" . site_url('gst/edit/$1') . "' title='" . lang("Edit_GST") . "' class='tip btn btn-warning btn-xs'><i class='fa fa-edit'></i></a> <a href='" . site_url('gst/delete/$1') . "' onClick=\"return confirm('" . lang('alert_x_category') . "')\" title='" . lang("gst_category") . "' class='tip btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></a></div></div>", "id, name, cgst, sgst");
        // $this->datatables->add_column("vat", "$1", "cgst, sgst");
        $this->datatables->unset_column('id');
        echo $this->datatables->generate();

    }

    function add() {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        $this->form_validation->set_rules('name', lang('name'), 'required');

        if ($this->form_validation->run() == true) {
           $data = array('name' => $this->input->post('name'), 'cgst' => $this->input->post('cgst'), 'sgst' => $this->input->post('sgst'));
echo "<script>console.log(\"XXX\")</script>";
             }

        if ($this->form_validation->run() == true && $this->gst_model->addGst($data)) {

            $this->session->set_flashdata('message', lang('GST Added'));
            redirect("gst");

        } else {
echo "<script>console.log(\"AAA\")</script>";
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('Add GST');
            $bc = array(array('link' => site_url('gst'), 'page' => lang('GST')), array('link' => '#', 'page' => lang('Add GST')));
            $meta = array('page_title' => lang('Add GST'), 'bc' => $bc);
            $this->page_construct('gst/add', $this->data, $meta);


        }


		echo "<script>console.log(\"CCC\")</script>";
    }

    function edit($id = NULL) {



        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('name', lang('name'), 'required');
		   $this->form_validation->set_rules('cgst', lang('cgst'), 'required');
		      $this->form_validation->set_rules('sgst', lang('sgst'), 'required');
           $this->form_validation->set_rules('vat', lang('vat'), 'required');

        if ($this->form_validation->run() == true) {
            $cgst= $this->input->post('vat')/2;
            $sgst=$cgst;
            $data = array('name' => $this->input->post('name'), 'cgst' => $cgst, 'sgst' => $sgst);


        }

        if ($this->form_validation->run() == true && $this->gst_model->updateGst($id, $data)) {

            $this->session->set_flashdata('message', lang('VAT_updated'));
            redirect("gst");

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

		  $this->data['gst_groups'] = $this->site->getGstByID($id);
            $this->data['page_title'] = lang('new_gst');
            $bc = array(array('link' => site_url('gst'), 'page' => lang('gst')), array('link' => '#', 'page' => lang('edit_vat')));
            $meta = array('page_title' => lang('edit_vat'), 'bc' => $bc);
            $this->page_construct('gst/edit', $this->data, $meta);

        }
    }

    function delete($id = NULL) {
        if(DEMO) {
            $this->session->set_flashdata('error', lang('disabled_in_demo'));
            redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
        }
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->gst_model->deleteDelete($id)) {
            $this->session->set_flashdata('message', lang("GST_Deleted"));
            redirect('gst');
        }
    }

    function import() {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect('pos');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '500';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("categories/import");
                }


                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("uploads/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                array_shift($arrResult);

                $keys = array('code', 'name');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                if (sizeof($final) > 1001) {
                    $this->session->set_flashdata('error', lang("more_than_allowed"));
                    redirect("categories/import");
                }

                foreach ($final as $csv_pr) {
                    if($this->site->getCategoryByCode($csv_pr['code'])) {
                        $this->session->set_flashdata('error', lang("check_category") . " (" . $csv_pr['code'] . "). " . lang("category_already_exist"));
                        redirect("categories/import");
                    }
                    $data[] = array('code' => $csv_pr['code'], 'name' => $csv_pr['name']);
                }
            }

        }

        if ($this->form_validation->run() == true && $this->categories_model->add_categories($data)) {

            $this->session->set_flashdata('message', lang("categories_added"));
            redirect('categories');

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('import_categories');
            $bc = array(array('link' => site_url('products'), 'page' => lang('products')), array('link' => site_url('categories'), 'page' => lang('categories')), array('link' => '#', 'page' => lang('import_categories')));
            $meta = array('page_title' => lang('import_categories'), 'bc' => $bc);
            $this->page_construct('categories/import', $this->data, $meta);

        }
    }

}
