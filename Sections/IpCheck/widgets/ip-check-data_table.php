<?php
    /**@var \Sections\IpCheck\IpCheckModel[] $ipCheckMas*/
    use _Common\Helpers\HtmlHelper;
?>
<div class="ip-check-data ip-check-data_table">
    <table id="ip-check-data__table">
        <thead>
        <tr>
            <th class="ip-check-data_table__cell ip-check-data_table__cell_header">Дата и время</th>
            <th class="ip-check-data_table__cell ip-check-data_table__cell_header">Адрес</th>
            <th class="ip-check-data_table__cell ip-check-data_table__cell_header">Среднее время отклика (сек)</th>
            <th class="ip-check-data_table__cell ip-check-data_table__cell_header">Мин. время отклика (сек)</th>
            <th class="ip-check-data_table__cell ip-check-data_table__cell_header">Макс. время отклика (сек)</th>
        </tr>
        </thead>
        <tbody class="ip-check-data_table__body">
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
