<?php

namespace CSlant\Blog\Api\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static StatusEnum DRAFT()
 * @method static StatusEnum PUBLISHED()
 * @method static StatusEnum PENDING()
 */
class StatusEnum extends Enum
{
    public const PUBLISHED = 'published';
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
}
