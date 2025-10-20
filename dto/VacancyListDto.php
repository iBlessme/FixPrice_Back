<?php

namespace app\dto;

use app\models\Vacancy;


class VacancyListDto
{
    public int $id;
    public string $title;
    public int $salary;
    public string $description;

    //Настраиваем получение полей согласно ТЗ
    public function __construct(Vacancy $vacancy)
    {
        $this->id = $vacancy->id;
        $this->title = $vacancy->title;
        $this->salary = $vacancy->salary;
        $this->description = $vacancy->description;
    }

    //Преобразовываем в JSON
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'salary' => $this->salary,
            'description' => $this->description,
        ];
    }
}

