<?php

namespace App\Http\Controllers;

use App\Enums\postStatusEnum;
use App\Http\Requests\PostRequest;
use App\Model\Attachment;
use App\Model\Image;
use App\Model\Post;
use App\Repositories\PostRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    use ApiResponseTrait;

    public function __construct()
    {
      
        $this->middleware(['role:guest'])->except('store','update','destroy');
        $this->middleware(['role:author'])->except('destroy');

    }

    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $search = $request->input('search');

        $posts = Post::when($filter, function ($q) use ($filter) {
                    return $q->where('status', $filter);
                    })->
                    when($search , function ($q) use ($search) {
                    return 
                    $q->where('id', $search)
                    ->orWhere('desc', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%");
                })->paginate(10);

        return $this->successResponse($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $postRequest)
    {
        $post = new Post();
        $post->title = $postRequest->title;
      
        $slug = str_replace(' ', '_', $postRequest->slug);
        $post->slug  = $slug;

        $post->desc  = $postRequest->desc;
        $post->category_id = $postRequest->category_id;
        $post->status      = postStatusEnum::Draft;
        $post->user_id     = 1;
        $post->save();

        if ($postRequest->tags) {
            $tags = json_decode($postRequest->tags);
            $post->tags()->sync($tags);
        }
        

        $images     = $postRequest->file('images');
        $attachments = $postRequest->file('attachment');
        $gallery     = $postRequest->gallery;

        if($images){
         
            foreach($images as $image){
              $filename            =  $this->storeFile($image , $post->id);
              $imageModel          = new Image();
              $imageModel->path    = $filename; 
              $imageModel->post_id = $post->id;
              $imageModel->save();
            }
        }
        if($attachments){

             foreach($attachments as $attach){
              $filename             = $this->storeFile($attach , $post->id);
              $attachModel          = new Attachment();
              $mimetype             = $attach->getmimeType();
              $attachModel->path    = $filename; 
              $attachModel->type    = $mimetype; 
              $attachModel->post_id = $post->id;
              $attachModel->save();
            }
        }

        if($gallery){
            $gallery = json_decode($gallery);
            $post->gallery()->sync($gallery);
        }

        return $this->successResponse($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::with('attachemnts,images,category,tags')->where('slug',$slug)->first();
        return $this->successResponse($post);
    }

 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $postRequest, Post $post)
    {
        // you can make polices to make the author dont change his post
        
        $post->title = $postRequest->title;
      
        $slug = str_replace(' ', '_', $postRequest->slug);
        $post->slug  = $slug;

        $post->desc        = $postRequest->desc;
        $post->category_id = $postRequest->category_id;
    
        $post->status      = $postRequest->status;

        $post->save();

        if ($postRequest->tags) {
            $tags = json_decode($postRequest->tags);
            $post->tags()->sync($tags);
        }

        $images     = $postRequest->file('images');
        $attachments = $postRequest->file('attachment');
        $gallery     = $postRequest->gallery;

        if($images){
            foreach($images as $image){
              $filename            =  $this->storeFile($image , $post->id);
              $imageModel          = new Image();
              $imageModel->path    = $filename; 
              $imageModel->post_id = $post->id;
              $imageModel->save();
            }
        }
        if($attachments){

            foreach($attachments as $attach){
             $filename             = $this->storeFile($attach , $post->id);
             $attachModel          = new Attachment();
             $mimetype             = $attach->getmimeType();
             $attachModel->path    = $filename; 
             $attachModel->type    = $mimetype; 
             $attachModel->post_id = $post->id;
             $attachModel->save();
           }
       }

       if($gallery){
           $gallery = json_decode($gallery);
           $post->gallery()->sync($gallery);
       }

       return $this->successResponse($post,'updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->attachments()->delete();
        $post->images()->delete();

        $post->gallery()->wherePivot('post_id','=',$post->id)->detach();
        $post->tags()->wherePivot('post_id','=',$post->id)->detach();
        $post->delete();

        return $this->successResponse($post,'Deleted successfully');
    }

    public function storeFile($file , $path)
    {
        $filename = $path . '/' . time() . '-' . $file->getClientOriginalName();
        Storage::disk('public')->put($filename, file_get_contents($file));
        return $filename;
    }
}
