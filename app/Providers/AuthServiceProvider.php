<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Course::class => \App\Policies\CoursePolicy::class,
        \App\Models\Review::class => \App\Policies\ReviewPolicy::class,
        \App\Models\BlogPost::class => \App\Policies\BlogPostPolicy::class,
        \App\Models\Announcement::class => \App\Policies\AnnouncementPolicy::class,
        \App\Models\Discussion::class => \App\Policies\DiscussionPolicy::class,
        \App\Models\Certificate::class => \App\Policies\CertificatePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Assignment::class => \App\Policies\AssignmentPolicy::class,
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
        \App\Models\CalendarEvent::class => \App\Policies\CalendarEventPolicy::class,
        \App\Models\SupportTicket::class => \App\Policies\SupportTicketPolicy::class,
        \App\Models\LiveSession::class => \App\Policies\LiveSessionPolicy::class,
        \App\Models\Resource::class => \App\Policies\ResourcePolicy::class,
        \App\Models\Coupon::class => \App\Policies\CouponPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
