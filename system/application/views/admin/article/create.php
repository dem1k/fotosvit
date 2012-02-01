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


<?=form_open_multipart('admin/article/create');?>
<?=form_error("title","<span class='error'>","</span><br/>")?>
<?=form_error("cut","<span class='error'>","</span><br/>")?>
<?=form_error("description","<span class='error'>","</span><br/>")?>

<h1>Создание статьи</h1>
<table cellspacing="0">
    <tr>
        <td>
            Название
        </td>
        <td>
            <input type="text" name="title" value="<?= set_value('title'); ?>"/>
        </td>
    </tr>
    <!--tr>
        <td>
            Категория
        </td>
        <td>
            <select name="category_art">

                < ?php foreach ($categoryes_art as $category):?>
                <option value="< ?=$category['id']?>"< ?=set_select('category_art', $category['id'])?> > < ?=$category['name']?></option>
                < ?php endforeach;?>
                <option value=""< ?=set_select('category_art', 0,true)?> >не выбрано</option>
            </select>
        </td>
    </tr-->
    <!--tr>
        <td>
            Кратко
        </td>
        <td>
            <textarea name="cut">< ?=set_value("cut")?></textarea>
        </td>
    </tr!-->
    <tr>
        <td>
            Статья
        </td>
        <td>
            <textarea name="content"><?=set_value("content")?></textarea>
        </td>
    </tr>
    <!--tr>
        <td>
            Картинка
        </td>
        <td>
            <input type="file" name="picture" value="< ?=set_value('picture')?>" />
        </td>
    </tr!-->
</table>
<input type="submit" value="Сохранить"/>
<input type="hidden" name="action" value="save"/>

<?php form_close();?>
