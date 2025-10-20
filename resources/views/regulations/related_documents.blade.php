@extends('layouts.master')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- Header -->
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Related Documents - {{ $regulation->title }}</h3>
                            <div class="nk-block-des text-soft">
                                Manage document relationships and version histories
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('regulations.index') }}" class="btn btn-outline-primary">
                                <em class="icon ni ni-arrow-left"></em>
                                <span>Back to Documents</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Alerts -->
                <div class="example-alert">
                    @if (\Session::has('success'))
                        <div class="alert alert-success alert-icon alert-dismissible">
                            <em class="icon ni ni-check-circle"></em>
                            <strong>{{ \Session::get('success') }}</strong>
                            <button class="close" data-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (\Session::has('error'))
                        <div class="alert alert-danger alert-icon alert-dismissible">
                            <em class="icon ni ni-cross-circle"></em>
                            <strong>{{ \Session::get('error') }}</strong>
                            <button class="close" data-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-icon alert-dismissible">
                            <strong>Opps!</strong> Something went wrong, please check below errors.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button class="close" data-dismiss="alert"></button>
                        </div>
                    @endif
                </div>

                <!-- Tabs -->
                <div class="nk-block nk-block-lg">
                    <div class="card card-preview">
                        <div class="card-inner">
                            <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#related-docs">
                                        <em class="icon ni ni-link"></em>
                                        <span>Related Documents</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#version-history">
                                        <em class="icon ni ni-clock"></em>
                                        <span>Version History</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#add-relationship">
                                        <em class="icon ni ni-plus"></em>
                                        <span>Add Relationship</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Related Documents Tab -->
                                <div class="tab-pane active" id="related-docs">
                                    <div class="nk-block">
                                        <div class="nk-block-head">
                                            <div class="nk-block-head-content">
                                                <h5 class="nk-block-title">Related Documents</h5>
                                                <div class="nk-block-des">
                                                    Documents that have relationships with this document
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-block-content">
                                            @if($relatedDocuments->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Title</th>
                                                                <th>Relationship Type</th>
                                                                <th>Product Type</th>
                                                                <th>Status</th>
                                                                <th>Notes</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($relatedDocuments as $doc)
                                                                <tr>
                                                                    <td>
                                                                        <strong>{{ $doc->formatted_title }}</strong><br>
                                                                        <small class="text-muted">Version: {{ $doc->document_version }}</small>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-primary">{{ $doc->relationship_type }}</span>
                                                                    </td>
                                                                    <td>{{ $doc->product_type ?? 'N/A' }}</td>
                                                                    <td>{{ $doc->status ?? 'N/A' }}</td>
                                                                    <td>{{ Str::limit($doc->relationship_notes, 50) }}</td>
                                                                    <td>
                                                                        <a href="{{ route('view_doc', $doc->id) }}" class="btn btn-sm btn-outline-primary">
                                                                            <em class="icon ni ni-eye"></em>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <em class="icon ni ni-link icon-3x text-muted"></em>
                                                    <h5 class="mt-3">No Related Documents</h5>
                                                    <p class="text-muted">No documents are currently related to this document.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Version History Tab -->
                                <div class="tab-pane" id="version-history">
                                    <div class="nk-block">
                                        <div class="nk-block-head">
                                            <div class="nk-block-head-content">
                                                <h5 class="nk-block-title">Version History</h5>
                                                <div class="nk-block-des">
                                                    Other versions of this document
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-block-content">
                                            @if($versionHistory->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Version</th>
                                                                <th>Effective Date</th>
                                                                <th>Issue Date</th>
                                                                <th>Status</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($versionHistory as $version)
                                                                <tr>
                                                                    <td>
                                                                        <strong>{{ $version->document_version }}</strong>
                                                                    </td>
                                                                    <td>{{ $version->effective_date }}</td>
                                                                    <td>{{ $version->issue_date }}</td>
                                                                    <td>
                                                                        @if($version->admin_status == 1)
                                                                            <span class="badge badge-success">Approved</span>
                                                                        @elseif($version->admin_status == 0)
                                                                            <span class="badge badge-warning">Pending</span>
                                                                        @elseif($version->admin_status == 2)
                                                                            <span class="badge badge-danger">Rejected</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('view_doc', $version->id) }}" class="btn btn-sm btn-outline-primary">
                                                                            <em class="icon ni ni-eye"></em>
                                                                        </a>
                                                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                                                data-toggle="modal" 
                                                                                data-target="#linkVersionModal"
                                                                                data-version-id="{{ $version->id }}">
                                                                            <em class="icon ni ni-link"></em>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <em class="icon ni ni-clock icon-3x text-muted"></em>
                                                    <h5 class="mt-3">No Version History</h5>
                                                    <p class="text-muted">No other versions of this document exist.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Relationship Tab -->
                                <div class="tab-pane" id="add-relationship">
                                    <div class="nk-block">
                                        <div class="nk-block-head">
                                            <div class="nk-block-head-content">
                                                <h5 class="nk-block-title">Add Document Relationship</h5>
                                                <div class="nk-block-des">
                                                    Link this document to other documents
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-block-content">
                                            <form method="POST" action="{{ route('document-relationships.store') }}">
                                                @csrf
                                                <input type="hidden" name="source_document_id" value="{{ $regulation->id }}">
                                                
                                                <div class="row gy-4">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="related_document_id">Related Document <span class="text-danger">*</span></label>
                                                            <select class="form-select" name="related_document_id" id="related_document_id" required>
                                                                <option value="">Select a document...</option>
                                                                @foreach($allRegulations as $reg)
                                                                    <option value="{{ $reg->id }}">{{ $reg->title }} (v{{ $reg->document_version }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="relationship_type">Relationship Type <span class="text-danger">*</span></label>
                                                            <select class="form-select" name="relationship_type" id="relationship_type" required>
                                                                <option value="">Select relationship type...</option>
                                                                <option value="Supersedes">Supersedes</option>
                                                                <option value="Amended">Amended</option>
                                                                <option value="Ceased">Ceased</option>
                                                                <option value="Repealed">Repealed</option>
                                                                <option value="Active Amendment">Active Amendment</option>
                                                                <option value="Reference">Reference</option>
                                                                <option value="Related">Related</option>
                                                                <option value="Superseded By">Superseded By</option>
                                                                <option value="Amended By">Amended By</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="product_type">Product Type</label>
                                                            <select class="form-select" name="product_type" id="product_type">
                                                                <option value="">Select product type...</option>
                                                                <option value="Bonds">Bonds</option>
                                                                <option value="Treasury Bills">Treasury Bills</option>
                                                                <option value="Equities">Equities</option>
                                                                <option value="Derivatives">Derivatives</option>
                                                                <option value="Money Market">Money Market</option>
                                                                <option value="Foreign Exchange">Foreign Exchange</option>
                                                                <option value="General">General</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="status">Status</label>
                                                            <input type="text" class="form-control" name="status" id="status" placeholder="Relationship status">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="notes">Notes</label>
                                                            <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Additional notes about this relationship"></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary">
                                                            <em class="icon ni ni-plus"></em>
                                                            <span>Create Relationship</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link Version History Modal -->
<div class="modal fade" id="linkVersionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Link Version History</h5>
                <a href="#" class="close" data-dismiss="modal">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form method="POST" action="{{ route('document-relationships.link-version-history') }}">
                @csrf
                <input type="hidden" name="source_document_id" value="{{ $regulation->id }}">
                <input type="hidden" name="relationship_type" value="Version History">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Select versions to link:</label>
                        <div class="form-control-wrap">
                            @foreach($versionHistory as $version)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" 
                                           name="related_document_ids[]" 
                                           value="{{ $version->id }}" 
                                           id="version_{{ $version->id }}">
                                    <label class="custom-control-label" for="version_{{ $version->id }}">
                                        Version {{ $version->document_version }} ({{ $version->effective_date }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="modal_product_type">Product Type</label>
                        <select class="form-select" name="product_type" id="modal_product_type">
                            <option value="">Select product type...</option>
                            <option value="Bonds">Bonds</option>
                            <option value="Treasury Bills">Treasury Bills</option>
                            <option value="Equities">Equities</option>
                            <option value="Derivatives">Derivatives</option>
                            <option value="Money Market">Money Market</option>
                            <option value="Foreign Exchange">Foreign Exchange</option>
                            <option value="General">General</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Link Versions</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle version linking modal
    $('#linkVersionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var versionId = button.data('version-id');
        
        // Clear all checkboxes first
        $('input[name="related_document_ids[]"]').prop('checked', false);
        
        // Check the specific version if provided
        if (versionId) {
            $('#version_' + versionId).prop('checked', true);
        }
    });
});
</script>
@endsection
