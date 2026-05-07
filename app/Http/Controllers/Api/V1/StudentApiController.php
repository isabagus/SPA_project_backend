<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{

    public function index()
    {
        $students = Student::all();
        return response()->json([
            'message' => 'Student Data List',
            'success' => true,
            'data' => StudentResource::collection($students),

        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'academic_year' => 'required',
                'level_class'   => 'required',
                'religion_name' => 'required',
                'name_student'  => 'required',
                'gender'        => 'required',
                'address'       => 'required',
                'phone_number'  => 'required',
                'mentor_id'     => 'nullable' 
            ]);

            $student = Student::create($data);

            return response()->json([
                'success' => true,
                'data'    => new StudentResource($student)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail data student',
            'data'    => new StudentResource($student),
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        
    }
    
    public function destroy(string $id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint destroy student siap digunakan'
        ], 200);
    }
}
