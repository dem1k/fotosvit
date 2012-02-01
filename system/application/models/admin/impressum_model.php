<?php

class Impressum_model extends Model {

    function getImpressum() {
        $this->db->select('')
                ->from('impressum');
        $q = $this->db->get();
        return $q->result();
    }

    function updateImpressum($data) {
        $data = array(
            'text'=>$data['text']
        );
        $q = $this->db->update('impressum', $data);
        return $q;
    }

}

?>
