@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>SEO & Marketing Tools</h2>
    </div>
    <div class="adomx-page-actions">
        <button type="button" class="adomx-btn adomx-btn-primary" onclick="bulkGenerate()">
            <i class="fas fa-magic"></i> Bulk Generate SEO
        </button>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">SEO Meta Tags</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Content Type</th>
                        <th>Title</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seoMetas as $seoMeta)
                    <tr>
                        <td>{{ class_basename($seoMeta->model_type) }}</td>
                        <td>
                            @if($seoMeta->model)
                                {{ $seoMeta->model->title ?? $seoMeta->model->name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ Str::limit($seoMeta->meta_title ?? 'Not Set', 50) }}</td>
                        <td>{{ Str::limit($seoMeta->meta_description ?? 'Not Set', 50) }}</td>
                        <td>
                            <span class="badge badge-{{ $seoMeta->meta_title ? 'success' : 'warning' }}">
                                {{ $seoMeta->meta_title ? 'Configured' : 'Not Set' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.seo.edit', $seoMeta) }}" class="adomx-action-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No SEO meta tags found. Generate them for your courses!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adomx-table-footer">
            {{ $seoMetas->links() }}
        </div>
    </div>
</div>

<form id="bulkGenerateForm" action="{{ route('admin.seo.bulk-generate') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
function bulkGenerate() {
    if (confirm('This will generate SEO meta tags for all courses. Continue?')) {
        document.getElementById('bulkGenerateForm').submit();
    }
}
</script>
@endsection

