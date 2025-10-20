<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vacancy}}`.
 */
class m240101_000000_create_vacancy_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%vacancy}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Название вакансии'),
            'description' => $this->text()->notNull()->comment('Описание вакансии'),
            'salary' => $this->integer()->notNull()->comment('Зарплата'),
            'created_at' => $this->dateTime()->notNull()->comment('Дата создания'),
            'updated_at' => $this->dateTime()->notNull()->comment('Дата обновления'),
        ]);

        // Создаем индексы для оптимизации сортировки
        $this->createIndex('idx-vacancy-salary', '{{%vacancy}}', 'salary');
        $this->createIndex('idx-vacancy-created_at', '{{%vacancy}}', 'created_at');
        $this->createIndex('idx-vacancy-title', '{{%vacancy}}', 'title');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%vacancy}}');
    }
}
