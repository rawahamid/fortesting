<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriesRequest;
use App\Model\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:sys_admin']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::when($search , function ($q) use ($search) {
            return $q->where('id', $search)
                ->orWhere('desc', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%");
            })
            ->orderby('id','desc')
            ->paginate(10);

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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category): \Illuminate\Http\JsonResponse
    {
        if (count($category->posts) === 0) {
            $category->delete();
            return $this->successResponse('Category Deleted Successfully');
        }

        return $this->serverErrorResponse('Error This Category Have Posts !');
    }

}
