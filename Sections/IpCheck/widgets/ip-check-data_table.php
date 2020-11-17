<?php
    /**@var \Sections\IpCheck\IpCheckModel[] $ipCheckMas*/
?>
<table id="ip-check-data__table">
    <thead>
        <tr>
            <th class="ip-check-data_table__cell">Дата и время</th>
            <th class="ip-check-data_table__cell">Адрес</th>
            <th class="ip-check-data_table__cell">Среднее время отклика</th>
            <th class="ip-check-data_table__cell">Мин. время отклика</th>
            <th class="ip-check-data_table__cell">Макс. время отклика</th>
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
        <?php //TODO добавить экранирование ?>
        <?php foreach ($ipCheckMas as &$IpCheckModel): ?>
            <tr data-id="<?php echo $IpCheckModel->id ?>">
                <td class="ip-check-data_table__cell"><?php echo $IpCheckModel->datetime ?></td>
                <td class="ip-check-data_table__cell"><?php echo $IpCheckModel->url ?></td>
                <td class="ip-check-data_table__cell"><?php echo $IpCheckModel->response_time_avg ?></td>
                <td class="ip-check-data_table__cell"><?php echo $IpCheckModel->response_time_min ?></td>
                <td class="ip-check-data_table__cell"><?php echo $IpCheckModel->response_time_max ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
