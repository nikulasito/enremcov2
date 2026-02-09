<div class="breadcrumb-container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-house-door"></i> Home
                </a>
            </li>

            @foreach ($breadcrumbs as $breadcrumb)
                @if (!$loop->last)
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $breadcrumb['label'] }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>

<style>
    .breadcrumb-container {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .breadcrumb {
        margin-bottom: 0;
    }
</style>
