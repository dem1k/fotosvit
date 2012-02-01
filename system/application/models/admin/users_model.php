<?php

class Users_model extends Model {
    
    function get_all() {
        $this->db->select('*')
                ->from('users');
        $query = $this->db->get();
        return $query->result();
    }
    
    function save($result) {
        $data = array(
            'login'=>$result['login'],
            'username'=>$result['username'],
            'email'=>$result['email'],
            'open_password'=>$result['password'],
            'type'=>'user',
            'status'=>'1',
            'region_id'=>$result['region_id'],
        );
        $query = $this->db->insert('users', $data);
        $id = $this->db->insert_id();
        return $id;
    }
    
    function update($result) {
        $data = array(
            'login'=>$result['login'],
            'username'=>$result['username'],
            'email'=>$result['email'],
            'open_password'=>$result['password'],
            'type'=>'user',
            'status'=>'1',
            'region_id'=>$result['region_id'],
            'type'=>$result['type']
        );
        $query = $this->db->update('users', $data, array('id' => $result['id']));
        return $query;
    }
    
    function updateRegion($region_id, $id) {
        $data = array(
            'region_id'=>$region_id
        );
        $query = $this->db->update('users', $data, array('id' => $id));
        return $query;
    }
    
    function getById($id) {
        $this->db->select('*')
                ->from('users')
                ->where('users.id', $id);

        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getPermissions($id) {
        $this->db->select('*')
                ->from('permission')
                ->where('users_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function SavePremmision($result) {
        $data = $result;
        $query = $this->db->insert('permission', $result);
        return $query;
    }
    
    function SaveOrderStatusPermission($result) {
        $data = $result;
        $query = $this->db->insert('orders_status_permission', $result);
        return $query;
    }
    
    function removeOldPermissions($id) {
        $query = $this->db->delete('permission', array('users_id' => $id));
        return $query;
    }
    function removeUser($id) {
        $query = $this->db->delete('users', array('id' => $id));
        return $query;
    }
    function removeOldOrderStatusPermission($id) {
        $query = $this->db->delete('orders_status_permission', array('user_id' => $id));
        return $query;
    }

    function getOrderStatusPermission($id) {
        $this->db->select('*')
                ->from('orders_status_permission')
                ->where('user_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }
}

?>
