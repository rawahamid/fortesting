<?php

namespace App\Http\Controllers;

use App\Enums\postStatusEnum;
use App\Http\Requests\CategoriesRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\TagRequest;
use App\Model\Attachment;
use App\Model\Category;
use App\Model\Gallery;
use App\Model\Image;
use App\Model\Post;
use App\Model\Tag;
use App\Repositories\PostRepository;
use App\Traits\ApiResponseTrait;
use Dotenv\Result\Success;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware(['role:sys_admin']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = 
            Category::
                when($search , function ($q) use ($search) {
                    return 
                    $q->where('id', $search)
                    ->orWhere('desc', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%");
                })->
                orderby('id','desc')->
                paginate(10);

        return $this->successResponse($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriesRequest $categoriesRequest)
    {
        $category = Category::create($categoriesRequest->validated());
        return $this->successResponse($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return  $this->successResponse($category);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriesRequest $categoriesRequest , Category $category)
    {
        $category->update($categoriesRequest->validated());
        return $this->successResponse($category);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (count($category->posts) == 0) {
            $category->delete();
            return $this->successResponse('Category Deleted Successfully');
        }
        
        return $this->serverErrorResponse('Error This Category Have Posts !');
    }

}
