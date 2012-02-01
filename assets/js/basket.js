

    var numb = "0123456789";
var alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
function chk(t,v){
    var w = "";
    for (i=0; i < t.val().length; i++) {
        x = t.val().charAt(i);


        if (v.indexOf(x,0) != -1)
            w += x;
    }
    t.val(w);

}
$(document).ready(function(){
    function ptbkt(product_id,prod_qty,rewrite){
        //        product_id=$(obj).parent().children('p.product_id').text();
        $.post("/basket/putProduct/", {
            to_basket: "ok",
            prod_id: product_id,
            prod_qty: prod_qty,
            rewrite: rewrite
        },
        function(data) {
            $('#bask_qty').html(data.basket_total);
            $('#bask_total_price').html(data.basket_total_price+' грн.');
            $('#table_total_price').html(data.basket_total_price+' грн.');
        }, "json");
    }
    $('div.add_basket a').click(function(){
        var pid=$(this).parent().children('p.product_id').text();
        ptbkt(pid,1,0);
        return false;
    }
    );
    $('tr > td > input').keyup(function(){
        parent=$(this).parents('tr');
        chk($(this),numb);
        var pid=$(parent).find('p.product_id').text();
        var count=$(this).val();
        pr_uah=$(parent).find('span.price_uah').text();
        pr_usd=$(parent).find('span.price_usd').text();
        $(parent).find('span.sum_price_uah').text(pr_uah*count);
        $(parent).find('span.sum_price_usd').text(pr_usd*count);
        ptbkt(pid,count,1);
    })
    $('a .remove_btn').click(function(){
        var pid=$(this).attr('prod_id');
        $.post("/basket/removeProduct/", {
            to_basket: "ok",
            prod_id: pid,
        },
        function(data) {
            $('#bask_qty').html(data.basket_total)
            if(data.basket_total == 0){
                $('a#fwrd').remove();
            }
            $('#bask_total_price').html(data.basket_total_price+' грн.')
        }, "json");
        $(this).parents('tr').remove();
        return false;
    })
})