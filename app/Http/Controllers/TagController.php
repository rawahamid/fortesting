<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Model\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:sys_admin']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $tags = Tag::when($search , function ($q) use ($search) {
                    return
                    $q->where('id', $search)
                    ->orWhere('desc', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%");
                })
                ->orderby('id','desc')
                ->paginate(10);

        return $this->successResponse($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $tagRequest)
    {
        $tag = Tag::create($tagRequest->validated());
        return $this->successResponse($tag);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return  $this->successResponse($tag);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $tagRequest , Tag $tag)
    {
        $tag->update($tagRequest->validated());
        return $this->successResponse($tag);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if (count($tag->posts) == 0) {
            $tag->delete();
            return $this->successResponse('Tag Deleted Successfully');
        }
        return $this->serverErrorResponse('Error This Tag Have Posts !');

    }

}
