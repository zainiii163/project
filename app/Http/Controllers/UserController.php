<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\Order;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'suspended') {
                $query->where('status', 'suspended');
            } elseif ($request->status == 'inactive') {
                $query->where('status', 'inactive');
            }
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student,guest',
            'username' => 'nullable|string|max:255|unique:users',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['registration_date'] = now();

        $user = User::create($validated);

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_created',
            'model_type' => User::class,
            'model_id' => $user->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        // Load detailed user information
        $user->load([
            'courses' => function($q) {
                $q->withPivot('enrolled_at', 'progress', 'completed_at');
            },
            'taughtCourses',
            'orders.transaction',
            'certificates.course',
            'reviews.course',
            'attempts.quiz.course',
        ]);

        // Get enrollment history
        $enrollmentHistory = $user->courses()
            ->with(['teacher', 'category'])
            ->orderByPivot('enrolled_at', 'desc')
            ->get();

        // Get quiz scores
        $quizScores = $user->attempts()
            ->with(['quiz.course'])
            ->select('quiz_id', DB::raw('AVG(score) as avg_score'), DB::raw('MAX(score) as max_score'), DB::raw('COUNT(*) as attempts'))
            ->groupBy('quiz_id')
            ->get();

        // Get payment history
        $payments = $user->orders()
            ->with(['items.course', 'transaction'])
            ->latest()
            ->get();

        // Get activity logs
        $activityLogs = AuditLog::where('user_id', $user->id)
            ->orWhere('model_type', User::class)
            ->where('model_id', $user->id)
            ->with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('admin.users.show', compact('user', 'enrollmentHistory', 'quizScores', 'payments', 'activityLogs'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $oldValues = $user->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,teacher,student,guest',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'status' => 'nullable|in:active,suspended,inactive',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_updated',
            'model_type' => User::class,
            'model_id' => $user->id,
            'old_values' => $oldValues,
            'new_values' => $user->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Log before deletion
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_deleted',
            'model_type' => User::class,
            'model_id' => $user->id,
            'old_values' => $user->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function approve(User $user)
    {
        $this->authorize('update', $user);
        
        $user->update(['status' => 'active']);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_approved',
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', 'User account approved successfully!');
    }

    public function suspend(User $user)
    {
        $this->authorize('update', $user);
        
        $user->update(['status' => 'suspended']);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_suspended',
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', 'User account suspended successfully!');
    }

    public function activate(User $user)
    {
        $this->authorize('update', $user);
        
        $user->update(['status' => 'active']);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_activated',
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', 'User account activated successfully!');
    }

    public function deactivate(User $user)
    {
        $this->authorize('update', $user);
        
        $user->update(['status' => 'inactive']);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_deactivated',
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', 'User account deactivated successfully!');
    }

    public function resetPassword(User $user)
    {
        $this->authorize('update', $user);
        
        $newPassword = \Str::random(12);
        $user->update(['password' => Hash::make($newPassword)]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'password_reset',
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', 'Password reset successfully! New password: ' . $newPassword);
    }

    public function forcePasswordUpdate(User $user)
    {
        $this->authorize('update', $user);
        
        // Mark user as requiring password update
        $user->update(['password_changed_at' => null]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'force_password_update',
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', 'User will be required to update password on next login.');
    }

    public function activityLogs(User $user)
    {
        $this->authorize('view', $user);
        
        $logs = AuditLog::where('user_id', $user->id)
            ->orWhere(function($q) use ($user) {
                $q->where('model_type', User::class)
                  ->where('model_id', $user->id);
            })
            ->with('user')
            ->latest()
            ->paginate(50);
        
        return view('admin.users.activity-logs', compact('user', 'logs'));
    }

    public function assignRole(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'role' => 'required|in:super_admin,admin,teacher,student,guest',
        ]);
        
        $oldRole = $user->role;
        $user->update(['role' => $validated['role']]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'role_assigned',
            'model_type' => User::class,
            'model_id' => $user->id,
            'old_values' => ['role' => $oldRole],
            'new_values' => ['role' => $validated['role']],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return back()->with('success', 'Role assigned successfully!');
    }

    public function bulkImport(Request $request)
    {
        $this->authorize('create', User::class);
        
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:10240',
        ]);
        
        try {
            $file = $request->file('file');
            $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
            
            $imported = 0;
            $errors = [];
            
            foreach ($data[0] as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                try {
                    User::create([
                        'name' => $row[0] ?? '',
                        'email' => $row[1] ?? '',
                        'password' => Hash::make($row[2] ?? \Str::random(12)),
                        'role' => $row[3] ?? 'student',
                        'username' => $row[4] ?? null,
                        'registration_date' => now(),
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }
            
            return back()->with('success', "Imported {$imported} users successfully." . (count($errors) > 0 ? ' Errors: ' . implode(', ', $errors) : ''));
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function bulkExport(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $query = User::query();
        
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->get();
        
        $filename = 'users_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Username', 'Role', 'Registration Date', 'Last Login']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->username,
                    $user->role,
                    $user->registration_date,
                    $user->last_login,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

