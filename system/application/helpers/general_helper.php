<?php

if (!function_exists('db_table')) {
    function db_table($table) {
        $ci = & get_instance();
        return $ci->config->item('tbl_prefix').$ci->config->item('tbl_'.$table);
    }
}

if(!function_exists('mysqldate')){
    function mysqldate($timestamp = false){
        return date('Y-m-d H:i', $timestamp ? $timestamp : time());
    }
}

if(!function_exists('get_date')){
    function get_date($mysqldate){
        return date('Y.m.d', strtotime($mysqldate));
    }
}

if(!function_exists('set_base_url_for_admin')){
    function set_base_url_for_admin(&$config){
        $config['base_url'] = str_replace("http://", "http://admin.", $config['base_url']);
    }
}

if(!function_exists('get_url')){
    function get_url(){
        $ci = & get_instance();
        return $ci->config->item('base_url');
    }
}

if(!function_exists('get_controller')){
    function get_controller(){
        $ci = & get_instance();
        if($controller = $ci->uri->segment(2)) {
            return $controller;
        }
        return false;
    }
}

// placeholders

@define("PLACEHOLDER_ERROR_PREFIX", "ERROR: ");

function sql_compile_placeholder($tmpl) {

  $compiled  = array();
  $p         = 0;
  $i         = 0;
  $has_named = false;

  while (false !== ($start = $p = strpos($tmpl, "?", $p))) {

    switch ($c = substr($tmpl, ++$p, 1)) {
      case '&': 
      case '%': 
      case '@': 
      case '#':
        $type = $c; 
        ++$p; 
        break;
      default:
        $type = ''; 
        break;
    }

    if (preg_match('/^((?:[^\s[:punct:]]|_)+)/', substr($tmpl, $p), $pock)) {

      $key = $pock[1];
      if ($type != '#') 
        $has_named = true;
      $p += strlen($key);

    } else {

      $key = $i;
      if ($type != '#') 
        $i++;

    }

    $compiled[] = array($key, $type, $start, $p - $start);
  }

  return array($compiled, $tmpl, $has_named);

}

function sql_placeholder_ex($tmpl, $args, &$errormsg) {

  if (is_array($tmpl)) {
    $compiled = $tmpl;
  } else {
    $compiled = sql_compile_placeholder($tmpl);
  }

  list ($compiled, $tmpl, $has_named) = $compiled;

  if ($has_named) 
    $args = @$args[0];

  $p   = 0;
  $out = '';
  $error = false;

  foreach ($compiled as $num=>$e) {

    list ($key, $type, $start, $length) = $e;

    $out .= substr($tmpl, $p, $start - $p);
    $p = $start + $length;

    $repl = '';
    $errmsg = '';

    do {
      
      if (!isset($args[$key]))
        $args[$key] = "";

      if ($type === '#') {
        $repl = @constant($key);
        if (NULL === $repl)
          $error = $errmsg = "UNKNOWN_CONSTANT_$key";
        break;
      }

      if (!isset($args[$key])) {
        $error = $errmsg = "UNKNOWN_PLACEHOLDER_$key";
        break;
      }

      $a = $args[$key];
      if ($type === '&') {
        global $db;
        if ($a === "")
          $repl = "null";
        else  
          $repl = "'".addslashes($a)."'";
        break;
      } else
      if ($type === '') {
        if (is_array($a)) {
          $error = $errmsg = "NOT_A_SCALAR_PLACEHOLDER_$key";
          break;
        }
        if ($a === "")
          $repl = "null";
        else {
          global $db;
          $repl = preg_match('/^[1-9]+[0-9]*$/', $a) ? $a : "'".addslashes($a)."'";
        }
        break;
      }

      if (!is_array($a)) {
        $error = $errmsg = "NOT_AN_ARRAY_PLACEHOLDER_$key";
        break;
      }

      if ($type === '@') {
        foreach ($a as $v) {
          global $db;
          $repl .= ($repl===''? "" : ",")."'".$v."'";
        }
      } else
      if ($type === '%') {
        $lerror = array();
        foreach ($a as $k=>$v) {
          if (!is_string($k)) {
            $lerror[$k] = "NOT_A_STRING_KEY_{$k}_FOR_PLACEHOLDER_$key";
          } else {
            $k = preg_replace('/[^a-zA-Z0-9_]/', '_', $k);
          }
          global $db;
          $repl .= ($repl===''? "" : ", ").$k."='".@addslashes($v)."'";
        }
        if (count($lerror)) {
          $repl = '';
          foreach ($a as $k=>$v) {
            if (isset($lerror[$k])) {
              $repl .= ($repl===''? "" : ", ").$lerror[$k];
            } else {
              $k = preg_replace('/[^a-zA-Z0-9_-]/', '_', $k);
              $repl .= ($repl===''? "" : ", ").$k."=?";
            }
          }
          $error = $errmsg = $repl;
        }
      }

    } while (false);

    if ($errmsg) 
      $compiled[$num]['error'] = $errmsg;

    if (!$error) 
      $out .= $repl;

  }
  $out .= substr($tmpl, $p);

  if ($error) {
    $out = '';
    $p   = 0;
    foreach ($compiled as $num=>$e) {
      list ($key, $type, $start, $length) = $e;
      $out .= substr($tmpl, $p, $start - $p);
      $p = $start + $length;
      if (isset($e['error'])) {
        $out .= $e['error'];
      } else {
        $out .= substr($tmpl, $start, $length);
      }
    }
    $out .= substr($tmpl, $p);
    $errormsg = $out;
    return false;
  } else {
    $errormsg = false;
    return $out;
  }

}

function sql_pholder() {

  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false) {
    $error = "Placeholder substitution error. Diagnostics: \"$error\"";
    if (function_exists("debug_backtrace")) {
      $bt = debug_backtrace();
      $error .= " in ".@$bt[0]['file']." on line ".@$bt[0]['line'];
    }
    trigger_error($error, E_USER_WARNING);
    return false;
  }
  return $result;

}

function placeholder() {

  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false)
    return PLACEHOLDER_ERROR_PREFIX.$error;
  else
    return $result;

}

function sql_placeholder() {
  $args = func_get_args();
  $tmpl = array_shift($args);
  $result = sql_placeholder_ex($tmpl, $args, $error);
  if ($result === false){
    return PLACEHOLDER_ERROR_PREFIX.$error;
  }
  else{
    return $result;
  }
}

/* End of file file_helper.php */
/* Location: ./system/helpers/file_helper.php */