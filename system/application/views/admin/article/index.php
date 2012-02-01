<a class="button" href="/admin/article/create">Создать статью </a><br/>
<table width="100%" border="1px solid " cellspacing="0" cellpadding="0">
    <thead>
    <th width="20px">ID</th>
    <th>Название</th>
    <th width="200px">Ссылка</th>
    <th width="200px">Действие</th>
</thead>
<tbody>
    <?php foreach ($object as $val):?>
    <tr>
        <td><?=$val['id']?>
        </td>
        <td><?=$val['title']?>
        </td>
        <td><?php if($val['slug']=='startpage')$val['slug']=''?>
            <a target="_blank" href="/<?=$val['slug']?>">/<?=$val['slug']?></a>
        </td>
        <td>
            <a href="/admin/article/edit/<?=$val['id']?>/">Редактировать</a>
        </td>
    </tr>
    <?endforeach;?>
</tbody>
<tfoot>
<th width="20px">ID</th>
<th>Название</th>
<th width="100px">Ссылка</th>
<th width="200px">Действие</th>
</tfoot>
</table>