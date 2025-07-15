<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;


/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter JWT Bearer token only"
 * )
 */

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/auth/register",
     *   tags={"Auth"},
     *   summary="User registration",
     *   @OA\RequestBody(
     *     @OA\JsonContent(required={"name","email","password"},
     *       @OA\Property(property="name",type="string"),
     *       @OA\Property(property="email",type="string",format="email"),
     *       @OA\Property(property="password",type="string",format="password"))
     *   ),
     *   @OA\Response(response=201, description="Registered")
     * )
     */
    public function register(Request $r)
    {
        try {
            $this->validate($r, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);
            DB::beginTransaction();
            $user = User::create([
                'name' => $r->name,
                'email' => $r->email,
                'password' => Hash::make($r->password),
            ]);
            $token = JWTAuth::fromUser($user);
            DB::commit();
            return response()->json(['token' => $token], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }
     /**
     * @OA\Post(
     *   path="/auth/login",
     *   tags={"Auth"},
     *   summary="User login",
     *  @OA\RequestBody(
     *    @OA\JsonContent(required={"email","password"},
     *      @OA\Property(property="email",type="string",format="email"),
     *     @OA\Property(property="password",type="string",format="password"))
     *  ),
     * @OA\Response(response=200, description="Logged in"),
     * @OA\Response(response=401, description="Invalid credentials")
     * )
     */

    public function login(Request $r)
    {
        try {
            $credentials = $r->only(['email', 'password']);
            if (!$token = JWTAuth::attempt($credentials))
                return response()->json(['error' => 'invalid_credentials'], 401);
            return compact('token');
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/auth/me",
     *   tags={"Auth"},
     *   summary="Get current authenticated user",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="User credentials")
     * )
     */
    public function me(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch user', 'message' => $e->getMessage()], 401);
        }
    }
}
