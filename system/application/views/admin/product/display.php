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
<table id="tablevent">
    <tr>
        <td>Артикул</td>
        <td>
            <input readonly readonly type="text" name="article"  value="<?=$object->article?>" />
        </td>
    </tr>
    <tr>
        <td>Название</td>
        <td>
            <input readonly type="text" name="name"  value="<?=$object->name?>" />
        </td>
    </tr>
    <tr>
        <td>Производитель</td>
        <td>
            <input readonly type="text" name="manufacturer" value="<?=$object->manufacturer ?>" size="40"/>
        </td>
    </tr>
     <tr>
        <td>Категория</td>
        <td>
            <input readonly type="text" name="category" value="<?=$object->category ?>" size="40"/>
        </td>
    </tr>
    <tr>
        <td>Цена ГРН:</td>
        <td>
           <input readonly type="text" name="price_uah" value="<?=$object->price_uah ?>" size="20"/>
        </td>
    </tr>
    <tr>
        <td>Цена $:</td>
        <td>
           <input readonly type="text" name="price_usd" value="<?=$object->price_usd ?>" size="20"/>
        </td>
    </tr>
    <tr>
        <td>Описание:</td>
        <td>
            <textarea  readonly class="ckeditor" rows="10" cols="45" name="description"><?=$object->description?></textarea>
        </td>
    </tr>
    <tr>
        <td>Сортировка:</td>
        <td>
            <input readonly type="text" name="sort" value="<?$object->sort?>" size="40"/>
        </td>
    </tr>
     <tr>
        <td>
            Большая картинка
        </td>
        <td>
                <img height="200px" src="/uploads/products/<?=$object->image_big ?>"/>
        </td>
    </tr>
    <tr>
        <td>
            Маленькая картинка
        </td>
        <td>
                <img height="100px"src="/uploads/products/<?=$object->image_small ?>"/>
            </div>
        </td>
    </tr>
</table>

<?= form_close()?>
