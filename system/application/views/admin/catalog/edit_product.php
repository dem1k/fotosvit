Редактирование Товара для раздела <strong>"<?=$catName['name']?>"</strong> в подразделе <strong>"<?=$subName['name']?>"</strong><br/>
<?=form_open_multipart('/admin/catalog/editProduct/'.$id)?>
<table >
    <tr>
        <td>
            Name
        </td>
        <td><?=form_error('name')?>
            <input type="text" name="name" value="<?=set_value('name',$product['name'])?>" />
        </td>
    </tr>
     <tr>
        <td>
            Показывать товар<br/>  при нажатии на раздел<br/>
        </td>
        <td><?=form_error('name')?>
           <input type="checkbox" name="top" value="1" <?=$product['top']==1?'checked':''?> />

        </td>
    </tr>
    <tr>
        <td>
            Description
        </td>
        <td>
            <?=form_error('description')?>
            <textarea name="description" cols="22" ><?=set_value('description',$product['description'])?></textarea>
        </td>
    </tr>
    <tr>
        <td>
            Image
        </td>
        <td><img height="135" src="/uploads/products/<?=$product['image']?>"  title="<?=$product['image']?>" alt="<?=$product['image']?>"> </img>
        <?= isset($error_upload) ? $error_upload : ""?>
            <br/>
            <input type="file" name="image" value="" />
        </td>
    </tr>
</table>
<input type="hidden" name="action" value="save">
<input type="submit" value="Save" />
</form>