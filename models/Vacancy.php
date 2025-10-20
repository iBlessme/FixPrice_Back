<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * логика вынесена в VacancyService.
 *
 * @property int $id
 * @property string $title Название вакансии
 * @property string $description Описание
 * @property int $salary Зарплата
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 */
class Vacancy extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy';
    }

    /**
     * {@inheritdoc}
     */
    //Плагин для модели чтобы дата проставлялась автоматически
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'salary'], 'required'],
            [['description'], 'string'],
            [['salary'], 'integer', 'min' => 1],
            [['title'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название вакансии',
            'description' => 'Описание',
            'salary' => 'Зарплата',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

}
