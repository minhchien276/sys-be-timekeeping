<?php

namespace App\Services\Blog;

use App\Models\blog;
use App\Supports\Responder;
use Exception;

class getBlogById
{
    public function handle($id)
    {
        try {
            $blog = blog::find($id);

            return Responder::success($blog, 'Chi tiết bài viết');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy bài viết', 404);
        }
    }
}
