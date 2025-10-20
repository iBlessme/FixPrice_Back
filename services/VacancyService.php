<?php

namespace app\services;

use app\models\Vacancy;
use app\dto\VacancyListDto;
use app\dto\VacancyDetailDto;
use yii\data\ActiveDataProvider;

/**
 * Сервис для работы с вакансиями
 */
class VacancyService
{

    // Получение списка вакансий
    public function getList($params = [])
    {
        $query = Vacancy::find();

        // Поиск по названию
        $search = isset($params['search']) ? trim((string)$params['search']) : '';
        if ($search !== '' && $search !== 'null' && $search !== 'undefined') {
            $query->andWhere(['like', 'title', $search]);
        }

        // Сортировка
        $allowedSortFields = ['salary', 'created_at'];
        $sortBy = in_array($params['sort_by'] ?? null, $allowedSortFields) ? $params['sort_by'] : 'created_at';
        $sortOrder = strtolower((string)($params['sort_order'] ?? '')) === 'asc' ? SORT_ASC : SORT_DESC;
        $query->orderBy([$sortBy => $sortOrder]);

        // Пагинация
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = max(1, min((int)($params['perPage'] ?? 10), 100));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $perPage,
                'page' => $page - 1,
            ],
        ]);

        // Преобразуем в дто
        $items = [];
        foreach ($dataProvider->getModels() as $vacancy) {
            $items[] = new VacancyListDto($vacancy);
        }

        return [
            'items' => $items,
            'pagination' => [
                'total_count' => $dataProvider->getTotalCount(),
                'page_count' => $dataProvider->getPagination()->getPageCount(),
            ],
        ];
    }

    // Получение конкретной вакансии
    public function getById($id, $fields = [])
    {
        $vacancy = Vacancy::findOne($id);

        if (!$vacancy) {
            return null;
        }

        return new VacancyDetailDto($vacancy, $fields);
    }

    // Создание вакансии
    public function create($data)
    {
        $vacancy = new Vacancy();
        $vacancy->title = $data['title'] ?? '';
        $vacancy->description = $data['description'] ?? '';
        $vacancy->salary = (int)($data['salary'] ?? 0);

        if ($vacancy->save()) {
            return [
                'success' => true,
                'id' => $vacancy->id,
                'errors' => [],
            ];
        }

        // форматирование ошибок
        $errors = [];
        foreach ($vacancy->getErrors() as $field => $messages) {
            $errors[] = [
                'field' => $field,
                'message' => is_array($messages) ? implode(', ', $messages) : $messages,
            ];
        }

        return [
            'success' => false,
            'id' => null,
            'errors' => $errors,
        ];
    }
}
