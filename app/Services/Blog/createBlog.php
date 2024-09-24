<?php

namespace App\Services\Blog;

use App\Models\blog;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class createBlog
{
    public function store($request)
    {
        $now = Carbon::now()->timestamp * 1000;

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required',
            'dateTimeBlog' => 'required',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'content.required' => 'Nội dung là bắt buộc.',
            'image.required' => 'Ảnh là bắt buộc.',
            'dateTimeBlog.required' => 'Thời gian đăng bài là bắt buộc.',
        ]);

        if ($validator->fails()) {
            return Responder::fail(null, $validator->errors()->first(), 400);
        }

        try {
            $blog = blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $request->image,
                'dateTimeBlog' => $request->dateTimeBlog,
                'createdAt' => $now,
            ]);

            return Responder::success($blog, 'Thêm mới bài viết thành công');
        } catch (Exception $e) {
            return Responder::fail(null, 'Thêm mới bài viết thất bại', 400);
        }
    }
}
