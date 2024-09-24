<?php

namespace App\Services\Document;

use App\Models\document;
use App\Supports\Responder;
use Exception;

class getById
{
    public function handle($employeeId)
    {
        try {
            $document = document::where('employeeId', $employeeId)->get();

            return Responder::success($document, 'Danh sách hồ sơ');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy hồ sơ', 404);
        }
    }
}
