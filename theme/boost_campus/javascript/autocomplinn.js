require(['jquery', 'jqueryui', 'theme_boost_campus/jquery.maskedinput'], function ($, jui) {

    if($( "#page-user-editadvanced" ).hasClass( "path-user" ) || $( "#page-user-edit" ).hasClass( "path-user" )){
        $(document).ready(function () {

            $("#id_profile_field_phone").mask("+7(999)999-99-99");

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
            });

            // ПОТОК ИЗ JSON
            var el = document.getElementById("id_profile_field_UF_VAVTADJSONS");
            var obj = el.getAttribute("value");
            console.log(obj.length);
            if(obj.length > 0){
                var UF_VAVTADJSONS = jQuery.parseJSON(obj);
                $('#id_profile_field_potok').val(UF_VAVTADJSONS.statusList[0].groupName);
                $('#id_profile_field_potok').text(UF_VAVTADJSONS.statusList[0].groupName);
                $('#id_profile_field_potok').attr('readonly', true);
            }


            /// Справочник отраслей
            function get_category_fn(okved, cat = ''){
                $('#id_profile_field_category').find('optgroup').remove();
                $('#id_profile_field_category').find('option').remove();

                $.ajax({
                    url: "/local/vavt_scripts/ajax.php",
                    type: "POST",
                    data: ({industry: okved,
                        action: 'get_category'}),
                    // dataType: "json",
                    dataType: "text",
                    success: function(data){
                        // $.each(data, function(key, value) {
                        //     $('#id_profile_field_category')
                        //         .append($("<option></option>")
                        //             .attr("value", value)
                        //             .text(value));
                        // });
                        $('#id_profile_field_category').append(data);
                        if(cat !== ''){
                            $("#id_profile_field_category option[value='"+cat+"']").attr("selected", "selected");
                        }
                    },
                    error: function () {
                        alert('Ошибка при получении категорий');
                    }
                });
            }

            var okved = $('#page-user-editadvanced #id_profile_field_okved').find(":selected").val()

            if(okved !== ''){
                var category = $('#page-user-editadvanced #id_profile_field_category').find(":selected").val()
                get_category_fn(okved, category);
            }else{
                $('#page-user-editadvanced #id_profile_field_category').find('option').remove();
            }
            $('#id_profile_field_okved').change(function(evt){
                get_category_fn($(this).val());
            });

        })
    }

});
