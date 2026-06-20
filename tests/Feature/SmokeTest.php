<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function registrar(): User
    {
        return User::where('role', 'registrar')->firstOrFail();
    }

    private function student(): User
    {
        return User::where('role', 'student')->firstOrFail();
    }

    public function test_student_pages_render(): void
    {
        $user = $this->student();

        foreach ([
            '/student/dashboard',
            '/student/enroll',
            '/student/enrollment/status',
            '/student/section',
            '/student/subjects',
            '/student/records',
        ] as $uri) {
            $this->actingAs($user)->get($uri)->assertOk();
        }
    }

    public function test_registrar_pages_render(): void
    {
        $user = $this->registrar();
        $enrollment = Enrollment::first();
        $student = Student::first();
        $section = Section::first();
        $subject = Subject::first();

        $uris = [
            '/registrar/dashboard',
            '/registrar/semester',
            '/registrar/enrollments',
            '/registrar/enrollments?status=pending',
            '/registrar/enrollments/'.$enrollment->id,
            '/registrar/students',
            '/registrar/students/'.$student->id,
            '/registrar/records/'.$student->id,
            '/registrar/sections',
            '/registrar/sections/create',
            '/registrar/sections/'.$section->id.'/edit',
            '/registrar/subjects',
            '/registrar/subjects/create',
            '/registrar/subjects/'.$subject->id.'/edit',
        ];

        foreach ($uris as $uri) {
            $this->actingAs($user)->get($uri)->assertOk();
        }
    }

    public function test_approved_enrollment_has_grade_form(): void
    {
        $user = $this->registrar();
        $approved = Enrollment::where('status', 'approved')->first();

        $this->actingAs($user)->get('/registrar/enrollments/'.$approved->id.'/grades')->assertOk();
    }
}
