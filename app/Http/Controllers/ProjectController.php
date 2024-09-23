<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('title')) {
            // Filtering
            $project = $query->where('title', 'like', '%' . $request->title . '%')->get();
        }
        else if ($request->has('sort_by')) {
            // Sorting
            $sortBy = $request->get('sort_by', 'id');
            $order = $request->get('order', 'asc');//desc
            $project = $query->orderBy($sortBy, $order)->get();
        }
        else if ($request->has('per_page')) {
            // Pagination
            $perPage = $request->get('per_page', 2);
            $project = $query->paginate($perPage);
        }
        else {
            // Get all projects
            $project = Project::all();
        }

        return response()->json($project, 200);
    }

    // Add new project
    public function add(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,deferred,completed',
        ]);

        $status = $request->status;
        if ($status === 'completed' && $request->start_date > now()) {
            return response()->json([
                'message' => 'A project cannot be marked as completed before it has started.'
            ], 422);
        }

        $project = Project::create($request->all());

        return response()->json($project, 201);
    }

    // Get project by id
    public function getById($id)
    {
        $project = Project::find($id);
        if (is_null($project)) {
            return response()->json(['message' => 'Project Not Found'], 404);
        }
        return response()->json($project, 200);
    }

    // Update project by id
    public function updateById(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'sometimes|required|in:active,deferred,completed',
        ]);

        if ($request->has('status')) {
            $newStatus = $request->status;
            $currentStatus = $project->status;

            // Prevent transitioning directly from deferred to completed
            if ($currentStatus === 'deferred' && $newStatus === 'completed') {
                return response()->json([
                    'message' => 'Cannot transition directly from deferred to completed.'
                ], 422);
            }

            // Prevent changing from completed to any other status
            if ($currentStatus === 'completed' && $newStatus !== 'completed') {
                return response()->json([
                    'message' => 'A completed project cannot be reopened or deferred.'
                ], 422);
            }

            // Ensure a project can only be marked as completed if the start date has passed
            if ($newStatus === 'completed' && $project->start_date > now()) {
                return response()->json([
                    'message' => 'A project cannot be marked as completed before it has started.'
                ], 422);
            }
        }

        $project->update($request->all());

        return response()->json($project);
    }

    // Delete a project by id
    public function deleteById($id)
    {
        $project = Project::find($id);
        if (is_null($project)) {
            return response()->json(['message' => 'Project Not Found'], 404);
        }
        $project->delete();
        return response()->json(null, 204);
    }
}
