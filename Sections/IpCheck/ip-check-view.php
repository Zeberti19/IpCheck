<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="_common/css/bootstrap.min.css">
    <script src="_common/js/jquery-3.5.1.min.js"></script>

    <link rel="stylesheet" href="IpCheck/css/ip-check.css">
    <script src="IpCheck/js/ip-check.js"></script>

    <title>Тестовое задание. Приложение для проверки целостности и качества соединений в ip-сетях</title>
</head>
<body>
    <header class="container">
        <h1>
            Тестовое задание Чеснокова Е.Н. Приложение для проверки целостности и качества соединений в ip-сетях.
        </h1>
    </header>
    <main class="container">
        <div class="row">
            <div class="ip-check-input col-12 col-md-6">
                <div>
                    <div>
                        <label for="ip-check-input__ip-field">Введитте IP (или URL), который нужно проверить:</label>
                    </div>
                    <div>
                        <input id="ip-check-input__ip-field" type="text"/>
                        <button id="ip-check-input__btn-check" class="btn btn-primary">Проверить</button>
                    </div>
                </div>
            </div>
            <div class="ip-check-data ip-check-data_table col-12 col-md-6">
                <table>
                    <thead>
                        <tr>
                            <th class="ip-check-data_table__cell">Адрес</th>
                            <th>Среднее время отклика</th>
                            <th>Мин. время отклика</th>
                            <th>Макс. время отклика</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer class="container"></footer>
    <script src="_common/js/bootstrap.bundle.min.js"></script>
</body>
</html>
