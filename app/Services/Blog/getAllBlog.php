<?php

namespace App\Services\Blog;

use App\Models\blog;
use App\Supports\Responder;
use Exception;

class getAllBlog
{
    public function handle()
    {
        try {
            $blogs = blog::orderBy('dateTimeBlog', 'desc')->get();

            return Responder::success($blogs, 'Danh sách bài viết');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy bài viết', 404);
        }
    }
}
