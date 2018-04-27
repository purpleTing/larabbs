<?php

namespace App\Http\Controllers\Api;

use App\Transformers\CategoriesTransformer;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoriesRequest;

class CategoriesController extends Controller
{
    public function index()
    {
        return $this->response->collection(Category::all(), new CategoriesTransformer());
    }
}
