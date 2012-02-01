<a class="button" href="/admin/shiping/create">Создать вид доставки </a><br/>
<table width="100%" border="1px solid " cellspacing="0" cellpadding="0">
    <thead>
    <th width="20px">ID</th>
    <th>Название</th>
    <th width="200px">Действие</th>
</thead>
<tbody>
    <?php foreach ($shipings as $shiping):?>
    <tr>
        <td><?=$shiping['id']?>
        </td>
        <td><?=$shiping['name']?>
        </td>
        <td>
            <a href="/admin/shiping/edit/<?=$shiping['id']?>/">Редактировать</a>
            <a  onclick="return confirm('Удалить вид доставки <?=$shiping['name']?>')"
                href="/admin/shiping/delete/<?=$shiping['id']?>/">Удалить</a>
        </td>
    </tr>
    <?endforeach;?>
</tbody>
<tfoot>
<th>ID</th>
<th>Название</th>
<th>Действие</th>
</tfoot>
</table>