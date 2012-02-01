<?php

    $config['app_root_path']      = dirname(dirname(dirname(dirname(__FILE__))));

    define('CLASSES_PATH',   $config['app_root_path'] . "/system/application/models/classes/");
    define('PAGES_PATH',     $config['app_root_path'] . "/system/application/models/pages/");
    define('ENTITIES_PATH',  $config['app_root_path'] . "/system/application/models/entities/");
    define('LIBRARIES_PATH', $config['app_root_path'] . "/system/application/libraries/");

    $config['cookie_lifetime'] = time() + 3600;
    
    // Userpic section
    $config['userpic_width'] = 100;
    $config['userpic_height'] = 100;
    $config['userpic_max_size'] = 2000;
    $config['userpic_allowed_types'] = "gif|jpg|png|jpeg";

    $config['guest_pages'] = array('auth',
                                   'main',
                                   'ajax',
                                   'test',
                                   'news',
                                   'downloads'
                                   );
    
    $config['global_date_format']       = 'Y/m/d';
    $config['global_datetime_format']   = 'Y/m/d H:i';
    $config['global_datepicker_format'] = 'yy-mm-dd';
    
    $config['noreply_email'] = 'noreply@futurewave.ru';

    //Authentications
    //Youtube. Developer Key for 'Futuwave' Product
    $config['youtube_key'] ='AI39si5AL0ABpjM1LwswAhoVwIa8tV22AzIxJA9sn4qbUdjUtxPO4ywdtlPzs_F55CY-o93aXO6dkIu9yb27vX9Cdb4nWoQ1lw';
    
?>