<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

/**
 * @OA\Tag(
 *     name="Quote",
 *     description="Inspirational quote endpoints"
 * )
 *
 * @OA\Get(
 *     path="/quote",
 *     tags={"Quote"},
 *     summary="Get a random inspirational quote",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="A random inspirational quote",
 *         @OA\JsonContent(
 *             @OA\Property(property="_id", type="string"),
 *             @OA\Property(property="content", type="string"),
 *             @OA\Property(property="author", type="string"),
 *             @OA\Property(property="tags", type="array", @OA\Items(type="string"))
 *         )
 *     )
 * )
 */
class QuoteController extends Controller
{
    public function daily()
    {
        try {
            $res = Http::get('https://api.quotable.io/random?tags=inspirational');
            return $res->json();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch quote'], 500);
        }
    }
}