<?php

namespace App\ServicesAdmin\Blog;

use App\Models\blog;
use Carbon\Carbon;
use Exception;

class editBlog
{
    public function edit($id)
    {
        $blog = blog::find($id);

        return view('admin.blog.edit', compact('blog'));
    }

    public function update($request, $id)
    {
        try {
            $today = Carbon::today()->timestamp * 1000;
            $now = Carbon::now()->timestamp * 1000;

            $blog = blog::where('id', $id)->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $request->image,
                'link' => $request->link,
                'dateTimeBlog' => $now,
                'updatedAt' => $today,
            ]);

            if (!$blog) {
                return redirect()->back()->with('error', 'Cập nhật bài viết không thành công!');
            }

            return redirect()->back()->with('success', 'Cập nhật bài viết thành công!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Cập nhật bài viết không thành công!');
        }
    }
}
