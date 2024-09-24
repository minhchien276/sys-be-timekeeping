<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DepartmentEnum extends Enum
{
    const Sales = 1;
    const SEO = 2;
    const IT = 3;
    const Warehouse = 4;
    const Marketing = 5;
    const HR = 6;
    const Designer = 7;
    const Skinnet = 8;
    const GA = 9;
    const Accounting = 10;
    const Director = 11;
    const Driver = 12;
}
