<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CalendarStatus extends Enum
{
    const Success = 1;
    const Lated = 2;
    const Missing = 3;
    const Pending = 4;
    const Dayoff = 5;
    const Origin = 6;
    const Holiday = 7;
}
