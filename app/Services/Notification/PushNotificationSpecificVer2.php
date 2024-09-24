<?php

namespace App\Services\Notification;

use Google_Client;
use GuzzleHttp\Client;
use Hamcrest\Arrays\IsArray;

class PushNotificationSpecificVer2
{
    private $credPath;
    private $projectId = "happy-new-year-9c3c5";

    public function __construct()
    {
        $this->credPath = public_path('e-tmsc-notification.json');
    }

    public function sendNotification($device_token, $title, $body, $type_noti, $id_noti)
    {

        $accessToken = $this->getAccessToken();

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json; UTF-8',
        ];

        $data = [];
        if (is_array($device_token)) {
            $data = array_merge($data, $device_token);
        } else {
            $data[] = $device_token;
        }
        foreach ($data as $item) {
            try {
                $notificationData = [
                    'message' => [
                        'token' => $item,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'apns' => [
                            'payload' => [
                                'aps' => [
                                    "sound" => "killpop.wav",
                                ],
                            ],
                        ],
                        "android" => [
                            "notification" => [
                                "channel_id" => "high_important_channel",
                                "sound" => "killpop.wav",
                            ]
                        ],
                        "data" => [
                            "type" => strval($type_noti),
                            "id" => strval($id_noti),
                        ]
                    ],
                ];

                $client = new Client();
                $response = $client->post($url, [
                    'headers' => $headers,
                    'body' => json_encode($notificationData),
                ]);

                // Kiểm tra phản hồi từ server (nếu cần)
                if ($response->getStatusCode() !== 200) {
                    // Xử lý phản hồi không thành công
                    throw new \Exception('Notification sending failed for token: ' . $item);
                }
            } catch (\Exception $e) {
                // Xử lý các lỗi khác
                error_log('Exception: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status_code' => $response->getStatusCode(),
            'response_json' => json_decode($response->getBody(), true),
            'project_id' => $this->projectId,
            'access_token' => $accessToken,
            'notification_data' => $notificationData,
        ]);
    }

    private function getAccessToken()
    {
        if (!file_exists($this->credPath)) {
            throw new \Exception("File does not exist: " . $this->credPath);
        }

        $client = new Google_Client();
        $client->setAuthConfig($this->credPath);
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');

        $accessToken = $client->fetchAccessTokenWithAssertion();

        if (isset($accessToken['error'])) {
            throw new \Exception("Error fetching access token: " . $accessToken['error']);
        }

        if (is_null($accessToken) || !isset($accessToken['access_token'])) {
            throw new \Exception("Failed to fetch access token.");
        }

        return $accessToken['access_token'];
    }
}
