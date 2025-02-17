<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StockStatus extends Enum
{
    const IN_STOCK = "in_stock";
    const OUT_OF_STOCK = "out_of_stock";
    const PRE_ORDER = "pre_order";
}
