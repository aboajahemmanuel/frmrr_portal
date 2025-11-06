{{-- Recursive partial for nested related documents --}}
<div class="mt-3" style="padding-left: {{ $level * 1.5 }}rem;">
    <div class="text-muted small mb-2">
        <em class="icon ni ni-link"></em>
        <strong>Related Documents ({{ $nestedDocuments->count() }})</strong>
    </div>
    
    <!-- Header Row for Nested Documents -->
    <div class="d-flex bg-light border-bottom p-2 fw-bold text-center border-start border-3 border-info" style="font-size: 12px; background-color: rgba(13, 202, 240, {{ 0.1 * $level }}) !important;">
        <div style="width: 8%; min-width: 40px;">S/N</div>
        <div style="width: 30%; min-width: 180px;">Title</div>
        <div style="width: 10%; min-width: 60px;">Year</div>
        <div style="width: 15%; min-width: 100px;">Effective Date</div>
        <div style="width: 15%; min-width: 100px;">Issued Date</div>
        <div style="width: 12%; min-width: 80px;">Status</div>
        <div style="width: 10%; min-width: 100px;">Action</div>
    </div>
    
    <div class="list-group list-group-flush">
        @foreach($nestedDocuments as $nestedIndex => $nestedDoc)
            <div class="list-group-item border-start border-3 border-info border-0 border-bottom" style="background-color: rgba(13, 202, 240, {{ 0.05 * $level }});">
                <div class="d-flex align-items-center p-1" style="font-size: 12px;">
                    <div style="width: 8%; min-width: 40px; text-align: center;">
                        <small class="text-muted">{{ $parentIndex }}.{{ $nestedIndex + 1 }}</small>
                    </div>
                    <div style="width: 30%; min-width: 180px;" class="text-truncate">
                        {{ $nestedDoc->title }}
                    </div>
                    <div style="width: 10%; min-width: 60px; text-align: center;">
                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                            {{ optional($nestedDoc->year)->name ?? 'N/A' }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                    <div style="width: 15%; min-width: 100px; text-align: center;">
                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                            {{ $nestedDoc->effective_date ? \Carbon\Carbon::parse($nestedDoc->effective_date)->format('M. j, Y') : 'N/A' }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                    <div style="width: 15%; min-width: 100px; text-align: center;">
                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                            {{ $nestedDoc->issue_date ? \Carbon\Carbon::parse($nestedDoc->issue_date)->format('M. j, Y') : 'N/A' }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                    <div style="width: 12%; min-width: 80px; text-align: center;">
                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                            <span class="badge badge-success" style="font-size: 10px;">{{ $nestedDoc->ceased ?? 'Active' }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                    <div style="width: 10%; min-width: 100px; text-align: center;">
                        <div class="d-flex gap-1 justify-content-center">
                            @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                <a href="{{ asset('public/pdf_documents/' . $nestedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-xs btn-outline-primary">
                                    <em class="icon ni ni-book-read"></em>
                                </a>
                                <a href="{{ route('download', $nestedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-xs btn-outline-primary">
                                    <em class="icon ni ni-download"></em>
                                </a>
                                <a href="#" onclick="document.getElementById('save-nested-{{ $nestedDoc->id }}-{{ $level }}').submit();" class="btn btn-icon btn-white btn-dim btn-xs btn-outline-primary">
                                    <em class="icon ni ni-save"></em>
                                </a>
                                <form id="save-nested-{{ $nestedDoc->id }}-{{ $level }}" action="{{ route('save-document', $nestedDoc->id) }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-xs btn-warning" style="font-size: 10px;">
                                    Upgrade
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Recursively include nested documents if they exist --}}
                @if($nestedDoc->nested_related_documents && $nestedDoc->nested_related_documents->count() > 0 && $level < 5)
                    @include('partials.nested-related-documents', [
                        'nestedDocuments' => $nestedDoc->nested_related_documents,
                        'parentIndex' => $parentIndex . '.' . ($nestedIndex + 1),
                        'level' => $level + 1,
                        'isSubscribed' => $isSubscribed
                    ])
                @endif
            </div>
        @endforeach
    </div>
</div>
