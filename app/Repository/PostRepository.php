<?php

namespace App\Repositories;

use App\Interfaces\IRepository;
use App\Model\Post;
use App\Traits\RepositoryTrait;

class PostRepository implements IRepository
{
    use RepositoryTrait;

    private $builder;

    public function __construct()
    {
        $this->builder = Post::with('categories');
    }

    public function filter($filter) 
    {
        $this->builder = $this->builder->when($filter, function ($q) use ($filter) {
                return $q->where('status', $filter);
        });
        return $this;
    }

    public function search($search) 
    {
        $this->builder = $this->builder->where(function ($q) use ($search) {
            return 
            $q->where('id', $search)
            ->orWhere('desc', 'LIKE', "%{$search}%")
            ->orWhere('title', 'LIKE', "%{$search}%");
           
        });
        return $this;
    }

    public function orderBy($orderBy) 
    {
        switch ($orderBy) {
            case 'id_asc':
                $this->builder = $this->builder->orderBy('id', 'asc');
                break;
            case 'id_desc':
                $this->builder = $this->builder->orderBy('id', 'desc');
                break;
         
            default:
                break;
        }
        return $this;
    }
}
