<?php

namespace App\ServicesAdmin\Blog;

use App\Models\blog;
use Carbon\Carbon;

class indexBlog
{
    public function index()
    {
        $blog = blog::orderBy('dateTimeBlog', 'desc')->get();

        $blog->map(function ($item) {
            if ($item->dateTimeBlog) {
                $dateTimeBlog = Carbon::createFromTimestamp($item->dateTimeBlog / 1000);
                $item->dateTimeBlog = $dateTimeBlog->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.blog.index', compact('blog'));
    }
}
