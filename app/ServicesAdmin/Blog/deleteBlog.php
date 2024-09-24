<?php

namespace App\ServicesAdmin\Blog;

use App\Models\blog;
use Exception;

class deleteBlog
{
    public function delete($id)
    {
        try {
            blog::where('id', $id)->delete();

            return redirect()->back()->with('success', 'Xóa bài viết thành công!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Xóa bài viết không thành công!');
        }
    }
}
