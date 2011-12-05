$(function(){
    $('form .delete-entries').click(function(e){
        e.preventDefault();

        var _form = $(this).parents('form');
        var checkboxes = _form.find('input[type=checkbox].del:checked');
        var ids = [];
        
        checkboxes.each(function(){
            ids.push($(this).val());
        });
        
        if (!ids.length) {
            $.alert('No entries selected.');
            return false;
        }

        $.confirm('Are you sure you want to delete selected entries?', function(){
            $.post(_form.attr('action'), {ids:ids}, function(data){
                if (data.error) {
                    $.alert(data.msg);
                    return false;
                } else {
                    checkboxes.each(function(){
                        $(this).parents('tr').fadeOut('fast', function(){
                            $(this).remove();
                        });
                    });
                }
            }, 'json');
        }, function(){
            checkboxes.attr('checked', false);
        });
    });
    $('form .ids-toggle').change(function() {
        $(this).parents('form').find('input[type=checkbox].del').attr('checked', $(this).is(':checked'));
    });
});