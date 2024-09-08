<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'Budget App API',
    title: 'Budget App API',
    contact: new OA\Contact(
        name: 'vkarchevskyi',
        email: 'vkarchevskyi10@gmail.com'
    ),
)]
abstract class Controller
{
    //
}
