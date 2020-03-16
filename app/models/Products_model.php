<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function getAllProducts() {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function products_count($category_id = NULL) {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
            return $this->db->count_all_results("products");
        } else {
            return $this->db->count_all("products");
        }
    }

    public function fetch_products($limit, $start = null, $category_id = NULL) {
        $this->db->select('id,name, code, barcode_symbology, price,quantity')
        ->limit($limit, $start)->order_by("code", "asc");
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        $q = $this->db->get("products");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function fetch_products_2($limit, $start,$id='',$where=array()) {
			if(!$id){
				$this->db->select('id,name, code, barcode_symbology, price,quantity')->limit($limit, $start)->order_by("code", "asc");
				$name = '';
				if(isset($where['name'])){
					$name = $where['name'];
					unset($where['name']);
					$this->db->like('name',$name,'both');
				}
				$this->db->where($where);
        $q = $this->db->get("products");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
			}
		}
			else{
        $this->db->where('id',$id);
        $q = $this->db->get("products");

        if ($q->num_rows() > 0) {
								return $q->row();
            }
			}
        return false;
    }

    public function fetch_products_total($where=array()) {
				$this->db->select('id');
				$name = '';
				if(isset($where['name'])){
					$name = $where['name'];
					unset($where['name']);
					$this->db->like('name',$name,'both');
				}
				$this->db->where($where);
        $q = $this->db->get("products");

        if ($q->num_rows() > 0) {
            return $q->num_rows();
        }
        return 0;
    }
	public function fetch_productsById($limit, $start = null, $productid = NULL) {
        $this->db->select('name, code, barcode_symbology, price');
     
        if ($productid) {
            $this->db->where('id', $productid);
        }
        $q = $this->db->get("products");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getProductByCode($code) {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

		public function get_latest_local_barcode($id='')
		{
			$sql = 'SELECT local_barcode FROM tec_products ORDER BY local_barcode DESC';
			$query = $this->db->query($sql,array($id));
			if(($query) && ($query->num_rows()))
				{
					$x = (int)$query->row()->local_barcode;
					return ($x+1);
				}
			return '1';
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

    public function add_products($data = array()) {
        if ($this->db->insert_batch('products', $data)) {
            return true;
        }
        return false;
    }

    public function getSupplierByid($id='') {
			$q = $this->db->get_where('suppliers', array('id'=>$id));
        if (($q) && ($q->num_rows())) {
            return true;
        }
        return false;
    }

    public function updatePrice($data = array()) {
        if ($this->db->update_batch('products', $data, 'code')) {
            return true;
        }
        return false;
    }

    public function updateProduct($id, $data = array(), $store_quantities = array(), $items = array(), $photo = NULL) {
        if ($photo) { $data['image'] = $photo; }
        if ($this->db->update('products', $data, array('id' => $id))) {
            if(! empty($store_quantities)) {
                foreach ($store_quantities as $store_quantity) {
                    $store_quantity['product_id'] = $id;
                    $this->setStoreQuantity($store_quantity);
                }
            }
            if(! empty($items)) {
                $this->db->delete('combo_items', array('product_id' => $id));
                foreach ($items as $item) {
                    $item['product_id'] = $id;
                    $this->db->insert('combo_items', $item);
                }
            }
            return true;
        }
        return false;
    }

    public function setStoreQuantity($data) {
        if ($this->getStoreQuantity($data['product_id'], $data['store_id'])) {
            $this->db->update('product_store_qty', array('quantity' => $data['quantity'], 'price' => $data['price']), array('product_id' => $data['product_id'], 'store_id' => $data['store_id']));
        } else {
            $this->db->insert('product_store_qty', $data);
        }
    }

    public function getStoreQuantity($product_id, $store_id = NULL) {
        if(!$store_id) {
            $store_id = $this->session->userdata('store_id') ? $this->session->userdata('store_id') : 1;
        }
        $q = $this->db->get_where('product_store_qty', array('product_id' => $product_id, 'store_id' => $store_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
				}
				$x = $this->db->error();
        return FALSE;
    }

    public function getStoresQuantity($product_id) {
        $q = $this->db->get_where('product_store_qty', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getComboItemsByPID($product_id) {
        $this->db->select($this->db->dbprefix('products') . '.id as id, ' . $this->db->dbprefix('products') . '.code as code, ' . $this->db->dbprefix('combo_items') . '.quantity as qty, ' . $this->db->dbprefix('products') . '.name as name')
        ->join('products', 'products.code=combo_items.item_code', 'left')
        ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function deleteProduct($id) {
        if ($this->db->delete('products', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getProductNames($term, $limit = 10) {
        if ($this->db->dbdriver == 'sqlite3') {
            $this->db->where("type != 'combo' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  (name || ' (' || code || ')') LIKE '%" . $term . "%')");
        } else {
            $this->db->where("type != 'combo' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        }
        $this->db->limit($limit);
        $q = $this->db->get('products');
        // ->join('uom', 'uom.id=products.uom_id', 'left');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

		
		public function get_gst_id($item)
		{
			$q = $this->db->get_where('tec_gst_groups',array('name'=>$item));
			if(($q)&&($q->num_rows())){
				return $q->row()->id;
			}else{
				$res = $this->db->insert('tec_gst_groups',array('name'=>$item));
				if($res){
					return $this->db->insert_id();
				}
				return '0';
			}
		}

		public function get_uom_id($item)
		{
			$q = $this->db->get_where('tec_uom',array('base_name'=>$item));
			if(($q)&&($q->num_rows())){
				return $q->row()->id;
			}else{
				$res = $this->db->insert('tec_uom',array('base_name'=>$item,'symbol'=>$item));
				if($res){
					return $this->db->insert_id();
				}
				return '0';
			}
		}

		public function get_brand_id($item)
		{
			$q = $this->db->get_where('tec_brand',array('name'=>$item));
			if(($q)&&($q->num_rows())){
				return $q->row()->id;
			}else{
				$res = $this->db->insert('tec_brand',array('name'=>$item));
				if($res){
					return $this->db->insert_id();
				}
				return '0';
			}
		}

		public function get_subcategory_id($item)
		{
			$q = $this->db->get_where('tec_sub_categories',array('name'=>$item));
			if(($q)&&($q->num_rows())){
				return $q->row()->id;
			}else{
				$res = $this->db->insert('tec_sub_categories',array('name'=>$item));
				if($res){
					return $this->db->insert_id();
				}
				return '0';
			}
		}
		public function get_next_cat_code($id='')
		{
			$sql = 'SELECT code FROM tec_categories ORDER BY code DESC';
			$query = $this->db->query($sql,array($id));
			if(($query) && ($query->num_rows()))
				{
					$x = (int)$query->row()->code;
					return ($x+1);
				}
			return '1001';
		}
		public function get_category_id($item)
		{
			$q = $this->db->get_where('tec_categories',array('name'=>$item));
			if(($q)&&($q->num_rows())){
				return $q->row()->id;
			}else{
				$res = $this->db->insert('tec_categories',array('name'=>$item,'code'=>$this->get_next_cat_code()));
				if($res){
					return $this->db->insert_id();
				}
				return '0';
			}
		}

		public function get_supplier_id($item1,$item2)
		{
			// return $item1;
			$q = $this->db->get_where('tec_suppliers',array('name'=>$item1));
			if(($q)&&($q->num_rows())){
				return $q->row()->id;
			}else{
				$res = $this->db->insert('tec_suppliers',array('name'=>$item1,'cf1'=>$item2));
				if($res){
					return $this->db->insert_id();
				}
				return '0';
			}
		}


}
