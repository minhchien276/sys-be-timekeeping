<?php

namespace App\ServicesAdmin\Blog;

use App\Enums\TypeNotification;
use App\Models\blog;
use App\Models\employee;
use App\Models\notification;
use App\Services\Notification\pushNotificationSpecific;
use App\Services\Notification\PushNotificationVer2;
use Carbon\Carbon;
use Exception;

class createBlog
{
    private $pushNotificationSpecific;
    private $pushNotificationVer2;

    public function __construct(
        pushNotificationSpecific $pushNotificationSpecific,
        PushNotificationVer2 $pushNotificationVer2,
    ) {
        $this->pushNotificationSpecific = $pushNotificationSpecific;
        $this->pushNotificationVer2 = $pushNotificationVer2;
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store($request)
    {
        try {
            $today = Carbon::today()->timestamp * 1000;
            $now = Carbon::now()->timestamp * 1000;

            $blog = blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $request->image,
                'link' => $request->link,
                'dateTimeBlog' => $now,
                'createdAt' => $today,
            ]);

            if (!$blog) {
                return redirect()->back()->with('error', 'Tạo mới bài viết không thành công!');
            }

            $topic = "members-v2";

            $notification = notification::create([
                'notiTitle' => $request->title,
                'notiContent' => $request->content,
                'receiverId' => 0,
                'senderId' => $request->session()->get('user')->id,
                'applicationId' => $blog->id,
                'type' => TypeNotification::Blog,
                'seen' => 1,
                'createdAt' => Carbon::now()->timestamp * 1000,
            ]);

            $type_noti = "blog";
            $id_noti = $blog->id;

            $this->pushNotificationVer2->sendNotification($topic, $request->title, $request->content, $type_noti, $id_noti);

            return redirect()->back()->with('success', 'Tạo mới bài viết thành công!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
