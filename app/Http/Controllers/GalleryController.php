<?php

namespace App\Http\Controllers;

use App\Http\Requests\GalleryRequest;
use App\Model\Gallery;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    
    use ApiResponseTrait;
    public function __construct()
    {
        $this->middleware(['role:sys_admin']);
    }
    
    public function index(Request $request)
    {
        
        $gallery = Gallery::orderBy('id','desc')->paginate(10);

        return $this->successResponse($gallery);
    }

    public function store(GalleryRequest $galleryRequest)
    {
        //  you can check if image count dosent more than 100 

        $gallery = new Gallery();
        $gallery->title   = $galleryRequest->title;
        $gallery->path    = $this->storeFile($galleryRequest->file('image'),'gallery');
        $gallery->user_id = 1;//$this->guard()->user;
        $gallery->save();

        return $this->successResponse($gallery);
    }

    public function delete(Gallery $gallery)
    {
        if (count($gallery->posts) == 0) {
            $gallery->delete();
            return $this->successResponse('image Deleted Successfully');
        }
        return $this->serverErrorResponse('Error This image Have Posts !');

    }

    public function storeFile($file , $path)
    {
        $filename = $path . '/' . time() . '-' . $file->getClientOriginalName();
        Storage::disk('public')->put($filename, file_get_contents($file));
        return $filename;
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
