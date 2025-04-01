<?php

namespace App\Services\Projects;

use App\Models\Projects\Project;
use App\Models\Program;
use App\Models\Projects\Researcher;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function updateProject($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $project = Project::findOrFail($id);

            $researcherNames = array_filter([
                $data['researcher_one'] ?? null,
                $data['researcher_two'] ?? null,
                $data['researcher_three'] ?? null,
            ]);

            $project->update($data);
            $researchers = collect($researcherNames)->map(function ($name) {
                return Researcher::firstOrCreate(['name' => ucfirst(strtolower(trim($name)))]);
            });

            $project->researchers()->sync($researchers->pluck('id'));

            return $this->formatProject($project->loadMissing('researchers'));
        });
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
                return Researcher::firstOrCreate([
                    'name' => ucfirst(strtolower(trim($name)))
                ]);
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
            'programa' => $project->program->name ?? null,
            'anio' => $project->start_date ? date('Y', strtotime($project->start_date)) : null,
            'procedencia' => $project->source,
            'investigadorUno' => $project->researchers[0]->name ?? null,
            'investigadorDos' => $project->researchers[1]->name ?? null,
            'investigadorTres' => $project->researchers[2]->name ?? null,
            'fechaInicio' => $project->start_date,
            'fechaFin' => $project->end_date,
            'estado' => $project->status,
            'valorProyecto' => $project->value,
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

    public function countProjects()
    {
        return Project::count();
    }

    public function countProjectsByPronoun(string $pronoun): int
    {
        $programId = Program::where('career', $pronoun)->value('id');
        if (!$programId) {
            return 0;
        }
        return Project::where('program_id', $programId)->count();
    }

    public function filterProjects(array $filters)
    {
        $query = Project::with(['program', 'researchers'])
            ->when(isset($filters['id']), function ($q) use ($filters) {
                $q->where('id', $filters['id']);
            })
            ->when(isset($filters['code']), function ($q) use ($filters) {
                $q->where('code', 'LIKE', '%'.$filters['code'].'%');
            })
            ->when(isset($filters['name']), function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%'.$filters['name'].'%');
            })
            ->when(isset($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(isset($filters['start_date']), function ($q) use ($filters) {
                $q->whereDate('start_date', $filters['start_date']);
            })
            ->when(isset($filters['end_date']), function ($q) use ($filters) {
                $q->whereDate('end_date', $filters['end_date']);
            })
            ->when(isset($filters['program_id']), function ($q) use ($filters) {
                $q->where('program_id', $filters['program_id']);
            });
    
        return $query->get()->map(function ($project) {
            return $this->formatProjectForAngular($project);
        });
    }

    private function formatProjectForAngular(Project $project)
    {
        return [
            'id' => $project->id,
            'codigo' => $project->code,
            'nombreProyecto' => $project->name,
            'objetivoGeneral' => $project->objective,
            'programa' => $project->program->name ?? 'Sin programa',
            'anio' => $project->start_date ? date('Y', strtotime($project->start_date)) : 'Sin fecha',
            'procedencia' => $project->source,
            'investigadorUno' => $project->researchers[0]->name ?? 'Sin investigador',
            'investigadorDos' => $project->researchers[1]->name ?? 'Sin investigador',
            'investigadorTres' => $project->researchers[2]->name ?? 'Sin investigador',
            'fechaInicio' => $project->start_date,
            'fechaFin' => $project->end_date,
            'estado' => $project->status,
            'valorProyecto' => number_format($project->value, 2, '.', ','), 
            'cantidadProyectos' => $project->researchers->count(),
            'alerta' => $this->generateAlert($project)
        ];
    }
    
    public function getProjectsByProgramName($programName)
    {
        return Project::with('researchers')
            ->whereHas('program', function($query) use ($programName) {
                $query->where('name', 'LIKE', '%' . $programName . '%');
            })
            ->get()
            ->map(function ($project) {
                return $this->formatProjectForAngular($project);
            });
    }
}