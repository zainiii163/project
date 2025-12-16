# SmartLearn LMS - Learning Management System

A comprehensive web-based Learning Management System built with Laravel 11, designed to simplify and digitalize the teaching and learning process.

## Features

### User Roles
- **Admin**: Manage users, courses, analytics, and system settings
- **Teacher**: Create and manage courses, lessons, quizzes, and track student performance
- **Student**: Enroll in courses, watch lessons, take quizzes, and earn certificates
- **Guest**: Browse courses and receive AI-based recommendations

### Core Modules

#### 1. User Management
- Registration and authentication
- Role-based access control (Admin, Teacher, Student)
- Profile management
- Password recovery

#### 2. Course Management
- Create, edit, and publish courses
- Course categorization
- Pricing and visibility settings
- Course reviews and ratings

#### 3. Lesson Management
- Structured lessons with videos, PDFs, and downloadable materials
- Lesson ordering and organization
- Progress tracking

#### 4. Quiz & Assessment System
- Create quizzes with multiple question types
- Multiple choice, true/false, and short answer questions
- Automated scoring
- Attempt tracking and results

#### 5. Order & Payment Management
- **Orders**: Complete order lifecycle management (pending, completed, cancelled, refunded)
- **Order Items**: Track individual course purchases within orders
- **Transactions**: Payment processing with multiple payment methods (credit card, PayPal, bank transfer, wallet)
- **Transaction Status**: Track payment status (pending, completed, failed, refunded)
- **Payment Gateway Integration**: Ready for Stripe, PayPal, and other payment processors
- **Invoice Generation**: Automatic invoice creation for completed orders
- **Payment History**: Complete transaction history for students and teachers

#### 6. E-Commerce Features
- **Shopping Cart**: Add multiple courses to cart before checkout
- **Order Management**: Admin can view, filter, and manage all orders
- **Transaction Tracking**: Real-time transaction status updates
- **Wallet System**: 
  - User wallet with balance management
  - Credit/debit operations
  - Wallet-to-wallet transfers
  - Purchase courses using wallet balance
- **Subscription Plans**:
  - Multiple subscription tiers (Basic, Premium, Enterprise)
  - Recurring billing support
  - Subscription-to-course mapping
  - Auto-renewal and expiration tracking
- **Coupon & Discount System**:
  - Percentage and fixed amount discounts
  - Minimum purchase requirements
  - Usage limits and expiration dates
  - Personal discount codes for students
  - Bulk coupon generation
- **Refund Management**: Process refunds for cancelled orders
- **Payment Disputes**: Handle payment disputes and chargebacks
- **Revenue Reports**: Detailed revenue analytics by date, course, teacher, and student

#### 7. Community Features
- Course discussions and Q&A
- Announcements
- Blog system with categories and tags
- Notifications

#### 8. Analytics & Reporting
- User performance tracking
- Course enrollment analytics
- Revenue reports
- Audit logs

#### 9. Role-Based Dashboards
- **Admin Dashboard**:
  - System-wide statistics (users, courses, revenue, enrollments)
  - Today's metrics (visitors, orders, revenue)
  - Revenue charts (12 months trend)
  - Market trends analysis
  - Recent courses and orders
  - Daily sales report (last 7 days)
  - Quick access to all management sections
- **Teacher Dashboard**:
  - Personal teaching statistics
  - Total courses and published courses
  - Total students and enrollments
  - Recent courses with quick actions
  - Course management shortcuts
  - Performance metrics
- **Student Dashboard**:
  - Learning progress overview
  - Enrolled courses count
  - Completed courses tracking
  - Certificates earned
  - In-progress courses
  - Recent certificates display
  - Quick access to browse courses, recommendations, and progress

#### 10. Certificate Generation
- Automatic certificate generation upon course completion
- Certificate management

#### 11. Payment & E-Commerce System (Complete Implementation)

**Order Management:**
- Create orders for course purchases
- Order status tracking (pending, completed, cancelled, refunded)
- Order history for students
- Admin order management dashboard
- Order filtering and search
- Order details with invoice generation

**Transaction Processing:**
- Multiple payment methods support (credit card, PayPal, bank transfer, wallet)
- Transaction status tracking (pending, completed, failed, refunded)
- Payment gateway transaction ID storage
- Transaction notes and metadata
- Failed transaction handling and retry mechanisms

**Wallet System:**
- User wallet with balance management
- Credit operations (deposits, refunds, rewards)
- Debit operations (course purchases)
- Wallet balance validation before purchases
- Transaction history for wallet operations

**Subscription Management:**
- Multiple subscription plans (Basic, Premium, Enterprise)
- Subscription creation and management
- Start and end date tracking
- Subscription status (active, expired, cancelled)
- Subscription-to-course access mapping
- Auto-renewal support (ready for implementation)
- Subscription expiration notifications

**Coupon & Discount System:**
- Create percentage-based discounts
- Create fixed-amount discounts
- Minimum purchase amount requirements
- Maximum usage limits per coupon
- Usage count tracking
- Validity period (valid_from, valid_until)
- Active/inactive coupon status
- Personal discount codes for students
- Bulk coupon generation
- Coupon validation before application

**Payment Features:**
- Apply coupons during checkout
- Calculate discounts automatically
- Multiple payment gateway integration ready
- Invoice generation for completed orders
- Payment receipt generation
- Refund processing
- Payment dispute handling
- Revenue reporting and analytics
- Payment tracking by student
- Payment tracking by teacher/course
- Revenue export functionality

**Admin Payment Management:**
- View all orders with filters
- View transaction history
- Manage coupons (create, edit, delete)
- Process refunds
- Handle payment disputes
- Generate revenue reports
- Export revenue data
- Track payments by student
- Track payments by teacher
- Payment analytics dashboard

**Student Payment Features:**
- Purchase courses
- View payment history
- View invoices
- Download invoices as PDF
- Apply coupon codes
- View subscription plans
- Purchase subscriptions
- Apply referral credits
- Wallet balance display

## Technology Stack

- **Backend**: Laravel 11 (PHP 8.1+)
- **Database**: MySQL
- **Frontend**: Sneat Bootstrap Admin Template
- **Architecture**: MVC (Model-View-Controller)

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js and NPM (for frontend assets)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd website
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**
   Edit `.env` file and set your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smartlearn_lms
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed Database**
   ```bash
   php artisan db:seed
   ```

8. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   ```

10. **Build Frontend Assets** (in another terminal)
    ```bash
    npm run dev
    # or for production
    npm run build
    ```

## Default Login Credentials

After seeding, you can login with:

- **Admin**
  - Email: `admin@smartlearn.com`
  - Password: `password`

- **Teacher**
  - Email: `teacher@smartlearn.com`
  - Password: `password`

- **Student**
  - Email: `student@smartlearn.com`
  - Password: `password`

## Database Structure (Entity Relationship Diagram - ERD)

The system uses UUIDs for all primary keys and follows a comprehensive database design. Below is the complete entity structure:

### Core Entities

#### User Management
- **Users**: Central entity with roles (super_admin, admin, teacher, student)
  - Attributes: name, email, username, password_hash, role, status, profile_picture, registration_date, last_login
  - Relationships: One-to-many with Courses (as teacher), Orders, Subscriptions, Wallets, Notifications, AuditLogs, Assignments, Certificates, Attempts
  - Many-to-many with Courses (enrollments), Announcements (recipients)
- **Roles**: Role definitions with permissions (JSON)
  - Attributes: name, permissions

#### Course & Learning Management
- **Categories**: Course categorization
  - Attributes: name, description, slug, parent_id
- **Courses**: Main course entity
  - Attributes: title, slug, description, teacher_id, category_id, price, status (draft/published), visibility, level, duration, thumbnail, objectives, requirements, prerequisites, skill_tags, content_type, scheduled_publish_at, approved_at, archived_at, rejection_reason
  - Relationships: Belongs to Teacher (User), Category; Has many Lessons, Quizzes, Assignments, Certificates, Reviews, Discussions, Announcements; Many-to-many with Users (students), Subscriptions
- **Lessons**: Course content units
  - Attributes: course_id, title, content_url, duration, order, description, video_url, file_url
  - Relationships: Belongs to Course
- **Lesson Progress**: Track student lesson completion
  - Attributes: user_id, lesson_id, completed, progress_percentage, last_position, last_accessed_at

#### Assessment System
- **Quizzes**: Assessment tests
  - Attributes: course_id, title, pass_score, time_limit, instructions
  - Relationships: Belongs to Course; Has many Questions, Attempts
- **Questions**: Quiz questions
  - Attributes: quiz_id, text, type, points
  - Relationships: Belongs to Quiz; Has many Options, Answers
- **Options**: Question answer choices
  - Attributes: question_id, text, is_correct
  - Relationships: Belongs to Question; Has many Answers
- **Attempts**: Student quiz attempts
  - Attributes: quiz_id, user_id, score, submitted_at, time_taken
  - Relationships: Belongs to Quiz, User; Has many Answers
- **Answers**: Student answers to questions
  - Attributes: attempt_id, question_id, option_id, answer_text
  - Relationships: Belongs to Attempt, Question, Option
- **Assignments**: Course assignments
  - Attributes: course_id, student_id, content, file_path, submitted_at, grade, feedback
  - Relationships: Belongs to Course, User (student)
- **Certificates**: Course completion certificates
  - Attributes: user_id, course_id, certificate_url, issued_at, verification_code
  - Relationships: Belongs to User, Course

#### Payment & E-Commerce System
- **Orders**: Purchase orders
  - Attributes: user_id, order_date, total_price, status (pending/completed/cancelled/refunded), coupon_code, discount_amount
  - Relationships: Belongs to User; Has many OrderItems, Has one Transaction
- **Order Items**: Individual course purchases in an order
  - Attributes: order_id, course_id, price, quantity
  - Relationships: Belongs to Order, Course
- **Transactions**: Payment transactions
  - Attributes: order_id, payment_method, amount, status (pending/completed/failed/refunded), transaction_date, transaction_id (gateway ID), notes
  - Relationships: Belongs to Order
- **Subscriptions**: User subscription plans
  - Attributes: user_id, plan, amount, start_date, end_date, status (active/expired/cancelled)
  - Relationships: Belongs to User; Many-to-many with Courses
- **Subscription Course**: Pivot table linking subscriptions to courses
  - Attributes: subscription_id, course_id
- **Wallets**: User wallet balances
  - Attributes: user_id, balance
  - Relationships: Belongs to User (one-to-one)
- **Coupons**: Discount codes
  - Attributes: code, type (percentage/fixed), value, min_purchase, max_uses, used_count, valid_from, valid_until, is_active
  - Relationships: Used in Orders

#### Community & Communication
- **Announcements**: System-wide or course-specific announcements
  - Attributes: title, content, scope (global/course), course_id, created_by
  - Relationships: Many-to-many with Users (recipients); Optional belongs to Course
- **Announcement User**: Pivot table for announcement recipients
  - Attributes: announcement_id, user_id, read_at
- **Notifications**: User notifications
  - Attributes: user_id, type, data (JSON), read_at
  - Relationships: Belongs to User
- **Discussions**: Course discussions and Q&A
  - Attributes: course_id, user_id, message, parent_id (for replies)
  - Relationships: Belongs to Course, User; Self-referencing for replies
- **Reviews**: Course reviews and ratings
  - Attributes: course_id, user_id, rating, comment, approved
  - Relationships: Belongs to Course, User

#### Content Management
- **Blog Posts**: Educational blog articles
  - Attributes: title, slug, content, author_id, featured_image, excerpt, status (draft/published/archived), published_at
  - Relationships: Belongs to User (author); Many-to-many with Categories, Tags
- **Tags**: Blog post tags
  - Attributes: name, slug
  - Relationships: Many-to-many with BlogPosts
- **Blog Post Category**: Pivot table for blog categorization
  - Attributes: blog_post_id, category_id
- **Blog Post Tag**: Pivot table for blog tagging
  - Attributes: blog_post_id, tag_id

#### System Management
- **Audit Logs**: System activity tracking
  - Attributes: user_id, action, model_type, model_id, data (JSON), created_at
  - Relationships: Belongs to User

### Database Relationships Summary

**One-to-Many Relationships:**
- User → Courses (as teacher), Orders, Subscriptions, Wallets, Notifications, AuditLogs, Assignments, Certificates, Attempts
- Course → Lessons, Quizzes, Assignments, Certificates, Reviews, Discussions, Announcements
- Quiz → Questions, Attempts
- Question → Options
- Attempt → Answers
- Order → OrderItems, Transaction

**Many-to-Many Relationships:**
- Users ↔ Courses (enrollments via course_user pivot)
- Users ↔ Announcements (recipients via announcement_user pivot)
- Subscriptions ↔ Courses (via subscription_course pivot)
- BlogPosts ↔ Categories (via blog_post_category pivot)
- BlogPosts ↔ Tags (via blog_post_tag pivot)

**Self-Referencing:**
- Discussions (parent_id for threaded replies)

## API Routes

API routes are available in `routes/api.php`. The system uses Laravel Sanctum for API authentication.

## Security Features

- CSRF protection
- Password hashing
- Role-based access control
- Input validation
- SQL injection prevention (Eloquent ORM)
- XSS protection

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── CourseController.php
│   │   ├── LessonController.php
│   │   ├── QuizController.php
│   │   └── ...
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   ├── User.php
│   ├── Course.php
│   ├── Lesson.php
│   └── ...
└── Policies/
    └── CoursePolicy.php

database/
├── migrations/
│   ├── 2014_10_12_000000_create_users_table.php
│   ├── 2024_01_01_000001_create_roles_table.php
│   └── ...
└── seeders/
    └── DatabaseSeeder.php
```

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support, email support@smartlearn.com or create an issue in the repository.

## Roadmap

- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Live class integration (Zoom, Google Meet)
- [ ] AI course recommendations
- [ ] Gamification system (badges, XP, leaderboards)
- [ ] Multi-language support
- [ ] Mobile app API
- [ ] Advanced analytics dashboard
- [ ] Email notifications
- [ ] Certificate PDF generation
- [ ] Cloud storage integration (AWS S3, DigitalOcean Spaces)
