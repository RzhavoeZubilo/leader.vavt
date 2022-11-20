define(['jquery'], function ($) {
    return {
        init: function () {
            $(document).ready(function () {
                $('.addfavoritevavt').on('click', function (evt) {
                    let btnfav = $(this);
                    let plugin = btnfav.data( "plugin" );
                    let objid = btnfav.data( "objid" );
                    let action = '';
                    if($(this).hasClass('addfav')){
                        action = 'favoritedel';
                    }else{
                        action = 'favoriteadd';
                    }
                    console.log(action);
                    $.ajax({
                        url: "/local/vavt_scripts/ajax.php",
                        type: "POST",
                        data: ({plugin:plugin,
                                objid:objid,
                                action: action}),
                        dataType: "text",
                        success: function(data){
                            btnfav.toggleClass('addfav');
                        },
                        error: function () {
                            alert('Ошибка при добавлении в избранное');
                        }
                    });
                });
            })
        }
    }

})