<style type="text/css">
    #fileToUpload_wrap_list a {
        width: 100px;
        float: left;
        color:#6A6A6A;

    }
    #uri{
        width: 100px;
    }
    .uri_cancel{
        width: 100px;
    }
    #fileToUpload_wrap_list a {
        width: 100px;
        float: left;
        color:#6A6A6A;
    }
    .tdw{
        width: 230px;
    }
</style>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
         plugins : "images,autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,images,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)
        content_css : "css/example.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
            username : "obruchka",
            staffid : "00001"
        }
    });
</script>
<script type="text/javascript" src="/assets/js/plupload/js/plupload.full.js"></script>
<script type="text/javascript" src="/assets/admin/js/product.js"></script>
<?php echo form_open_multipart('/admin/product/create');?>
<?php echo form_error('name', '<div class="error">', '</div>'); ?>
<?php echo form_error('article', '<div class="error">', '</div>'); ?>
<?php echo form_error('price_uah', '<div class="error">', '</div>'); ?>
<?php echo form_error('price_usd', '<div class="error">', '</div>'); ?>
<?php echo form_error('description', '<div class="error">', '</div>'); ?>
<?php echo form_error('manufacturer', '<div class="error">', '</div>'); ?>
<?php echo form_error('category', '<div class="error">', '</div>'); ?>
<?php echo form_error('description', '<div class="error">', '</div>'); ?>
<table id="tablevent">
    <tr>
        <td>Артикул</td>
        <td>
            <input type="text" name="article"  value="<?= set_value('article')?>" />
        </td>
    </tr>
    <tr>
        <td>Название</td>
        <td>
            <input type="text" name="name"  value="<?= set_value('name')?>" />
            <?php echo form_error('name', '<div class="error">', '</div>'); ?>
        </td>
    </tr>
    <tr>
        <td>Производитель</td>
        <td>
            <select name="manufacturer">

                <?php foreach ($manufacturers as $manufacturer):?>
                <option value="<?=$manufacturer['id']?>"<?=set_select('manufacturer', $manufacturer['id'])?> > <?=$manufacturer['name']?></option>
                <?php endforeach;?>
                <option value=""<?=set_select('manufacturer', 0,true)?> >не выбрано</option>
            </select>
        </td>
    </tr>
     <tr>
        <td>Категория</td>
        <td>
            <select name="category">

                <?php foreach ($categories as $category):?>
                <option value="<?=$category['id']?>"<?=set_select('category', $category['id'])?> > <?=$category['name']?></option>
                <?php endforeach;?>
                <option value=""<?=set_select('category', 0,true)?> >не выбрано</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Цена ГРН:</td>
        <td>
           <input type="text" name="price_uah" value="<?= set_value('price_uah'); ?>" size="20"/>
        </td>
    </tr>
    <tr>
        <td>Цена $:</td>
        <td>
           <input type="text" name="price_usd" value="<?= set_value('price_usd'); ?>" size="20"/>
        </td>
    </tr>
    <tr>
        <td>Описание:</td>
        <td>
            <textarea  class="ckeditor" rows="10" cols="45" name="description"><?= set_value('description'); ?></textarea>
            <?php echo form_error('description', '<div class="error">', '</div>'); ?>
        </td>
    </tr>
    <tr>
        <td>Сортировка:</td>
        <td>
            <input type="text" name="sort" value="<?= set_value('sort'); ?>" size="40"/>
            <?php echo form_error('sort', '<div class="error">', '</div>'); ?>
        </td>
    </tr>
    <!--tr>
        <td>Фото1:</td>
        <td>
            <input type="text" name="photo1" value="<?= set_value('photo1'); ?>" size="40"/>
        </td>
    </tr>
    <tr>
        <td>Фото2:</td>
        <td>
            <input type="text" name="photo2" value="<?= set_value('photo2'); ?>" size="40"/>
            
        </td>
    </tr-->
    <tr>
        <td>
            Большая картинка
        </td>
        <td>
            <div id="container_big">
                <img height="200px" src="/uploads/products/<?php echo set_value('image_big'); ?>"/>
                <input type="hidden" name="image_big" value="<?php echo set_value('image_big'); ?>" />
                <div id="filelist_big">No runtime found.</div>
                <br />
                <a id="pickfiles_big" href="#">[Выбрать]</a>
                <div id=""></div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Маленькая картинка
        </td>
        <td>
            <div id="container_small">
                <img height="100px"src="/uploads/products/<?php echo set_value('image_small'); ?>"/>
                <input type="hidden" name="image_small" value="<?php echo set_value('image_small'); ?>" />
                <div id="filelist_small">No runtime found.</div>
                <br />
                <a id="pickfiles_small" href="#">[Выбрать]</a>
                <div id=""></div>
            </div>
        </td>
    </tr>
    <tr>
        <td><input type="submit" value="Сохранить" name="submit" class="button"></td>
        <td><input type="hidden" name="action" value="save" /></td>
    </tr>

</table>

<?=form_close()?>
