<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchases_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function getProductByID($id) {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }
 public function addProduct($data, $store_quantities, $items = array()) {
        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();
            if(! empty($store_quantities)) {
                foreach ($store_quantities as $store_quantity) {
                    $store_quantity['product_id'] = $product_id;
                    $this->db->insert('product_store_qty', $store_quantity);
                }
            }
            if(! empty($items)) {
                foreach ($items as $item) {
                    $item['product_id'] = $product_id;
                    $this->db->insert('combo_items', $item);
                }
            }
            return true;
        }
        return false;
    }

    public function getPurchaseByID($id) {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getReturnPurchaseByID($id) {
        $q = $this->db->get_where('purchases_return', array('id' => $id), 1);
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function UpdatePurchaseItems($data) {
				foreach ($data as $item) {
					$id = $item['id'];
					$id = $item['id'];
					unset($item['id']);
					$this->db->set('returned', 'returned + '.$item['returned'], FALSE);
					$this->db->where(array('id'=>$id));
					$q = $this->db->update('purchase_items');
					$x = $this->db->error();
					$x2 = $this->db->queries;
					$x1 = $this->db->last_query();
				}
        if($q) {
						$this->db->where_in('id',array_column($data,'id'));
            return $this->db->get('purchase_items')->result_array();
        }
        return FALSE;
    }

    public function getAllPurchaseItems($purchase_id) {
        $this->db->select('purchase_items.*, products.code as product_code, products.name as product_name,products.cost as product_cost,
		products.price as product_price,categories.name as category')
            ->join('products', 'products.id=purchase_items.product_id', 'left')
			  ->join('categories', 'categories.id=products.category_id', 'left')
            ->group_by('purchase_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPurchaseReturnItems($purchase_id) {
        $this->db->select('purchase_items_return.*, products.code as product_code, products.name as product_name,products.cost as product_cost,
		products.price as product_price,categories.name as category')
            ->join('products', 'products.id=purchase_items_return.product_id', 'left')
			  ->join('categories', 'categories.id=products.category_id', 'left')
            ->group_by('purchase_items_return.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_items_return', array('purchase_return_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function addPurchase($data, $items) {
        if ($this->db->insert('purchases', $data)) {
            $purchase_id = $this->db->insert_id();
            foreach ($items as $item) {
                $item['purchase_id'] = $purchase_id;
                if($this->db->insert('purchase_items', $item)) {
                    $this->setStoreQuantity($item['product_id'], $data['store_id'], $item['quantity']);
								}
								$x = $this->db->error();
            }
            return true;
        }
        return false;
    }

    public function returnPurchase($data, $items) {
        if ($this->db->insert('purchases_return', $data)) {
            $purchase_id = $this->db->insert_id();
            foreach ($items as $item) {
                $item['purchase_return_id'] = $purchase_id;
                if($this->db->insert('purchase_items_return', $item)) {
                    $this->decStoreQuantity($item['product_id'], $data['store_id'], $item['quantity']);
								}
								$x = $this->db->error();
            }
            return $purchase_id;
        }
        return false;
    }

    public function setStoreQuantity($product_id, $store_id, $quantity) {
        if ($store_qty = $this->getStoreQuantity($product_id, $store_id)) {
            $this->db->update('product_store_qty', array('quantity' => ($store_qty->quantity+$quantity)), array('product_id' => $product_id, 'store_id' => $store_id));
        } else {
            $this->db->insert('product_store_qty', array('product_id' => $product_id, 'store_id' => $store_id, 'quantity' => $quantity));
        }
    }

    public function decStoreQuantity($product_id, $store_id, $quantity) {
        if ($store_qty = $this->getStoreQuantity($product_id, $store_id)) {
            $this->db->update('product_store_qty', array('quantity' => ($store_qty->quantity-$quantity)), array('product_id' => $product_id, 'store_id' => $store_id));
        } else {
            $this->db->insert('product_store_qty', array('product_id' => $product_id, 'store_id' => $store_id, 'quantity' => $quantity));
        }
    }

    public function getStoreQuantity($product_id, $store_id) {
        $q = $this->db->get_where('product_store_qty', array('product_id' => $product_id, 'store_id' => $store_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updatePurchase($id, $data = NULL, $items = array()) {
        $oitems = $this->getAllPurchaseItems($id);
        foreach ($oitems as $oitem) {
            $product = $this->site->getProductByID($oitem->product_id);
            $this->db->update('products', array('quantity' => ($product->quantity-$oitem->quantity)), array('id' => $product->id));
        }
        if ($this->db->update('purchases', $data, array('id' => $id)) && $this->db->delete('purchase_items', array('purchase_id' => $id))) {
            foreach ($items as $item) {
                $item['purchase_id'] = $id;
                if($this->db->insert('purchase_items', $item)) {
                    $product = $this->site->getProductByID($item['product_id']);
                    $this->db->update('products', array('quantity' => ($product->quantity+$item['quantity'])), array('id' => $product->id));
                }
            }
            return true;
        }
        return false;
    }

    public function updateReturnedPurchase($id, $data = NULL, $items = array()) {
        $oitems = $this->getAllPurchaseReturnItems($id);
        foreach ($oitems as $oitem) {
            $product = $this->site->getProductByID($oitem->product_id);
            $this->db->update('products', array('quantity' => ($product->quantity-$oitem->quantity)), array('id' => $product->id));
        }
        if ($this->db->update('purchases_return', $data, array('id' => $id)) && $this->db->delete('purchase_items_return', array('purchase_id' => $id))) {
            foreach ($items as $item) {
                $item['purchase_return_id'] = $id;
                if($this->db->insert('purchase_items_return', $item)) {
                    $product = $this->site->getProductByID($item['product_id']);
                    $this->db->update('products', array('quantity' => ($product->quantity+$item['quantity'])), array('id' => $product->id));
                }
            }
            return true;
        }
        return false;
    }

    public function deletePurchase($id) {
        $oitems = $this->getAllPurchaseItems($id);
        foreach ($oitems as $oitem) {
            $product = $this->site->getProductByID($oitem->product_id);
            $this->db->update('products', array('quantity' => ($product->quantity-$oitem->quantity)), array('id' => $product->id));
        }
        if ($this->db->delete('purchases', array('id' => $id)) && $this->db->delete('purchase_items', array('purchase_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteReturnedPurchase($id) {
        $oitems = $this->getAllPurchaseReturnItems($id);
        foreach ($oitems as $oitem) {
            $product = $this->site->getProductByID($oitem->product_id);
            $this->db->update('products', array('quantity' => ($product->quantity-$oitem->quantity)), array('id' => $product->id));
        }
        if ($this->db->delete('purchases_return', array('id' => $id)) && $this->db->delete('purchase_items_return', array('purchase_return_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getProductNames($term, $limit = 10) {
         //......new_pa
        $this->db->select('products.*, uom.symbol as symbol')
            ->join('uom', 'uom.id=products.uom_id', 'left');
            //......new_pa
        if ($this->db->dbdriver == 'sqlite3') {
            $this->db->where("type != 'combo' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  (name || ' (' || code || ')') LIKE '%" . $term . "%')");
        } else {
            $this->db->where("type != 'combo' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        }
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getExpenseByID($id) {
        $q = $this->db->get_where('expenses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addExpense($data = array()) {
        if ($this->db->insert('expenses', $data)) {
            return true;
        }
        return false;
    }

    public function updateExpense($id, $data = array()) {
        if ($this->db->update('expenses', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteExpense($id) {
        if ($this->db->delete('expenses', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
