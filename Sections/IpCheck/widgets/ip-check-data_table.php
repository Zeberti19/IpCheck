<?php
    /**@var \Sections\IpCheck\IpCheckModel[] $ipCheckMas*/
    use _Common\Helpers\HtmlHelper;
?>
<div class="ip-check-data ip-check-data_table col-12 col-md-6">
    <table id="ip-check-data__table">
        <thead>
        <tr>
            <th class="ip-check-data_table__cell">Дата и время</th>
            <th class="ip-check-data_table__cell">Адрес</th>
            <th class="ip-check-data_table__cell">Среднее время отклика (сек)</th>
            <th class="ip-check-data_table__cell">Мин. время отклика (сек)</th>
            <th class="ip-check-data_table__cell">Макс. время отклика (сек)</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$ipCheckMas): ?>
            <tr>
                <td class="ip-check-data_table__cell">...</td>
                <td class="ip-check-data_table__cell">...</td>
                <td class="ip-check-data_table__cell">...</td>
                <td class="ip-check-data_table__cell">...</td>
                <td class="ip-check-data_table__cell">...</td>
            </tr>
        <?php endif ?>
        <?php foreach ($ipCheckMas as &$IpCheckModel): ?>
            <tr data-id="<?= HtmlHelper::encode($IpCheckModel->id) ?>">
                <td class="ip-check-data_table__cell"><?= HtmlHelper::encode($IpCheckModel->datetime) ?></td>
                <td class="ip-check-data_table__cell"><?=  HtmlHelper::encode($IpCheckModel->url) ?></td>
                <td class="ip-check-data_table__cell"><?=  HtmlHelper::encode($IpCheckModel->response_time_avg) ?></td>
                <td class="ip-check-data_table__cell"><?=  HtmlHelper::encode($IpCheckModel->response_time_min) ?></td>
                <td class="ip-check-data_table__cell"><?=  HtmlHelper::encode($IpCheckModel->response_time_max) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
