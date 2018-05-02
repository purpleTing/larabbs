<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\PermissionTransformer;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = $this->user->getAllPermissions();
        return $this->response->collection($permissions, new PermissionTransformer());
    }
}
