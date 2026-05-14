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
                return $query->where('name', 'like', "%{$keyword}%")
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

        \DB::transaction(function () use ($request) {
            $userId = $request->user_id;

            if (!$userId) {
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => \Hash::make($request->password),
                    'role' => 'mentor',
                ]);
                $userId = $user->user_id;
            }

            Mentor::create([
                'user_id' => $userId,
                'name' => $request->name_mentor, // Mentor model uses 'name' or 'name_mentor'? Let's check model.
                'name_mentor' => $request->name_mentor,
                'nip' => $request->nip,
                'phone_number' => $request->phone_number,
            ]);
        });

        return redirect()->route('admin.mentors.index')->with('success', 'Mentor Created Successfully with user account.');
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
                'class_id' => 'required|string|max:255',
                'mentor_id' => 'required|string|max:255',
            ]);

            // dd($validated);

            $currentMentor = Mentor::with('classes')->where('mentor_id', $validated['mentor_id'])->first();

            // dd($currentMentor);
            foreach ($currentMentor->classes as $key => $class) {
                $class->mentor_id = null;
                $class->save();
            }

            $class = LevelClass::findOrFail($validated['class_id']);
            $class->mentor_id = null;
            $class->mentor_id = $validated['mentor_id'];
            $class->save();

            return redirect()->route('admin.mentors.index')->with('success', 'Mentor Set Successfully');
        } catch (Throwable $th) {
            return redirect()->route('admin.mentors.index')->with('error', $th->getMessage());
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
        $mentor->delete();

        return redirect()->route('admin.mentors.index')->with('success', 'Mentor Deleted Successfully');
    }
}
