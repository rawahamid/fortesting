<?php

namespace App\Interfaces;

interface IRepository
{
    public function get();

    public function paginate($num = 10);

    public function first();

    public function builder();

    public function filter($filter) ;

    public function search($search) ;

    public function orderBy($order) ;

}
