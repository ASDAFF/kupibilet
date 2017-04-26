$('document').ready(function () {
    $('.delete').click(function(){
        var id = $(this).data('id');
        $.post('/ajax/order.php',{
            action: 'remove',
            order_id: id
        },function(data){
            if(data.SUCCESS){
                $('#order-'+id).remove();
                $('#item-order-'+id).remove();
            }
        });
    });
});