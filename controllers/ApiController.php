<?php

namespace app\controllers;

use app\controllers\behaviors\DebugBehavior;
use app\exceptions\ValidateException;
use app\forms\GuestForm;
use app\repositories\GuestRepository;
use app\services\GuestService;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @SWG\Swagger(
 *     schemes={"http, https"},
 *     host="localhost:8000",
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         description="Version: __1.0.0__",
 *     ),
 * )
 *
 * @SWG\Tag(
 *   name="guest",
 * )
 */
class ApiController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;

    public function __construct($id, $module,
                                private GuestService $guestService,
                                private GuestRepository $guestRepository,
                                $config = [])
    {
        parent::__construct($id, $module, $config);
    }



    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'debug' => [
                'class' => DebugBehavior::class,
            ],
        ];
    }

    /**
     * @param $id
     * @param $params
     * @return Response
     */
    public function runAction($id, $params = []): Response
    {
        try {
            return parent::runAction($id, $params);
        } catch (\Exception $e) {
            return $this->asJson([
                'exception' => [
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode(),
                ],
                'status' => 'error',
            ]);
        }
    }

    /**
     * @SWG\Definition(
     *        definition="Guest",
     *        type="object",
     *        @SWG\Property(property="name", type="string"),
     *        @SWG\Property(property="last_name", type="string"),
     *        @SWG\Property(property="phone", type="string", example="+79643333333"),
     *        @SWG\Property(property="email", type="string", example="mail@mail.ru"),
     *    )
     * @SWG\Post(
     *    path="/api/create",
     *    tags={"Guest"},
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *          name="guest",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Guest")
     *     ),
     *  )
     */
    public function actionCreate(): Response
    {
        $post = json_decode($this->request->getRawBody(), true);
        $model = new GuestForm();

        $model->load($post);
        if ($model->validate()) {
            $guest = $this->guestService->create($model);
        } else {
            throw new ValidateException(implode("\n", $model->getFirstErrors()));
        }

        return $this->asJson($guest);
    }


    /**
     * @SWG\Patch(
     *    path="/api/{id}",
     *    summary="Редактирование",
     *    tags={"Guest"},
     *      @SWG\Parameter(
     *            name="id",
     *            in="path",
     *            description="guest id",
     *            required=true,
     *            type="integer",
     *            default="3"
     *        ),
     *    @SWG\Parameter(
     *             name="guest",
     *             in="body",
     *             required=true,
     *             @SWG\Schema(
     *                  @SWG\Property(type="string", property="name", example="name",)
     *                  @SWG\Property(type="string", property="last_name", example="last_name",)
     *                  @SWG\Property(type="string", property="email", example="mail1@mail.ru",)
     *                  @SWG\Property(type="string", property="phone", example="+37369612904",)
     *              )
     *         ),
     *     @SWG\Response(
     *          response=200,
     *          description="изменение ",
     *          @SWG\Schema(ref="#/definitions/Guest")
     *        ),
     *        @SWG\Response(
     *          response=404,
     *          description="Guest not found",
     *        )
     *  )
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function actionUpdate(int $id): Response
    {
        $guest = $this->guestRepository->find($id);
        $post = json_decode($this->request->getRawBody(), true);
        $model = new GuestForm($guest);

        $model->load($post);

        if ($model->validate()) {
            $guest = $this->guestService->update($model, $guest);
        } else {
            throw new ValidateException(implode("\n", $model->getFirstErrors()));
        }

        return $this->asJson($guest);
    }

    /**
     * @SWG\Get(
     *    path="/api/{id}",
     *    tags={"Guest"},
     *      @SWG\Parameter(
     *            name="id",
     *            in="path",
     *            description="guest id",
     *            required=true,
     *            type="integer",
     *            default="3"
     *        ),
     *     @SWG\Response(
     *          response=200,
     *          description="Данные по guest",
     *          @SWG\Schema(ref="#/definitions/Guest")
     *        ),
     *        @SWG\Response(
     *          response=404,
     *          description="Comment not found",
     *        )
     *  )
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): Response
    {
        $guest = $this->guestRepository->find($id);
        return $this->asJson($guest);
    }

    /**
     * @SWG\Delete (
     *    path="/api/{id}",
     *     summary="Удаление",
     *    tags={"Guest"},
     *      @SWG\Parameter(
     *            name="id",
     *            in="path",
     *            description="guest id",
     *            required=true,
     *            type="integer",
     *            default="245185"
     *        ),
     *        @SWG\Response(
     *          response=404,
     *          description="Comment not found",
     *        )
     *  )
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function actionDelete(int $id): Response
    {
        $guest = $this->guestRepository->find($id);
        $this->guestService->delete($guest);

        return $this->asJson(null);
    }
}