<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\createBlogRequest;
use App\Services\Blog\createBlog;
use App\Services\Blog\getAllBlog;
use App\Services\Blog\getBlogById;
use Illuminate\Http\Request;

class ApiBlogController extends Controller
{
    private $getAllBlog;
    private $createBlog;
    private $getBlogById;

    public function __construct(
        getAllBlog $getAllBlog,
        createBlog $createBlog,
        getBlogById $getBlogById,
    ) {
        $this->middleware('auth:api');
        $this->getAllBlog = $getAllBlog;
        $this->createBlog = $createBlog;
        $this->getBlogById = $getBlogById;
    }

    public function getAllBlog()
    {
        return $this->getAllBlog->handle();
    }

    public function create(Request $request)
    {
        return $this->createBlog->store($request);  
    }

    public function getBlogById($id)
    {
        return $this->getBlogById->handle($id);  
    }
}
