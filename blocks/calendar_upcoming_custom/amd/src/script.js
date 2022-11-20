define(['jquery', 'jqueryui'], function ($, jui) {
    return {
        init: function () {
            $('.block_calendar_upcoming_custom #cal-next').click(function () {
                var numevent = Number($('.block_calendar_upcoming_custom #numevent').val())+1;
                var allevent = $('.block_calendar_upcoming_custom #allevent').val();

                $.ajax({
                    url: "blocks/calendar_upcoming_custom/ajax.php",
                    type: "GET",
                    data: {
                        action: 'loadevent',
                        allevent: allevent,
                        event: numevent,
                    },
                    success: function (response) {
                        $('.block_calendar_upcoming_custom #numevent').val(numevent);
                        $('.block_calendar_upcoming_custom .prevcal').removeClass('prevdis');
                        if(numevent === 0){
                            $('.block_calendar_upcoming_custom .prevcal#cal-prev').addClass('prevdis');
                        }
                        $('.block_calendar_upcoming_custom .content .eventcontent').html(response);
                    },
                    error: function () {
                        alert('Ошибка при загрузке событий');
                    }
                });
            });

            $('.block_calendar_upcoming_custom #cal-prev').click(function () {
                var numevent = Number($('.block_calendar_upcoming_custom #numevent').val())-1;
                var allevent = $('.block_calendar_upcoming_custom #allevent').val();

                $.ajax({
                    url: "blocks/calendar_upcoming_custom/ajax.php",
                    type: "GET",
                    data: {
                        action: 'loadevent',
                        allevent: allevent,
                        event: numevent,
                    },
                    success: function (response) {
                        $('.block_calendar_upcoming_custom #numevent').val(numevent);
                        $('.block_calendar_upcoming_custom .content .eventcontent').html(response);
                    },
                    error: function () {
                        alert('Ошибка при загрузке событий');
                    }
                });
            });

        }
    }
});
