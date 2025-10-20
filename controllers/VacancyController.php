<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use app\services\VacancyService;


class VacancyController extends Controller
{
    // Отключаем CSRF для API
    public $enableCsrfValidation = false;
    
    private $vacancyService;

    // Cors и фомат ответа
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => Yii::$app->params['corsWhitelist'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Max-Age' => 86400,
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    // Инициализация сервиса вакансии
    public function init()
    {
        parent::init();
        $this->vacancyService = new VacancyService();
    }

    
    //Список вакансий
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $result = $this->vacancyService->getList($params);
        
        return [
            'success' => true,
            'data' => [
                'vacancies' => array_map(fn($dto) => $dto->toArray(), $result['items']),
                'pagination' => $result['pagination'],
            ],
        ];
    }

    //получение конкретной вакансии
    public function actionView($id)
    {
        $fields = Yii::$app->request->get('fields', []);

        //преобразовываем в массив
        if (is_string($fields)) {
            $fields = array_map('trim', explode(',', $fields));
        }
        
        $vacancyDto = $this->vacancyService->getById((int)$id, $fields);
        
        if (!$vacancyDto) {
            Yii::$app->response->statusCode = 404;
            return [
                'success' => false,
                'message' => 'Вакансия не найдена',
            ];
        }
        
        return [
            'success' => true,
            'data' => $vacancyDto->toArray(),
        ];
    }

    //Создание вакансии
    public function actionCreate()
    {
        $data = Yii::$app->request->getBodyParams();
        $result = $this->vacancyService->create($data);
        
        if ($result['success']) {
            Yii::$app->response->statusCode = 201;
            return [
                'success' => true,
                'data' => [
                    'id' => $result['id'],
                ],
            ];
        }
        
        Yii::$app->response->statusCode = 400;
        return [
            'success' => false,
            'errors' => $result['errors'],
        ];
    }
}
