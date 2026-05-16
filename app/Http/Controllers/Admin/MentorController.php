<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Throwable;
use App\Models\User;
use App\Models\Mentor;
use App\Models\LevelClass;
use Illuminate\Pagination\PaginationServiceProvider;

class MentorController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;

        $mentors = Mentor::with(['user', 'classes'])
            ->latest()
            ->when($keyword, function ($query, $keyword) {
                return $query->where('name_mentor', 'like', "%{$keyword}%")
                    ->orWhere('nip', 'like', "%{$keyword}%")
                    ->orWhere('phone_number', 'like', "%{$keyword}%")
                    // Search Tabel Master (User)
                    ->orWhereHas('user', function ($q) use ($keyword) {
                        $q->where('email', 'like', "%{$keyword}%")
                            ->orWhere('username', 'like', "%{$keyword}%");
                    });
            })
            ->paginate(10)
            ->withQueryString();

        $classes = LevelClass::all();

        return view('layouts.mentors.index', compact('mentors', 'classes'));
    }

    public function create()
    {
        $usersMentor = User::where('role', 'mentor')->doesntHave('mentor')->get();
        return view('layouts.mentors.create', compact('usersMentor'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name_mentor' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ];

        if (!$request->user_id) {
            $rules['username'] = 'required|string|max:255|unique:users,username';
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['user_id'] = 'required|exists:users,user_id';
        }

        $request->validate($rules);

        \DB::beginTransaction();
        try {
            $userId = $request->user_id;

            if (!$userId) {
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => \Hash::make($request->password),
                    'role' => 'mentor',
                    'phone_number' => $request->phone_number,
                ]);
                $userId = $user->user_id;
            }

            Mentor::create([
                'user_id' => $userId,
                'name_mentor' => $request->name_mentor,
                'nip' => $request->nip,
                'phone_number' => $request->phone_number,
            ]);

            \DB::commit();
            return redirect()->route('admin.mentors.index')->with('success', 'Mentor Created Successfully with user account.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat mentor: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $mentor = Mentor::findOrFail($id);
        $usersMentor = User::where('role', 'mentor')->doesntHave('mentor')
            ->orWhere('user_id', $mentor->user_id)
            ->get();

        return view('layouts.mentors.edit', compact('mentor', 'usersMentor'));
    }

    public function show($id)
    {
        $mentor = Mentor::findOrFail($id);
        $usersMentor = User::where('role', 'mentor')->doesntHave('mentor')
            ->orWhere('user_id', $mentor->user_id)
            ->get();

        return view('layouts.mentors.edit', compact('mentor', 'usersMentor'));
    }

    public function showSetClassView()
    {
        try {
            $mentors = Mentor::all();
            $classes = LevelClass::all();

            return view('layouts.mentors.set-class', compact('classes', 'mentors'));
        } catch (Throwable $th) {
            return redirect()->route('admin.mentors.index')->with('error', $th->getMessage());
        }
    }

    public function updateSetClass(Request $request)
    {
        try {
            $validated = $request->validate([
                'class_id' => 'required|integer|exists:classes,class_id',
                'mentor_id' => 'required|integer|exists:mentors,mentor_id',
            ]);

            $mentorId = $validated['mentor_id'];
            $classId = $validated['class_id'];

            // 1. Reset class_id yang sebelumnya diampu oleh mentor ini (jika sistem 1 mentor 1 kelas)
            LevelClass::where('mentor_id', $mentorId)->update(['mentor_id' => null]);

            // 2. Set mentor baru ke class yang dipilih
            $class = LevelClass::findOrFail($classId);
            $class->mentor_id = $mentorId;
            $class->save();

            return redirect()->route('admin.mentors.index')->with('success', 'Mentor assigned to class successfully.');
        } catch (Throwable $th) {
            return redirect()->route('admin.mentors.index')->with('error', 'Error: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'name_mentor' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $mentor = Mentor::findOrFail($id);
        $mentor->update($request->all());

        return redirect()->route('admin.mentors.index')->with('success', 'Mentor Updated Successfully');
    }

    public function destroy($id)
    {
        $mentor = Mentor::findOrFail($id);
        
        \DB::transaction(function () use ($mentor) {
            // Unassign mentor from classes first to avoid foreign key constraint error
            \App\Models\LevelClass::where('mentor_id', $mentor->mentor_id)
                                 ->update(['mentor_id' => null]);

            $user = $mentor->user;
            $mentor->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.mentors.index')->with('success', 'Mentor and associated User account deleted successfully.');
    }
}
