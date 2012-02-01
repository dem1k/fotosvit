<a class="button" href="/admin/category/create">Создать категорию </a><br/>
<table width="100%" border="1px solid " cellspacing="0" cellpadding="0">
            <thead>
            <th width="20px">ID</th>
            <th>Название</th>
            <th width="200px">slug(путь)</th>
            <th width="200px">Действие</th>
            </thead>
            <tbody>
                <?php foreach ($categories as $category):?>
                <tr>
                    <td><?=$category['id']?>
                    </td>
                    <td><?=$category['name']?>
                    </td>
                     <td><?=$category['slug']?>
                    </td>
                    <td>
                        <a href="/admin/category/edit/<?=$category['id']?>/">Редактировать</a>
                        <a  onclick="return confirm('Удалить категорию <?=$category['name']?>')"
                            href="/admin/category/delete/<?=$category['id']?>/">Удалить</a>
                    </td>
                </tr>
                <?endforeach;?>
            </tbody>
            <tfoot> <th>ID</th>
            <th>Название</th>
            <th>Действие</th></tfoot>
        </table>