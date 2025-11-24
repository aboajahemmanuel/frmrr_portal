  <div style="background-color: #fff; padding: 20px; width: 100%">
            <div class="row" style="width: 100%">
                <div class="col-md-12">
                    @if (Auth::check())
                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                            <table id="example" class="datatable-init responsive table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Category</th>
                                        <th style="text-align: center;">Subcategory</th>
                                        <th style="text-align: center;">Version Number</th>
                                        <th style="text-align: center;">Issue Date</th>
                                        <th style="text-align: center;">Year</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Market Product</th>
                                        <th style="text-align: center;">Related Docs</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $result)
                                        <tr>
                                            <td class="" style="text-align:justify">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#pdfModal-{{ $result->id }}">
                                                    {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                                </a>
                                            </td>
                                            <td style="text-align: center">{{ optional($result->category)->name }}</td>
                                            <td style="text-align: center">{{ optional($result->subcategory)->name }}</td>
                                            
                                            <td style="text-align: center">{{ $result->document_version }}</td>
                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ $result->year->name }}</td>
                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                             <td style="text-align: center">
                                                <span class="badge fmdq_Blue">{{$result->ceased}}</span>
                                            </td>

                                             <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->ceased_date)->format('M. d, Y') }}
                                            </td>

                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                            <td style="text-align: center">
                                                @php
                                                    $tags = $result->marketProductTags ?? collect();
                                                    if (($tags instanceof \Illuminate\Support\Collection ? $tags->isEmpty() : empty($tags)) && !empty($result->market_product_tag)) {
                                                        $ids = array_filter(explode(',', $result->market_product_tag));
                                                        if (!empty($ids)) {
                                                            $tags = \App\Models\MarketProductTag::whereIn('id', $ids)->get();
                                                        }
                                                    }
                                                @endphp
                                                @if($tags && $tags->count())
                                                    @foreach($tags as $tag)
                                                        <span class="badge badge-info" style="margin: 0 2px;">{{ $tag->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            
                                            {{-- Related Documents Column --}}
                                            <td style="text-align: center">
                                                @if($result->related_docs)
                                                    @php
                                                        $relatedDocuments = $result->related_documents;
                                                        $relatedCount = $relatedDocuments->count();
                                                    @endphp
                                                    <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            {{-- End Related Documents Column --}}
                                            
                                            <td class="tb-odr-action"
                                                style="display: flex !important; align-items: center; justify-content: center">
                                                <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">





                                                    @if ($isSubscribed)
                                                        <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}"
                                                            target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                            <em class="icon ni ni-book-read"></em>
                                                        </a>

                                                        <a href="{{ route('download', $result->id) }}"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                class="icon ni ni-download"></em></a>
                                                    @else
                                                        @if (Auth::check())
                                                            <a href="{{ route('subscribe') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('subscribe') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @else
                                                            <a href="{{ route('login') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('login') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @endif
                                                    @endif




                                                    <a href="#" id="submit"
                                                        onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                            class="icon ni ni-save"></em></a>






                                                    <form id="save-{{ $result->id }}"
                                                        action="{{ route('save-document', $result->id) }}" method="POST"
                                                        class="d-none" style="display: none">
                                                        @csrf

                                                    </form>




                                                </div>

                                            </td>
                                        </tr>

                                        <!-- Modal for PDF Preview -->
                                        <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-body">
                                                        <div id="pdf-viewer-{{ $result->id }}">
                                                            <canvas id="canvas-page1-{{ $result->id }}"
                                                                class="pdf-page"></canvas>
                                                            <canvas id="canvas-page2-{{ $result->id }}"
                                                                class="pdf-page"></canvas>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal for Related Documents -->
                                        <div class="modal fade" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">Related Documents</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $relatedDocuments = $result->related_documents;
                                                        @endphp
                            
                                                        @if($relatedDocuments && $relatedDocuments->count() > 0)
                                                            <div class="card w-100 border-0 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <h5 class="mb-0 fw-bold">Related Documents</h5>
                                                                </div>
                                
                                                                <div class="card-body p-0">
                                                                    <!-- Header Row -->
                                                                    <div class="d-flex bg-light border-bottom p-3 fw-bold text-center" style="font-size: 14px;">
                                                                        <div style="width: 8%; min-width: 50px;">S/N</div>
                                                                        <div style="width: 30%; min-width: 200px;">Title</div>
                                                                        <div style="width: 10%; min-width: 80px;">Year</div>
                                                                        <div style="width: 15%; min-width: 120px;">Effective Date</div>
                                                                        <div style="width: 15%; min-width: 120px;">Issued Date</div>
                                                                        <div style="width: 12%; min-width: 100px;">Status</div>
                                                                        <div style="width: 10%; min-width: 120px;">Action</div>
                                                                    </div>
                                                                    
                                                                    <div class="list-group w-100">
                                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 30%; min-width: 200px;" class="text-truncate">
                                                                                            {{ $relatedDoc->title }}
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 80px; text-align: center;">
                                                                                            {{ optional($relatedDoc->year)->name ?? 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->effective_date ? \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->issue_date ? \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 12%; min-width: 100px; text-align: center;">
                                                                                            <span class="badge badge-success">{{ $relatedDoc->ceased ?? 'Active' }}</span>
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 120px; text-align: center;">
                                                                                            <div class="d-flex gap-1 justify-content-center">
                                                                                                <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-book-read"></em>
                                                                                                </a>
                                                                                                <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-download"></em>
                                                                                                </a>
                                                                                                <a href="#" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-save"></em>
                                                                                                </a>
                                                                                                <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                                    @csrf
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => $isSubscribed || Auth::user()->usertype == 'internal'
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 72%; min-width: 400px;" class="text-muted">
                                                                                            Restricted - Upgrade to view document details
                                                                                        </div>
                                                                                        <div style="width: 20%; min-width: 120px; text-align: center;">
                                                                                            <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-sm btn-warning">
                                                                                                Upgrade to Access
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => false
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p>No related documents found.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal for Related Documents -->
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <table id="example" class="datatable-init responsive table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Category</th>
                                        <th style="text-align: center;">Subcategory</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Related Docs</th>
                                        <th style="text-align: center;"><span style=" display:none">Entity</span></th>
                                        <th style="text-align: center;"><span>Action</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $result)
                                        <tr>
                                            <td class="" style="text-align: justify;">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#pdfModal-{{ $result->id }}">
                                                    {{ $result->title }} <em class="icon ni ni-zoom-in"></em>
                                                </a>
                                            </td>
                                            <td style="text-align: center">{{ optional($result->category)->name }}</td>
                                            <td style="text-align: center">{{ optional($result->subcategory)->name }}</td>
                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                            <td style="text-align: center">
                                                @php
                                                    $tags = $result->marketProductTags ?? collect();
                                                    if (($tags instanceof \Illuminate\Support\Collection ? $tags->isEmpty() : empty($tags)) && !empty($result->market_product_tag)) {
                                                        $ids = array_filter(explode(',', $result->market_product_tag));
                                                        if (!empty($ids)) {
                                                            $tags = \App\Models\MarketProductTag::whereIn('id', $ids)->get();
                                                        }
                                                    }
                                                @endphp
                                                @if($tags && $tags->count())
                                                    @foreach($tags as $tag)
                                                        <span class="badge badge-info" style="margin: 0 2px;">{{ $tag->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            {{-- Related Documents Column --}}
                                            <td style="text-align: center">
                                                @if($result->related_docs)
                                                    @php
                                                        $relatedDocuments = $result->related_documents;
                                                        $relatedCount = $relatedDocuments->count();
                                                    @endphp
                                                    <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            {{-- End Related Documents Column --}}
                                            
                                            <td style="text-align: center;"><span
                                                    style=" display:none">{{ $result->year->name }}</span></td>
                                            <td class="tb-odr-action"
                                                style="display: flex !important; align-items: center; justify-content: center">
                                                <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">
                                                    @if ($isSubscribed)
                                                        <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}"
                                                            target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                            <em class="icon ni ni-book-read"></em>
                                                        </a>
                                                        <a href="{{ route('download', $result->id) }}"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                class="icon ni ni-download"></em></a>
                                                    @else
                                                        @if (Auth::check())
                                                            <a href="{{ route('subscribe') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('subscribe') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @else
                                                            <a href="{{ route('login') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('login') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @endif
                                                    @endif
                                                    <a href="#" id="submit"
                                                        onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                            class="icon ni ni-save"></em></a>
                                                    <form id="save-{{ $result->id }}"
                                                        action="{{ route('save-document', $result->id) }}" method="POST"
                                                        class="d-none" style="display: none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div id="pdf-viewer-{{ $result->id }}">
                                                            <canvas id="canvas-page1-{{ $result->id }}"
                                                                class="pdf-page"></canvas>
                                                            <canvas id="canvas-page2-{{ $result->id }}"
                                                                class="pdf-page"></canvas>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal for Related Documents -->
                                        <div class="modal fade" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">Related Documents</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $relatedDocuments = $result->related_documents;
                                                        @endphp
                                                        @if($relatedDocuments && $relatedDocuments->count() > 0)
                                                            <div class="card w-100 border-0 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <h5 class="mb-0 fw-bold">Related Documents</h5>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <!-- Header Row -->
                                                                    <div class="d-flex bg-light border-bottom p-3 fw-bold text-center" style="font-size: 14px;">
                                                                        <div style="width: 8%; min-width: 50px;">S/N</div>
                                                                        <div style="width: 30%; min-width: 200px;">Title</div>
                                                                        <div style="width: 10%; min-width: 80px;">Year</div>
                                                                        <div style="width: 15%; min-width: 120px;">Effective Date</div>
                                                                        <div style="width: 15%; min-width: 120px;">Issued Date</div>
                                                                        <div style="width: 12%; min-width: 100px;">Status</div>
                                                                        <div style="width: 10%; min-width: 120px;">Action</div>
                                                                    </div>
                                                                    <div class="list-group w-100">
                                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 30%; min-width: 200px;" class="text-truncate">
                                                                                            {{ $relatedDoc->title }}
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 80px; text-align: center;">
                                                                                            {{ optional($relatedDoc->year)->name ?? 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->effective_date ? \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->issue_date ? \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 12%; min-width: 100px; text-align: center;">
                                                                                            <span class="badge badge-success">{{ $relatedDoc->ceased ?? 'Active' }}</span>
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 120px; text-align: center;">
                                                                                            <div class="d-flex gap-1 justify-content-center">
                                                                                                <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-book-read"></em>
                                                                                                </a>
                                                                                                <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-download"></em>
                                                                                                </a>
                                                                                                <a href="#" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-save"></em>
                                                                                                </a>
                                                                                                <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                                    @csrf
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => $isSubscribed || Auth::user()->usertype == 'internal'
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 72%; min-width: 400px;" class="text-muted">
                                                                                            Restricted - Upgrade to view document details
                                                                                        </div>
                                                                                        <div style="width: 20%; min-width: 120px; text-align: center;">
                                                                                            <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-sm btn-warning">
                                                                                                Upgrade to Access
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => false
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p>No related documents found.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal for Related Documents -->
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @else
                        <table id="example" class="datatable-init responsive table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Title</th>
                                    <th style="text-align: center;">Category</th>
                                    <th style="text-align: center;">Subcategory</th>
                                    <th style="text-align: center;">Effective Date</th>
                                    <th style="text-align: center;">Entity</th>
                                    <th style="text-align: center;">Related Docs</th>
                                    <th style="text-align: center;"><span style=" display:none">Entity</span></th>
                                    <th style="text-align: center;"><span>Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $result)
                                    <tr>
                                        <td class="" style="text-align: justify;">
                                            <a href="#" data-toggle="modal"
                                                data-target="#pdfModal-{{ $result->id }}">
                                                {{ $result->title }} <em class="icon ni ni-zoom-in"></em>
                                            </a>
                                        </td>
                                        <td style="text-align: center">{{ optional($result->category)->name }}</td>
                                        <td style="text-align: center">{{ optional($result->subcategory)->name }}</td>
                                        <td style="text-align: center">
                                            {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                        </td>
                                        <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                        <td style="text-align: center">
                                            @php
                                                $tags = $result->marketProductTags ?? collect();
                                                if (($tags instanceof \Illuminate\Support\Collection ? $tags->isEmpty() : empty($tags)) && !empty($result->market_product_tag)) {
                                                    $ids = array_filter(explode(',', $result->market_product_tag));
                                                    if (!empty($ids)) {
                                                        $tags = \App\Models\MarketProductTag::whereIn('id', $ids)->get();
                                                    }
                                                }
                                            @endphp
                                            @if($tags && $tags->count())
                                                @foreach($tags as $tag)
                                                    <span class="badge badge-info" style="margin: 0 2px;">{{ $tag->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">None</span>
                                            @endif
                                        </td>
                                        {{-- Related Documents Column --}}
                                        <td style="text-align: center">
                                            @if($result->related_docs)
                                                @php
                                                    $relatedDocuments = $result->related_documents;
                                                    $relatedCount = $relatedDocuments->count();
                                                @endphp
                                                <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                            @else
                                                <span class="badge badge-secondary">None</span>
                                            @endif
                                        </td>
                                        {{-- End Related Documents Column --}}
                                        <td style="text-align: center;"><span
                                                style=" display:none">{{ $result->year->name }}</span></td>
                                        <td class="tb-odr-action"
                                            style="display: flex !important; align-items: center; justify-content: center">
                                            <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">
                                                @if ($isSubscribed)
                                                    <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}"
                                                        target="_blank"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                        <em class="icon ni ni-book-read"></em>
                                                    </a>
                                                    <a href="{{ route('download', $result->id) }}"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                            class="icon ni ni-download"></em></a>
                                                @else
                                                    @if (Auth::check())
                                                        <a href="{{ route('subscribe') }}" target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                            <em class="icon ni ni-book-read"></em>
                                                        </a>
                                                        <a href="{{ route('subscribe') }}"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                class="icon ni ni-download"></em></a>
                                                    @else
                                                        <a href="{{ route('login') }}" target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                            <em class="icon ni ni-book-read"></em>
                                                        </a>
                                                        <a href="{{ route('login') }}"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                class="icon ni ni-download"></em></a>
                                                    @endif
                                                @endif
                                                <a href="#" id="submit"
                                                    onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                    class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                        class="icon ni ni-save"></em></a>
                                                <form id="save-{{ $result->id }}"
                                                    action="{{ route('save-document', $result->id) }}" method="POST"
                                                    class="d-none" style="display: none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div id="pdf-viewer-{{ $result->id }}">
                                                        <canvas id="canvas-page1-{{ $result->id }}"
                                                            class="pdf-page"></canvas>
                                                        <canvas id="canvas-page2-{{ $result->id }}"
                                                            class="pdf-page"></canvas>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal for Related Documents -->
                                    <div class="modal fade" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">Related Documents</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @php
                                                        $relatedDocuments = $result->related_documents;
                                                    @endphp
                                                    @if($relatedDocuments && $relatedDocuments->count() > 0)
                                                        <div class="card w-100 border-0 shadow-sm">
                                                            <div class="card-header bg-light">
                                                                <h5 class="mb-0 fw-bold">Related Documents</h5>
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <!-- Header Row -->
                                                                <div class="d-flex bg-light border-bottom p-3 fw-bold text-center" style="font-size: 14px;">
                                                                    <div style="width: 8%; min-width: 50px;">S/N</div>
                                                                    <div style="width: 30%; min-width: 200px;">Title</div>
                                                                    <div style="width: 10%; min-width: 80px;">Year</div>
                                                                    <div style="width: 15%; min-width: 120px;">Effective Date</div>
                                                                    <div style="width: 15%; min-width: 120px;">Issued Date</div>
                                                                    <div style="width: 12%; min-width: 100px;">Status</div>
                                                                    <div style="width: 10%; min-width: 120px;">Action</div>
                                                                </div>
                                                                <div class="list-group w-100">
                                                                    @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                        @foreach($relatedDocuments as $index => $relatedDoc)
                                                                            <div class="list-group-item border-0 border-bottom">
                                                                                <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                    <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                        <strong>{{ $index + 1 }}</strong>
                                                                                    </div>
                                                                                    <div style="width: 30%; min-width: 200px;" class="text-truncate">
                                                                                        {{ $relatedDoc->title }}
                                                                                    </div>
                                                                                    <div style="width: 10%; min-width: 80px; text-align: center;">
                                                                                        {{ optional($relatedDoc->year)->name ?? 'N/A' }}
                                                                                    </div>
                                                                                    <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                        {{ $relatedDoc->effective_date ? \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') : 'N/A' }}
                                                                                    </div>
                                                                                    <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                        {{ $relatedDoc->issue_date ? \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') : 'N/A' }}
                                                                                    </div>
                                                                                    <div style="width: 12%; min-width: 100px; text-align: center;">
                                                                                        <span class="badge badge-success">{{ $relatedDoc->ceased ?? 'Active' }}</span>
                                                                                    </div>
                                                                                    <div style="width: 10%; min-width: 120px; text-align: center;">
                                                                                        <div class="d-flex gap-1 justify-content-center">
                                                                                            <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                <em class="icon ni ni-book-read"></em>
                                                                                            </a>
                                                                                            <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                <em class="icon ni ni-download"></em>
                                                                                            </a>
                                                                                            <a href="#" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                <em class="icon ni ni-save"></em>
                                                                                            </a>
                                                                                            <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                                @csrf
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                {{-- Check if this related document has its own related documents --}}
                                                                                @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                    @include('partials.nested-related-documents', [
                                                                                        'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                        'parentIndex' => $index + 1,
                                                                                        'level' => 1,
                                                                                        'isSubscribed' => $isSubscribed || Auth::user()->usertype == 'internal'
                                                                                    ])
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        @foreach($relatedDocuments as $index => $relatedDoc)
                                                                            <div class="list-group-item border-0 border-bottom">
                                                                                <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                    <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                        <strong>{{ $index + 1 }}</strong>
                                                                                    </div>
                                                                                    <div style="width: 72%; min-width: 400px;" class="text-muted">
                                                                                        Restricted - Upgrade to view document details
                                                                                    </div>
                                                                                    <div style="width: 20%; min-width: 120px; text-align: center;">
                                                                                        <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-sm btn-warning">
                                                                                            Upgrade to Access
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                                {{-- Check if this related document has its own related documents --}}
                                                                                @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                    @include('partials.nested-related-documents', [
                                                                                        'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                        'parentIndex' => $index + 1,
                                                                                        'level' => 1,
                                                                                        'isSubscribed' => false
                                                                                    ])
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p>No related documents found.</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal for Related Documents -->
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="gda-cards-container">
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var isPrivileged = {!! ($isSubscribed || (Auth::check() && Auth::user()->usertype == 'internal')) ? 'true' : 'false' !!};
                @foreach ($records as $result)
                    (function(id) {
                        var url = '{{ asset("public/pdf_documents/$result->regulation_doc") }}';
                        var pageCount = {{ $result->page_count ?? 0 }};
                        var pdfjsLib = window['pdfjs-dist/build/pdf'];
                        pdfjsLib.GlobalWorkerOptions.workerSrc =
                            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

                        pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                            // Clear existing content
                            var viewer = document.getElementById('pdf-viewer-' + id);
                            if (!viewer) return;
                            viewer.innerHTML = '';
                            
                            // Use actual page count from PDF if backend didn't provide it
                            var actualPageCount = pageCount > 0 ? pageCount : pdfDoc.numPages;
                            
                            // Implement page-based blur logic (only for non-privileged users)
                            if (actualPageCount === 1) {
                                if (!isPrivileged) {
                                    renderPartialPage(pdfDoc, 1, viewer, 0.5);
                                } else {
                                    renderFullPage(pdfDoc, 1, viewer, false);
                                }
                            } else if (actualPageCount === 2) {
                                renderFullPage(pdfDoc, 1, viewer, false);
                                if (!isPrivileged) {
                                    renderFullPage(pdfDoc, 2, viewer, true);
                                } else {
                                    renderFullPage(pdfDoc, 2, viewer, false);
                                }
                            } else if (actualPageCount >= 3) {
                                renderFullPage(pdfDoc, 1, viewer, false);
                                if (!isPrivileged) {
                                    renderPartialPage(pdfDoc, 2, viewer, 0.5);
                                    for (var i = 3; i <= Math.min(actualPageCount, 5); i++) {
                                        renderFullPage(pdfDoc, i, viewer, true);
                                    }
                                } else {
                                    if (pdfDoc.numPages >= 2) {
                                        renderFullPage(pdfDoc, 2, viewer, false);
                                    }
                                    for (var i = 3; i <= Math.min(actualPageCount, 5); i++) {
                                        renderFullPage(pdfDoc, i, viewer, false);
                                    }
                                }
                            } else {
                                // Fallback: show first 2 pages
                                renderFullPage(pdfDoc, 1, viewer, false);
                                if (pdfDoc.numPages >= 2) {
                                    renderFullPage(pdfDoc, 2, viewer, false);
                                }
                            }
                        }).catch(function(error) {
                            console.error('Error loading PDF:', error);
                            var viewer = document.getElementById('pdf-viewer-' + id);
                            if (viewer) {
                                viewer.innerHTML = '<p class="text-center text-muted">Error loading PDF preview.</p>';
                            }
                        });
                        
                        // Function to render a full page
                        function renderFullPage(pdfDoc, pageNum, viewer, blurred) {
                            if (pageNum > pdfDoc.numPages) return;
                            
                            pdfDoc.getPage(pageNum).then(function(page) {
                                var viewport = page.getViewport({ scale: 1.5 });
                                var canvas = document.createElement('canvas');
                                canvas.className = 'pdf-page';
                                if (blurred) {
                                    canvas.className += ' blurred';
                                }
                                canvas.id = 'canvas-page' + pageNum + '-' + id;
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                
                                viewer.appendChild(canvas);
                                page.render(renderContext);
                            });
                        }
                        
                        // Function to render a partial page with blur effect
                        function renderPartialPage(pdfDoc, pageNum, viewer, visibleRatio) {
                            if (pageNum > pdfDoc.numPages) return;
                            
                            pdfDoc.getPage(pageNum).then(function(page) {
                                var viewport = page.getViewport({ scale: 1.5 });
                                var canvas = document.createElement('canvas');
                                canvas.className = 'pdf-page partial-page';
                                canvas.id = 'canvas-page' + pageNum + '-' + id;
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                
                                viewer.appendChild(canvas);
                                page.render(renderContext).promise.then(function() {
                                    // Apply blur effect to the hidden portion
                                    if (visibleRatio < 1) {
                                        var ctx = canvas.getContext('2d');
                                        var height = canvas.height;
                                        var hiddenStart = height * visibleRatio;
                                        
                                        // Apply blur to the hidden portion
                                        ctx.filter = 'blur(5px)';
                                        ctx.globalAlpha = 0.7;
                                        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                                        ctx.fillRect(0, hiddenStart, canvas.width, height - hiddenStart);
                                        
                                        // Add gradient mask for smoother transition
                                        var gradient = ctx.createLinearGradient(0, hiddenStart - 50, 0, hiddenStart);
                                        gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
                                        gradient.addColorStop(1, 'rgba(255, 255, 255, 1)');
                                        ctx.filter = 'none';
                                        ctx.globalAlpha = 1;
                                        ctx.fillStyle = gradient;
                                        ctx.fillRect(0, hiddenStart - 50, canvas.width, 50);
                                    }
                                });
                            });
                        }
                    })({{ $result->id }});
                @endforeach
            });
        </script>