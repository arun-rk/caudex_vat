<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uom_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function addUom($data) {
        if ($this->db->insert('uom', $data)) {
            return true;
        }
        return false;
    }

    public function updateUom($id, $data = NULL) {
        if ($this->db->update('tec_uom', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteDelete($id) {
        if ($this->db->delete('tec_uom', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
