<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Uom extends MY_Controller
{

    function __construct() {
        parent::__construct();


        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('uom_model');
    }

    function index() {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['page_title'] = lang('UOM');
        $bc = array(array('link' => '#', 'page' => lang('UOM')));
        $meta = array('page_title' => lang('UOM'), 'bc' => $bc);
        $this->page_construct('uom', $this->data, $meta);

    }

    function get_gst_groups() {
        $this->load->library('datatables');
        $this->datatables->select("id, base_name, symbol");
        $this->datatables->from('tec_uom');
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group'> <a href='#' title='" . lang("Edit_UOM") . "' class='tip btn btn-warning btn-xs edit-data' id='edit-data'><i class='fa fa-edit'></i></a> <a href='#'  class='tip btn btn-danger btn-xs' id='delete-data'><i class='fa fa-trash-o'></i></a></div></div>", "id, name, cgst, sgst");
        // $this->datatables->add_column("vat", "$1", "cgst, sgst");
        $this->datatables->unset_column('id');
        echo $this->datatables->generate();

    }

    function saveUom() 
    {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }

        //$this->form_validation->set_rules('base_name', lang('base name'), 'required');

        if ($a=1) 
            {
                $data = array(
                    'base_name'=>$this->input->get('base_name'),
                    'symbol'=>$this->input->get('symbol'),
                ); 
                $status = $this->uom_model->addUom($data);
                if($status)
                {
                    print 1;
                }
                else
                {
                    print 3;
                }
            }
            else
            {
                print 2;
            }

    }

    function updateUom() {

        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('pos');
        }


        $hidid=$this->input->get('hidid');
        if($hidid>0)
        {
            $data = array(
                'base_name'=>$this->input->get('base_name'),
                'symbol'=>$this->input->get('symbol'),
            ); 
            $status = $this->uom_model->updateUom($hidid,$data);
            if($status)
            {
                print 1;
            }
            else
            {
                print 3;
            }
        }
        else
        {
            print 2;
        }
    }

    function deleteUom($id = NULL) {
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

        if ($this->uom_model->deleteDelete($id)) {
            print 1;
        }
        else
        {
            print 2;
        }
    }

   

}
