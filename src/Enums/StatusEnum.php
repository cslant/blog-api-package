<?php

namespace CSlant\Blog\Api\Enums;

enum StatusEnum: string
{
    case PUBLISHED = 'published';

    case DRAFT = 'draft';

    case PENDING = 'pending';
}
