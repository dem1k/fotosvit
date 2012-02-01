Создание Товара для раздела <strong>"<?=$catName['name']?>"</strong> в подразделе <strong>"<?=$subName['name']?>"</strong><br/>
<?=form_open_multipart('/admin/catalog/createProduct/'.$id)?>
<table >
    <tr>
        <td>
            Name
        </td>
        <td><?=form_error('name')?>
            <input type="text" name="name" value="<?=set_value('name')?>" />
           
        </td>
    </tr>
    <tr>
        <td>
            Показывать товар<br/>  при нажатии на раздел<br/>
        </td>
        <td><?=form_error('name')?>
           <input type="checkbox" name="top" value="1" />
           
        </td>
    </tr>

    <tr>
        <td>
            Description
        </td>
        <td>
            <?=form_error('description')?>
            <textarea name="description" cols="22" ><?=set_value('description')?></textarea>
        </td>
    </tr>
    <tr>
        <td>
            Image
        </td>
        <td><?= isset($error_upload) ? $error_upload : ""?>
            <input type="file" name="image" value="" />
        </td>
    </tr>
</table>
<input type="hidden" name="action" value="save">
<input type="submit" value="Save" />
</form>