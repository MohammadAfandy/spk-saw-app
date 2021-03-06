<?php

namespace app\models;

use Yii;

use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%spk}}".
 *
 * @property int $id
 * @property string $nama_spk
 * @property string $keterangan
 * @property string $created_date
 * @property string $updated_date
 */
class Spk extends \yii\db\ActiveRecord
{
    const BOBOT_PREFERENSI = 0;
    const BOBOT_PERSEN = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%spk}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_spk', 'jenis_bobot'], 'required'],
            [['keterangan'], 'string'],
            [['created_date', 'updated_date'], 'safe'],
            [['nama_spk'], 'string', 'max' => 250],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_spk' => 'Nama SPK',
            'keterangan' => 'Keterangan',
            'jenis_bobot' => 'Jenis Bobot',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_date', 'updated_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_date'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}
