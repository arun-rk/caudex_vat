<?php if (!defined('BASEPATH')) {   exit('No direct script access allowed'); }

class Shift extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            redirect('login');
        }
		}
		public function start()
		{
			$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'start' => date('Y-m-d H:i:s', time()),
			);
			$res = $this->db->insert('tec_shift',$data);
			if($res){
				echo $this->db->insert_id();
				exit;
			}
			echo 0;
			exit;
		}
		public function end()
		{
			$id = $this->input->post('id');
			$data = array(
				'end' => date('Y-m-d H:i:s', time()),
			);
			$res = $this->db->update('tec_shift',$data,array( 'id'=>$id));
			if($res){
				echo $id;
				exit;
			}
			echo 0;
			exit;
		}
		
    public function shift_close($user_id = null)
    {
			$id = $this->input->get('id');
        // if (!$this->Admin) {
        //     $user_id = $this->session->userdata('user_id');
        // }
        // $this->form_validation->set_rules('total_cash', lang("total_cash"), 'trim|required|numeric');
        // $this->form_validation->set_rules('total_cheques', lang("total_cheques"), 'trim|required|numeric');
        // $this->form_validation->set_rules('total_cc_slips', lang("total_cc_slips"), 'trim|required|numeric');

        // if ($this->form_validation->run() == true) {
        //     if ($this->Admin) {
        //         $user_register = $user_id ? $this->pos_model->registerData($user_id) : null;
        //         $rid = $user_register ? $user_register->id : $this->session->userdata('register_id');
        //         $user_id = $user_register ? $user_register->user_id : $this->session->userdata('user_id');
        //         $register_open_time = $user_register ? $user_register->date : $this->session->userdata('register_open_time');
        //         $cash_in_hand = $user_register ? $user_register->cash_in_hand : $this->session->userdata('cash_in_hand');
        //         $ccsales = $this->pos_model->getRegisterCCSales($register_open_time, $user_id);
        //         $cashsales = $this->pos_model->getRegisterCashSales($register_open_time, $user_id);
        //         $expenses = $this->pos_model->getRegisterExpenses($register_open_time, $user_id);
        //         $chsales = $this->pos_model->getRegisterChSales($register_open_time, $user_id);
        //         $total_cash = ($cashsales->paid ? ($cashsales->paid + $cash_in_hand) : $cash_in_hand);
        //         $total_cash -= ($expenses->total ? $expenses->total : 0);
        //     } else {
        //         $rid = $this->session->userdata('register_id');
        //         $user_id = $this->session->userdata('user_id');
        //         $register_open_time = $this->session->userdata('register_open_time');
        //         $cash_in_hand = $this->session->userdata('cash_in_hand');
        //         $ccsales = $this->pos_model->getRegisterCCSales($register_open_time);
        //         $cashsales = $this->pos_model->getRegisterCashSales($register_open_time);
        //         $expenses = $this->pos_model->getRegisterExpenses($register_open_time);
        //         $chsales = $this->pos_model->getRegisterChSales($register_open_time);
        //         $total_cash = ($cashsales->paid ? ($cashsales->paid + $cash_in_hand) : $cash_in_hand);
        //         $total_cash -= ($expenses->total ? $expenses->total : 0);
        //     }

        //     $data = array('closed_at' => date('Y-m-d H:i:s'),
        //         'total_cash' => $total_cash,
        //         'total_cheques' => $chsales->total_cheques,
        //         'total_cc_slips' => $ccsales->total_cc_slips,
        //         'total_cash_submitted' => $this->input->post('total_cash_submitted'),
        //         'total_cheques_submitted' => $this->input->post('total_cheques_submitted'),
        //         'total_cc_slips_submitted' => $this->input->post('total_cc_slips_submitted'),
        //         'note' => $this->input->post('note'),
        //         'status' => 'close',
        //         'transfer_opened_bills' => $this->input->post('transfer_opened_bills'),
        //         'closed_by' => $this->session->userdata('user_id'),
        //     );

        //     // $this->tec->print_arrays($data);

        // } elseif ($this->input->post('close_register')) {
        //     $this->session->set_flashdata('error', (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
        //     redirect("pos");
        // }

        // if ($this->form_validation->run() == true && $this->pos_model->closeRegister($rid, $user_id, $data)) {
        //     $this->session->unset_userdata('register_id');
        //     $this->session->unset_userdata('cash_in_hand');
        //     $this->session->unset_userdata('register_open_time');
        //     $this->session->set_flashdata('message', lang("register_closed"));
        //     redirect("welcome");
        // } else {
        //     if ($this->Admin) {
        //         $user_register = $user_id ? $this->pos_model->registerData($user_id) : null;
        //         $register_open_time = $user_register ? $user_register->date : $this->session->userdata('register_open_time');
        //         $this->data['cash_in_hand'] = $user_register ? $user_register->cash_in_hand : null;
        //         $this->data['register_open_time'] = $user_register ? $register_open_time : null;
        //     } else {
        //         $register_open_time = $this->session->userdata('register_open_time');
        //         $this->data['cash_in_hand'] = null;
        //         $this->data['register_open_time'] = null;
        //     }
        //     $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        //     $this->data['ccsales'] = $this->pos_model->getRegisterCCSales($register_open_time, $user_id);
        //     $this->data['cashsales'] = $this->pos_model->getRegisterCashSales($register_open_time, $user_id);
        //     $this->data['chsales'] = $this->pos_model->getRegisterChSales($register_open_time, $user_id);
        //     $this->data['other_sales'] = $this->pos_model->getRegisterOtherSales($register_open_time, $user_id);
        //     $this->data['gcsales'] = $this->pos_model->getRegisterGCSales($register_open_time, $user_id);
        //     $this->data['stripesales'] = $this->pos_model->getRegisterStripeSales($register_open_time, $user_id);
        //     $this->data['totalsales'] = $this->pos_model->getRegisterSales($register_open_time, $user_id);
        //     $this->data['expenses'] = $this->pos_model->getRegisterExpenses($register_open_time);
        //     $this->data['users'] = $this->tec->getUsers($user_id);
        //     $this->data['suspended_bills'] = $this->pos_model->getSuspendedsales($user_id);
        //     $this->data['user_id'] = $user_id;
						$this->data['shift'] = $this->db->get_where('tec_shift',array('id'=>$id))->row();
						// var_dump($this->data['shift']);
            $this->load->view($this->theme . 'pos/shit_close', $this->data);
        // }
    }
}
