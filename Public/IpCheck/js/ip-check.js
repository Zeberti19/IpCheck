//TODO использовать MVC
$(document).ready(function ()
{
    $('#ip-check-input__btn-check').click(function () {
        const errMsgPrefix='Ошибка ipCheckInputBtnCheckClick. ';
        const input$ = $('#ip-check-input__ip-field');
        //обрезание лишних пробелов происходит на сервере, т.к. что здесь это делать не вижу смысла
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
            success: function(response,textStatus,jqXHR){
                if (!response)
                {
                    let errMsg=errMsgPrefix + 'AJAX запрос не вернул никаких данных';
                    console.log(errMsg);
                    alert(errMsg);
                    return;
                }
                response=JSON.parse(response);
                if (!response)
                {
                    let errMsg=errMsgPrefix + 'AJAX запрос не вернул никаких данных';
                    console.log(errMsg);
                    alert(errMsg);
                    return;
                }
                if (response && 'error' === response.status)
                {
                    let errMsg=errMsgPrefix + 'Во время обработки запроса произошла ошибка';
                    if (response.message) errMsg+='. Описание ошибки: ' + response.message;
                    console.log(errMsg);
                    alert(errMsg);
                    return;
                }
                alert('Данные:\n' + response.data);
                console.log(response.data);
            },
            error: function(jqXHR, textStatus, errorThrown){
                let errMsg=errMsgPrefix + 'AJAX запрос на проверку хоста не удалось выполнить';
                if (textStatus) errMsg+='. Тип ошибки от jQuery: ' + textStatus;
                if (errorThrown) errMsg+='. Описание HTTP ошибки: ' + errorThrown;
                console.log(errMsg);
                alert(errMsg);
            }
        });

    });
});
