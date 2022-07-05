<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Bcrew会員ランク区分
 *
 * @access public
 * @packege Enums
 */
final class BcrewCustomerRankTypeDefine extends Enum
{
    /** B-crew無料会員 */
    const FREE = '1';
    /** B-crew300 */
    const RANK1 = '2';
    /** B-crewVIP */
    const RANK2 = '3';
}
