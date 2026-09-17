@extends('layouts.headerexternal')

@section('content')
<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

<style>
    .apply-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 40px 20px 80px;
        font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .apply-nav-bar {
        width: 100%;
        max-width: 680px;
        margin-bottom: 20px;
        display: flex;
        justify-content: flex-start;
    }

    .btn-apply-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.95);
        color: #0c2b70 !important;
        font-weight: 600;
        font-size: 13.5px;
        padding: 8px 16px;
        border-radius: 30px;
        text-decoration: none !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.25s ease;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .btn-apply-back:hover {
        background: #ffffff;
        color: #0c2b70 !important;
        transform: translateX(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .apply-card {
        width: 100%;
        max-width: 680px;
        background: #ffffff;
        border-radius: 24px;
        padding: 44px 40px;
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.35), 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.8);
        position: relative;
    }

    @media (max-width: 576px) {
        .apply-card {
            padding: 30px 20px;
            border-radius: 18px;
        }
    }

    .academic-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fef9ed;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 30px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 14px;
    }

    .apply-header {
        text-align: center;
        margin-bottom: 26px;
    }

    .apply-header h2 {
        color: #0c2b70;
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }

    .apply-header p {
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
        margin: 0 auto;
        max-width: 540px;
    }

    /* Benefits Strip */
    .benefits-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 28px;
    }

    @media (max-width: 576px) {
        .benefits-strip {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }

    .benefit-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px 6px;
    }

    .benefit-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #eff6ff;
        color: #1a3a8f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        border: 1px solid #dbeafe;
    }

    .benefit-text {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.3;
    }

    .benefit-subtext {
        font-size: 11px;
        font-weight: 400;
        color: #64748b;
    }

    /* Form Fields */
    .apply-form .form-group {
        margin-bottom: 22px;
    }

    .apply-form label {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #1e293b;
        font-weight: 600;
        font-size: 13.5px;
        margin-bottom: 8px;
    }

    .apply-form label em {
        color: #0c2b70;
        font-size: 16px;
    }

    .input-wrap-custom {
        position: relative;
    }

    .apply-form input[type="text"] {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 14px;
        font-family: inherit;
        background: #ffffff;
        outline: none;
        transition: all 0.2s ease;
    }

    .apply-form input[type="text"]:focus {
        border-color: #0c2b70;
        box-shadow: 0 0 0 3px rgba(12, 43, 112, 0.12);
    }

    /* Elevated File Dropzone Area */
    .file-dropzone-box {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        background: #fafbfc;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        position: relative;
        transition: all 0.25s ease;
    }

    .file-dropzone-box:hover {
        border-color: #0c2b70;
        background: #f0f7ff;
    }

    .file-dropzone-box.has-file {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .file-dropzone-box input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .dropzone-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1a3a8f;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }

    .file-dropzone-box:hover .dropzone-icon-circle {
        transform: translateY(-2px);
        background: #0c2b70;
        color: #ffffff;
    }

    .file-dropzone-box.has-file .dropzone-icon-circle {
        background: #ecfdf5;
        color: #059669;
    }

    .dropzone-main-text {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .dropzone-sub-text {
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .dropzone-specs {
        display: inline-block;
        background: #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .file-selected-indicator {
        display: none;
        margin-top: 10px;
        padding: 8px 14px;
        background: #ffffff;
        border: 1px solid #a7f3d0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #065f46;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    /* Privacy Security Callout */
    .privacy-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 26px;
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.5;
    }

    .privacy-notice em {
        color: #10b981;
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* Submit Button */
    .apply-submit {
        width: 100% !important;
        background: linear-gradient(135deg, #0c2b70 0%, #1a3a8f 100%);
        color: #ffffff !important;
        border: none !important;
        border-radius: 30px !important;
        padding: 15px 24px !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        transition: all 0.25s ease !important;
        box-shadow: 0 8px 20px rgba(12, 43, 112, 0.28) !important;
    }

    .apply-submit:hover {
        background: linear-gradient(135deg, #143ca3 0%, #204dbf 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 26px rgba(12, 43, 112, 0.38) !important;
    }

    /* Custom Alert */
    .custom-alert {
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
    }
</style>

<div class="apply-container">
    <!-- Back to Subscriptions Navigation -->
    <div class="apply-nav-bar">
        <a href="{{ url('subscribe') }}" class="btn-apply-back">
            <em class="icon ni ni-arrow-left"></em> Back to Subscription Plans
        </a>
    </div>

    <div class="apply-card">
        <!-- Header -->
        <div class="apply-header">
            <div class="academic-badge">
                <em class="icon ni ni-cap"></em> Academic Grant Program
            </div>
            <h2>Apply for Student Research Access</h2>
            <p>
                Get complimentary 30-day, view-only access to financial market rules, regulations, and circulars for your academic dissertation, thesis, or coursework.
            </p>
        </div>

        <!-- Benefits Strip -->
        <div class="benefits-strip">
            <div class="benefit-item">
                <div class="benefit-icon">
                    <em class="icon ni ni-calendar"></em>
                </div>
                <div>
                    <div class="benefit-text">30-Day Pass</div>
                    <div class="benefit-subtext">Complimentary view access</div>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-icon">
                    <em class="icon ni ni-book-read"></em>
                </div>
                <div>
                    <div class="benefit-text">Full Database</div>
                    <div class="benefit-subtext">Rules, circulars & codes</div>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-icon">
                    <em class="icon ni ni-shield-check"></em>
                </div>
                <div>
                    <div class="benefit-text">FMDQ Verified</div>
                    <div class="benefit-subtext">Official compliance review</div>
                </div>
            </div>
        </div>

        <!-- Flash Alerts -->
        @if (\Session::has('success'))
            <div class="alert alert-success custom-alert">
                <em class="icon ni ni-check-circle" style="font-size: 18px;"></em>
                <div>{{ \Session::get('success') }}</div>
            </div>
        @endif

        @if (\Session::has('error'))
            <div class="alert alert-danger custom-alert">
                <em class="icon ni ni-alert-circle" style="font-size: 18px;"></em>
                <div>{{ \Session::get('error') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger custom-alert" style="display: block;">
                <div class="d-flex align-items-center mb-1">
                    <em class="icon ni ni-cross-circle mr-1" style="font-size: 18px;"></em>
                    <strong>Please review the following errors:</strong>
                </div>
                <ul style="margin: 0; padding-left: 22px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Application Form -->
        <form class="apply-form" method="POST" action="{{ route('studentVerification.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="institution_name">
                    <em class="icon ni ni-building"></em> Academic Institution / University <span class="text-danger">*</span>
                </label>
                <div class="input-wrap-custom">
                    <input type="text" name="institution_name" id="institution_name" required 
                        placeholder="e.g. University of Lagos, Covenant University, Pan-Atlantic University"
                        value="{{ old('institution_name') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="student_id_number">
                    <em class="icon ni ni-id-card"></em> Student ID / Matric Number <span class="text-muted" style="font-size: 12px; font-weight: 400;">(Optional)</span>
                </label>
                <div class="input-wrap-custom">
                    <input type="text" name="student_id_number" id="student_id_number" 
                        placeholder="e.g. MAT/2023/10892"
                        value="{{ old('student_id_number') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="proof">
                    <em class="icon ni ni-file-docs"></em> Proof of Enrollment / Student ID <span class="text-danger">*</span>
                </label>

                <!-- Modern Drag-and-Drop Area -->
                <div class="file-dropzone-box" id="dropzoneBox">
                    <input type="file" name="proof" id="proof" required accept=".pdf,.jpg,.jpeg,.png">
                    <div class="dropzone-icon-circle">
                        <em class="icon ni ni-upload-cloud"></em>
                    </div>
                    <div class="dropzone-main-text" id="dropzoneText">
                        Click to select or drag & drop your student proof
                    </div>
                    <div class="dropzone-sub-text">
                        Student ID card, admission letter, or current semester registration slip
                    </div>
                    <span class="dropzone-specs">Accepted: PDF, JPG, PNG (Max 5MB)</span>

                    <div class="file-selected-indicator" id="fileIndicator">
                        <em class="icon ni ni-check-circle"></em>
                        <span id="selectedFileName">Document selected</span>
                    </div>
                </div>
            </div>

            <!-- Privacy & Storage Notice -->
            <div class="privacy-notice">
                <em class="icon ni ni-shield-check"></em>
                <div>
                    <strong>Data Privacy Guaranteed:</strong> Your proof of enrollment is stored securely and encrypted. It is solely reviewed by authorized FMDQ compliance officers to confirm student eligibility.
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="apply-submit">
                <em class="icon ni ni-send"></em> Submit Research Access Application
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('proof');
        const dropzoneBox = document.getElementById('dropzoneBox');
        const fileIndicator = document.getElementById('fileIndicator');
        const selectedFileName = document.getElementById('selectedFileName');
        const dropzoneText = document.getElementById('dropzoneText');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    selectedFileName.textContent = `${file.name} (${fileSizeMB} MB)`;
                    fileIndicator.style.display = 'inline-flex';
                    dropzoneBox.classList.add('has-file');
                    dropzoneText.textContent = 'File ready to upload';
                } else {
                    fileIndicator.style.display = 'none';
                    dropzoneBox.classList.remove('has-file');
                    dropzoneText.textContent = 'Click to select or drag & drop your student proof';
                }
            });

            // Drag over effects
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzoneBox.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzoneBox.style.borderColor = '#0c2b70';
                    dropzoneBox.style.backgroundColor = '#eff6ff';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzoneBox.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzoneBox.style.borderColor = '';
                    dropzoneBox.style.backgroundColor = '';
                }, false);
            });
        }
    });
</script>
@endsection
