<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar data students',
            'data'    => $students
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        return response()->json([
            'success' => true,
            'message' => 'Endpoint store student siap digunakan'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data student tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data student',
            'data'    => $student
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Contoh implementasi update
        return response()->json([
            'success' => true,
            'message' => 'Endpoint update student siap digunakan'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Contoh implementasi destroy
        return response()->json([
            'success' => true,
            'message' => 'Endpoint destroy student siap digunakan'
        ], 200);
    }
}
