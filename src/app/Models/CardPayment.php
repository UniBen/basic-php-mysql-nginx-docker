<?php

namespace App\Models;

use Framework\Model;

/**
 * Class CardPayment
 * @package App\Models
 *
 * @property int $payid
 * @property int $cno
 * @property string $ctype
 * @property string $cexpr
 */
class CardPayment extends Model
{
    /**
     * @var string
     */
    protected $table = 'fss_CardPayment';

    /**
     * @var string
     */
    protected $primaryKey = 'payid';
}