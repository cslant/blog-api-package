<?php

namespace CSlant\Blog\Api\Http\Actions;

use Botble\Base\Http\Controllers\Concerns\HasHttpResponse;

/**
 * Base Action class providing common functionality for API actions
 *
 * @package CSlant\Blog\Api\Http\Actions\Concerns
 * @group Blog API
 */
abstract class Action
{
    use HasHttpResponse;
}
