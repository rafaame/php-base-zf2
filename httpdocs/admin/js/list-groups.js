$(function()
{

    $(".sortable").sortable
    ({

        placeholder: 'list-group-item list-group-item-placeholder',
        forcePlaceholderSize: true

    });

    $("#product-nestable").nestable
    ({

        maxDepth: 3,
        group: 1,

    });

});