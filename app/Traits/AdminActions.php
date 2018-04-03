<?php

namespace App\Traits;

trait AdminActions
{
    /**
     * Determina si el usuario es administrador, entonces anula las politicas siguientes
     *
     * @return boolean
     */
    public function before($user, $ability)
    {
        if ($user->userAdmin())
        {
            return true;
        }
    }
}