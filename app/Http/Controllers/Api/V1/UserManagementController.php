<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MentorResource;
use App\Http\Resources\V1\ParentResource;
use App\Http\Resources\V1\TeacherResource;
use App\Models\Mentor;
use App\Models\Parents;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Mengambil data berdasarkan tipe user (mentor, teacher, parent)
     */
    public function index(Request $request)
    {
        $type = $request->query('type');

        switch ($type) {
            case 'mentor':
                return MentorResource::collection(Mentor::with('user')->get());

            case 'teacher':
                return TeacherResource::collection(Teacher::with('user')->get());

            case 'parent':
                return ParentResource::collection(Parents::with(['user', 'student'])->get());

            default:
                return response()->json(['message' => 'Invalid type. Use mentor, teacher, or parent.'], 400);
        }
    }

    /**
     * Simpan User baru (Mentor, Teacher, atau Parent)

    /**
     * Menampilkan detail satu user
     */
    public function show($id, Request $request)
    {
        $type = $request->query('type');

        if ($type === 'mentor') return new MentorResource(Mentor::with('user')->findOrFail($id));
        if ($type === 'teacher') return new TeacherResource(Teacher::with('user')->findOrFail($id));
        if ($type === 'parent') return new ParentResource(Parents::with(['user', 'student'])->findOrFail($id));

        return response()->json(['message' => 'Resource not found'], 404);
    }

    /**
     * Update User & Details
     */
    public function update(Request $request, $id)
    {
        $type = $request->input('type');

        return DB::transaction(function () use ($request, $id, $type) {
            if ($type === 'mentor') $detail = Mentor::with('user')->findOrFail($id);
            elseif ($type === 'teacher') $detail = Teacher::with('user')->findOrFail($id);
            elseif ($type === 'parent') $detail = Parents::with('user')->findOrFail($id);
            else return response()->json(['message' => 'Invalid type'], 400);

            $user = $detail->user;

            // Update User
            $userData = $request->validate([
                'username' => ['sometimes', 'string', Rule::unique('users')->ignore($user->user_id, 'user_id')],
                'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->user_id, 'user_id')],
                'password' => 'sometimes|string|min:6',
            ]);

            if (isset($userData['password'])) $userData['password'] = Hash::make($userData['password']);
            $user->update($userData);

            // Update Details
            if ($type === 'mentor') {
                $detail->update($request->validate(['name_mentor' => 'sometimes', 'nip' => 'sometimes', 'phone_number' => 'sometimes']));
                return new MentorResource($detail->fresh('user'));
            } 
            if ($type === 'teacher') {
                $detail->update($request->validate(['name' => 'sometimes', 'phone_number' => 'sometimes']));
                return new TeacherResource($detail->fresh('user'));
            }
            if ($type === 'parent') {
                $detail->update($request->validate(['name_parent' => 'sometimes', 'student_id' => 'sometimes']));
                return new ParentResource($detail->fresh(['user', 'student']));
            }
        });
    }

    /**
     * Hapus User & Details
     */
    public function destroy($id, Request $request)
    {
        $type = $request->query('type');

        return DB::transaction(function () use ($id, $type) {
            if ($type === 'mentor') $detail = Mentor::findOrFail($id);
            elseif ($type === 'teacher') $detail = Teacher::findOrFail($id);
            elseif ($type === 'parent') $detail = Parents::findOrFail($id);
            else return response()->json(['message' => 'Invalid type'], 400);

            $user = $detail->user;
            $detail->delete();
            if ($user) $user->delete();

            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        });
    }
}
