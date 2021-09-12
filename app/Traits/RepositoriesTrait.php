<?php

namespace App\Traits;

trait RepositoriesTrait
{
    public function get()
    {
        return $this->builder->get();
    }

    public function paginate($num = 10)
    {
        return $this->builder->paginate($num);
    }

    public function first()
    {
        return $this->builder->first();
    }

    public function builder()
    {
        return $this->builder;
    }
}
