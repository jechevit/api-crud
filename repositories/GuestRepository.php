<?php

namespace app\repositories;

use app\exceptions\SaveErrorException;
use app\models\Guest;
use app\models\ProjectsEditorComments;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class GuestRepository implements DbRepository
{

    /**
     * @param Guest $guest
     * @return void
     * @throws Exception
     */
    public function save(Guest $guest): void
    {

        if (!$guest->save()) {
            throw new SaveErrorException('Guest not saved');
        }
    }

    /**
     * @param Guest $guest
     * @return void
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function remove(Guest $guest): void
    {
       if (!$guest->delete()) {
           throw new SaveErrorException('Guest not remove');
       }
    }

    public function get(int $id): Guest
    {

    }

    public function find(int $id): Guest
    {
        $guest = Guest::findOne($id);
        if (!$guest) {
            throw new NotFoundHttpException('Guest not found');
        }
        return $guest;
    }
}