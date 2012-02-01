<?php
class Personal_model extends Model {

    function getPersonal() {
        $this->db->select('')
                ->from('personal');
        $q = $this->db->get();
        return $q->result();
    }

    function updatePesonal($data) {
        $q = $this->db->update('personal', $data);
        return $q;
    }

    function saveMessage ($data) {
        $q = $this->db->insert('messages', $data);
        return $q;
    }
}


?>
