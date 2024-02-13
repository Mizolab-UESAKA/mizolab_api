<?php

namespace App\Traits;

use Illuminate\Container\Container;
use App\AdminPersonalAccessTokenFactory;

trait HasAdminApiTokens
{
    public function createAdminToken($name, array $scopes = [])
    {
        return Container::getInstance()->make(AdminPersonalAccessTokenFactory::class)->make(
            $this->getKey(), $name, $scopes
        );
    }
}
