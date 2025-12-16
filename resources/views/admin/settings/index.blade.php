@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Settings</h2>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>General Settings</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <a href="{{ route('admin.settings.branding') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(79, 70, 229, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-palette" style="font-size: 24px; color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Branding</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Logo, colors, site name</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.email-templates') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope" style="font-size: 24px; color: var(--success-color);"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Email Templates</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Customize email templates</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.notifications') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bell" style="font-size: 24px; color: var(--info-color);"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Notifications</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Notification preferences</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.seo') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-search" style="font-size: 24px; color: var(--warning-color);"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">SEO</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">SEO meta tags</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.localization') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(139, 92, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-globe" style="font-size: 24px; color: #8b5cf6;"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Localization</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Language & timezone</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.storage') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-database" style="font-size: 24px; color: var(--danger-color);"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Storage</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">File storage settings</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.gamification') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(236, 72, 153, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-trophy" style="font-size: 24px; color: #ec4899;"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Gamification</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Points, badges, leaderboard</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.integrations') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(14, 165, 233, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-plug" style="font-size: 24px; color: #0ea5e9;"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Integrations</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Third-party integrations</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.security') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(34, 197, 94, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shield-alt" style="font-size: 24px; color: #22c55e;"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Security</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Password & security policies</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.backup') }}" class="adomx-card" style="padding: 20px; text-decoration: none; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.3s;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; background: rgba(168, 85, 247, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-cloud-download-alt" style="font-size: 24px; color: #a855f7;"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Backup</h4>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--text-secondary);">Database backups</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

