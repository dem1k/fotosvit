<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * @package    RA_Session
 * @author        Darren Nolan - Rapid Hosting
 * @link        http://www.rapidhosting.com.au
 * @since        Version 2.3
 *
 * Native PHP Session library
 * including two main features
 * Persistant Login (Able to set the session length on a per session basis)
 * Remember Me (Set 1 variable to remember, normally a username)
 *
 */

/**
 * Renamed into Session to use instead standard library
 *
 * @author nrgiser
 *
 */

class Session {
    //DEFAULT VARIABLES FOR SESSIONS - ABLE TO BE MODIFIED IN APPLICATION CONFIG.PHP
    //
    var $sess_encryption        = TRUE;            //Encrypt session data (stored on server-side). Default = TRUE
    var $sess_match_ip          = TRUE;            //User session must match the IP used when session was created. Default = TRUE
    var $sess_match_useragent   = TRUE;            //User session must match the browser when session was created. Default = TRUE
    var $sess_name              = 'session_id';    //Session Name. Default = 'ra_session'
    var $sess_length            = 0;            //Time (in seconds) before session expires. Default = 0

    /**
     * Fix of flash data storing
     * @since 03 april 2009
     * @author nrgiser
     */
    var $flashdata_key         = 'flash';


    //Session time of 0 will terminate session on browser close
    //END OF CUSTOM VARIABLES

    var $CI;
    var $userdata                = array();        //Userdata Array

    function Session()
    {
        $this->CI =& get_instance();

        log_message('debug', "RA Session Class Initialized");

        if ($this->CI->config->item('sess_encryption') != FALSE) {
            $this->sess_encryption = $this->CI->config->item('sess_encryption');
        }
        if ($this->sess_encryption) {
            $this->CI->load->library('encrypt');
        }

        //Load up our values from the applications config.php file and update our settings if new settings are available.
        if ($this->CI->config->item('sess_encryption') != FALSE) {
            $this->sess_encryption = $this->CI->config->item('sess_encryption');
        }
        if ($this->CI->config->item('sess_match_ip') != FALSE) {
            $this->sess_match_ip = $this->CI->config->item('sess_match_ip');
        }
        if ($this->CI->config->item('sess_match_useragent') != FALSE) {
            $this->sess_match_useragent = $this->CI->config->item('sess_match_useragent');
        }
        if ($this->CI->config->item('sess_name') != FALSE) {
            $this->sess_name = $this->CI->config->item('sess_name');
        }
        if ($this->CI->config->item('sess_length') != FALSE AND is_numeric($this->CI->config->item('sess_length'))) {
            $this->sess_length = $this->CI->config->item('sess_length');
        }
        /**
         * Fix of flash data storing
         * @since 03 april 2009
         * @author nrgiser
         */
        if ($this->CI->config->item('sess_flashdata_key') != FALSE) {
            $this->flashdata_key = $this->CI->config->item('sess_flashdata_key');
        }

        //When our library is loaded, begin to create a new session or continue running old session.
        $this->sess_run();

        // Delete 'old' flashdata (from last request)
        $this->_flashdata_sweep();

        // Mark all new flashdata as old (data will be deleted before next request)
        $this->_flashdata_mark();
    }

    function sess_run()
    {
        //Firstly - lets check if we are meant to be using a persistant session
        //If we are, update the session length to reflect the "user specific length"
        if ($this->persistant_session() != FALSE) {
            $this->sess_length = $this->persistant_session();
        }

        //Set Session Name
        session_name($this->sess_name);

        //Set Session Lifetime (this is in seconds, unlike setting a normal cookie which requires a date/time)
        ini_set('session.cookie_lifetime', $this->sess_length);

        $current_session_id = session_id();
        if ($current_session_id != '') {
            //By calling Session_ID - with the current session_id - we force to renew the cookie (and the expiry time)
            session_id($current_session_id);
        }
        //If the session doesn't exist yet, there is no need to do this.

        //Start or Continue our Session
        session_start();

        //User IP Match - Run if required to do so
        if ($this->sess_match_ip == TRUE) {
            if (!isset($_SESSION['ip_address'])) {
                //If our session does not previously contain an IP address, this is the user's first visit.  Record their IP.
                //Do not check again now until next page load.
                $_SESSION['ip_address'] = $this->_ra_encode($this->CI->input->ip_address());
            } else {
                if ($this->_ra_decode($_SESSION['ip_address']) != $this->CI->input->ip_address()) {
                    //User IP Match failed - destory the session (and any stored data) immediantly.
                    $this->sess_destroy();
                    //Do not continue, and return "FALSE"
                    return FALSE;
                }
            }
        }

        //User UserAgent (browser) Match - Run if required to do so
        if ($this->sess_match_useragent == TRUE) {
            if (!isset($_SESSION['user_agent'])) {
                //If our session does not previously contain a UserAgent, this is the user's first visit.  Record their UserAgent.
                //Do not check again now until next page load.
                $_SESSION['user_agent'] = $this->_ra_encode(trim(substr($this->CI->input->user_agent(), 0, 50)));
            } else {
                if ($this->_ra_decode($_SESSION['user_agent']) != trim(substr($this->CI->input->user_agent(), 0, 50))) {
                    //User UserAgent (browser) Match failed - destory the session (and any stored data) immediantly.
                    $this->sess_destroy();
                    //Do not continue, and return "FALSE";
                    return FALSE;
                }
            }
        }

        //If session encryption is enabled, decode the session data and pass it to the array userdata() for compatability reasons.
        $this->userdata = $this->_ra_decode($_SESSION);

        //Return TRUE - successfully started/continued our session
        return TRUE;
    }

    function sess_id()
    {
        //Returns the Session ID in use
        return session_id();
    }

    function sess_destroy ()
    {
        //Destroy the Session Cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', (time()-42000), '/');
        }

        //Destroy the Session Array
        $_SESSION = array();

        //Destroy the userdata array - in case any further calls after the destory session is called, "stale" information is not called upon.
        $this->userdata = array();

        //Complete the session destory process by calling PHP's native session_destroy() function
        session_destroy();
    }

    function userdata($item)
    {
        //Returns an item of userdata
        return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
    }

    function all_userdata()
    {
        //Returns the array of userdata
        return ( ! isset($this->userdata)) ? FALSE : $this->userdata;
    }

    function set_userdata($newdata = array(), $newval = '')
    {
        //Set a userdata item
        if (is_string($newdata)) {
            $newdata = array($newdata => $newval);
        }
        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                $this->userdata[$key] = $val;
                $_SESSION[$key] = $this->_ra_encode($val);
            }
        }
    }

    function unset_userdata($newdata = array())
    {
        //Unset (delete) userdata item
        if (is_string($newdata)) {
            $newdata = array($newdata => '');
        }

        if (count($newdata) > 0) {
            foreach ($newdata as $key => $val) {
                unset($this->userdata[$key]);
                unset($_SESSION[$key]);
            }
        }
    }

    function remember_me ($remember_me = NULL)
    {
        /**************************************************
         // Remember Me Usage
         // remember_me ('my_username');    //sets remember_me value as 'my_username'
         // remember_me ($username);        //sets remember_me value to $username
         // remember_me ();            //returns remember_me value
         //
         // Default Action - RETURN CURRENT SETTING
         //
         // FALSE - Destory the Setting/Cookie
         // $string - Remember this string
         // NULL or '' - Return Current String for Remember Me
         //
         **************************************************/


        //Set the "Remember Me" cookie name
        $cookie_name = $this->sess_name . '_remember';

        if ($remember_me === FALSE) {
            //Destroy the "Remember Me" setting
                
            /**
             * @todo replace '/' by related path
             * Added by NRGiser
             */
            setcookie($cookie_name, '', time()-42000, '/');
            return FALSE;
        }
        if ($remember_me === '' OR $remember_me === NULL) {
            //Return the value of the "Remember Me" setting
            if (isset($_COOKIE[$cookie_name])) {
                return $this->_ra_decode($_COOKIE[$cookie_name]);
            }
            /**
             * Added by NRGiser to avoid creation of remember me cookie for all users
             */
            return FALSE;
        }

        //We must be setting the remember me setting
        setcookie($cookie_name, $this->_ra_encode($remember_me), (time() + (60*60*24*365)), '/');
        return TRUE;
    }

    function persistant_session ($time_to_stay_active = NULL)
    {
        /**************************************************
         // Persistant Session Usage (must be entered in as seconds)
         // persistant_session ('3600');        //sets this session length to 1 hour (60 * 60)
         // persistant_session ($session_length);    //sets this session legnth to $session_length
         // persistant_session ();                //returns this session length time
         //
         // Default Action - RETURN CURRENT SETTING
         //
         // FALSE - Destory the Setting/Cookie
         // $time - Keep Session Active for $time in seconds
         // NULL or '' - Return Current String for Remember Me
         // TRUE - Keep Session Active indefinantly (2 years)
         **************************************************/

        //Set the "Persistant Session" cookie name
        $cookie_name = $this->sess_name . '_persist';

        if ($time_to_stay_active === '' OR $time_to_stay_active === NULL) {
            //Return the length of the Persistant Session setting
            if (isset($_COOKIE[$cookie_name])) {
                //Persistant Session setting exists, return the value
                return ($this->_ra_decode($_COOKIE[$cookie_name]));
            } else {
                //Persistant Session setting does not already exist, return FALSE
                return FALSE;
            }
        }

        if ($time_to_stay_active === FALSE) {
            //Destory the Persistant Session setting (next page load will give the user the default session expiry)
            $this->sess_length = 0;
            setcookie($cookie_name, $this->_ra_encode('0'), time() - 42000, '/');

            //Return FALSE (the current setting)
            return FALSE;

        }

        if ($time_to_stay_active === TRUE) {
            //Remember this session for 2 years
            $this->sess_length = (60 * 60 * 24 * 365 * 2);
            setcookie($cookie_name, $this->_ra_encode($this->sess_length), (time() + $this->sess_length), '/');

            return $this->sess_length;
        }

        if (is_numeric($time_to_stay_active)) {
            //If we have a valid number, set the Persistant Session setting
            $this->sess_length = $time_to_stay_active;
            setcookie($cookie_name, $this->_ra_encode($time_to_stay_active), time() + $time_to_stay_active, '/');

            return $time_to_stay_active;
        }

        //Unable to determine correct setting - destory Persistant Session setting
        $this->sess_length = 0;
        setcookie($cookie_name, $this->_ra_encode('0'), time() - 42000, '/');

        return FALSE;
    }

    function regenerate_session ($session_id = '')
    {
        /**************************************************
         // Regenerate Session Usage (optional new session name)
         // persistant_session ('0001');        //regenerates the session with an ID of 0001
         // persistant_session ($new_session_id);    //regenerates the session with an ID of $new_session_id
         // persistant_session('NEW');            //regenerates the session with a new generated id
         // persistant_session ();                //regenerates the session with the same session id
         //
         // Default Action - regenerate the session with the same session id
         //
         // NULL or '' - regenerate the session with the same session id
         // $string - regnereate the session with a session id = $string
         // 'NEW' - regenerate the session with a newly generated id
         **************************************************/

        //Regenerate the Session, normally to set new expiry time for cookie
        //
        //You may specify "NEW" in the function to generate a new Session ID.
        //Leave it blank to regenerate the sesion with the SAME ID.

        //Copy their current session information and session ID
        $saved_session_info = $_SESSION;
        $saved_session_id = session_id();

        //Destory the current Session (and cookie) using our function
        $this->sess_destroy();

        //Ensure we have the default defaults for the session name and the current Session Length
        session_name($this->sess_name);
        ini_set('session.cookie_lifetime', $this->sess_length);

        //Check if we need to generate a new Session ID, define the Session ID or leave the current Session ID in tact.
        if ($session_id === '' OR $session_id === NULL) {
            //Session ID is blank, give them their old Session ID
            session_id($saved_session_id);
        } elseif (strtoupper($session_id == 'NEW')) {
            //Update the current session ID with a newly generate one
            session_regenerate_id();
        } else {
            //Session ID is defined by calling function, declare the Session ID
            session_id($session_id);
        }

        //Restart the Session
        session_start();

        //Load back our saved session data
        $_SESSION = $saved_session_info;

        //Unload our saved data
        unset ($saved_session_info);
        unset ($saved_session_id);
    }


    function _ra_encode ($value)
    {
        //_ra_encode first checks if encryption is enabled, and then encodes as required
        //_ra_encode supports encoding entire Arrays

        if (is_array($value)) {
            $temp_array = array();
            foreach ($value as $key => $val) {
                if ($this->sess_encryption) {
                    $temp_array[$key] = $this->_ra_encode($val);
                } else {
                    $temp_array[$key] = $val;
                }
            }
            return $temp_array;
        } else {
            if ($this->sess_encryption) {
                $value = $this->CI->encrypt->encode($value);
                return $value;
            }
            return $value;
        }
    }

    function _ra_decode ($value)
    {
        //_ra_decode first checks if encryption is enabled, and then decodes as required
        //_ra_decode supports decoding entire Arrays

        if (is_array($value)) {
            $temp_array = array();
            foreach ($value as $key => $val) {
                if ($this->sess_encryption) {
                    $temp_array[$key] = $this->_ra_decode($val);
                } else {
                    $temp_array[$key] = $val;
                }
            }
            return $temp_array;
        }
        if ($this->sess_encryption && is_string($value)) {
            $value = $this->CI->encrypt->decode($value);
            return $value;
        }
        return $value;
    }

    // ------------------------------------------------------------------------

    /**
     * Add or change flashdata, only available
     * until the next request
     *
     * @access    public
     * @param    mixed
     * @param    string
     * @return    void
     */
    function set_flashdata($newdata = array(), $newval = '')
    {
        if (is_string($newdata))
        {
            $newdata = array($newdata => $newval);
        }

        if (count($newdata) > 0)
        {
            foreach ($newdata as $key => $val)
            {
                $flashdata_key = $this->flashdata_key.':new:'.$key;
                $this->set_userdata($flashdata_key, $val);
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Keeps existing flashdata available to next request.
     *
     * @access    public
     * @param    string
     * @return    void
     */
    function keep_flashdata($key)
    {
        // 'old' flashdata gets removed.  Here we mark all
        // flashdata as 'new' to preserve it from _flashdata_sweep()
        // Note the function will return FALSE if the $key
        // provided cannot be found
        $old_flashdata_key = $this->flashdata_key.':old:'.$key;
        $value = $this->userdata($old_flashdata_key);

        $new_flashdata_key = $this->flashdata_key.':new:'.$key;
        $this->set_userdata($new_flashdata_key, $value);
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch a specific flashdata item from the session array
     *
     * @access    public
     * @param    string
     * @return    string
     */
    function flashdata($key)
    {
        $flashdata_key = $this->flashdata_key.':old:'.$key;
        return $this->userdata($flashdata_key);
    }

    // ------------------------------------------------------------------------

    /**
     * Identifies flashdata as 'old' for removal
     * when _flashdata_sweep() runs.
     *
     * @access    private
     * @return    void
     */
    function _flashdata_mark()
    {
        $userdata = $this->all_userdata();
        foreach ($userdata as $name => $value)
        {
            $parts = explode(':new:', $name);
            if (is_array($parts) && count($parts) === 2)
            {
                $new_name = $this->flashdata_key.':old:'.$parts[1];
                $this->set_userdata($new_name, $value);
                $this->unset_userdata($name);
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Removes all flashdata marked as 'old'
     *
     * @access    private
     * @return    void
     */

    function _flashdata_sweep()
    {
        $userdata = $this->all_userdata();
        foreach ($userdata as $key => $value)
        {
            if (strpos($key, ':old:'))
            {
                $this->unset_userdata($key);
            }
        }

    }
}
?>