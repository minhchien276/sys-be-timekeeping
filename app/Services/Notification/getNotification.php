<?php

namespace App\Services\Notification;

use App\Enums\TypeNotification;
use App\Models\employee;
use App\Models\notification;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Exception;

class getNotification
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle($request)
    {
        try {
            $employee = $this->parseToken->handle();

            $perPage = 20;
            $offset = ($request->page) * $perPage;

            $notifications = notification::leftJoin('application', 'notification.applicationId', '=', 'application.id')
                ->select('application.title', 'application.content', 'application.image', 'application.type', 'application.status', 'application.employeeId', 'application.approverId', 'application.createdAt', 'notification.*')
                ->where(function ($query) use ($employee) {
                    $query->where('notification.receiverId', 0)
                        ->orWhere('notification.receiverId', '=', $employee->id);
                })
                ->orderBy('notification.createdAt', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();

            if (!$notifications) {
                return Responder::fail(null, 'Không tìm thấy thông báo', 404);
            }

            foreach ($notifications as $noti) {
                $title = $noti->title;
                $sender = employee::where('id', $noti->senderId)->first();

                if ($noti->type === TypeNotification::Exam) {
                    $noti->text = "đã gửi đến bạn bài kiểm tra kiến thức chuyên môn";
                    $noti->senderName = $sender->fullname;
                    $noti->image = $sender->image;
                } elseif ($noti->type === TypeNotification::Salary) {
                    $noti->text = $noti->notiContent;
                    $noti->senderName = $sender->fullname;
                    $noti->image = $sender->image;
                } elseif ($noti->type === TypeNotification::NewEmployee) {
                    $noti->text = $noti->notiContent;
                    $noti->senderName = $sender->fullname;
                    $noti->image = $sender->image;
                } elseif ($noti->type === TypeNotification::Blog) {
                    $noti->text = $noti->notiContent;
                    $noti->senderName = $sender->fullname;
                    $noti->image = $sender->image;
                } elseif ($noti->type === TypeNotification::Normal) {
                    $noti->text = $noti->notiContent;
                    $noti->senderName = $sender->fullname;
                    $noti->image = $sender->image;
                } elseif ($noti->type === TypeNotification::WorkOrder) {
                    $noti->text = $noti->notiContent;
                    $noti->senderName = $sender->fullname;
                    $noti->image = $sender->image;
                } else {
                    if ($noti->approverId == $noti->senderId) {
                        if ($noti->status == 0) {
                            $noti->text = "đã từ chối " . $title . " của bạn";
                            $noti->senderName = $sender->fullname;
                            $noti->image = $sender->image;
                        } else if ($noti->status == 1) {
                            $noti->text = "đã phê duyệt " . $title . " của bạn";
                            $noti->senderName = $sender->fullname;
                            $noti->image = $sender->image;
                        } else {
                            $noti->text = "chưa phê duyệt " . $title . " của bạn";
                            $noti->senderName = $sender->fullname;
                            $noti->image = $sender->image;
                        }
                    } else {
                        $noti->text = "đã gửi " . $title . " đến bạn";
                        $noti->senderName = $sender->fullname;
                        $noti->image = $sender->image;
                    }
                }
            }

            return Responder::success($notifications, 'Lấy thông báo thành công');
        } catch (Exception $e) {
            return Responder::fail(null, $e->getMessage());
        }
    }
}
