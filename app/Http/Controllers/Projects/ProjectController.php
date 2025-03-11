<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\Projects\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        return response()->json($this->projectService->getAllProjects(), Response::HTTP_OK);
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectById($id);
        if (!$project) {
            return response()->json(['message' => 'Proyecto no encontrado'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($project, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'objective' => 'nullable|string',
            'source' => 'nullable|string',
            'program_id' => 'nullable|integer',
            'value' => 'nullable|numeric',
            'researcher_one' => 'nullable|string',
            'researcher_two' => 'nullable|string',
            'researcher_three' => 'nullable|string',
        ]);

        $project = $this->projectService->createProject($validatedData);

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'code' => 'string|unique:projects,code,' . $id,
            'name' => 'string',
            'status' => 'string',
            'start_date' => 'date',
            'end_date' => 'nullable|date',
            'objective' => 'string',
            'source' => 'string',
            'program_id' => 'nullable|exists:programs,id',
            'value' => 'numeric|min:0'
        ]);

        $project = $this->projectService->updateProject($id, $validatedData);
        return response()->json($project, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->projectService->deleteProject($id);
        return response()->json(['message' => 'Proyecto eliminado correctamente'], Response::HTTP_OK);
    }
}
