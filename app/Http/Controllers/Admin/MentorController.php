<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mentor;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = Mentor::with('user')->get();
        return view('layouts.mentors.index', compact('mentors'));
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
