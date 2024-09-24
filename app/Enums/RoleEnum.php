<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RoleEnum extends Enum
{
    const Director = 1;
    const Leader = 2;
    const Manager = 3;
    const Staff = 4;
    const Intern = 5;
    const PartTime = 6;
    const ThuViec = 7;
    const Security = 8;
    const Receptionist = 9;
    const Housemate = 10;
}
