<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\Projects\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Projects\Project;

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
            'name' => 'string',
            'status' => 'string',
            'end_date' => 'nullable|date',
            'objective' => 'string',
            'source' => 'string',
            'value' => 'numeric|min:0',
            'researcher_one' => 'nullable|string',
            'researcher_two' => 'nullable|string',
            'researcher_three' => 'nullable|string',
        ]);

        $project = $this->projectService->updateProject($id, $validatedData);
        return response()->json($project, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $project = $this->projectService->getProjectById($id);
        if (!$project) {
            return response()->json(['message' => 'Proyecto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $this->projectService->deleteProject($id);
        return response()->json(['message' => 'Proyecto eliminado correctamente'], Response::HTTP_OK);
    }


    public function count()
    {
        return (string) $this->projectService->countProjects();
    }

    public function countProjectsByPronoun($pronoun)
    {
        return (string) $this->projectService->countProjectsByPronoun($pronoun);
    }

}
