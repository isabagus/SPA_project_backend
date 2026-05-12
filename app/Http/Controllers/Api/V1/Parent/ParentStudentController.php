<?php

namespace App\Http\Controllers\Api\V1\Parent;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentStudentController extends Controller
{
    /**
     * Ambil daftar murid (anak) yang terhubung dengan akun Parent yang login.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil semua data dari tabel parents yang user_id-nya sesuai dengan parent yang login
        $parentRelations = Parents::where('user_id', $user->user_id)
            ->with(['student'])
            ->get();

        // Transformasi data agar langsung mengembalikan array objek student
        $children = $parentRelations->map(function ($relation) {
            return $relation->student;
        });

        return response()->json([
            'success' => true,
            'data' => $children
        ]);
    }
}
