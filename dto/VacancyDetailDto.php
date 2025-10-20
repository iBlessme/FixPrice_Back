<?php

namespace app\dto;

use app\models\Vacancy;

class VacancyDetailDto
{
    private int $id;
    private string $title;
    private int $salary;
    private string $description;
    private array $requestedFields;

    //настраиваем получение полей из БД по ТЗ
    public function __construct(Vacancy $vacancy, array $fields = [])
    {
        $this->id = $vacancy->id;
        $this->title = $vacancy->title;
        $this->salary = $vacancy->salary;
        $this->description = $vacancy->description;
        $this->requestedFields = $fields;
    }

    // Преобразование в массив для JSON
    public function toArray(): array
    {
        $allFields = [
            'id' => $this->id,
            'title' => $this->title,
            'salary' => $this->salary,
            'description' => $this->description,
        ];

        // все поля если не указан fields
        if (empty($this->requestedFields)) {
            return $allFields;
        }


        $result = [
            'id' => $this->id,
            'salary' => $this->salary,
        ];

        //обработка если указан fields
        foreach ($this->requestedFields as $field) {
            if (isset($allFields[$field]) && $field !== 'id' && $field !== 'salary') {
                $result[$field] = $allFields[$field];
            }
        }

        return $result;
    }
}

