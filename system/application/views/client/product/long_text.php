<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta  http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
                <script type="text/javascript" src="/assets/js/cufon-yui.js"></script>
                <script type="text/javascript" src="/assets/js/MagistralTT_700.font.js"></script>
                <script type="text/javascript">Cufon.replace("h1");</script>
                <script type="text/javascript" src="/assets/js/jquery-1.4.2.js"></script>
                <script type="text/javascript" src="/assets/js/jquery.lightbox-0.5.js"></script>
                <script type="text/javascript" src="/assets/js/swfobject.js"></script>
                <link rel="stylesheet" type="text/css" href="/assets/css/jquery.lightbox-0.5.css" media="screen" />
                <link rel="stylesheet" href="/assets/css/colorbox.css" type="text/css"/>
                <script type="text/javascript" src="/assets/js/jquery.colorbox.js" ></script>
                <title><?php echo $product[0]->name; ?> || Детальное описание</title>
                </head>
                <body>
                    <div id="wrapper" style="color: black; background-color: white; padding-left: 5px; padding-right: 5px; background-image: none; width: 970px;">
                        <?php echo $product[0]->long_text; ?>
                    </div>
                    <div style=" clear:both"></div>
                    <div id="foot" style="width: 981px;">
                        <div id="fh">
                            <h1>
                                <?php echo anchor('pages/about', 'О КОМПАНИИ'); ?>
                                <?php echo anchor('pages/water', 'О ВОДЕ'); ?>
                                <?php echo anchor('pages/aqua', 'АКВАСТРОНГ'); ?>
                                <?php echo anchor('pages/doc', 'ДОКУМЕНТЫ'); ?>
                                <?php echo anchor('/download', 'СКАЧАТЬ'); ?>
                                <?php echo anchor('/map', 'КАРТА'); ?>
                                <?php echo anchor('/reviewsclient', 'ОТЗЫВЫ'); ?>
                            </h1>
                        </div>
                        <div id="fl"><h1 style="font-size:12px; font-weight:normal; text-align:center; padding-top:30px;"><?php echo date('Y'); ?>  © Copyright Aquastrong. All rights reserved.</h1></div>
                    </div>
                </body>
                </html>

