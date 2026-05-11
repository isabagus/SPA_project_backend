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
        $search = $request->search;

        $mentors = Mentor::with(['user', 'classes'])
            ->latest()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    // Search Tabel Master (User)
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
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
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'name_mentor' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        Mentor::create($request->all());

        return redirect()->route('admin.mentors.index')->with('success', 'Mentor Created Successfully');
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
