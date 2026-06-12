<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Mentor;
use App\Models\LevelClass;
use App\Models\AcademicYear;
use App\Models\Religion;
use App\Models\Subject;
use App\Models\Parents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->admin = User::create([
            'username' => 'admin_test',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Setup Master Data
        AcademicYear::create(['academic_year' => '2023/2024']);
        Religion::create(['religion_name' => 'Christian']);
        DB::table('terms')->insert(['term' => 'Term 1']);
        DB::table('categories_subject')->insert(['category_subject' => 'Mathematics']);
    }

    /** @test */
    public function test_it_can_create_mentor_and_assign_to_class()
    {
        $mentorData = [
            'name_mentor' => 'Sarah Smith',
            'nip' => 'PIAGET-M-001',
            'phone_number' => '08123456789',
            'username' => 'smith_mentor',
            'email' => 'smith@piaget.com',
            'password' => 'password123',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.mentors.store'), $mentorData);

        $response->assertRedirect(route('admin.mentors.index'));
        $this->assertDatabaseHas('mentors', ['name_mentor' => 'Sarah Smith']);
        $this->assertDatabaseHas('users', ['email' => 'smith@piaget.com', 'role' => 'mentor']);

        // Test Assign Mentor to Class
        $mentor = Mentor::where('name_mentor', 'Sarah Smith')->first();
        $class = LevelClass::create([
            'level_name' => 'Year 1',
            'section_name' => '-',
            'level_class' => 'Year 1',
        ]);

        $assignData = [
            'mentor_id' => $mentor->mentor_id,
            'class_id' => $class->class_id,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.mentors.updateSetClass'), $assignData);
        
        $response->assertRedirect(route('admin.mentors.index'));
        $this->assertDatabaseHas('classes', [
            'class_id' => $class->class_id,
            'mentor_id' => $mentor->mentor_id
        ]);
    }

    /** @test */
    public function test_it_can_create_student_bulk()
    {
        // Setup Mentor and Class first
        $mentor = Mentor::create([
            'user_id' => User::create(['username' => 'm1', 'email' => 'm1@m.com', 'password' => 'p', 'role' => 'mentor'])->user_id,
            'name_mentor' => 'Mentor A',
            'phone_number' => '123'
        ]);
        $class = LevelClass::create(['level_name' => 'Y1', 'level_class' => 'Year 1', 'mentor_id' => $mentor->mentor_id]);

        $studentData = [
            'academic_year' => ['2023/2024'],
            'level_class' => ['Year 1'],
            'religion_name' => ['Christian'],
            'nis' => ['NIS-001'],
            'name_student' => ['Alexander Junior'],
            'gender' => ['Male'],
            'address' => ['Piaget Street'],
            'phone_number' => ['0812345'],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.students.store'), $studentData);

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('students', [
            'name_student' => 'Alexander Junior',
            'nis' => 'NIS-001',
            'mentor_id' => $mentor->mentor_id
        ]);
    }

    /** @test */
    public function test_it_can_create_teacher_and_subject()
    {
        // 1. Create Teacher
        $teacherData = [
            'name' => 'Michael Lee', 
            'phone_number' => '082211223344',
            'username' => 'lee_teacher',
            'email' => 'lee@piaget.com',
            'password' => 'password123',
        ];

        $this->actingAs($this->admin)->post(route('admin.teachers.store'), $teacherData);
        $teacher = Teacher::where('name', 'Michael Lee')->first();

        // 2. Create Subject with Rubrics and Criteria
        $class = LevelClass::create(['level_name' => 'Y1', 'level_class' => 'Year 1']);

        $subjectData = [
            'category_subject' => 'Mathematics',
            'term' => 'Term 1',
            'class_id' => $class->class_id,
            'rubrics' => [
                [
                    'name' => 'Algebra Knowledge',
                    'teacher_id' => $teacher->teacher_id,
                    'criteria' => [
                        ['name' => 'Can solve linear equations'],
                        ['name' => 'Understand variables'],
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.subjects.store'), $subjectData);

        $response->assertRedirect(route('admin.subjects.index'));
        $this->assertDatabaseHas('subjects', ['category_subject' => 'Mathematics']);
        $this->assertDatabaseHas('rubric_categories', ['rubric_name' => 'Algebra Knowledge']);
        $this->assertDatabaseHas('rubric_criteria', ['criteria_name' => 'Can solve linear equations']);
    }

    /** @test */
    public function test_it_can_delete_student()
    {
        $mentor = Mentor::create([
            'user_id' => User::create(['username' => 'm3', 'email' => 'm3@m.com', 'password' => 'p', 'role' => 'mentor'])->user_id,
            'name_mentor' => 'Mentor C'
        ]);
        $class = LevelClass::create(['level_name' => 'Y3', 'level_class' => 'Year 3', 'mentor_id' => $mentor->mentor_id]);
        $student = Student::create([
            'name_student' => 'Delete Me',
            'nis' => 'NIS-DELETE',
            'academic_year' => '2023/2024',
            'class_id' => $class->class_id,
            'level_class' => 'Year 3',
            'religion_name' => 'Christian',
            'mentor_id' => $mentor->mentor_id,
            'gender' => 'Male',
            'address' => '-',
            'phone_number' => '000'
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.students.destroy', $student->student_id));

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseMissing('students', ['student_id' => $student->student_id]);
    }

    /** @test */
    public function test_it_can_create_parent_and_link_to_student()
    {
        // Setup Student
        $mentor = Mentor::create([
            'user_id' => User::create(['username' => 'm2', 'email' => 'm2@m.com', 'password' => 'p', 'role' => 'mentor'])->user_id,
            'name_mentor' => 'Mentor B'
        ]);
        $class = LevelClass::create(['level_name' => 'Y2', 'level_class' => 'Year 2', 'mentor_id' => $mentor->mentor_id]);
        $student = Student::create([
            'name_student' => 'John Doe',
            'nis' => 'NIS-999',
            'academic_year' => '2023/2024',
            'class_id' => $class->class_id,
            'level_class' => 'Year 2',
            'religion_name' => 'Christian',
            'mentor_id' => $mentor->mentor_id,
            'gender' => 'Male',
            'address' => '-',
            'phone_number' => '000'
        ]);

        $parentData = [
            'name_parent' => 'Mr. Doe Senior',
            'email' => 'parent@gmail.com',
            'username' => 'parent_test',
            'password' => 'password123',
            'student_id' => $student->student_id,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.parents.store'), $parentData);

        $response->assertRedirect(route('admin.parents.index'));
        $this->assertDatabaseHas('parents', [
            'name_parent' => 'Mr. Doe Senior',
            'student_id' => $student->student_id
        ]);
        $this->assertDatabaseHas('users', ['email' => 'parent@gmail.com', 'role' => 'parent']);
    }
}
