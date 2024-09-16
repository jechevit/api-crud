<?php

namespace app\repositories;

use app\models\Guest;

interface DbRepository
{
    public function save(Guest $guest);
    public function remove(Guest $guest);
    public function get(int $id);
    public function find(int $id);
}