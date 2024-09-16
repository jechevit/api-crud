<?php

namespace app\services;

use app\forms\GuestForm;
use app\models\Guest;
use app\repositories\GuestRepository;

class GuestService
{
    public function __construct(private GuestRepository $guestRepository){}

    public function create(GuestForm $form): Guest
    {
        $guest = Guest::create(
            $form->name,
            $form->last_name,
            $form->phone,
            $form->email,
            $form->country_code
        );

        $this->guestRepository->save($guest);
        return $guest;
    }

    public function update(GuestForm $form, Guest $guest): Guest
    {
        $guest->edit(
            $form->name,
            $form->last_name,
            $form->phone,
            $form->email,
            $form->country_code
        );

        $this->guestRepository->save($guest);
        return $guest;
    }

    public function delete(Guest $guest): void
    {
        $this->guestRepository->remove($guest);
    }
}