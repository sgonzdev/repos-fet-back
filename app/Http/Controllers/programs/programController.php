<?php

namespace App\Http\Controllers\programs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Http\JsonResponse;


class programController extends Controller
{
     public function mount(): int
     {
         return Program::count();
     }

     public function get(): JsonResponse
     {
         $programs = Program::all(['id', 'name as carrera', 'career as pronombre']);
         return response()->json($programs);
     }
}
