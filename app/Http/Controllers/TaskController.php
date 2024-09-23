<?php

namespace App\Http\Controllers;

use App\Models\Junction;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Get all tasks
    public function index()
    {
        return response()->json(Task::all(), 200);
    }

    // Add new task
    public function add(Request $request)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:in progress,deferred,completed',
        ]);

        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    // Assign task to a team memeber
    public function assign(Request $request)
    {
        $junction = Junction::create($request->all());
        return response()->json($junction, 201);
    }

    // update task status or change task owner
    public function updateById(Request $request, string $id)
    {
        $request->validate([
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:in progress,deferred,completed',
        ]);

        $task = Task::find($id);
        if (is_null($task)) {
            return response()->json(['message' => 'Task Not Found'], 404);
        }

        if ($request->has('status')) {
            $newStatus = $request->status;
            $currentStatus = $task->status;

            // Prevent transitioning directly from deferred to completed
            if ($currentStatus === 'deferred' && $newStatus === 'completed') {
                return response()->json([
                    'message' => 'Cannot transition directly from deferred to completed.'
                ], 422);
            }

            // Ensure a project can only be marked as completed if the start date has passed
            if ($newStatus === 'completed' && $task->start_date > now()) {
                return response()->json([
                    'message' => 'A project cannot be marked as completed before it has started.'
                ], 422);
            }
        }

        Notification::create($request->all());
        // We can set the messages to sent for assigned users only

        $task->update($request->all());

        return response()->json($task, 200);
    }
}
