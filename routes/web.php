<?php

use App\Http\Controllers\Auth\FirstPasswordController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Registrar\ApplicationController as RegistrarApplication;
use App\Http\Controllers\Registrar\DashboardController as RegistrarDashboard;
use App\Http\Controllers\Registrar\EnrollmentController as RegistrarEnrollment;
use App\Http\Controllers\Registrar\GradeController as RegistrarGrade;
use App\Http\Controllers\Registrar\SectionController as RegistrarSection;
use App\Http\Controllers\Registrar\SemesterController as RegistrarSemester;
use App\Http\Controllers\Registrar\SemesterRecordController as RegistrarSemesterRecord;
use App\Http\Controllers\Registrar\StudentController as RegistrarStudent;
use App\Http\Controllers\Registrar\SubjectController as RegistrarSubject;
use App\Http\Controllers\Student\ApplicationController as StudentApplication;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollment;
use App\Http\Controllers\Student\RecordController as StudentRecord;
use App\Http\Controllers\Student\SectionController as StudentSection;
use App\Http\Controllers\Student\SubjectController as StudentSubject;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [TestController::class, 'startPage'])->name('landing');

Route::middleware('auth')->group(function () {
    Route::get('/first-password', [FirstPasswordController::class, 'show'])->name('password.first');
    Route::post('/first-password', [FirstPasswordController::class, 'update'])->name('password.first.update');
});

Route::middleware(['auth', 'mustchange'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/documents/application/{document}', [DocumentController::class, 'application'])->name('documents.application');
    Route::get('/documents/enrollment/{document}', [DocumentController::class, 'enrollment'])->name('documents.enrollment');
});

Route::get('/two-factor-challenge', [TwoFactorController::class, 'showChallenge'])->name('two-factor.showChallenge');
Route::post('/two-factor-challenge', [TwoFactorController::class, 'postChallenge'])->name('two-factor.postChallenge');

Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/application', [StudentApplication::class, 'show'])->name('application.show');
    Route::post('/application/save', [StudentApplication::class, 'save'])->name('application.save');
    Route::post('/application/submit', [StudentApplication::class, 'submit'])->name('application.submit');
    Route::get('/application/status', [StudentApplication::class, 'status'])->name('application.status');
});

Route::group(['prefix' => 'student', 'as' => 'student.', 'middleware' => ['auth', 'verified', 'admitted', 'mustchange', 'role:student']], function () {
    Route::get('/dashboard', [StudentDashboard::class, 'showDashboard'])->name('showDashboard');

    Route::get('/enroll', [StudentEnrollment::class, 'showEnrollForm'])->name('showEnrollForm');
    Route::post('/enroll', [StudentEnrollment::class, 'postEnrollForm'])->name('postEnrollForm');
    Route::get('/enrollment/status', [StudentEnrollment::class, 'showEnrollStatus'])->name('showEnrollStatus');
    Route::get('/enrollment/certificate', [StudentEnrollment::class, 'showCertificate'])->name('showCertificate');

    Route::get('/section', [StudentSection::class, 'showSection'])->name('showSection');
    Route::get('/schedule', [StudentSection::class, 'showSchedule'])->name('showSchedule');
    Route::get('/subjects', [StudentSubject::class, 'showSubjects'])->name('showSubjects');
    Route::get('/records', [StudentRecord::class, 'showRecords'])->name('showRecords');
});

Route::group(['prefix' => 'registrar', 'as' => 'registrar.', 'middleware' => ['auth', 'verified', 'role:registrar']], function () {
    Route::get('/dashboard', [RegistrarDashboard::class, 'showDashboard'])->name('showDashboard');

    Route::group(['prefix' => 'semester', 'as' => 'semester.'], function () {
        Route::get('/', [RegistrarSemester::class, 'index'])->name('index');
        Route::post('/', [RegistrarSemester::class, 'store'])->name('store');
        Route::patch('/{schoolYear}/activate', [RegistrarSemester::class, 'activate'])->name('activate');
        Route::patch('/{schoolYear}/set-semester', [RegistrarSemester::class, 'setSemester'])->name('setSemester');
        Route::patch('/{schoolYear}/toggle-enrollment', [RegistrarSemester::class, 'toggleEnrollment'])->name('toggleEnrollment');
        Route::post('/{schoolYear}/finalize', [RegistrarSemester::class, 'finalize'])->name('finalize');
    });

    Route::get('/applications', [RegistrarApplication::class, 'showApplications'])->name('showApplications');
    Route::get('/applications/{application}', [RegistrarApplication::class, 'showApplication'])->name('showApplication');
    Route::post('/applications/{application}/return', [RegistrarApplication::class, 'returnApplication'])->name('returnApplication');
    Route::post('/applications/{application}/qualify', [RegistrarApplication::class, 'qualifyApplication'])->name('qualifyApplication');
    Route::post('/applications/{application}/designate', [RegistrarApplication::class, 'designateApplication'])->name('designateApplication');

    Route::get('/enrollments', [RegistrarEnrollment::class, 'showEnrollments'])->name('showEnrollments');
    Route::post('/enrollments/batch-approve', [RegistrarEnrollment::class, 'batchApprove'])->name('batchApproveEnrollments');
    Route::get('/enrollments/{enrollment}', [RegistrarEnrollment::class, 'showEnrollment'])->name('showEnrollment');
    Route::post('/enrollments/{enrollment}/approve', [RegistrarEnrollment::class, 'approveEnrollment'])->name('approveEnrollment');
    Route::post('/enrollments/{enrollment}/reject', [RegistrarEnrollment::class, 'rejectEnrollment'])->name('rejectEnrollment');
    Route::post('/enrollments/{enrollment}/revert', [RegistrarEnrollment::class, 'revertEnrollment'])->name('revertEnrollment');

    Route::get('/enrollments/{enrollment}/grades', [RegistrarGrade::class, 'show'])->name('showGradeForm');
    Route::put('/enrollments/{enrollment}/grades', [RegistrarGrade::class, 'update'])->name('updateGrades');

    Route::get('/students', [RegistrarStudent::class, 'showStudents'])->name('showStudents');
    Route::get('/students/{student}', [RegistrarStudent::class, 'showStudent'])->name('showStudent');

    Route::group(['prefix' => 'sections', 'as' => 'sections.'], function () {
        Route::get('/', [RegistrarSection::class, 'showSections'])->name('showSections');
        Route::get('/create', [RegistrarSection::class, 'showCreateSection'])->name('showCreateSection');
        Route::post('/', [RegistrarSection::class, 'postCreateSection'])->name('postCreateSection');
        Route::get('/{section}/schedule', [RegistrarSection::class, 'showSchedule'])->name('showSchedule');
        Route::post('/{section}/schedule', [RegistrarSection::class, 'generateSchedule'])->name('generateSchedule');
        Route::get('/{section}', [RegistrarSection::class, 'showSection'])->name('showSection');
        Route::get('/{section}/edit', [RegistrarSection::class, 'showEditSection'])->name('showEditSection');
        Route::put('/{section}', [RegistrarSection::class, 'updateSection'])->name('updateSection');
        Route::delete('/{section}', [RegistrarSection::class, 'deleteSection'])->name('deleteSection');
    });

    Route::group(['prefix' => 'subjects', 'as' => 'subjects.'], function () {
        Route::get('/', [RegistrarSubject::class, 'showSubjects'])->name('showSubjects');
        Route::get('/create', [RegistrarSubject::class, 'showCreateSubject'])->name('showCreateSubject');
        Route::post('/', [RegistrarSubject::class, 'postCreateSubject'])->name('postCreateSubject');
        Route::get('/{subject}', [RegistrarSubject::class, 'showSubject'])->name('showSubject');
        Route::get('/{subject}/edit', [RegistrarSubject::class, 'showEditSubject'])->name('showEditSubject');
        Route::put('/{subject}', [RegistrarSubject::class, 'updateSubject'])->name('updateSubject');
        Route::delete('/{subject}', [RegistrarSubject::class, 'deleteSubject'])->name('deleteSubject');
    });

    Route::get('/records/{student}', [RegistrarSemesterRecord::class, 'showSemesterRecord'])->name('showSemesterRecord');
    Route::put('/records/{student}', [RegistrarSemesterRecord::class, 'updateSemesterRecord'])->name('updateSemesterRecord');
});

Route::get('/dashboard', function (Request $request) {
    $role = $request->user()->role;
    if ($role === 'student') {
        return redirect()->route('student.showDashboard');
    } elseif ($role === 'registrar') {
        return redirect()->route('registrar.showDashboard');
    }

    return redirect('/');
})->middleware(['auth', 'verified', 'admitted', 'mustchange'])->name('dashboard');

require __DIR__.'/auth.php';
