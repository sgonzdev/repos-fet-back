<?php

namespace App\Services\Projects;

use App\Models\Projects\Project;
use App\Models\Projects\Researcher;
use Illuminate\Support\Facades\DB;

class ProjectService
{

    public function updateProject($id, array $data)
    {
        $project = Project::findOrFail($id);
        $project->update($data);
        return $project;
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
    }
    public function getAllProjects()
    {
        return Project::with('researchers')->get()->map(function ($project) {
            return $this->formatProject($project);
        });
    }

    public function getProjectById($id)
    {
        $project = Project::with('researchers')->find($id);
        return $project ? $this->formatProject($project) : null;
    }

    public function createProject(array $data)
    {
        return DB::transaction(function () use ($data) {
            $researcherNames = array_filter([
                $data['researcher_one'] ?? null,
                $data['researcher_two'] ?? null,
                $data['researcher_three'] ?? null,
            ]);
    
            $project = Project::create($data);
    
            $researchers = collect($researcherNames)->map(function ($name) {
                return Researcher::firstOrCreate(['name' => ucfirst(strtolower(trim($name)))]);
            });
    
            $project->researchers()->sync($researchers->pluck('id'));
    
            return $this->formatProject($project->loadMissing('researchers'));
        });
    }
    
    private function formatProject(Project $project)
    {
        return [
            'id' => $project->id,
            'codigo' => $project->code,
            'nombreProyecto' => $project->name,
            'objetivoGeneral' => $project->objective,
            'programa' => $project->source,
            'anio' => $project->start_date ? date('Y', strtotime($project->start_date)) : null,
            'procedencia' => $project->source,
            'investigadorUno' => $project->researchers[0]->name ?? null,
            'investigadorDos' => $project->researchers[1]->name ?? null,
            'investigadorTres' => $project->researchers[2]->name ?? null,
            'fechaInicio' => $project->start_date,
            'fechaFin' => $project->end_date,
            'estado' => $project->status,
            'valorProyecto' => $project->value,
            'cantidadProyectos' => $project->researchers->count(),
            'alerta' => $this->generateAlert($project),
        ];
    }

    private function generateAlert(Project $project)
    {
        if ($project->start_date && $project->end_date) {
            $fechaFin = strtotime($project->end_date);
            $ahora = time();

            if ($project->status === "TERMINADO") return "bg-danger";
            if ($project->status === "DETENIDO") return "bg-orange";
            if ($ahora < strtotime("-10 days", $fechaFin)) return "bg-danger";
            if ($ahora >= strtotime("-10 days", $fechaFin) && $ahora < $fechaFin) return "bg-warning";
        }
        return "bg-success";
    }
}