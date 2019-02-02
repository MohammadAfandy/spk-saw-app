<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PenilaianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ($id) ? 'Penilaian - ' . \app\models\Spk::namaSpk($id) : 'Penilaian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penilaian-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row" style="margin-top: 30px;">
        <div class="col-lg-2">
            <label>PILIH NAMA SPK</label>
        </div>
        <div class="col-lg-4">
            <?= Html::dropDownList('spk', $id, \yii\helpers\ArrayHelper::map($data_spk, 'id', 'nama_spk'), ['prompt' => '--PILIH--', 'class' => 'form-control', 'id' => 'pilih_spk']) ?>
        </div>
    </div>

    <?php if ($id): ?>
        <p>
            <?= Html::a('Tambah Penilaian', ['create', 'id' => $id], ['class' => 'btn btn-success']) ?>
        </p>
    
        <?php
        echo $this->render('_list_penilaian', [
            'penilaian' => $penilaian,
            'kriteria' => $kriteria,
            'nilai' => $nilai,
        ]);
        ?>
    <?php endif; ?>
</div>

<?php
$this->registerJs(
    '

    $("#pilih_spk").on("change", function() {
        window.location.href = "' . \yii\helpers\Url::to(['index']) . '/" + this.value;
    });

    ',
    \yii\web\View::POS_READY,
    'alternatif-js'
);
?>