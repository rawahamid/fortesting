<?php

namespace App\Scopes;

use App\Enums\postStatusEnum;
use App\Enums\userTypeEnum;
use App\Model\User;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserScope implements Scope
{
    /**
     * To be sure that the model applying the scope does or doesn't have branch_id
     * @var bool
     */

    public function __construct()
    { }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {


        $user = auth('api')->user() ?? null;
        /// if the user guest
        if ($user == null || $user->hasRole(userTypeEnum::Guest)) {
            $builder->where('status', postStatusEnum::Publish);
        } 
        /// if uesr admin return all
        else if ($user->hasRole(userTypeEnum::Admin)) {
                 
        } 
        /// if user author return only the user post
        else {
            $builder->where('user_id', $user->id);
        }
    }
}
