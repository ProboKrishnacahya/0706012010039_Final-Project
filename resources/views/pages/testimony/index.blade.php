@section('title', 'Testimony')

<x-app-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-grid gap-3">
        <div>
            @section('page_title', 'Testimoni')
        </div>

        @if ($testimony->isEmpty())
            <div class="alert alert-info">
                Belum terdapat testimoni pada vendor.
            </div>
        @endif
        @foreach ($testimony as $item)
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src={{ $item->customer?->profile_photo_url }} alt="" width="48">
                            <span>{{ $item?->customer?->name }}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong>{{ $item->rating }}/5</strong>
                        </div>
                        <span>
                            <pre class="mb-0">{{ $item->description ?? '-' }}</pre>
                        </span>
                        <img src="/assets/image/testimony_photo/{{ $item->testimony_photo }}" alt=""
                            class="rounded-1" width="196">
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-secondary">{{ $item->created_at ?? '-' }}</small>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
