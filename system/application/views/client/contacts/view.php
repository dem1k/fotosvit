<div id="article">
    <form action="/contacts/message" method="post">
        <div class="kontakt">
            <?=form_error('email')?>
            <table width="680" border="0">
                <tr>
                    <?=form_error('')?>
                    <td width="100" height="100" rowspan="3"><img  width="100" src="/uploads/products/<?=$personal->image?>"/></td>
                    <td align="right">
                        <div class="box">
                            Email:
                        </div>
                        <input name="email" type="text" size="31"  value="<?=set_value('email')?>"/><?=form_error('email')?>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <div class="box">
                            Name:
                        </div>
                        <input name="name" type="text" size="31" value="<?=set_value('name')?>"/><?=form_error('name')?>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <div class="box">
                            Betreff
                        </div>
                        <input name="betreff" type="text" size="31" value="<?=set_value('betreff')?>"/><?=form_error('betreff')?>
                    </td>
                </tr>
                <tr>
                    <td><div class="info">Tel:<?=$personal->tel?></div>
                        <div class="info">Mobil:<?=$personal->mobil?></div>
                        <div class="info">Email:<?=$personal->email?></div>
                        <div class="info">Site:<?=$personal->site?></div></td>
                    <td align="right"><div class="box">Komentar</div>
                        <textarea name="komentar" cols="35" rows="10" wrap="off"><?=set_value('komentar')?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td align="right">
                        <input type="hidden" name="action" value="send" />
                        <input name="Send" type="submit" value="Senden" />
                    </td>
                </tr>
            </table>


    </form>
</div>
</div>