<?php
namespace App\Models;

class Referral
{
    public $depth;
    public $userModel;

    public function __construct(User $user, $depth)
    {
        $this->userModel = $user;
        $this->depth = $depth;
    }
}