<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TypeApplication extends Enum
{
    const PaidLeave = 0;
    const UnpaidLeave = 1;
    const OverTime = 2;
    const Lated = 3;
    const Early = 4;
}
