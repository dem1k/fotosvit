<center>Раздел <strong>"<?=$catName['name']?>"</strong></center>
<br/>
<a class="button" href="/admin/catalog/createSubsection/<?=$catId?>">Создать Подраздел</a>
<br/>
<table class="extended">
    <thead>
    <th>ID</th>
    <th>Название</th>
    <th>Действие</th>
</thead>
<tbody>
    <?php foreach($subsections as $subsection):?>
    <tr>
        <td><?=$subsection['id']?></td>
        <td><?=$subsection['name']?></td>
        <td><a href="/admin/catalog/showProducts/<?=$subsection['id']?>">Просмотр </a><a  href="/admin/catalog/editSubsection/<?=$subsection['id']?>">Редактирование</a><a >Удаление</a></td>
    </tr>
    <?php endforeach;?>
</tbody>
<tfoot>
<th>ID</th>
<th>Название</th>
<th>Действие</th>
</tfoot>
</table>