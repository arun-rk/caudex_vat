<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function getCustomerByID($id) {
        $q = $this->db->get_where('customers', array('id' => $id), 1);
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getList($id) {
			if($id){
				$q = $this->db->get_where('customers',array('id' => $id ));
        if( $q->num_rows()) {
					$data = $q->row();
					$data->outstanding_amt = $data->total_balance;
            return $data;
        }
			}
			else{
				$q = $this->db->get('customers');
        if( $q->num_rows()) {
            return $q->result();
        }
			}
      return FALSE;
    }
    // public function getList($id) {
		// 	if($id){
		// 		$q = $this->db->get_where('customers',array('id' => $id ));
    //     if( $q->num_rows()) {
		// 			$data = $q->row();
		// 			$data->outstanding_amt = $this->get_customer_outstanding_amt($data->id);
    //         return $data;
    //     }
		// 	}
		// 	else{
		// 		$q = $this->db->get('customers');
    //     if( $q->num_rows()) {
    //         return $q->result();
    //     }
		// 	}
    //   return FALSE;
    // }

    public function get_customer_outstanding_amt($id) {
        if($q=$this->db->query('SELECT SUM(grand_total)-(SUM(paid) + SUM(return_amount))  AS outstanding_amt FROM  tec_sales
				WHERE customer_id = ?',array($id))) {
            return $q->row()->outstanding_amt;
        }
        return 0;
    }

    public function addCustomer($data = array()) {
        if($this->db->insert('customers', $data)) {
            return $this->db->insert_id();
				}
				$x = $this->db->error();
        return false;
    }

    public function addSales($data = array()) {
        if($this->db->insert('sales', $data)) {
            return $this->db->insert_id();
				}
        return false;
    }

    public function updateCustomer($id, $data = array()) {
        if($this->db->update('customers', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteCustomer($id) {
        if($this->db->delete('customers', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
