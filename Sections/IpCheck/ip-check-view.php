<?php
    /**@var string $ipCheckDataTableWidget*/
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="_common/css/bootstrap.min.css">
    <script src="_common/js/jquery-3.5.1.min.js"></script>

    <link rel="stylesheet" href="_common/css/_common.css">

    <link rel="stylesheet" href="IpCheck/css/ip-check.css">
    <script src="IpCheck/js/ip-check.js"></script>

    <title>Тестовое задание. Приложение для проверки целостности и качества соединений в ip-сетях</title>
</head>
<body>
    <header class="container-fluid header">
        <h1 class="header__text">
            Тестовое задание Чеснокова Е.Н. Приложение для проверки целостности и качества соединений в ip-сетях.
        </h1>
    </header>
    <main class="container main">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="ip-check-input">
                    <div>
                        <label for="ip-check-input__ip-field">Введитте IP (или URL), который нужно проверить:</label>
                    </div>
                    <div>
                        <input id="ip-check-input__ip-field" type="text"/>
                        <button id="ip-check-input__btn-check" class="btn btn-primary">Проверить</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <?php echo $ipCheckDataTableWidget; ?>
            </div>
        </div>
    </main>
    <footer class="container"></footer>
    <script src="_common/js/bootstrap.bundle.min.js"></script>
</body>
</html>
