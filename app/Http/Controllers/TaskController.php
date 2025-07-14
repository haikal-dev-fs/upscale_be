<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Task",
 *     description="Task management endpoints"
 * )
 *
 * @OA\Get(
 *     path="/tasks",
 *     tags={"Task"},
 *     summary="List tasks for the authenticated user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Filter by status (pending|done)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of tasks"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/tasks",
 *     tags={"Task"},
 *     summary="Create a new task",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title"},
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Task created"
 *     )
 * )
 *
 * @OA\Patch(
 *     path="/tasks/{id}",
 *     tags={"Task"},
 *     summary="Update a task",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="status", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task updated"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/tasks/{id}",
 *     tags={"Task"},
 *     summary="Delete a task",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Task deleted"
 *     )
 * )
 */
class TaskController extends Controller
{
    public function index(Request $r)
    {
        try {
            $user = JWTAuth::user();
            $q = $user->tasks();
            if ($r->has('status')) $q->where('status',$r->status);
            return $q->latest()->paginate(10);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch tasks'], 500);
        }
    }

    public function store(Request $r)
    {
        try {
            DB::beginTransaction();
            $this->validate($r, ['title'=>'required']);
            $task = JWTAuth::user()->tasks()->create($r->only('title','description'));
            DB::commit();
            return response()->json($task,201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }

    public function update(Request $r,$id)
    {
        try {
            DB::beginTransaction();
            $task = JWTAuth::user()->tasks()->findOrFail($id);
            $task->update($r->only('title','description','status'));
            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $task = JWTAuth::user()->tasks()->find($id);
            if (!$task) {
                DB::rollBack();
                return response()->json(['error' => 'Task not found'], 404);
            }
            $task->delete();
            DB::commit();
            return response()->json(['message' => 'Task deleted'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete task', 'message' => $e->getMessage()], 500);
        }
    }
}
