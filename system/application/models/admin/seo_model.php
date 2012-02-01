<?php
class Seo_model extends Model {

    function getSeo() {
        return $this->db->get('seo')->row();
    }

    function updateSeo($data) {
        return $this->db->update('seo', $data);
    }

}


?>
