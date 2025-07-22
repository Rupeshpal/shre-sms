<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController as AuthV1Controller;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\NoticeController;
use App\Http\Controllers\Api\V1\EventCalendarController;
use App\Http\Controllers\Api\V1\Subject\SubjectController;
use App\Http\Controllers\Api\V1\Section\SectionController;
use App\Http\Controllers\Api\V1\Assignment\AssignmentController;
use App\Http\Controllers\Api\V1\Teacher\TeacherController;
use App\Http\Controllers\Api\V1\Teacher\TeacherRoutineController;
use App\Http\Controllers\Api\V1\Teacher\TeacherClassController;
use App\Http\Controllers\Api\V1\Teacher\TeacherSubjectController;
use App\Http\Controllers\Api\V1\Teacher\TeacherSocialLinkController;
use App\Http\Controllers\Api\V1\Teacher\TeacherBankDetailController;
use App\Http\Controllers\Api\V1\Teacher\TeacherLeaveInfoController;
use App\Http\Controllers\Api\V1\Teacher\TeacherAttendanceController;
use App\Http\Controllers\Api\V1\Teacher\TeacherLeaveRequestController;
use App\Http\Controllers\Api\V1\Parent\ParentController;
use App\Http\Controllers\Api\V1\AcademicYearController;
use App\Http\Controllers\Api\V1\Student\StudentPersonalInfoController;
use App\Http\Controllers\Api\V1\Student\StudentFatherInfoController;
use App\Http\Controllers\Api\V1\Student\StudentMotherInfoController;
use App\Http\Controllers\Api\V1\Student\StudentGuardianInfoController;
use App\Http\Controllers\Api\V1\Student\StudentSiblingController;
use App\Http\Controllers\Api\V1\Student\StudentAddressController;
use App\Http\Controllers\Api\V1\Student\StudentTransportController;
use App\Http\Controllers\Api\V1\Student\PreviousSchoolDetailController;
use App\Http\Controllers\Api\V1\Student\StudentLeaveRequestController;
use App\Http\Controllers\Api\V1\Exam\ExamResultController;
use App\Http\Controllers\Api\V1\Exam\ExamAttendanceController;
use App\Http\Controllers\Api\V1\Exam\TestResultController;
use App\Http\Controllers\Api\V1\Exam\TestResultSubjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| assigned the "api" middleware group.
|
*/


Route::prefix('v1')->group(function () {

    Route::post('/register', action: [AuthV1Controller::class, 'register']);
    Route::post('/login',    [AuthV1Controller::class, 'login']);

    Route::middleware('auth:sanctum')->group(callback: function () {
        Route::post('/logout', [AuthV1Controller::class, 'logout']);
        Route::get('/me',      [AuthV1Controller::class, 'me']);

        // Resource routes
        Route::apiResource('events',               EventController::class);
        Route::apiResource('notices',              NoticeController::class);
        Route::apiResource('event-calendar',       EventCalendarController::class);
        Route::apiResource('subjects',             SubjectController::class);
        Route::apiResource('sections',             SectionController::class);
        Route::apiResource('assignments',          AssignmentController::class);
        Route::apiResource('teachers',             TeacherController::class);
        Route::apiResource('teacher-routines',     TeacherRoutineController::class);
        Route::apiResource('teacher-classes',      TeacherClassController::class);
        Route::apiResource('teacher-subjects',     TeacherSubjectController::class);
        Route::apiResource('teacher-social-links', TeacherSocialLinkController::class);
        Route::apiResource('teacher-bank-details', TeacherBankDetailController::class);
        Route::apiResource('teacher-leave-infos', TeacherLeaveInfoController::class);
        Route::apiResource('teacher-attendances', TeacherAttendanceController::class);
        Route::apiResource('teacher-leave-requests', TeacherLeaveRequestController::class);
        Route::apiResource('parents', ParentController::class);
        Route::apiResource('academic-years', AcademicYearController::class);
        Route::apiResource('student-personal-info', StudentPersonalInfoController::class);
        Route::apiResource('student-father-info', StudentFatherInfoController::class);
        Route::apiResource('student-mother-info', StudentMotherInfoController::class);
        Route::apiResource('student-guardian-info', StudentGuardianInfoController::class);
        Route::apiResource('student-siblings', StudentSiblingController::class);
        Route::apiResource('student-transports', StudentTransportController::class);
        Route::apiResource('student-addresses', StudentAddressController::class);
        Route::apiResource('previous-school-details', PreviousSchoolDetailController::class);
        Route::apiResource('student-leave-requests', StudentLeaveRequestController::class);
        Route::apiResource('exams', \App\Http\Controllers\Api\V1\Exam\ExamController::class);
        Route::apiResource('exam-results', ExamResultController::class);
        Route::apiResource('exam-attendances', ExamAttendanceController::class);
        Route::apiResource('exam-stats', App\Http\Controllers\Api\V1\Exam\ExamStatController::class);
        Route::apiResource('test-results', TestResultController::class);
        Route::apiResource('test-result-subjects', TestResultSubjectController::class);

    });
});

require __DIR__.'/auth.php';
