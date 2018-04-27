<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoriesTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'name'=>$category->name,
            'id'=>$category->id,
            'description'=>$category->descripton,
        ];
    }
}