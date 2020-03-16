<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function getSaleByID($id) {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
				}
				$x = $this->db->error();
        return FALSE;
    }
 public function getSaleItemsBySaleID($sale_id,$arr=NULL) {
				 $this->db->where('sale_id',$sale_id);
				 if($arr){
					 $this->db->where_in('id',$arr);
				 }
        $q = $this->db->get('sale_items');
        if ($q->num_rows() > 0) {
			 foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
		public function getReturnItemsBySaleID($sale_id) {
			
			$this->db->select('sale_items_returns.*,tec_products.name');
			$this->db->from('sale_items_returns');
			$this->db->join('tec_products','tec_products.id = tec_sale_items_returns.product_id');
			$this->db->where('return_sale_id',$sale_id);
			$q = $this->db->get();
			if ($q->num_rows() > 0) {
				return $q->result_array();
			}
			$x = $this->db->error();
			return FALSE;	
 		}
	 public function getPaymentsBySaleID($sale_id) {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	
    public function deleteInvoice($id) {
        if($this->db->delete('sale_items', array('sale_id' => $id)) &&
        $this->db->delete('sales', array('id' => $id)) && $this->db->delete('payments', array('sale_id' => $id))) {
            return true;
        }
        return FALSE;
    }
 public function deleteReturnInvoice($id) {
        if($this->db->delete('sale_items_returns', array('sale_id' => $id)) &&
        $this->db->delete('sales_returns', array('id' => $id)) && $this->db->delete('payments_returns', array('sale_id' => $id))) {
            return true;
        }
        return FALSE;
    }
	 public function returnInvoice($id) {
        if($this->db->delete('sale_items', array('sale_id' => $id)) &&
        $this->db->delete('sales', array('id' => $id)) && $this->db->delete('payments', array('sale_id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	  public function AddReturnInvoice($sales_return,$Sale_Payments) {
        
		      
               if($this->db->insert('sales_returns', $sales_return) && $this->db->insert('payments_returns', $Sale_Payments)) {
				 
                    return true; 
         
			   }
	            
 	 
				 return false;
				}
	  public function AddReturnSale($sales_return) {
				if($this->db->insert('sales_returns', $sales_return) ) {
						return $this->db->insert_id(); 
			   }
        $x= $this->db->error();
       	return false;
    }

  	public function AddReturnItems($items_returns) {
			if($this->db->insert('sale_items_returns', $items_returns) ) {
					return true; 
			}
			$x= $this->db->error();
      return false;
    }
   	

   public function deleteOpenedSale($id) {
        if($this->db->delete('suspended_items', array('suspend_id' => $id)) && $this->db->delete('suspended_sales', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	 public function deletequotation($id) {
        if($this->db->delete('estimate_items', array('estimate_id' => $id)) && $this->db->delete('estimate_sales', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }


    public function getSalePayments($sale_id) {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getPaymentByID($id) {
        $q = $this->db->get_where('payments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addPayment($data = array()) {
        if ($this->db->insert('payments', $data)) {
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCard($data['gc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['gc_no']));
            }
            $this->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }

    public function updatePayment($id, $data = array()) {
        $payment = $this->getPaymentByID($id);
        if ($payment->paid_by == 'gift_card') {
            $gc = $this->site->getGiftCard($payment->gc_no);
            $this->db->update('gift_cards', array('balance' => ($gc->balance + $payment->amount)), array('card_no' => $payment->gc_no));
        }
        if ($this->db->update('payments', $data, array('id' => $id))) {
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCard($data['gc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['gc_no']));
            }
            $this->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }

    public function deletePayment($id) {
        $payment = $this->getPaymentByID($id);
        if ($payment->paid_by == 'gift_card') {
            $gc = $this->site->getGiftCard($payment->gc_no);
            $this->db->update('gift_cards', array('balance' => ($gc->balance + $payment->amount)), array('card_no' => $payment->gc_no));
        }
        if ($this->db->delete('payments', array('id' => $id))) {
            $this->syncSalePayments($payment->sale_id);
            return true;
        }
        return FALSE;
    }

    public function syncSalePayments($id) {
        $sale = $this->getSaleByID($id);
        $payments = $this->getSalePayments($id);
        $paid = 0;
        if($payments) {
            foreach ($payments as $payment) {
                $paid += $payment->amount;
            }
        }
        $status = $paid <= 0 ? 'due' : (@$sale->grand_total <= $paid ? 'paid' : 'partial');
        if ($this->db->update('sales', array('paid' => $paid, 'status' => $status), array('id' => $id))) {
            return true;
        }

        return FALSE;
		}

		public function update_customer_balance($duedetails,$sale=TRUE)
		{

			$dues = $this->db->get_where('tec_customers',array('id'=>$duedetails['id']))->row();
			$this->db->where('id',$duedetails['id']);
			
			if($dues){
				if($sale){
					$duedetails['total_balance'] =  ((float)$dues->total_balance*1000 + (float)$duedetails['total_balance']*1000)/1000  ;
				}
				else{
					$duedetails['total_balance'] =  ((float)$dues->total_balance*1000 - (float)$duedetails['total_balance']*1000)/1000  ;
				}
			}
			$res = $this->db->update('tec_customers',array('total_balance' =>$duedetails['total_balance']));
		}


			public function get_customer_dues($id)
			{
				
				$query = $this->db->get_where('customers',array('id' => $id ));
        // if( $q->num_rows()) {
				// 	$data = $q->row();
				// 	$data->outstanding_amt = $data->total_balance;
        //     return $data;
        // }
			// $sql = 'SELECT id,grand_total,paid,(grand_total-(paid+return_amount)) AS due FROM tec_sales WHERE tec_sales.customer_id = ?
			// AND  (grand_total-(paid+return_amount))  > 0
			// ORDER BY (grand_total- (paid+return_amount)) DESC';
			// $query = $this->db->query($sql,array($id));
			if(($query) && ($query->num_rows()))
				{
					return $query->row()->total_balance;
				}
			return FALSE;
			}
			// public function get_customer_dues($id)
			// {
			// $sql = 'SELECT id,grand_total,paid,(grand_total-(paid+return_amount)) AS due FROM tec_sales WHERE tec_sales.customer_id = ?
			// AND  (grand_total-(paid+return_amount))  > 0
			// ORDER BY (grand_total- (paid+return_amount)) DESC';
			// $query = $this->db->query($sql,array($id));
			// if(($query) && ($query->num_rows()))
			// 	{
			// 		return $query->result();
			// 	}
			// return FALSE;
			// }

    public function updateStatus($id, $status) {
        if ($this->db->update('sales', array('status' => $status), array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function AddProductQty($id, $qty) {
				$this->db->where('product_id', $id);
				$this->db->set('quantity', 'quantity+'.$qty, FALSE);
        if ($this->db->update('product_store_qty')) {
            return true;
        }
        return false;
    }

    public function getAllSaleItems($sale_id) {
        $j = "(SELECT id, code, name, tax_method from {$this->db->dbprefix('products')}) P";
        $this->db->select("sale_items.*,
            (CASE WHEN {$this->db->dbprefix('sale_items')}.product_code IS NULL THEN {$this->db->dbprefix('products')}.code ELSE {$this->db->dbprefix('sale_items')}.product_code END) as product_code,
            (CASE WHEN {$this->db->dbprefix('sale_items')}.product_name IS NULL THEN {$this->db->dbprefix('products')}.name ELSE {$this->db->dbprefix('sale_items')}.product_name END) as product_name,
            {$this->db->dbprefix('products')}.tax_method as tax_method", FALSE)
        ->join('products', 'products.id=sale_items.product_id', 'left outer')
        ->order_by('sale_items.id');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSalePayments($sale_id) {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCustomerByID($id) {
        $q = $this->db->get_where('customers', array('id' => $id), 1);
          if( $q->num_rows() > 0 ) {
            return $q->row();
          }
          return FALSE;
    }
    public function getCustomerBySaleID($id) {
        $q = $this->db->get_where('sales_returns', array('id' => $id), 1);
          if( $q->num_rows() > 0 ) {
            return $this->getCustomerByID($q->row()->customer_id);
          }
          return FALSE;
    }

}
