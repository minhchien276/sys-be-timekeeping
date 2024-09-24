<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StatusTaskEnum extends Enum
{
    // const Pending = 1;          // Đang chờ xử lý
    // const InProgress = 2;       // Đang thực hiện
    // const Completed = 3;        // Hoàn thành
    // const OnHold = 4;           // Tạm dừng
    // const Canceled = 5;         // Đã hủy
    // const Overdue = 6;          // Quá hạn
    // const Reviewed = 7;         // Đã được xem xét

    const Todo = 1;
    const Working = 2;
    const Done = 3;
    const Cancel = 4;
}
