<div class="panel-body">
    <fieldset class="fieldset">
        <legend>Ranking</legend>
        <table class="table table-bordered">
            <thead>
                <th>Peringkat</th>
                <th>Nama Alternatif</th>
                <th>Nilai</th>
            </thead>
            <tbody>
                <?php if (!empty($rank) && is_array($rank)): ?>
                    <?php $no = 1; ?>
                    <?php foreach($rank as $key => $r): ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= \app\models\Penilaian::namaAlternatif($key) ?></td>
                            <td><?= round($r, 3) ?></td>
                            <?php $no++; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Data Tidak Ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
</div>