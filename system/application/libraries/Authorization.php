<?php

/**
 * Library Authorization
 *
 * @author NRGiser
 */
class Authorization {

    private $ci;
    private $user_cache = NULL;

    /**
     * Constructor
     *
     * @access  public
     */
    function Authorization() {
        $this->ci = get_instance();
        $this->ci->load->config('authorization');
        $this->ci->load->model('entities/user_model', 'user');
        $this->ci->lang->load('auth');

        if (!$this->is_logged_in() && !in_array($this->ci->router->class, $this->ci->config->item('guest_pages'))) {
            $this->redirect_to_login();
        }
    }

    /**
     * Method to store user's data into session
     *
     * @access  private
     * @param $user User's data
     */
    private function _set_session($user, $remember = NULL) {

        // Set session data array
        $user_data = array(
            'user_id'           => $user->id,
            'logged_in'         => TRUE,
            'user_type'              => $user->type,
            'user_region_id'              => $user->region_id,
        );



        $this->ci->session->set_userdata($user_data);

        if ($remember) {

            // Set remember session data array
            $remember = array(
                'id'           => $user->id,
                'user_type'         => $user->type,
                'user_region_id'              => $user->region_id,
                'logged_in'         => TRUE,
            );
            $remember = json_encode($remember);
            $cookie = array(
                'name'   => 'login_in_lidomed',
                'value'  => $remember,
                'expire' => '865000',
            );
            $this->ci->load->helper('cookie');
            set_cookie($cookie);
        }
    }

    /**
     * Method to remove user's data stored from the session
     *
     * @access  private
     * @param $user User's data
     */
    private function _unset_session() {

        // Set session data array
        $data = array(
            'user_id'           => NULL,
            'username'          => NULL,
            'logged_in'         => FALSE,
            'user_region_id'              =>NULL,
            'user_type'              => NULL
        );

        $this->ci->session->unset_userdata($data);
        $this->ci->load->helper('cookie');
        delete_cookie('login_in_lidomed');
    }

    /**
     * Method tries to login with assumed login data
     *
     * @access  public
     * @param $login Username or eMail
     * @param $password Password
     * @return bool Was logged in successfully?
     */
    function login($login, $password, $remember = FALSE) {

        $user = $this->ci->user->get_by_login($login)->row();

        if ($user) {
            //if(crypt($this->encode($password), $user->password) !== $user->password) {
            if ($password !== $user->open_password) {
                if ($this->ci->config->item('auth_max_login_count')) {
                    $this->_increment_login_counter($user->id, $this->ci->input->ip_address());
                }
                throw new Exception('auth_wrong_password');
                return FALSE;

            }

            if ($this->ci->config->item('auth_email_verification') && $user->status != '1') {
                throw new Exception('auth_user_not_activated');
                return FALSE;
            }

            $this->_set_session($user, $remember);

            return $user->id;
        }
        throw new Exception('auth_wrong_login');
        return FALSE;
    }

    /**
     * Method tries to remember user
     *
     * @access  public
     * @param $data
     * @return bool Was logged in successfully?
     */
    function remember() {
        if ($this->is_logged_in()) {
            return TRUE;
        }
        $data = unserialize($this->ci->session->remember_me());

        if (!array($data)) {
            return FALSE;
        }

        $user = $this->ci->user->get_by_login($data['login'])->row();
        if ($user && $user->id == $data['id']) {

            if ($this->ci->config->item('auth_email_verification') && $user->status != 'activated') {
                return FALSE;
            }

            $this->_set_session($user, TRUE);
            return $user->id;
        }
        return FALSE;
    }

    /**
     * Method redirects to login page
     * @param string $return Return URL
     * @access  public
     * @return void
     */
    function redirect_to_login($return = NULL) {
        if (is_null($return)) {
            $return = $this->ci->uri->uri_string;
        }
        redirect('/admin/auth/login/' . base64_encode($return));
        die();
    }

    /**
     * Method
     * @access  public
     * @return bool Is user logged in?
     */
    function is_logged_in() {
        // load helper
        if (!$this->ci->session->userdata('logged_in')) {
            $this->ci->load->helper('cookie');
            $mycookie = get_cookie('login_in_lidomed');
            if ($mycookie) {
                $mycookie = json_decode($mycookie);
                $i = 0;
                foreach ($mycookie as $key => $value) {
                    $user[$key] = $value;
                    $i++;
                    if ($i == 5) {
                        break;
                    }
                }
                if (isset($user)) {
                    // Set session data array
                    $user_data = array(
                        'user_id'           => $user['id'],
                        'logged_in'         => TRUE,
                        'user_type'              => $user['user_type'],
                        'user_region_id'              => $user['user_region_id'],
                    );
                    $this->ci->session->set_userdata($user_data);
                }
            }
        }
        return $this->ci->session->userdata('logged_in');
    }

    /**
     * Function: _encode
     *
     * @access private
     */
    public function encode($password) {

        $majorsalt = $this->ci->config->item('auth_crypt_salt');

        // if PHP5
        if (function_exists('str_split')) {
            $_pass = str_split($password);
        }
        // if PHP4
        else {
            $_pass = array();
            if (is_string($password)) {
                for ($i = 0; $i < strlen($password); $i++) {
                    array_push($_pass, $password[$i]);
                }
            }
        }

        // encrypts every single letter of the password
        foreach ($_pass as $_hashpass) {
            $majorsalt .= md5($_hashpass);
        }

        // encrypts the string combinations of every single encrypted letter
        // and finally returns the encrypted password
        return md5($majorsalt);
    }
    
    function get_user_id() {

        if (!$this->is_logged_in()) {
            return FALSE;
        } elseif(!$this->ci->session->userdata('user_id')) {
            $this->logout();
            redirect();
            die();
        }
        return $this->ci->session->userdata('user_id');
    }

    /**
     * Logging out funcion
     */
    function logout() {
        set_cookie('session_id');
        $this->_unset_session();
    }

    /**
     * @return object User
     */
    function get_user() {
        if (!$this->is_logged_in()) {
            $this->logout();
            redirect();
            die();
        }
        if (is_null($this->user_cache)) {
            $this->user_cache = $this->ci->user->get_by_id($this->get_user_id());
            if (empty($this->user_cache)) {
                $this->logout();
                redirect();
                die();
            }
        }
        return $this->user_cache;
    }

    /**
     *
     * @return string Username
     */
    function get_username() {
        if (!$this->is_logged_in()) {
            $this->logout();
            redirect();
            die();
        }

        if ($this->ci->session->userdata('username')) {
            return $this->ci->session->userdata('username');
        } else {
            $this->user_cache = $this->ci->user->get_by_id($this->get_user_id());
            if (empty($this->user_cache)) {
                $this->logout();
                redirect();
                die();
            }
            return $this->user_cache->username;
        }
    }
    
    function get_preference($pref_name) {

        $res = $this->ci->user->get_user_preference($this->get_user_id(), $pref_name);
        return $res->$pref_name;
    }
    
    function set_preference($pref_name, $value) {

        $this->ci->user->update_user_preferences($this->get_user_id(), array($pref_name => $value));
    }
    
    function _upload_userpic() {

        $config['upload_path'] = $this->ci->config->item('auth_userpic_path');
        $config['allowed_types'] = $this->ci->config->item('auth_userpic_allowed_types');
        $config['max_size'] = $this->ci->config->item('auth_userpic_max_size');
        $config['encrypt_name'] = TRUE;

        $this->ci->load->library('upload', $config);

        if ($this->ci->upload->do_upload('userpic')) {
            $data = array();

            $upload_data = $this->ci->upload->data();

            $data['file'] = basename($upload_data['full_path']);

            if ($upload_data['is_image']) {
                $this->ci->load->library('ImageSizer');
                $this->ci->imagesizer->load($upload_data['full_path']);
                $width = $this->ci->config->item('auth_userpic_width');
                $height = $this->ci->config->item('auth_userpic_height');
                if ($this->ci->imagesizer->getWidth() > $width) {
                    $this->ci->imagesizer->resizeToWidth($width);
                }
                if ($this->ci->imagesizer->getHeight() > $height) {
                    $this->ci->imagesizer->resizeToHeight($height);
                }
                $this->ci->imagesizer->save($upload_data['full_path']);
            }
            return $data;
        } else {
            return FALSE;
        }
    }
    
    function register($data) {
        // load capture library
        $CI = & get_instance();

        if ($this->ci->config->item('auth_captcha_registration')) {
            $CI->load->library('Captcha');
            $captcha = $CI->captcha->get_value();
        }

        if (!$this->ci->user->check_username_busy($data['username'])) {
            throw new Exception('auth_username_busy');
            return FALSE;
        }
        if (!$this->ci->user->check_email_busy($data['email'])) {
            throw new Exception('auth_email_busy');
            return FALSE;
        }

        if ($this->ci->config->item('auth_captcha_registration') && $data['captcha'] != $captcha) {
            throw new Exception('auth_captcha_wrong');
            return FALSE;
        }

        $data['password'] = crypt($this->encode($data['password']));
        if ($this->ci->config->item('auth_email_verification')) {
            $data['activation_key'] = $this->_gen_pass(32);
        } else {
            $data['status'] = 'activated';
        }

        $this->ci->load->model('entities/' . $data['type'] . '_model', $data['type']);
        $id = $this->ci->{$data['type']}->insert_additional_details($data);

        if ($id && $this->ci->config->item('auth_email_verification')) {
            $activation_link = site_url("auth/activate/{$id}/{$data['activation_key']}");

            // @TODO right back address and good format
            $this->_email($data['email'], $this->ci->config->item('auth_email_sender'), 'Email Verification', sprintf(lang('auth_verify_email'), $activation_link));
        }

        return $id;
    }

    /**
     * Returns a string with encrypted pass
     * @param string $data Password
     * @return string Crypted Password
     */
    function crypt_encode($pass) {
        return crypt($this->encode($pass));
    }
    
    function update($user_id, $data) {

        $user = $this->ci->user->get_by_id($user_id);
        if (empty($user)) {
            throw new Exception('user_user_not_exist');
            return FALSE;
        } else {
            if (isset($data['username']) && $data['username'] != $user->username) {
                if (!$this->ci->user->check_username_busy($data['username'])) {
                    throw new Exception('user_username_busy');
                    return FALSE;
                }
            }
            if (isset($data['email']) && $data['email'] != $user->email) {
                if (!$this->ci->user->check_email_busy($data['email'])) {
                    throw new Exception('user_email_busy');
                    return FALSE;
                }
                $data['activation_key'] = $this->_gen_pass(32);
                $data['status'] = 'none';
            }

            if (isset($data['password']) && $data['password']) {
                $data['password'] = crypt($this->encode($data['password']));
            }

            $rows = $this->ci->user->update($user_id, $data);
            if ($rows && $this->ci->config->item('auth_email_verification') && isset($data['activation_key'])) {
                $activation_link = site_url("auth/activate/{$user_id}/{$data['activation_key']}");

                // @TODO right back address and good format
                $this->_email($data['email'], $this->ci->config->item('auth_email_sender'), 'Email Verification', sprintf(lang('auth_verify_email'), $activation_link));
            }

            return $rows;
        }
    }
    
    function reset_password($login) {

        $user = $this->ci->user->get_by_login($login)->row();
        if ($user) {
            $password = $this->_gen_pass();
            $data = array('password' => crypt($this->encode($password)));
            $result = $this->ci->user->update($user->id, $data);
            if ($result) {
                // @TODO right back address and good format
                $this->_email($user->email, $this->ci->config->item('auth_email_sender'), 'New Password', sprintf(lang('auth_reset_password_email'), $password));
            }
            return $result;
        } else {
            return FALSE;
        }
    }

    /*
	 function unregister() {
	 // @TODO implement method
	 }
    */
    function _gen_pass($len = 8) {
        // No Zero (for user clarity);
        $pool = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
        }

        return $str;
    }
    
    function _email($to, $from, $subject, $message) {
        $this->ci->load->library('Email');
        $email = $this->ci->email;

        $email->from($from);
        $email->to($to);
        $email->subject($subject);
        $email->message($message);

        return $email->send();
    }
}

// END ValantAuth Class

/* End of file ValantAuth.php */
/* Location: ./system/application/libraries/ValantAuth.php */