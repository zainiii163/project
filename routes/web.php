<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminLessonController;
use App\Http\Controllers\Admin\AdminQuizController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminDiscussionController;
use App\Http\Controllers\Admin\AdminCertificateController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Teacher\TeacherCourseController;
use App\Http\Controllers\Student\StudentCommunityController;
use App\Http\Controllers\Student\StudentPaymentController;
use App\Http\Controllers\Teacher\TeacherLessonController;
use App\Http\Controllers\Teacher\TeacherQuizController;
use App\Http\Controllers\Teacher\TeacherAssignmentController;
use App\Http\Controllers\Teacher\TeacherDiscussionController;
use App\Http\Controllers\Student\StudentCourseController;
use App\Http\Controllers\Student\StudentProgressController;
use App\Http\Controllers\Student\StudentAssignmentController;
use App\Http\Controllers\Student\StudentQuizController;
use App\Http\Controllers\Student\StudentCertificateController;
use App\Http\Controllers\Student\StudentReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public Course Routes
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')
        ->middleware('role:super_admin,admin');

    // User Management (Super Admin/Admin Only)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('/admin/users/{user}/approve', [UserController::class, 'approve'])->name('admin.users.approve');
        Route::post('/admin/users/{user}/suspend', [UserController::class, 'suspend'])->name('admin.users.suspend');
        Route::post('/admin/users/{user}/activate', [UserController::class, 'activate'])->name('admin.users.activate');
        Route::post('/admin/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
        Route::post('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::post('/admin/users/{user}/force-password-update', [UserController::class, 'forcePasswordUpdate'])->name('admin.users.force-password-update');
        Route::get('/admin/users/{user}/activity-logs', [UserController::class, 'activityLogs'])->name('admin.users.activity-logs');
        Route::post('/admin/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('admin.users.assign-role');
        Route::post('/admin/users/bulk-import', [UserController::class, 'bulkImport'])->name('admin.users.bulk-import');
        Route::get('/admin/users/export', [UserController::class, 'bulkExport'])->name('admin.users.export');
    });

    Route::get('/teacher/dashboard', [DashboardController::class, 'teacher'])
        ->name('teacher.dashboard')
        ->middleware('role:teacher');

    Route::get('/student/dashboard', [DashboardController::class, 'student'])
        ->name('student.dashboard')
        ->middleware('role:student');

    // Enrollment
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])
        ->name('courses.enroll')
        ->middleware('role:student');

    // Course Management (Teacher/Admin/Super Admin)
    Route::middleware('role:teacher,admin,super_admin')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::post('/courses/{course}/publish', [CourseController::class, 'publish'])->name('courses.publish');

        // Lessons
        Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
        Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');

        // Assignments
        Route::get('/courses/{course}/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/courses/{course}/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/courses/{course}/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
        Route::post('/assignments/{assignment}/grade', [AssignmentController::class, 'grade'])->name('assignments.grade');

        // Quizzes
        Route::get('/courses/{course}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
        Route::post('/courses/{course}/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
        Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    });

    // Student Routes - Lessons
    Route::middleware('role:student')->group(function () {
        Route::get('/courses/{course:slug}/lessons/{lesson}', [LessonController::class, 'show'])
            ->name('lessons.show');
    });

    // Quiz Routes
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/take/{attempt}', [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/attempt', [QuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/result/{attempt}', [QuizController::class, 'result'])->name('quizzes.result');

    // Reviews
    Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Discussions
    Route::get('/courses/{course}/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/courses/{course}/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::put('/discussions/{discussion}', [DiscussionController::class, 'update'])->name('discussions.update');
    Route::delete('/discussions/{discussion}', [DiscussionController::class, 'destroy'])->name('discussions.destroy');

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements/{announcement}/read', [AnnouncementController::class, 'markAsRead'])->name('announcements.read');

    // Certificates
    Route::post('/courses/{course}/certificate', [CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
});

// Blog Routes (Public)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Blog Management (Authenticated)
Route::middleware(['auth', 'role:teacher,admin'])->group(function () {
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');
});

// Announcement Management (Admin/Teacher)
Route::middleware(['auth', 'role:admin,teacher,super_admin'])->group(function () {
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
});

// Order Management (Admin/Super Admin)
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
});

// Admin Panel Routes (Super Admin/Admin Only)
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    // Course Management
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/publish', [AdminCourseController::class, 'publish'])->name('courses.publish');
    Route::post('/courses/{course}/unpublish', [AdminCourseController::class, 'unpublish'])->name('courses.unpublish');
    Route::post('/courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/reject', [AdminCourseController::class, 'reject'])->name('courses.reject');
    Route::get('/courses/{course}/moderate', [AdminCourseController::class, 'moderate'])->name('courses.moderate');
    Route::put('/courses/{course}/visibility', [AdminCourseController::class, 'updateVisibility'])->name('courses.update-visibility');
    Route::post('/courses/{course}/schedule', [AdminCourseController::class, 'schedulePublication'])->name('courses.schedule');
    Route::post('/courses/{course}/archive', [AdminCourseController::class, 'archive'])->name('courses.archive');
    Route::post('/courses/{course}/unarchive', [AdminCourseController::class, 'unarchive'])->name('courses.unarchive');
    Route::get('/courses/{course}/quality-check', [AdminCourseController::class, 'qualityCheck'])->name('courses.quality-check');

    // Lesson Management
    Route::get('/lessons', [AdminLessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/create', [AdminLessonController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [AdminLessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('lessons.destroy');

    // Quiz Management
    Route::get('/quizzes', [AdminQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [AdminQuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [AdminQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [AdminQuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/edit', [AdminQuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [AdminQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [AdminQuizController::class, 'destroy'])->name('quizzes.destroy');

    // Review Management
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Subscription Management
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/create', [AdminSubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [AdminSubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [AdminSubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Notification Management
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::delete('/notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Audit Logs
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AdminAuditLogController::class, 'show'])->name('audit-logs.show');
    Route::get('/audit-logs/export/csv', [AdminAuditLogController::class, 'export'])->name('audit-logs.export');

    // Analytics
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/courses', [AdminAnalyticsController::class, 'courses'])->name('analytics.courses');
    Route::get('/analytics/revenue', [AdminAnalyticsController::class, 'revenue'])->name('analytics.revenue');
    Route::get('/analytics/users', [AdminAnalyticsController::class, 'users'])->name('analytics.users');
    Route::get('/analytics/kpis', [AdminAnalyticsController::class, 'kpis'])->name('analytics.kpis');
    Route::get('/analytics/quiz-stats', [AdminAnalyticsController::class, 'quizStats'])->name('analytics.quiz-stats');
    Route::get('/analytics/ai-insights', [AdminAnalyticsController::class, 'aiInsights'])->name('analytics.ai-insights');
    Route::post('/analytics/generate-report', [AdminAnalyticsController::class, 'generateReport'])->name('analytics.generate-report');

    // Teacher Management
    Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/{teacher}', [AdminTeacherController::class, 'show'])->name('teachers.show');
    Route::post('/teachers/{teacher}/approve', [AdminTeacherController::class, 'approve'])->name('teachers.approve');
    Route::post('/teachers/{teacher}/suspend', [AdminTeacherController::class, 'suspend'])->name('teachers.suspend');
    Route::get('/teachers/{teacher}/payouts', [AdminTeacherController::class, 'payouts'])->name('teachers.payouts');
    Route::post('/teachers/{teacher}/payout', [AdminTeacherController::class, 'managePayout'])->name('teachers.manage-payout');
    Route::post('/teachers/{teacher}/assign-task', [AdminTeacherController::class, 'assignTask'])->name('teachers.assign-task');

    // Student Management
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/activity', [AdminStudentController::class, 'monitorActivity'])->name('students.activity');
    Route::post('/students/{student}/complaint', [AdminStudentController::class, 'handleComplaint'])->name('students.handle-complaint');
    Route::post('/students/{student}/refund', [AdminStudentController::class, 'processRefund'])->name('students.process-refund');
    Route::get('/students/{student}/feedback', [AdminStudentController::class, 'viewFeedback'])->name('students.feedback');
    Route::post('/students/{student}/suspend', [AdminStudentController::class, 'suspend'])->name('students.suspend');
    Route::post('/students/{student}/activate', [AdminStudentController::class, 'activate'])->name('students.activate');

    // Payment Management
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{order}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/transactions', [AdminPaymentController::class, 'transactions'])->name('payments.transactions');
    Route::get('/payments/coupons', [AdminPaymentController::class, 'coupons'])->name('payments.coupons');
    Route::get('/payments/coupons/create', [AdminPaymentController::class, 'createCoupon'])->name('payments.coupons.create');
    Route::post('/payments/coupons', [AdminPaymentController::class, 'storeCoupon'])->name('payments.coupons.store');
    Route::get('/payments/coupons/{coupon}/edit', [AdminPaymentController::class, 'editCoupon'])->name('payments.coupons.edit');
    Route::put('/payments/coupons/{coupon}', [AdminPaymentController::class, 'updateCoupon'])->name('payments.coupons.update');
    Route::post('/payments/{order}/dispute', [AdminPaymentController::class, 'handleDispute'])->name('payments.handle-dispute');
    Route::post('/payments/{order}/refund', [AdminPaymentController::class, 'processRefund'])->name('payments.process-refund');
    Route::get('/payments/revenue-report', [AdminPaymentController::class, 'revenueReport'])->name('payments.revenue-report');
    Route::get('/payments/revenue-report/export', [AdminPaymentController::class, 'exportRevenueReport'])->name('payments.revenue-report.export');
    Route::get('/payments/student/{student}', [AdminPaymentController::class, 'trackPaymentsByStudent'])->name('payments.student');
    Route::get('/payments/teacher/{teacher}', [AdminPaymentController::class, 'trackPaymentsByTeacher'])->name('payments.teacher');

    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/branding', [AdminSettingsController::class, 'branding'])->name('settings.branding');
    Route::put('/settings/branding', [AdminSettingsController::class, 'updateBranding'])->name('settings.branding.update');
    Route::get('/settings/email-templates', [AdminSettingsController::class, 'emailTemplates'])->name('settings.email-templates');
    Route::put('/settings/email-templates/{template}', [AdminSettingsController::class, 'updateEmailTemplate'])->name('settings.email-templates.update');
    Route::get('/settings/notifications', [AdminSettingsController::class, 'notifications'])->name('settings.notifications');
    Route::put('/settings/notifications', [AdminSettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::get('/settings/seo', [AdminSettingsController::class, 'seo'])->name('settings.seo');
    Route::put('/settings/seo', [AdminSettingsController::class, 'updateSeo'])->name('settings.seo.update');
    Route::get('/settings/localization', [AdminSettingsController::class, 'localization'])->name('settings.localization');
    Route::put('/settings/localization', [AdminSettingsController::class, 'updateLocalization'])->name('settings.localization.update');
    Route::get('/settings/storage', [AdminSettingsController::class, 'storage'])->name('settings.storage');
    Route::put('/settings/storage', [AdminSettingsController::class, 'updateStorage'])->name('settings.storage.update');
    Route::get('/settings/gamification', [AdminSettingsController::class, 'gamification'])->name('settings.gamification');
    Route::put('/settings/gamification', [AdminSettingsController::class, 'updateGamification'])->name('settings.gamification.update');
    Route::get('/settings/integrations', [AdminSettingsController::class, 'integrations'])->name('settings.integrations');
    Route::put('/settings/integrations/{integration}', [AdminSettingsController::class, 'updateIntegration'])->name('settings.integrations.update');
    Route::get('/settings/security', [AdminSettingsController::class, 'security'])->name('settings.security');
    Route::put('/settings/security', [AdminSettingsController::class, 'updateSecurity'])->name('settings.security.update');
    Route::get('/settings/backup', [AdminSettingsController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/backup', [AdminSettingsController::class, 'createBackup'])->name('settings.backup.create');
    Route::post('/settings/backup/{backupId}/restore', [AdminSettingsController::class, 'restoreBackup'])->name('settings.backup.restore');

    // Discussion Management
    Route::get('/discussions', [AdminDiscussionController::class, 'index'])->name('discussions.index');
    Route::get('/discussions/{discussion}', [AdminDiscussionController::class, 'show'])->name('discussions.show');
    Route::post('/discussions/{discussion}/approve', [AdminDiscussionController::class, 'approve'])->name('discussions.approve');
    Route::post('/discussions/{discussion}/reject', [AdminDiscussionController::class, 'reject'])->name('discussions.reject');
    Route::delete('/discussions/{discussion}', [AdminDiscussionController::class, 'destroy'])->name('discussions.destroy');

    // Certificate Management
    Route::get('/certificates', [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [AdminCertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates', [AdminCertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{certificate}', [AdminCertificateController::class, 'show'])->name('certificates.show');
    Route::delete('/certificates/{certificate}', [AdminCertificateController::class, 'destroy'])->name('certificates.destroy');

    // Blog Management
    Route::get('/blog', [AdminBlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [AdminBlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [AdminBlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{post}/edit', [AdminBlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{post}', [AdminBlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{post}', [AdminBlogController::class, 'destroy'])->name('blog.destroy');

    // Announcement Management
    Route::get('/announcements', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AdminAnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AdminAnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AdminAnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AdminAnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Category Management
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Coupon Management
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
});

// Teacher Panel Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // My Courses
    Route::get('/courses', [TeacherCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [TeacherCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [TeacherCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [TeacherCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [TeacherCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [TeacherCourseController::class, 'update'])->name('courses.update');
    Route::get('/courses/{course}/students', [TeacherCourseController::class, 'students'])->name('courses.students');
    Route::get('/courses/{course}/performance', [TeacherCourseController::class, 'performance'])->name('courses.performance');
    Route::post('/courses/{course}/duplicate', [TeacherCourseController::class, 'duplicate'])->name('courses.duplicate');
    Route::get('/courses/{course}/analytics', [TeacherCourseController::class, 'analytics'])->name('courses.analytics');
    Route::get('/courses/{course}/monetization', [TeacherCourseController::class, 'monetization'])->name('courses.monetization');
    Route::put('/courses/{course}/pricing', [TeacherCourseController::class, 'updatePricing'])->name('courses.update-pricing');
    Route::post('/courses/{course}/promotion', [TeacherCourseController::class, 'applyPromotion'])->name('courses.apply-promotion');

    // My Lessons
    Route::get('/lessons', [TeacherLessonController::class, 'index'])->name('lessons.index');

    // My Quizzes
    Route::get('/quizzes', [TeacherQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/courses/{course}/quizzes/create', [TeacherQuizController::class, 'create'])->name('quizzes.create');
    Route::post('/courses/{course}/quizzes', [TeacherQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [TeacherQuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/analytics', [TeacherQuizController::class, 'analytics'])->name('quizzes.analytics');
    Route::post('/quizzes/{quiz}/ai-generate', [TeacherQuizController::class, 'generateWithAI'])->name('quizzes.ai-generate');
    Route::post('/quizzes/{quiz}/award-badge', [TeacherQuizController::class, 'awardBadge'])->name('quizzes.award-badge');

    // My Assignments
    Route::get('/assignments', [TeacherAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/courses/{course}/assignments/create', [TeacherAssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/courses/{course}/assignments', [TeacherAssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [TeacherAssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/grade', [TeacherAssignmentController::class, 'grade'])->name('assignments.grade');
    Route::post('/assignments/{assignment}/feedback', [TeacherAssignmentController::class, 'provideFeedback'])->name('assignments.feedback');
    Route::get('/courses/{course}/struggling-students', [TeacherAssignmentController::class, 'flagStrugglingStudents'])->name('courses.struggling-students');
    Route::get('/courses/{course}/export-report', [TeacherAssignmentController::class, 'exportReport'])->name('courses.export-report');

    // Q&A & Discussions
    Route::get('/discussions', [TeacherDiscussionController::class, 'index'])->name('discussions.index');
    Route::get('/discussions/{discussion}', [TeacherDiscussionController::class, 'show'])->name('discussions.show');
    Route::post('/discussions/{discussion}/reply', [TeacherDiscussionController::class, 'reply'])->name('discussions.reply');
});

// Student Panel Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // My Courses
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/bookmark', [StudentCourseController::class, 'bookmark'])->name('courses.bookmark');
    Route::get('/courses/{course}/resume', [StudentCourseController::class, 'resume'])->name('courses.resume');
    Route::get('/courses/{course}/download-resources', [StudentCourseController::class, 'downloadResources'])->name('courses.download-resources');
    Route::get('/courses/recommendations', [StudentCourseController::class, 'recommendations'])->name('courses.recommendations');
    Route::get('/courses/learning-path', [StudentCourseController::class, 'learningPath'])->name('courses.learning-path');

    // My Progress
    Route::get('/progress', [StudentProgressController::class, 'index'])->name('progress.index');

    // My Assignments
    Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('assignments.show');

    // My Quizzes
    Route::get('/quizzes', [StudentQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/attempts', [StudentQuizController::class, 'myAttempts'])->name('quizzes.attempts');
    Route::get('/quizzes/{quiz}/attempt', [StudentQuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quizzes/{quiz}/submit', [StudentQuizController::class, 'submitAttempt'])->name('quizzes.submit');
    Route::get('/quizzes/result/{attempt}', [StudentQuizController::class, 'result'])->name('quizzes.result');
    Route::get('/quizzes/improvement', [StudentQuizController::class, 'trackImprovement'])->name('quizzes.improvement');

    // My Certificates
    Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [StudentCertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/share/{platform}', [StudentCertificateController::class, 'share'])->name('certificates.share');
    Route::get('/certificates/{certificate}/download', [StudentCertificateController::class, 'download'])->name('certificates.download');
    Route::get('/certificates/verify/{certificateId}', [StudentCertificateController::class, 'verify'])->name('certificates.verify');

    // Community
    Route::get('/courses/{course}/discussions', [StudentCommunityController::class, 'discussions'])->name('community.discussions');
    Route::post('/courses/{course}/discussions', [StudentCommunityController::class, 'createDiscussion'])->name('community.discussions.create');
    Route::post('/discussions/{discussion}/reply', [StudentCommunityController::class, 'replyDiscussion'])->name('community.discussions.reply');
    Route::get('/courses/{course}/qa', [StudentCommunityController::class, 'qa'])->name('community.qa');
    Route::post('/courses/{course}/ask-question', [StudentCommunityController::class, 'askQuestion'])->name('community.ask-question');
    Route::post('/courses/{course}/rate', [StudentCommunityController::class, 'rateCourse'])->name('community.rate-course');
    Route::post('/teachers/{teacher}/follow', [StudentCommunityController::class, 'followTeacher'])->name('community.follow-teacher');
    Route::post('/students/{student}/follow', [StudentCommunityController::class, 'followStudent'])->name('community.follow-student');
    Route::get('/messages/{user}', [StudentCommunityController::class, 'message'])->name('community.message');
    Route::post('/messages/{user}/send', [StudentCommunityController::class, 'sendMessage'])->name('community.send-message');

    // Payments
    Route::post('/courses/{course}/purchase', [StudentPaymentController::class, 'purchaseCourse'])->name('payments.purchase');
    Route::get('/payments/process/{order}', [StudentPaymentController::class, 'processPayment'])->name('payments.process');
    Route::post('/payments/process/{order}', [StudentPaymentController::class, 'completePayment'])->name('payments.complete');
    Route::get('/payments/history', [StudentPaymentController::class, 'transactionHistory'])->name('payments.history');
    Route::get('/payments/invoices', [StudentPaymentController::class, 'invoices'])->name('payments.invoices');
    Route::get('/payments/invoices/{order}/download', [StudentPaymentController::class, 'downloadInvoice'])->name('payments.invoices.download');
    Route::post('/payments/apply-coupon', [StudentPaymentController::class, 'applyCoupon'])->name('payments.apply-coupon');
    Route::get('/payments/subscriptions', [StudentPaymentController::class, 'subscriptions'])->name('payments.subscriptions');
    Route::post('/payments/subscriptions/{subscription}/purchase', [StudentPaymentController::class, 'purchaseSubscription'])->name('payments.subscriptions.purchase');
    Route::post('/payments/apply-referral', [StudentPaymentController::class, 'applyReferralCredit'])->name('payments.apply-referral');

    // My Reviews
    Route::get('/reviews', [StudentReviewController::class, 'index'])->name('reviews.index');
});