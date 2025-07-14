<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Todo API",
 *     version="1.0.0",
 *     description="Backend Lumen untuk technical‑test"
 *   ),
 *   @OA\Server(
 *     url="/",  
 *     description="Production"
 *   )
 * )
 * @OA\PathItem(path="/")
 */
class Base {}
