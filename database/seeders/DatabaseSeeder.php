<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin User
        $superAdmin = User::create([
            'id' => Str::uuid(),
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@smartlearn.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'registration_date' => now(),
        ]);

        // Create Admin User
        $admin = User::create([
            'id' => Str::uuid(),
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@smartlearn.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'registration_date' => now(),
        ]);

        // Create Teacher User
        $teacher = User::create([
            'id' => Str::uuid(),
            'name' => 'Teacher User',
            'username' => 'teacher',
            'email' => 'teacher@smartlearn.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'registration_date' => now(),
        ]);

        // Create Student User
        $student = User::create([
            'id' => Str::uuid(),
            'name' => 'Student User',
            'username' => 'student',
            'email' => 'student@smartlearn.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'registration_date' => now(),
        ]);

        // Create wallet for student
        Wallet::create([
            'id' => Str::uuid(),
            'user_id' => $student->id,
            'balance' => 0,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Mobile Development', 'slug' => 'mobile-development'],
            ['name' => 'Data Science', 'slug' => 'data-science'],
            ['name' => 'Design', 'slug' => 'design'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Marketing', 'slug' => 'marketing'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'id' => Str::uuid(),
                'name' => $category['name'],
                'slug' => $category['slug'],
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Super Admin: superadmin@smartlearn.com / password');
        $this->command->info('Admin: admin@smartlearn.com / password');
        $this->command->info('Teacher: teacher@smartlearn.com / password');
        $this->command->info('Student: student@smartlearn.com / password');
    }
}
