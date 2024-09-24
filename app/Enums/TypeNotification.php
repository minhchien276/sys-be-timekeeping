<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TypeNotification extends Enum
{
    const Exam = 1;
    const Salary = 2;
    const NewEmployee = 3;
    const Blog = 4;
    const Normal = 5;
    const WorkOrder = 6;
}
