@extends('layouts.master')

@section('content')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Session Settings</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Configure the session timeout period for all users</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="more-options">
                                        <ul class="nk-block-tools g-3">
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    
                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-lg-6">
                                <div class="card card-preview">
                                    <div class="card-inner">
                                        @if(session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        
                                        @if($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.session-settings.update') }}">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="form-group">
                                                <label class="form-label" for="timeout_minutes">Timeout Period (minutes)</label>
                                                <div class="form-control-wrap">
                                                    <select name="timeout_minutes" class="form-control" id="timeout_minutes" required>
                                                        <option value="2" {{ (old('timeout_minutes', $setting->timeout_minutes ?? 2) == 2) ? 'selected' : '' }}>2 minutes</option>
                                                        <option value="5" {{ (old('timeout_minutes', $setting->timeout_minutes ?? 5) == 5) ? 'selected' : '' }}>5 minutes</option>
                                                        <option value="10" {{ (old('timeout_minutes', $setting->timeout_minutes ?? 15) == 10) ? 'selected' : '' }}>10 minutes</option>
                                                        <option value="15" {{ (old('timeout_minutes', $setting->timeout_minutes ?? 15) == 15) ? 'selected' : '' }}>15 minutes</option>
                                                        <option value="20" {{ (old('timeout_minutes', $setting->timeout_minutes ?? 15) == 20) ? 'selected' : '' }}>20 minutes</option>
                                                    </select>
                                                </div>
                                                <div class="form-note">
                                                    Select the inactivity timeout period before users are automatically logged out.
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update Settings</button>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- .card-preview -->
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection