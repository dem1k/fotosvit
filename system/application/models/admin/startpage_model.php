<?php

class Startpage_model extends Model {

    function getStartpage() {
        $this->db->select('')
                ->from('startpage');
        $q = $this->db->get();
        return $q->result();
    }

    function updateStartpage($data) {
        $data = array(
            'text'=>$data['text']
        );
        $q = $this->db->update('startpage', $data);
        return $q;
    }

}

?>
