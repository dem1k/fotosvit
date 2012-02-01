<?=form_open('/admin/catalog/editSubsection/'.$catId)?><br/>
Редактирование подраздела для раздела <strong>"<?=$catName['name']?>"</strong>
<table >
    <tr>
        <td>
            Имя подраздела
        </td>
        <td><?=form_error('name')?>
            <input type="text" name="name" value="<?=set_value('name',$catName['name'])?>" />
        </td>
    </tr>
</table>
<input type="hidden" name="action" value="save">
<input type="submit" value="Сохранить" />
</form>