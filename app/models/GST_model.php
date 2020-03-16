<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class GST_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function addGst($data) {
        if ($this->db->insert('gst_groups', $data)) {
            return true;
        }
        return false;
    }

    public function add_categories($data = array()) {
        if ($this->db->insert_batch('gst_groups', $data)) {
            return true;
        }
        return false;
    }

    public function updateGst($id, $data = NULL) {
        if ($this->db->update('gst_groups', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteDelete($id) {
        if ($this->db->delete('gst_groups', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
