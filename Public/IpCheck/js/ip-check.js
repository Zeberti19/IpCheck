//TODO передалать всё на MVC, вынести общие функции в отдельный файл
$(document).ready(function ()
{
    $('#ip-check-input__btn-check').click(function () {
        const errMsgPrefix='Ошибка ipCheckInputBtnCheckClick. ';
        const input$ = $('#ip-check-input__ip-field');
        //обрезание лишних пробелов происходит на сервере, т.к. что здесь это делать не вижу смысла
        const url = input$.val();

        if (''===url) {
            alert('Пожалуйста, укажите IP хоста!');
            return;
        }

        $.ajax({
            type: 'POST',
            url: $(location).attr('href'),
            data:
                {
                    route: 'ip-check',
                    action: 'ipCheck',
                    url: url,
                    isAjax: true
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
                const dataRender=
                    {
                        id: response.data.id,
                        url: url,
                        datetime: response.data.datetime,
                        avg: response.data.avg,
                        min: response.data.min,
                        max: response.data.max
                    };
                dataRowAdd(dataRender);
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

function dataRowAdd(data)
{
    //удаляем фейковую строку, если есть
    let tableFirstRow$=$('#ip-check-data__table > tbody > tr');
    if (!tableFirstRow$.attr('data-id')) tableFirstRow$.remove();
    //добавляем/обновляем новую
    let tableRow$=$('#ip-check-data__table > tbody > tr[data-id=' + data.id + ']');
    if (tableRow$) tableRow$.remove();
    $('#ip-check-data__table > tbody').prepend(
        "<tr data-id='" + data.id +"'>" +
        "<td class='ip-check-data_table__cell'>" + data.datetime + "</td>" +
        "<td class='ip-check-data_table__cell'>" + data.url + "</td>" +
        "<td class='ip-check-data_table__cell'>" + data.avg + "</td>" +
        "<td class='ip-check-data_table__cell'>" + data.min + "</td>" +
        "<td class='ip-check-data_table__cell'>" + data.max + "</td>" +
        "</tr>"
    );
}
