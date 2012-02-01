<?php
/**
 * Abstract class for work with objects which has table
 * representation in database
 */
abstract class DB_Model extends Model {

	/**
	 * Table name which model represent
	 *
	 * @var string
	 */
	protected $_table_name;

	/**
	 * Field name of primary key in self::_table_name
	 *
	 * @var string
	 */
	protected $_primary_key;

	/**
	 * List of field names from self::_table_name
	 *
	 * @var array
	 */
	protected $_fields_list;

    public $count_rows = 0;

	/**
	 * Constuctor
	 *
	 * @return object
	 */
	function __construct () {

		parent::Model();
		$this->load->library('Form_validation', 'form_validation');
	}

	/**
	 * Process insert action
	 *
	 * @param array $data Assoc array of inserting data
	 * @return mixed False on fail
	 *               Created row ID on success
	 */
	function insert ($data, $validate=TRUE) {
		if ($validate && !$this->validate_on_insert($data)) {
			return FALSE;
		}
		$data_to_insert = array();
		foreach ($this->_fields_list as $field) {
			if ($validate && $value = $this->form_validation->set_value($field) ) {
				$data_to_insert[$field] = $value;
			}
			elseif (!$validate && isset($data[$field]))
			{
				$data_to_insert[$field] = $data[$field];
			}
		}
	    if ( $this->db->query($this->db->insert_string($this->_table_name, $data_to_insert)) ) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}

	/**
	 * Process update action
	 *
	 * @param array $data Assoc array of updating data
	 * @param array $where 'Where' condition as assoc array
	 * @return mixed False on fail
	 *               True if no one row was changed
	 *               Modified rows count on success
	 */
	function update ($data, $where, $limit=null) {
        if ( is_numeric($where) ) {
			$where = "`".$this->_primary_key."`='".$where."'";
		}

		if(!$this->validate_on_update($data)) {
			return FALSE;
		}

		$data_to_update = array();
		foreach ($this->_fields_list as $field) {
			if ( ($value = $this->form_validation->set_value($field, FALSE)) !== FALSE ) {
				$data_to_update[$field] = $value;
			}
		}

		if ( empty($data_to_update) ) {
		    return TRUE;
		}
		$limit = isset($limit) ? 'LIMIT '.$limit : '';

		$this->db->query($this->db->update_string($this->_table_name, $data_to_update, $where).$limit);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows == 0 ? TRUE : ( $affected_rows > 0 ? $affected_rows : FALSE );
	}

	/**
	 * Process delete action
	 *
	 * @param $where
	 * @return mixed False on fail
	 *               True if no one row was changed
	 *               Modified rows count on success
	 */
	function delete ($where, $limit=null) {

		if ( is_numeric($where) ) {
			$where = "`".$this->_primary_key."`='".$where."'";
		}

		$where = $this->build_where($where, 0);
		$limit = isset($limit) ? 'LIMIT '.$limit : '';

		$this->db->query("DELETE FROM `".$this->_table_name."` WHERE ".$where.$limit);
		$affected_rows = $this->db->affected_rows();
		return $affected_rows == 0 ? TRUE : ( $affected_rows > 0 ? $affected_rows : FALSE );
	}

	/**
	 * @todo are we really need this method? - please check method find_where()
	 * 			used in Messages controller. Notify me if you remove this method. akademi4eg
	 * @return CI_DB_Object
	 */
	/*function get() {
	 return $this->db->get($this->_table_name);
	 }*/

	/**
	 * @todo by Anton
	 * @param $where
	 * @param $default
	 * @return unknown_type
	 */
	function build_where ($where, $default = '1') {

		if (is_string($where)) {
			$where = $where;
		} elseif (is_array($where)) {
			$where_array = array();
			foreach ($where as $key=>$value) {
				if (is_array($value)) {
					$where_array[] = "$key IN (".implode(',', $this->db->escape($value)).")";
				} else {
					$where_array[] = "$key=".$this->db->escape($value)."";
				}
			}
			$where = implode(' AND ', $where_array);
		} else {
			$where = isset($default) ? $default : '0';
		}

		return $where;
	}

	/**
	 * Process select query with custom 'where' condition
	 *
	 * @param array $where Where statement for sql
	 * @return object Result of $this->db->query()
	 */
	function find_where ($where) {

		$sql = "SELECT * FROM `$this->_table_name`".
               "WHERE ".$this->build_where($where);

		return $this->db->query($sql);
	}

	/**
	 * Method
	 *
	 * @TODO What if I have only limit without offset?
	 *
	 * @param $row_count
	 * @param $offset
	 * @return unknown_type
	 */
	function get_slice ($row_count = null, $offset = null, $sets = array()) {
        $select = isset($sets['select']) ?
		(is_array($sets['select']) ? implode(', ', $sets['select']) : $sets['select'] ) :
    	       '*';
		if (isset($sets['where'])) {
			$where = $this->build_where($sets['where'] , '1');
		} else {
			$where = '1';
		}

		$order = isset($sets['order']) ? "\nORDER BY ".
		(is_array($sets['order']) ? implode(', ', $sets['order']) : $sets['order'] ) : '';

		$limit = intval($offset) ? "\nLIMIT ".intval($offset).", ".intval($row_count) : '' ;
        
		$sql =
		    "SELECT {$select}\n".
		    "FROM `{$this->_table_name}`\n".
            "WHERE {$where}".
            "{$order}".
            "{$limit};";
        
        return $this->db->query($sql);
        
	}

	/**
	 * Get row by primary key value
	 *
	 * @param $id
	 * @return unknown_type
	 */
	function get_by_id ($id) {
	    
		return $this->db->query("SELECT *\n".
                                "FROM `{$this->_table_name}`\n".
                                "WHERE `{$this->_primary_key}`=?\n".
                                "LIMIT 1;", $id)->row();
	}

	/**
	 * Sell for Form_validation::run() method.
	 * Allow apply it to custom data array.
	 *
	 * @param array $data (optional) Data to validate
	 *                               If empty use $_POST
	 * @return boolean
	 */
	function run_validation ($data = array()) {
		if (is_array($data) && count($data)) {
			$result = $this->form_validation->run($data);
			$this->form_validation->close_seance();
		} else {
			$result = $this->form_validation->run();
		}
		return $result;
	}

	/**
	 *
	 * @todo Possible will be better to realize these function here and
	 * add other abstract method like setValidationRules(). It may be useful with
	 * using Form_Validation lib.
	 *
	 */
	abstract protected function validate_on_insert($data);
	abstract protected function validate_on_update($data);

    /**
     * This function returns rows count for last previous executed query
     *
     */
    public function get_found_rows(){
        if(!$this->count_rows){
            return $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        }
        else{
            return $this->count_rows;
        }
    }
}
?>