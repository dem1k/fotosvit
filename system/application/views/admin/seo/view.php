<style>
    input,textarea{
        width: 100%;
}
textarea{
    height: 200px;
}
table,tr,td{
    vertical-align: top;
}
</style>
<h1>Настройки сайта</h1>
<?=form_open('/admin/seo')?>

<table >
    <tr>
        <td>
            Слоган
        </td>
        <td style="width: 600px"><?=form_error('slogan')?>
            <input type="text" name="slogan" value="<?=set_value('slogan',$seo->slogan)?>" />
        </td>
    </tr>
    <tr>
        <td>
            Заголовок
        </td>
        <td><?=form_error('title')?>
            <input type="text" name="title" value="<?=set_value('title',$seo->title)?>" />
        </td>
    </tr>
    <tr>
        <td>
            Keywords
        </td>
        <td>
            <?=form_error('keywords')?>
            <textarea  name="keywords"> <?=set_value('keywords',$seo->keywords)?> </textarea>
    </tr>
    <tr>
        <td>
            Description:
        </td>
        <td>
            <?=form_error('description')?>
            <textarea  name="description"><?=set_value('description',$seo->description)?> </textarea>
        </td>
    </tr>
    <tr>
        <td>
            Курс $
        </td>
        <td>
            <?=form_error('kurs')?>
            <input type="text" name="kurs" value="<?=set_value('kurs',$seo->kurs)?>" />
    </tr>
    <tr>
        <td>
            Название сайта
        </td>
        <td>
            <?=form_error('site_name')?>
            <input type="text" name="site_name" value=" <?=set_value('site_name',$seo->site_name)?> "/>
    </tr>
    <tr>
        <td>
            Адрес сайта
        </td>
        <td>
            <?=form_error('site_url')?>
            <input type="text" name="site_url" value=" <?=set_value('site_url',$seo->site_url)?> "/>
    </tr>
    <tr>
        <td>
            Номер телефона
        </td>
        <td>
            <?=form_error('phone')?>
            <input type="text" name="phone" value=" <?=set_value('phone',$seo->phone)?> "/>
    </tr>
     <tr>
        <td>
            Email
        </td>
        <td>
            <?=form_error('email')?>
            <input type="text" name="email" value=" <?=set_value('email',$seo->email)?> "/>
    </tr>
</table>
<input type="hidden" name="action" value="save">
<input style="width: 100px;"type="submit" value="Сохранить" />
<?php form_close()?>