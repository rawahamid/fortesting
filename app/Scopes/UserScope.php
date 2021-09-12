<?php

namespace App\Scopes;

use App\Enums\PostStatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserScope implements Scope
{
    public function __construct()
    { }

    public function apply(Builder $builder, Model $model): void
    {
        $user = auth('api')->user();
        /// if the user guest
        if ($user === null || $user->hasRole(userTypeEnum::GUEST)) {
            $builder->where('status', postStatusEnum::PUBLISH);
        }

        /// if uesr admin return all
        if (!$user->hasRole(userTypeEnum::ADMIN)) {
            $builder->where('user_id', $user->id);
        }
    }
}
