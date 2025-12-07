<?php
namespace App\Services\Users\Managers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Services\Users\Models\User;


class SendLetterManager
{
    public function send(User $newuser)
    {
        event(new Registered($newuser));
        //Auth::login($newuser);
    }
}
