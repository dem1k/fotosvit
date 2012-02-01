<a class="button" href="/admin/news/create">Создать новость </a><br/>
<table width="100%" border="1px solid " cellspacing="0" cellpadding="0">
    <thead>
    <th width="20px">ID</th>
    <th>Название</th>
    <th width="200px">Дата </th>
    <th width="200px">Действие</th>
</thead>
<tbody>
    <?php foreach ($object as $val):?>
    <tr>
        <td><?=$val['id']?>
        </td>
        <td><?=$val['name']?>
        </td>
        <td>
            <?=$val['date']?>
        </td>
        <td>
            <a href="/admin/news/edit/<?=$val['id']?>/">Редактировать</a>
            <a  onclick="return confirm('Удалить новость <?=$val['name']?>?')"
                href="/admin/news/delete/<?=$val['id']?>/">Удалить</a>
        </td>
    </tr>
    <?endforeach;?>
</tbody>
<tfoot>
<th width="20px">ID</th>
<th>Название</th>
<th width="200px">Дата</th>
<th width="200px">Действие</th>
</tfoot>
</table>