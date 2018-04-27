<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Requests\Api\ImagesRequest;
use App\Transformers\ImageTransformer;
use App\Handlers\ImageUploadHandler;


class ImagesController extends Controller
{
    public function store(ImagesRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        $user = $this->user;

        $size           = $request->type == 'avatar' ? 362 : 1024;
        $result         = $uploader->save($request->image, str_plural($request->type), $user->id, $size);
        $image->path    = $result['path'];
        $image->user_id = $user->id;
        $image->type    = $request->type;
        $image->save();

        return $this->response->item($image, new ImageTransformer())->setStatusCode(201);
    }
}
