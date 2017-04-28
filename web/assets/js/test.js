
function deleteProduct(id) {
    $.ajax({
    url: '/products/' + id + '.json',
    method: 'DELETE', 
    }).done(function(data) {
        console.log(data.notice);
        $('tr#product-'+id).remove();
        $('.panel.panel-success')
            .show({
                duration: 2000,
                complete: function() {
                    $(this).hide();
                }
            });
        $('.panel.panel-success p')
            .text(data.notice);
    });
}