<?=form_open_multipart('/admin/personal')?>

<table >
    <tr>
        <td>
            Tel:
        </td>
        <td><?=form_error('name')?>
            <input type="text" name="tel" value="<?=set_value('tel',$personal->tel)?>" />
        </td>
    </tr>
    <tr>
        <td>
           Mobil:
        </td>
        <td>
            <?=form_error('mobil')?>
            <input name="mobil" value="<?=set_value('mobil',$personal->mobil)?>" />
    </tr>
    <tr>
        <td>
           Email:
        </td>
        <td>
            <?=form_error('email')?>
            <input name="email" value="<?=set_value('email',$personal->email)?>" />
        </td>
    </tr>
    <tr>
        <td>
           Site:
        </td>
        <td>
            <?=form_error('site')?>
            <input name="site" value="<?=set_value('site',$personal->site)?>" />
        </td>
    </tr>
    <tr>
        <td>
            Image
        </td>
        <td><img height="135" src="/uploads/products/<?=$personal->image?>"  title="<?=$personal->image?>" alt="<?=$personal->image?>"> </img>
        <?= isset($error_upload) ? $error_upload : ""?>
            <br/>
            <input type="file" name="image" value="" />
        </td>
    </tr>
</table>
<input type="hidden" name="action" value="save">
<input type="submit" value="Save" />
</form>