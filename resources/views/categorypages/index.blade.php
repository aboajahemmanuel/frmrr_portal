<table id="example" class="datatable-init responsive table table-striped"
style="width:100%">
<thead>
    <tr>
        <th style="text-align: center;">Title</th>
        <th style="text-align: center;">Version Number</th>
        <th style="text-align: center;">Issue Date</th>
        <th style="text-align: center;">Year</th>
        <th style="text-align: center;">Effective Date</th>
        <th style="text-align: center;">Entity</th>
        <th style="text-align: center;">Related Docs</th>
        <th style="text-align: center;">Action</th>
    </tr>
</thead>
<tbody>
    @foreach ($reg as $result)
        <tr>
              <td>

@if ($result->doc_preview == 1)
 <a href="#" data-toggle="modal"
                        data-target="#pdfModal-{{ $result->id }}">
                        {{ $result->formatted_title }} <em class="icon ni ni-zoom-in"></em>

                    </a>
@else
    {{ $result->formatted_title }}
@endif

</td>
            <td style="text-align: center">{{ $result->document_version }}</td>
            <td style="text-align: center">
                {{-- {{ \Carbon\Carbon::parse($result->issue_date)->format('M. d, Y') }} --}}
                  {{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}
            </td>
            <td style="text-align: center">{{ $result->year->name }}</td>
            <td style="text-align: center">
                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
            </td>
            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
            
            {{-- Related Documents Column --}}
            <td style="text-align: center">
                @if($result->related_docs)
                    @php
                        $relatedDocuments = $result->related_documents;
                        $relatedCount = $relatedDocuments->count();
                    @endphp
                    @if ($isSubscribed || Auth::user()->usertype == 'internal')
                        <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                    @else
                        <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                    @endif
                @else
                    <span class="badge badge-secondary">None</span>
                @endif
            </td>
            {{-- End Related Documents Column --}}
            
            <td class="tb-odr-action"
                style="display: flex !important; align-items: center; justify-content: center">
                <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">





                    @if ($isSubscribed || Auth::user()->usertype == 'internal' )
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
                        action="{{ route('save-document', $result->id) }}"
                        method="POST" class="d-none" style="display: none">
                        @csrf

                    </form>




                </div>

            </td>
        </tr>

        <!-- Modal for PDF Preview -->
        <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1"
            role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <div id="pdf-viewer-{{ $result->id }}">
                            <!-- Canvas elements will be dynamically added based on page count -->
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
                        <h5 class="" id="relatedDocsModalLabel-{{ $result->id }}">{{ $result->title }}</h5>
                       
                    </div>
                    <div class="modal-body">
                      
                                           @php
                        $relatedDocuments = $result->related_documents;
                    @endphp
                    @php
                        $relatedDocuments = $result->related_documents;
                    @endphp

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
                                                        'level' => 1
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
                                                    <div style="width: 30%; min-width: 200px;" class="text-truncate">
                                                        <span class="text-muted">Restricted - Upgrade to view</span>
                                                    </div>
                                                    <div style="width: 10%; min-width: 80px; text-align: center;">
                                                        <span class="text-muted">-</span>
                                                    </div>
                                                    <div style="width: 15%; min-width: 120px; text-align: center;">
                                                        <span class="text-muted">-</span>
                                                    </div>
                                                    <div style="width: 15%; min-width: 120px; text-align: center;">
                                                        <span class="text-muted">-</span>
                                                    </div>
                                                    <div style="width: 12%; min-width: 100px; text-align: center;">
                                                        <span class="text-muted">-</span>
                                                    </div>
                                                    <div style="width: 10%; min-width: 120px; text-align: center;">
                                                        <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-sm btn-warning">
                                                            Upgrade
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                  
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal for Related Documents -->
        <!-- End Modal for Related Documents -->
    @endforeach
</tbody>
</table>