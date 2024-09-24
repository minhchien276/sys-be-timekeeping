<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBlogRequest;
use App\ServicesAdmin\Blog\createBlog;
use App\ServicesAdmin\Blog\deleteBlog;
use App\ServicesAdmin\Blog\editBlog;
use App\ServicesAdmin\Blog\indexBlog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private $indexBlog;
    private $createBlog;
    private $editBlog;
    private $deleteBlog;

    public function __construct(
        indexBlog $indexBlog,
        createBlog $createBlog,
        editBlog $editBlog,
        deleteBlog $deleteBlog,
    ) {
        $this->indexBlog = $indexBlog;
        $this->createBlog = $createBlog;
        $this->editBlog = $editBlog;
        $this->deleteBlog = $deleteBlog;
    }

    public function index()
    {
        return $this->indexBlog->index();
    }

    public function create()
    {
        return $this->createBlog->create();
    }

    public function store(CreateBlogRequest $request)
    {
        return $this->createBlog->store($request);
    }

    public function edit($id)
    {
        return $this->editBlog->edit($id);
    }

    public function update(CreateBlogRequest $request, $id)
    {
        return $this->editBlog->update($request, $id);
    }

    public function delete($id)
    {
        return $this->deleteBlog->delete($id);
    }
}
