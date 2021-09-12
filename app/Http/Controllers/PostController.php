<?php

namespace App\Http\Controllers;

use App\Enums\PostStatusEnum;
use App\Http\Requests\PostRequest;
use App\Model\Attachment;
use App\Model\Image;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class PostController extends Controller
{
    public function __construct()
    {

        $this->middleware(['role:guest'])->except('store','update','destroy');
        $this->middleware(['role:author'])->except('destroy');

    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filter = $request->input('filter');
            $search = $request->input('search');

            $posts = Post::when($filter, function ($q) use ($filter) {
                return $q->where('status', $filter);
            })
                ->when($search , function ($q) use ($search) {
                    return $q->where('id', $search)
                        ->orWhere('desc', 'LIKE', "%{$search}%")
                        ->orWhere('title', 'LIKE', "%{$search}%");
                })->paginate(10);

            return $this->successResponse($posts);
        } catch (Exception $ex) {
            return $this->invalidDataResponse($ex->getMessage());
        }
    }

    public function store(PostRequest $postRequest): JsonResponse
    {
        DB::beginTransaction();
        try {
            $post = Post::create([
                'title'         => $postRequest->title,
                'slug'          => str_replace(' ', '_', $postRequest->slug),
                'desc'          => $postRequest->desc,
                'category_id'   => $postRequest->category_id,
                'status'        => PostStatusEnum::DRAFT,
                'user_id'       => auth()->id()
            ]);

            if ($postRequest->tags) {
                $tags = json_decode($postRequest->tags, true);
                $post->tags()->sync($tags);
            }
            $images = $postRequest->file('images');
            $attachments = $postRequest->file('attachment');
            $gallery = $postRequest->gallery;

            if ($images) {
                foreach ($images as $image) {
                    $filename = $this->storeFile($image, $post->id);
                    // Same as in top
                    $imageModel = new Image();
                    $imageModel->path = $filename;
                    $imageModel->post_id = $post->id;
                    $imageModel->save();
                }
            }
            if ($attachments) {
                foreach ($attachments as $attach) {
                    $filename = $this->storeFile($attach, $post->id);
                    // Same as in top
                    $attachModel = new Attachment();
                    $mimetype = $attach->getmimeType();
                    $attachModel->path = $filename;
                    $attachModel->type = $mimetype;
                    $attachModel->post_id = $post->id;
                    $attachModel->save();
                }
            }
            if ($gallery) {
                $gallery = json_decode($gallery, true);
                $post->gallery()->sync($gallery);
            }
            DB::commit();
            return $this->successResponse($post);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->invalidDataResponse();
        }
    }

    public function show($slug)
    {
        $post = Post::with('attachemnts,images,category,tags')->where('slug',$slug)->first();
        return $this->successResponse($post);
    }

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
