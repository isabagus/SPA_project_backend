<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\PaginationServiceProvider;
use App\Models\User;
use App\Models\Parents;
use App\Models\Student;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index()
    {
        $parents = Parents::with(['user', 'student'])->latest()->paginate(10);
        return view('layouts.parents.index', compact('parents'));
    }

    public function create()
    {
        $usersParent = User::where('role', 'parent')->doesntHave('parent')->get();
        $students = Student::all();
        
        return view('layouts.parents.create', compact('usersParent', 'students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:parents,user_id',
            'student_id' => 'required|exists:students,student_id',
            'name_parent' => 'required|string|max:150',
        ]);

        Parents::create($data);

        return redirect()->route('admin.parents.index')->with('success', 'Parent profile created successfully.');
    }

    public function edit($id)
    {
        $parent = Parents::findOrFail($id);

        $usersParent = User::where('role', 'parent')
            ->where(function($query) use ($parent) {
                $query->doesntHave('parent')
                      ->orWhere('user_id', $parent->user_id);
            })->get();
            
        $students = Student::all();

        return view('layouts.parents.edit', compact('parent', 'usersParent', 'students'));
    }


    public function update(Request $request, $id)
    {
        $parent = Parents::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'required|exists:users,user_id|unique:parents,user_id,' . $parent->parent_id . ',parent_id',
            'student_id' => 'required|exists:students,student_id',
            'name_parent' => 'required|string|max:150',
        ]);

        $parent->update($data);

        return redirect()->route('admin.parents.index')->with('success', 'Parent profile updated successfully.');
    }

    public function destroy($id)
    {
        $parent = Parents::findOrFail($id);
        $parent->delete();

        return redirect()->route('admin.parents.index')->with('success', 'Parent profile deleted successfully.');
    }
}
