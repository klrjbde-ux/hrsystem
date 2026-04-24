<?php

use App\Http\Controllers\SalaryIndexController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\centerController;
use App\Http\Controllers\SallaryController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\TimingController;
use App\Http\Controllers\DailyStandupController;
use App\Http\Controllers\Performance\PerformanceReviewController;
use App\Http\Controllers\Performance\AppraisalController;
use App\Http\Controllers\Performance\PerformanceReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\Auth\RegisterController; // <-- add this
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\InterviewController;


use App\Http\Controllers\EmployeeInterviewController;
use App\Http\Controllers\OfficePolicyController;


use Illuminate\Support\Facades\Artisan;

// Route::get('/link', function()
// {
// Artisan::call('storage:link');
// });


Route::get('/master', function () {
    return view('master');
});

// Route::get('/', function () {
//     return view('auth.login');
// })->name('login');

Route::post('/addemployee', [EmployeeController::class, 'store']);
Route::get('/addemployee', [EmployeeController::class, 'EmployeDetails'])->name('addemployee');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Auth::routes(['register' => false]); // disables default /register



    //Employees
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('myprofile/{employee}', [EmployeeController::class, 'show'])->name('myprofile.show');
    #Route::post('/register', 'Auth\RegisterController@register')->name('register');
    // Route::post('/register_ak', [RegisterController::class, 'register'])->name('cust_register');
    // Route::get('/register_ak', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::put('/employee/{id?}', [EmployeeController::class, 'update'])->name('employee.update');

    Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');

    Route::get('/delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');
    Route::get('accountsetting/{employee}', [EmployeeController::class, 'account_setting'])->name('accountsetting.show');


    // Route::get('/leaves', [LeaveController::class, 'totalLeaves'])->name('totalleaves');
    // Route::get('/leave/addleave', [LeaveController::class, 'addleaveform'])->name('addleaveform');

    //leave managenment
    Route::get('/ApplyLeave', [LeaveController::class, 'ApplyLeave'])->name('ApplyLeave');
    Route::post('/ApplyUnpaidLeave', [LeaveController::class, 'ApplyUnpaidLeave'])->name('ApplyUnpaidLeave');
    Route::post('/storeapplyleave', [LeaveController::class, 'storeapplyleave'])->name('storeapplyleave');
    Route::put('/LeaveRequestDenied/{id}', [LeaveController::class, 'denied'])->name('denied');
    Route::put('/LeaveRequestapprove/{id}', [LeaveController::class, 'approve'])->name('approve');
    Route::get('/employeeleavehistroy/{id}', [LeaveController::class, 'employeeleavehistroy'])->name('employeeleavehistroy.show');

    //setting/leave
    Route::get('/leaves', [LeaveController::class, 'totalLeaves'])->name('totalleaves');
    Route::get('/leave/addleave', [LeaveController::class, 'addleaveform'])->name('addleaveform');
    Route::post('/addleavedata', [LeaveController::class, 'addleavedata'])->name('addleavedata');
    Route::get('/leaveedit/{id}', [LeaveController::class, 'addleaveform'])->name('leave.editleave');
    Route::get('/leavedelete/{id}', [LeaveController::class, 'delete'])->name('leave.deleteleave');


    // *
    // *
    // leave controller
    // *#
    // *
    Route::get('/ApproveLeaves', [LeaveController::class, 'ApproveLeaves'])->name('ApproveLeaves');
    Route::get('/LeavesStatus', [LeaveController::class, 'LeavesStatus'])->name('LeavesStatus');
    Route::get('/leave/create', [LeaveController::class, 'leaveform'])->name('leaveform');
    Route::post('/leave/addleave', [LeaveController::class, 'applyleave'])->name('applyleave');
    Route::get('/leave/create', [LeaveController::class, 'leaveform'])->name('leaveform');
    // employee side
    Route::get('/LeaveReqestaprove/{id}', [LeaveController::class, 'approvereqest'])->name('aprovereqest');
    Route::post('/LeaveReqestaprove/{id}', [LeaveController::class, 'updateRequest'])->name('aprovereqest.update');



    // Sallary
    Route::get('/Salaryindex', [SallaryController::class, 'salaryindex'])->name('salary.index');
    Route::get('/Salary', [SallaryController::class, 'Store'])->name('salary.store');
    Route::get('/bonus/{id?}', [SallaryController::class, 'bonus'])->name('bonus');
    Route::post('/salary/update/{id}', [SallaryController::class, 'update'])->name('updateSalary');
    Route::get('/deduction/{id?}', [SallaryController::class, 'deduction'])->name('deduction');
    Route::post('/salary/deduction/update/{id}', [SallaryController::class, 'deductionupdate'])->name('deductionupdateSalary');
    Route::get('/deductiondelete/{id?}', [SallaryController::class, 'deductiondelete'])->name('deductiondelete');
    Route::get('/bonusdelete/{id?}', [SallaryController::class, 'bonusdelete'])->name('bonusdelete');
    Route::get('/Salary/slip123', [SallaryController::class, 'slip'])->name('slip');
    Route::get('/salary/slip/{id}/{employee_id}', [SalaryIndexController::class, 'slip'])->name('salary.slip');
    // PDF
    route::get('/employee/{id}/{employee_id}/generate-slip', [App\Http\Controllers\PdfController::class, 'generateSlipPDF'])->name('pdf');

    //Signature

    Route::get('/signature', [SignatureController::class, 'signature'])->name('signature');
    Route::get('/addsignature', [SignatureController::class, 'addsignature'])->name('addsignature');
    Route::post('/addsignaturedata', [SignatureController::class, 'addsignaturedata'])->name('addsignaturedata');
    Route::get('/deletesignature/{id?}', [SignatureController::class, 'deletesignature'])->name('deletesignature');

    // interview
    Route::resource('interviews', InterviewController::class);






    // Attedance //


    Route::get('/editattendance/{id}', [AttendanceController::class, 'edit']);
    Route::get('/viewemployeeattendance/{id}', [AttendanceController::class, 'index'])->name('show_list');
    Route::get('/therangetest', [AttendanceController::class, 'rangeindex'])->name('therange');
    Route::get('/attendance_range', fn() => redirect()->route('view'));
    Route::post('/attendance_range', [AttendanceController::class, 'range']);
    Route::post('update_attendance/{id}', [AttendanceController::class, 'update'])->name('update.attendance');
    Route::get('/delete_attendance/{id}', [AttendanceController::class, 'delete'])->name('delete.attendance');

    Route::get('/viewattendance', [AttendanceController::class, 'view'])->name('view');

    Route::get('/attendance/editable', [AttendanceController::class, 'editable'])->name('attendance.editable');
    Route::post('/attendance/editable/save', [AttendanceController::class, 'editableSave'])->name('attendance.editable.save');
    Route::delete('/attendance/editable/{id}', [AttendanceController::class, 'editableDelete'])->name('attendance.editable.delete');


    Route::post('/putattendancedata', [AttendanceController::class, 'store'])->name('putdata');
    Route::get('/addemployeeattendance', [AttendanceController::class, 'create'])->name('create');


    Route::get('/addattendance', [AttendanceController::class, 'addattendance'])->name('addattendance');
    Route::post('/saveattendancedata', [AttendanceController::class, 'saveattendancedata'])->name('saveattendancedata');



    //office timing
    Route::get('/officetimingindex', [TimingController::class, 'officetimingindex'])->name('officetimingindex');
    Route::get('/officetiming', [TimingController::class, 'officetiming'])->name('officetiming');
    Route::post('/addofficetiming', [TimingController::class, 'addofficetiming'])->name('addofficetiming');
    Route::get('/editofficetiming/{id?}', [TimingController::class, 'officetiming'])->name('editofficetiming');
    Route::get('/deleteofficetiming/{id?}', [TimingController::class, 'deleteofficetiming'])->name('deleteofficetiming');
    //Break

    Route::post('/addemployeestartbreak', [TimingController::class, 'addemployeestartbreak'])->name('addemployeestartbreak');

    // Daily Standup (Admin & HR only)
    Route::middleware(['role:admin|hr_manager'])->group(function () {
        Route::prefix('daily-standup')->group(function () {
            Route::get('/', [DailyStandupController::class, 'index'])->name('dailystandup.index');
            Route::get('/create', [DailyStandupController::class, 'create'])->name('dailystandup.create');
            Route::post('/', [DailyStandupController::class, 'store'])->name('dailystandup.store');
            Route::post('/store-ajax', [DailyStandupController::class, 'storeAjax'])->name('dailystandup.storeAjax');
            Route::get('/manage', [DailyStandupController::class, 'manage'])->name('dailystandup.manage');
            Route::get('/data', [DailyStandupController::class, 'dataList'])->name('dailystandup.data');
            Route::post('/update-ajax', [DailyStandupController::class, 'updateAjax'])->name('dailystandup.updateAjax');
            Route::post('/delete-ajax', [DailyStandupController::class, 'destroyAjax'])->name('dailystandup.deleteAjax');
            Route::get('/{id}/edit', [DailyStandupController::class, 'edit'])->name('dailystandup.edit');
            Route::post('/{id}', [DailyStandupController::class, 'update'])->name('dailystandup.update');
            Route::get('/{id}/delete', [DailyStandupController::class, 'destroy'])->name('dailystandup.delete');
        });
    });





    ///
    ///
    ///
    ///
    ///     Roles & Permissions
    ///
    ///
    ///
    route::get('Role.Create', [RolesController::class, 'show'])->name('create.Roles');
    route::post('store.routes', [RolesController::class, 'store'])->name('store.routes');


    //
    //
    //
    // CenterController
    //
    //

    route::get('center/{email}', [centerController::class, 'gotoemploye'])->name('center');

    //
    //
    //
    // SETTING CONTROLLER
    //
    //
    //
    Route::get('designations/list', [SettingController::class, 'designationlist'])->name('designationlist');
    Route::delete('designations/destroy/{designation}', [SettingController::class, 'designationdestroy'])->name('designationsdestroy');





    Route::post('add/designation', [SettingController::class, 'store'])->name('desigstore');

    Route::get('designations/form', [SettingController::class, 'form'])->name('desigform');
    Route::get('/Salaryindex', [SallaryController::class, 'salaryindex'])->name('salary.index');
    Route::post('/change-password', [EmployeeController::class, 'changePassword'])->name('changePassword');
    Route::post('profile/update',  [EmployeeController::class, 'updateProfile'])->name('profile');
    //
    //
    //
    //   Event Cylender
    //
    //
    //

    Route::get('full-calender', [FullCalenderController::class, 'index']);

    Route::post('full-calender/action', [FullCalenderController::class, 'action']);

    Route::get('/editattendance/{id}', [AttendanceController::class, 'edit']);
    Route::get('/viewemployeeattendance/{id}', [AttendanceController::class, 'index'])->name('show_list');
    Route::get('/therangetest', [AttendanceController::class, 'rangeindex'])->name('therange');
    Route::get('/attendance_range', fn() => redirect()->route('view'));
    Route::post('/attendance_range', [AttendanceController::class, 'range']);
    Route::post('update_attendance/{id}', [AttendanceController::class, 'update'])->name('update.attendance');
    Route::get('/delete_attendance/{id}', [AttendanceController::class, 'delete'])->name('delete.attendance');

    Route::get('/viewattendance', [AttendanceController::class, 'view'])->name('view');

    Route::get('/attendance/editable', [AttendanceController::class, 'editable'])->name('attendance.editable');
    Route::post('/attendance/editable/save', [AttendanceController::class, 'editableSave'])->name('attendance.editable.save');
    Route::delete('/attendance/editable/{id}', [AttendanceController::class, 'editableDelete'])->name('attendance.editable.delete');


    Route::post('/putattendancedata', [AttendanceController::class, 'store'])->name('putdata');
    Route::get('/addemployeeattendance', [AttendanceController::class, 'create'])->name('create');


    Route::get('/addattendance', [AttendanceController::class, 'addattendance'])->name('addattendance');
    Route::post('/saveattendancedata', [AttendanceController::class, 'saveattendancedata'])->name('saveattendancedata');



    //office timing
    Route::get('/officetimingindex', [TimingController::class, 'officetimingindex'])->name('officetimingindex');
    Route::get('/officetiming', [TimingController::class, 'officetiming'])->name('officetiming');
    Route::post('/addofficetiming', [TimingController::class, 'addofficetiming'])->name('addofficetiming');
    Route::get('/editofficetiming/{id?}', [TimingController::class, 'officetiming'])->name('editofficetiming');
    Route::get('/deleteofficetiming/{id?}', [TimingController::class, 'deleteofficetiming'])->name('deleteofficetiming');
    //Break

    Route::post('/addemployeestartbreak', [TimingController::class, 'addemployeestartbreak'])->name('addemployeestartbreak');

    // Daily Standup (Admin & HR only) – literal paths before {id} so AJAX routes match
    Route::middleware(['role:admin|hr_manager'])->group(function () {
        Route::get('/daily-standup', [DailyStandupController::class, 'index'])->name('dailystandup.index');
        Route::get('/daily-standup/create', [DailyStandupController::class, 'create'])->name('dailystandup.create');
        Route::post('/daily-standup', [DailyStandupController::class, 'store'])->name('dailystandup.store');
        Route::post('/daily-standup/store-ajax', [DailyStandupController::class, 'storeAjax'])->name('dailystandup.storeAjax'); // NEW
        Route::get('/daily-standup/manage', [DailyStandupController::class, 'manage'])->name('dailystandup.manage');
        Route::get('/daily-standup/data', [DailyStandupController::class, 'dataList'])->name('dailystandup.data');
        Route::post('/daily-standup/update-ajax', [DailyStandupController::class, 'updateAjax'])->name('dailystandup.updateAjax');
        Route::post('/daily-standup/delete-ajax', [DailyStandupController::class, 'destroyAjax'])->name('dailystandup.deleteAjax');
        Route::get('/daily-standup/{id}/edit', [DailyStandupController::class, 'edit'])->name('dailystandup.edit');
        Route::post('/daily-standup/{id}', [DailyStandupController::class, 'update'])->name('dailystandup.update');
        Route::get('/daily-standup/{id}/delete', [DailyStandupController::class, 'destroy'])->name('dailystandup.delete');
    });

    // Performance Management (Admin & HR only)
    Route::middleware(['role:admin|hr_manager'])->prefix('performance')->name('performance.')->group(function () {
        Route::get('/reviews', [PerformanceReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create', [PerformanceReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [PerformanceReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{id}', [PerformanceReviewController::class, 'show'])->name('reviews.show');
        Route::get('/reviews/{id}/edit', [PerformanceReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{id}', [PerformanceReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{id}', [PerformanceReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/appraisals', [AppraisalController::class, 'index'])->name('appraisals.index');
        Route::get('/appraisals/create', [AppraisalController::class, 'create'])->name('appraisals.create');
        Route::post('/appraisals', [AppraisalController::class, 'store'])->name('appraisals.store');
        Route::get('/appraisals/{id}/edit', [AppraisalController::class, 'edit'])->name('appraisals.edit');
        Route::put('/appraisals/{id}', [AppraisalController::class, 'update'])->name('appraisals.update');
        Route::delete('/appraisals/{id}', [AppraisalController::class, 'destroy'])->name('appraisals.destroy');

        Route::get('/reports', [PerformanceReportController::class, 'index'])->name('reports.index');
    });




    ///
    ///
    ///
    ///
    ///     Roles & Permissions
    ///
    ///
    ///
    route::get('Role.Create', [RolesController::class, 'show'])->name('create.Roles');
    route::post('store.routes', [RolesController::class, 'store'])->name('store.routes');


    //
    //
    //
    // CenterController
    //
    //

    route::get('center/{email}', [centerController::class, 'gotoemploye'])->name('center');

    //
    //
    //
    // SETTING CONTROLLER
    //
    //
    //
    Route::get('designations/list', [SettingController::class, 'designationlist'])->name('designationlist');
    Route::delete('designations/destroy/{designation}', [SettingController::class, 'designationdestroy'])->name('designationsdestroy');





    Route::post('add/designation', [SettingController::class, 'store'])->name('desigstore');

    Route::get('designations/form', [SettingController::class, 'form'])->name('desigform');
    Route::get('/Salaryindex', [SallaryController::class, 'salaryindex'])->name('salary.index');
    Route::post('/change-password', [EmployeeController::class, 'changePassword'])->name('changePassword');
    Route::post('profile/update',  [EmployeeController::class, 'updateProfile'])->name('profile');
    //
    //
    //
    //   Event Cylender
    //
    //
    //

    Route::get('full-calender', [FullCalenderController::class, 'index']);

    Route::post('full-calender/action', [FullCalenderController::class, 'action']);

    Route::get('eventform', [FullCalenderController::class, 'eventform'])->name('eventform');
    Route::post('eventform/store', [FullCalenderController::class, 'AddEvent'])->name('addevent');
});

Route::get('/office-policies', [OfficePolicyController::class, 'index'])->name('officepolicy.index');
Route::get('/office-policies/create', [OfficePolicyController::class, 'create'])->name('officepolicy.create');
Route::post('/office-policies', [OfficePolicyController::class, 'store'])->name('officepolicy.store');
Route::get('/{officePolicy}/edit', [OfficePolicyController::class, 'edit'])->name('officepolicy.edit');
Route::put('/{officePolicy}', [OfficePolicyController::class, 'update'])->name('officepolicy.update');
Route::delete('/{officePolicy}', [OfficePolicyController::class, 'destroy'])->name('officepolicy.destroy');


// Employee Interviews

Route::get('/employee-interviews', [EmployeeInterviewController::class, 'index'])
    ->name('employeeinterviews.index');

Route::get('/employee-interviews/create', [EmployeeInterviewController::class, 'create'])
    ->name('employeeinterviews.create');

Route::post('/employee-interviews', [EmployeeInterviewController::class, 'store'])
    ->name('employeeinterviews.store');

Route::get('/employee-interviews/{id}/edit', [EmployeeInterviewController::class, 'edit'])
    ->name('employeeinterviews.edit');

Route::put('/employee-interviews/{id}', [EmployeeInterviewController::class, 'update'])
    ->name('employeeinterviews.update');

Route::delete('/employee-interviews/{id}', [EmployeeInterviewController::class, 'destroy'])
    ->name('employeeinterviews.destroy');


// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




// Show login form
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

// Handle login submission
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Redirect root URL to login
Route::get('/', function () {
    return redirect()->route('login');
});
//  trello dashboard
Route::get('/trello', function () {
    $user = Auth::user();
    $tasksCount = $user->tasks()->count();
    $routinesCount = $user->routines()->count();
    $notesCount = $user->notes()->count();
    $remindersCount = $user->reminders()->count();
    $filesCount = $user->files()->count();
    $recentTasks = $user->tasks()->latest()->take(5)->get();
    $todayRoutines = $user->routines()->whereDate('start_time', now())->get();
    $recentNotes = $user->notes()->latest()->take(5)->get();

    $upcomingReminders = $user->reminders()->where('date', '>=', now())->orderBy('date')->take(5)->get();

    return view('dashboard_trello', compact(
        'tasksCount',
        'routinesCount',
        'notesCount',
        'remindersCount',
        'filesCount',
        'recentTasks',
        'todayRoutines',
        'recentNotes',
        'upcomingReminders'
    ));
})->name('dashboard');
Route::resource('projects', ProjectController::class);
Route::post('project/team', [ProjectController::class, 'addMember'])->name('projects.addMember');
Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');
Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');

Route::get('/tasks/{task}/history', [TaskController::class, 'history'])->name('tasks.history');

Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::post('tasks/{task}/update-status', [TaskController::class, 'updateStatus']);

Route::resource('routines', RoutineController::class)->except(['show']);
Route::get('routines/showAll', [RoutineController::class, 'showAll'])->name('routines.showAll');
Route::get('routines/daily', [RoutineController::class, 'showDaily'])->name('routines.showDaily');
Route::get('routines/weekly', [RoutineController::class, 'showWeekly'])->name('routines.showWeekly');
Route::get('routines/monthly', [RoutineController::class, 'showMonthly'])->name('routines.showMonthly');
Route::resource('files', FileController::class);
Route::resource('notes', NoteController::class);
Route::resource('reminders', ReminderController::class);
Route::resource('checklist-items', ChecklistItemController::class);
Route::get('checklist-items/{checklistItem}/update-status', [ChecklistItemController::class, 'updateStatus'])->name('checklist-items.update-status');
Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('my.tasks');



Route::get('/tasks/{task}/comments', [TaskController::class, 'getComments']);
Route::post('/tasks/{task}/comments', [TaskController::class, 'addComment']);

Route::put('/comments/{id}', [TaskController::class, 'updateComment']);
Route::delete('/comments/{id}', [TaskController::class, 'deleteComment']);

Route::get('/comments/{comment}/history', [TaskController::class, 'commentHistory']);
