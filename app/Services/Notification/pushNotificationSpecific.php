<?php

namespace App\Services\Notification;

class pushNotificationSpecific

{
    public function handle($deviceTokens, $title, $body, $type_noti, $id_noti)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = 'AAAAUFP2lUs:APA91bF7dTxtnesPtMdpPI6rA87UHmynacivs2YFgyE_Oc5oX3xsCbIW5W7mIfzeAwvQzgjq2oiHr-JGtKS8dGZQgHOI4J6mPC10qzP9T966prw8UdTDf5k8u6ZHCe-4x2Hr1e2nlsum'; // ADD SERVER KEY HERE PROVIDED BY FCM

        $data = [
            "registration_ids" => $deviceTokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "mutable_content" => true,
                "sound" => "killpop.wav",
                "android_channel_id" => "high_important_channel",
            ],
            "android" => [
                "notification" => [
                    "channel_id" => "high_important_channel",
                    "sound" => "killpop.wav",
                ]
            ],
            "data" => [
                "type" => $type_noti,
                "id" => $id_noti,
            ]
        ];

        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init(); //Khởi tạo một phiên cURL mới.

        curl_setopt($ch, CURLOPT_URL, $url); // URL mà yêu cầu sẽ được gửi đến.
        curl_setopt($ch, CURLOPT_POST, true); // Thiết lập phương thức của yêu cầu là POST.
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Thiết lập các header của yêu cầu.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Thiết lập để cURL trả về dữ liệu sau khi thực hiện yêu cầu.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Thiết lập xác minh máy chủ SSL. Trong trường hợp này, nó được đặt thành 0, nghĩa là không xác minh.
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // Thiết lập phiên bản giao thức HTTP được sử dụng. Ở đây, sử dụng HTTP/1.1.
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Tạm thời vô hiệu hóa xác minh chứng chỉ SSL.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData); // Thiết lập dữ liệu được gửi trong yêu cầu POST. Dữ liệu này thường được mã hóa dưới dạng một chuỗi trước khi gửi.
        // Execute post
        $result = curl_exec($ch); //Thực thi yêu cầu cURL đã được cấu hình.
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch)); // Trả về mô tả lỗi của cURL nếu có.
        }
        // Close connection
        curl_close($ch); // Đóng phiên cURL sau khi hoàn thành yêu cầu.
    }
}
