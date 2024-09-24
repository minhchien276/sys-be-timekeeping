<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class TuViController extends Controller
{
    public function xemtuoikethon($age, $sex, $year)
    {
        // $age = $request->query('age');
        // $sex = $request->query('sex') === 'true' ? 'true' : 'false';
        // $year = $request->query('year');

        $url = "https://tuvi.vn/xem-tuoi-ket-hon?NamSinhKetHon=" . $age . "&GioiTinhKetHon=" . $sex . "&NamKetHon=" . $year;

        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ]
        ]);

        // Tạo client GuzzleHttp và thêm User-Agent
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ]
        ]);
        $response = $client->get($url);
        $body = $response->getBody()->getContents();

        $crawler = new Crawler($body);

        // Lấy dữ liệu từ bảng "Thông tin gia chủ"
        $infoTable = $crawler->filter('table.horoscope-board')->first();
        $infoData = [];
        if ($infoTable->count()) {
            $infoRows = $infoTable->filter('tr');
            foreach ($infoRows as $row) {
                $cols = (new Crawler($row))->filter('td');
                if ($cols->count() == 2) {
                    $key = trim($cols->eq(0)->text());
                    $value = trim($cols->eq(1)->text());
                    $infoData[$key] = $value;
                }
            }
        }

        // Lấy dữ liệu từ tất cả các bảng "kết quả phép xem tuổi" và chọn bảng cuối cùng
        $resultTables = $crawler->filter('table.horoscope-board');
        $resultTable = $resultTables->last();
        $resultData = [];
        if ($resultTable->count()) {
            $resultRows = $resultTable->filter('tr');
            foreach ($resultRows as $index => $row) {
                if ($index === 0) continue; // Bỏ qua hàng tiêu đề
                $cols = (new Crawler($row))->filter('td');
                if ($cols->count() == 4) {
                    $resultData[] = [
                        'STT' => trim($cols->eq(0)->text()),
                        'Tuổi hợp' => trim($cols->eq(1)->text()),
                        'Can chi' => trim($cols->eq(2)->text()),
                        'Điểm đánh giá' => trim($cols->eq(3)->text()),
                    ];
                }
            }
        }

        return response()->json(['info' => $infoData, 'results' => $resultData]);
    }
}
