require(['jquery', 'jqueryui'], function ($, jui) {
    $(document).ready(function () {
        $('#id_profile_field_inn').autocomplete({
            source: '/dadata.php?action=inn',
            select: function (event, ui) {
                $('#id_profile_field_orgname').val(ui.item.label);
            }
        });
        $('#id_profile_field_orgname').autocomplete({
            source: '/dadata.php?action=nameorg',
            select: function (event, ui) {
                $('#id_profile_field_inn').val(ui.item.hid);
            }
        })
    })
});
