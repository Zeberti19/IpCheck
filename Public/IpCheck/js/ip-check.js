//TODO использовать MVC
$(document).ready(function ()
{
    $('#ip-check-input__btn-check').click(function () {
        const errMsgPrefix='Ошибка ipCheckInputBtnCheckClick. ';
        const input$ = $('#ip-check-input__ip-field');
        const url = input$.val();

        if (''===url) alert('Пожалуйста, укажите IP хоста!');

        $.ajax({
            type: 'POST',
            url: $(location).attr('href'),
            data:
                {
                    route: 'ip-check',
                    action: 'ipCheck',
                    url: url
                } ,
            success: function(data,textStatus,jqXHR){
                if (!data)
                {
                    let errMsg=errMsgPrefix + 'AJAX запрос не вернул данных о проверке';
                    console.log(errMsgPrefix + 'AJAX запрос не вернул данных о проверке');
                    alert(errMsgPrefix + 'AJAX запрос не вернул данных о проверке');
                }
                alert('Данные:\n' + data);
                console.log(data);
            },
            error: function(jqXHR, textStatus, errorThrown){
                let errMsg=errMsgPrefix + 'AJAX запрос на проверку хоста не удалось выполнить';
                if (textStatus) errMsg+='. Тип ошибки от jQuery: ' + textStatus;
                if (textStatus) errMsg+='. Описание HTTP ошибки: ' + errorThrown;
                console.log(errMsg);
                alert(errMsg);
            }
        });

    });
});
