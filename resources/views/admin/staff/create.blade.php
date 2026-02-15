<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Staff') }}
        </h2>
    </x-slot>

    <!-- Load Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card Container -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100"
                style="font-family: 'Outfit', sans-serif;">

                <style>
                    /* --- Variables & Base --- */
                    :root {
                        --primary: #FB5200;
                        --primary-hover: #d14400;
                        --gray-light: #f9fafb;
                        --gray-border: #e5e7eb;
                        --text-dark: #111827;
                        --text-gray: #6b7280;
                        --radius: 12px;
                    }

                    /* --- Wizard Header --- */
                    .gbs-wizard-top-steps {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 24px 40px;
                        background: #ffffff;
                        border-bottom: 1px solid var(--gray-border);
                    }

                    .gbs-step-h-item {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        gap: 10px;
                        position: relative;
                        z-index: 2;
                        opacity: 1;
                        /* Keep visible but use colors to denote state */
                    }

                    .gbs-step-h-circle {
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        background: white;
                        border: 2px solid #e5e7eb;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 600;
                        font-size: 14px;
                        color: #9ca3af;
                        transition: all 0.3s ease;
                        position: relative;
                        z-index: 10;
                    }

                    .gbs-step-h-label {
                        font-size: 0.8rem;
                        font-weight: 600;
                        color: #9ca3af;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                        transition: color 0.3s ease;
                    }

                    /* Active State */
                    .gbs-step-h-item.active .gbs-step-h-circle {
                        border-color: var(--primary);
                        color: white;
                        background: var(--primary);
                        box-shadow: 0 0 0 4px rgba(251, 82, 0, 0.15);
                        transform: scale(1.05);
                    }

                    .gbs-step-h-item.active .gbs-step-h-label {
                        color: var(--primary);
                    }

                    /* Completed State */
                    .gbs-step-h-item.completed .gbs-step-h-circle {
                        background: #10b981;
                        /* Success Green */
                        border-color: #10b981;
                        color: white;
                    }

                    .gbs-step-h-item.completed .gbs-step-h-label {
                        color: #10b981;
                    }

                    /* Connecting Line */
                    .gbs-step-h-line {
                        flex: 1;
                        height: 2px;
                        background: #f3f4f6;
                        margin: 0 15px;
                        margin-bottom: 24px;
                        /* Align with circle center roughly */
                        position: relative;
                        top: -12px;
                        z-index: 1;
                    }

                    .gbs-step-h-line.filled {
                        background: #10b981;
                    }

                    /* --- Wizard Content --- */
                    .gbs-wizard-content {
                        padding: 40px;
                        min-height: 450px;
                        background: #ffffff;
                    }

                    .gbs-step-pane {
                        display: none;
                        animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                    }

                    .gbs-step-pane.active {
                        display: block;
                    }

                    @keyframes slideUpFade {
                        from {
                            opacity: 0;
                            transform: translateY(10px);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    /* --- Form Inputs --- */
                    .gbs-input-group {
                        margin-bottom: 24px;
                        position: relative;
                    }

                    .gbs-input-label {
                        display: block;
                        font-size: 0.9rem;
                        font-weight: 600;
                        color: var(--text-dark);
                        margin-bottom: 8px;
                    }

                    .gbs-input-modern {
                        width: 100%;
                        padding: 12px 16px;
                        border: 1px solid #e5e7eb;
                        border-radius: 10px;
                        font-size: 0.95rem;
                        color: var(--text-dark);
                        background: #f9fafb;
                        transition: all 0.2s ease;
                        outline: none;
                        font-family: inherit;
                    }

                    .gbs-input-modern:hover {
                        background: #ffffff;
                        border-color: #d1d5db;
                    }

                    .gbs-input-modern:focus {
                        background: #ffffff;
                        border-color: var(--primary);
                        box-shadow: 0 0 0 4px rgba(251, 82, 0, 0.1);
                    }

                    .gbs-input-modern.error {
                        border-color: #ef4444;
                        background: #fef2f2;
                    }

                    .gbs-input-modern.error:focus {
                        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
                    }

                    /* Error Message */
                    .gbs-error-feedback {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        color: #ef4444;
                        font-size: 0.8rem;
                        font-weight: 500;
                        margin-top: 6px;
                        animation: fadeIn 0.2s;
                    }

                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                        }

                        to {
                            opacity: 1;
                        }
                    }

                    /* --- Connect Card --- */
                    .gbs-connect-card {
                        display: flex;
                        align-items: center;
                        padding: 16px;
                        border: 1px solid #e5e7eb;
                        border-radius: 12px;
                        cursor: pointer;
                        transition: all 0.2s;
                        background: white;
                        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                    }

                    .gbs-connect-card:hover {
                        border-color: var(--primary);
                        box-shadow: 0 4px 12px rgba(251, 82, 0, 0.08);
                        transform: translateY(-1px);
                    }

                    .gbs-connect-icon {
                        width: 48px;
                        height: 48px;
                        background: #eff6ff;
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 16px;
                    }

                    .gbs-connect-title {
                        font-weight: 600;
                        color: var(--text-dark);
                        font-size: 1rem;
                    }

                    .gbs-connect-sub {
                        font-size: 0.85rem;
                        color: var(--text-gray);
                    }

                    /* --- Wizard Footer --- */
                    .gbs-wizard-footer {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 24px 40px;
                        border-top: 1px solid var(--gray-border);
                        background: #f9fafb;
                    }

                    .gbs-btn-primary-clean {
                        background: var(--primary);
                        color: white;
                        border: none;
                        padding: 12px 28px;
                        border-radius: 10px;
                        font-weight: 600;
                        font-size: 0.95rem;
                        cursor: pointer;
                        transition: all 0.2s;
                        box-shadow: 0 2px 4px rgba(251, 82, 0, 0.2);
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        gap: 8px;
                    }

                    .gbs-btn-primary-clean:hover {
                        background: var(--primary-hover);
                        transform: translateY(-1px);
                        box-shadow: 0 4px 8px rgba(251, 82, 0, 0.3);
                    }

                    .gbs-btn-secondary-clean {
                        background: white;
                        border: 1px solid #d1d5db;
                        color: #374151;
                        padding: 12px 24px;
                        border-radius: 10px;
                        font-weight: 600;
                        font-size: 0.95rem;
                        cursor: pointer;
                        transition: all 0.2s;
                    }

                    .gbs-btn-secondary-clean:hover {
                        background: #f3f4f6;
                        color: #111827;
                        border-color: #9ca3af;
                    }

                    /* --- Services Grid --- */
                    .gbs-services-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 16px;
                    }

                    .gbs-service-card {
                        position: relative;
                        background: white;
                        border: 1px solid #e5e7eb;
                        border-radius: 12px;
                        padding: 16px;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    }

                    .gbs-service-card:hover {
                        border-color: #d1d5db;
                        background: #f9fafb;
                    }

                    .gbs-service-card.selected {
                        border-color: var(--primary);
                        background: #fff7ed;
                        /* orange-50 */
                        box-shadow: 0 0 0 1px var(--primary);
                    }

                    .gbs-service-checkbox {
                        width: 20px;
                        height: 20px;
                        accent-color: var(--primary);
                    }

                    .gbs-service-label {
                        font-weight: 600;
                        color: var(--text-dark);
                        cursor: pointer;
                        flex: 1;
                    }
                </style>

                <!-- Wizard Container -->
                <div class="gbs-wizard-container">
                    <!-- Top Steps -->
                    <div class="gbs-wizard-top-steps">
                        <!-- Step 1 -->
                        <div class="gbs-step-h-item active" data-step="1">
                            <div class="gbs-step-h-circle">1</div>
                            <span class="gbs-step-h-label">General Info</span>
                        </div>
                        <div class="gbs-step-h-line"></div>
                        <!-- Step 2 -->
                        <div class="gbs-step-h-item" data-step="2">
                            <div class="gbs-step-h-circle">2</div>
                            <span class="gbs-step-h-label">Calendar</span>
                        </div>
                        <div class="gbs-step-h-line"></div>
                        <!-- Step 3 -->
                        <div class="gbs-step-h-item" data-step="3">
                            <div class="gbs-step-h-circle">3</div>
                            <span class="gbs-step-h-label">Services</span>
                        </div>
                        <div class="gbs-step-h-line"></div>
                        <!-- Step 4 -->
                        <div class="gbs-step-h-item" data-step="4">
                            <div class="gbs-step-h-circle">4</div>
                            <span class="gbs-step-h-label">Schedule</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="gbs-wizard-content">
                        <form id="form-add-staff-wizard" action="{{ route('admin.staff.store') }}" method="POST">
                            @csrf

                            <!-- Step 1: General Info -->
                            <div class="gbs-step-pane active" id="step-pane-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">Staff Details</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="gbs-input-group">
                                        <label for="wiz-staff-name" class="gbs-input-label">Full Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" id="wiz-staff-name" name="name"
                                            class="gbs-input-modern @error('name') error @enderror"
                                            value="{{ old('name') }}" placeholder="e.g. John Doe">

                                        <!-- Error Container -->
                                        <div class="gbs-error-feedback" id="error-name"
                                            style="display: {{ $errors->has('name') ? 'flex' : 'none' }}">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            <span>{{ $errors->first('name') ?? 'Please enter a valid name.' }}</span>
                                        </div>
                                    </div>

                                    <div class="gbs-input-group">
                                        <label for="wiz-staff-email" class="gbs-input-label">Email Address <span
                                                class="text-red-500">*</span></label>
                                        <input type="email" id="wiz-staff-email" name="email"
                                            class="gbs-input-modern @error('email') error @enderror"
                                            value="{{ old('email') }}" placeholder="john@example.com">

                                        <div class="gbs-error-feedback" id="error-email"
                                            style="display: {{ $errors->has('email') ? 'flex' : 'none' }}">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            <span>{{ $errors->first('email') ?? 'Please enter a valid email.' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="gbs-input-group">
                                        <label for="wiz-staff-phone" class="gbs-input-label">Phone Number</label>
                                        <input type="tel" id="wiz-staff-phone" name="phone" class="gbs-input-modern"
                                            value="{{ old('phone') }}" placeholder="+1 234 567 890">
                                    </div>
                                </div>

                                <div class="gbs-input-group">
                                    <label for="wiz-staff-info" class="gbs-input-label">Additional Info / Bio</label>
                                    <textarea id="wiz-staff-info" name="info" class="gbs-input-modern" rows="4"
                                        placeholder="Short bio or notes about this staff member...">{{ old('info') }}</textarea>
                                </div>
                            </div>

                            <!-- Step 2: Google Calendar -->
                            <div class="gbs-step-pane" id="step-pane-2">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">Calendar Settings</h3>

                                <div class="gbs-input-group mb-8">
                                    <label class="gbs-input-label mb-3">Daily Working Hours Limit</label>
                                    <style>
                                        /* Hide spinners */
                                        input[type=number]::-webkit-inner-spin-button,
                                        input[type=number]::-webkit-outer-spin-button {
                                            -webkit-appearance: none;
                                            margin: 0;
                                        }

                                        input[type=number] {
                                            -moz-appearance: textfield;
                                        }
                                    </style>
                                    <div
                                        class="gbs-counter-wrapper inline-flex items-center bg-white border border-gray-300 rounded-lg p-1">
                                        <button type="button" id="btn-limit-minus"
                                            class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded text-gray-600 transition-colors">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                        <div class="flex items-center gap-2 px-4">
                                            <input type="number" id="wiz-staff-limit-hours" name="limit_hours" value="8"
                                                min="1" max="24"
                                                class="w-16 text-center text-xl font-bold text-gray-900 border-none p-0 focus:ring-0 bg-transparent">
                                            <span class="text-sm text-gray-500 font-medium">Hours</span>
                                        </div>
                                        <button type="button" id="btn-limit-plus"
                                            class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 rounded text-gray-600 transition-colors">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-8">
                                    <label class="gbs-input-label flex items-center gap-2 mb-4">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                                fill="#4285F4" />
                                            <path
                                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                                fill="#34A853" />
                                            <path
                                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.24l-.19-.6z"
                                                fill="#FBBC05" />
                                            <path
                                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.57 2.77c.87-2.6 3.3-4.46 6.25-4.46z"
                                                fill="#EA4335" />
                                        </svg>
                                        Google Integration
                                    </label>
                                    @if(isset($google_account) && $google_account)
                                        <div
                                            class="gbs-connect-card max-w-md w-full border border-gray-200 bg-white flex items-start p-4 rounded-xl shadow-sm">
                                            <div class="mr-4">
                                                <!-- Google Calendar Icon Approximation -->
                                                <svg width="40" height="40" viewBox="0 0 48 48" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="8" y="10" width="32" height="30" rx="4" fill="white" />
                                                    <path d="M13 9H35V4H13V9Z" fill="#1967D2" />
                                                    <!-- Top Blue Bar Hack if needed, or just standard calendar paths -->
                                                    <!-- Better multi-color calendar icon -->
                                                    <rect x="4" y="8" width="40" height="34" rx="3" fill="white"
                                                        stroke="#dadce0" />
                                                    <path d="M11 6V11" stroke="#1a73e8" stroke-width="3"
                                                        stroke-linecap="round" />
                                                    <path d="M37 6V11" stroke="#1a73e8" stroke-width="3"
                                                        stroke-linecap="round" />
                                                    <path d="M4 16H44" stroke="#dadce0" stroke-width="2" />
                                                    <rect x="18" y="24" width="4" height="4" fill="#1a73e8" />
                                                    <text x="24" y="34" font-family="Arial" font-size="18" fill="#1a73e8"
                                                        text-anchor="middle" font-weight="bold">31</text>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-base font-bold text-gray-900">Google Calendar</div>
                                                <div class="text-sm text-gray-500 mb-1">{{ $google_account['email'] }}</div>
                                                <a href="#" class="text-sm text-blue-600 hover:underline">Checking
                                                    {{ isset($google_account['calendar_ids']) ? count($google_account['calendar_ids']) : 1 }}
                                                    calendars</a>
                                            </div>
                                            <div class="ml-2">
                                                <button type="button" id="btn-disconnect-cal"
                                                    class="text-red-500 hover:text-red-700 p-1">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <script>
                                            document.getElementById('btn-disconnect-cal').addEventListener('click', function () {
                                                if (confirm('Are you sure you want to remove this calendar?')) {
                                                    fetch('/api/admin/auth/google/disconnect?context=staff', {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Content-Type': 'application/json'
                                                        }
                                                    }).then(res => res.json()).then(data => {
                                                        if (data.success) window.location.reload();
                                                    });
                                                }
                                            });
                                        </script>
                                    @else
                                        <div class="gbs-connect-card max-w-md w-full" id="btn-wiz-connect-google">
                                            <div class="gbs-connect-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                            </div>
                                            <div class="gbs-connect-info flex-1">
                                                <div class="gbs-connect-title">Sync with Google Calendar</div>
                                                <div class="gbs-connect-sub">Automatically block off busy recurring events.
                                                </div>
                                            </div>
                                            <div class="gbs-connect-action text-blue-600">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <polyline points="9 18 15 12 9 6"></polyline>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="gbs-input-group max-w-md">
                                    <label class="gbs-input-label mb-2 font-bold text-gray-900">Staff Timezone</label>
                                    <div class="relative">
                                        <input type="hidden" id="wiz-staff-timezone-value" name="timezone"
                                            value="{{ date_default_timezone_get() }}">

                                        <!-- Trigger Button -->
                                        <button type="button" id="wiz-staff-tz-btn"
                                            class="flex items-center gap-2 text-primary font-medium text-base hover:underline focus:outline-none">
                                            <span id="wiz-staff-tz-label"
                                                class="gbs-current-timezone-label">{{ date_default_timezone_get() }}</span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </button>

                                        <!-- Dropdown -->
                                        <div id="wiz-staff-tz-dropdown"
                                            class="hidden absolute bottom-full left-0 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50 p-2 mb-1 flex-col"
                                            style="max-height: 400px;">
                                            <div class="p-2 flex-shrink-0 bg-white z-10 sticky top-0">
                                                <input type="text" id="wiz-staff-tz-search"
                                                    class="w-full border border-primary rounded-md px-3 py-2 text-sm focus:outline-none placeholder-gray-500 text-gray-700"
                                                    placeholder="Search...">
                                            </div>
                                            <div id="wiz-staff-tz-list" class="overflow-y-auto flex-grow px-1 max-h-60">
                                                @foreach ($groupedTimezones as $region => $items)
                                                    <div class="gbs-tz-group">
                                                        <div
                                                            class="gbs-tz-header px-3 py-1 text-xs font-bold text-gray-500 uppercase bg-gray-50 sticky top-0">
                                                            {{ $region }}</div>
                                                        @foreach ($items as $item)
                                                            <div class="gbs-tz-item px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-primary rounded cursor-pointer duration-150 flex justify-between items-center"
                                                                data-value="{{ $item['value'] }}"
                                                                data-region="{{ strtolower($region) }}">
                                                                <span class="font-medium">{{ $item['name'] }}</span>
                                                                <span class="text-xs text-gray-400">{{ $item['time'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Services -->
                            <div class="gbs-step-pane" id="step-pane-3">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Assign Services</h3>
                                <p class="text-gray-500 mb-8">Select which services this staff member is qualified to
                                    perform.</p>

                                <div class="gbs-services-grid">
                                    @forelse($services as $service)
                                        <label class="gbs-service-card"
                                            onclick="this.classList.toggle('selected', this.querySelector('input').checked)">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                id="service-{{ $service->id }}"
                                                class="gbs-service-checkbox w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                                            <div class="flex flex-col">
                                                <span class="gbs-service-label text-base">{{ $service->name }}</span>
                                                <span class="text-xs text-gray-500">{{ $service->duration_minutes }} mins •
                                                    {{ $service->price }}</span>
                                            </div>
                                        </label>
                                    @empty
                                        <div
                                            class="col-span-2 text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                            <p class="text-gray-500">No services found. Please create services first.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Step 4: Schedule (Placeholder) -->
                            <div class="gbs-step-pane" id="step-pane-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Weekly Schedule</h3>
                                <p class="text-gray-500 mb-8">Define standard working hours.</p>

                                <div class="bg-gray-50 rounded-xl p-8 text-center border border-dashed border-gray-300">
                                    <div
                                        class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mb-4 text-gray-400">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-900">Schedule Configuration</h4>
                                    <p class="text-gray-500 mt-2 max-w-sm mx-auto">Default schedule is set to Mon-Fri,
                                        9am-5pm. Advanced schedule controls will be available after creation.</p>
                                </div>
                            </div>

                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="gbs-wizard-footer">
                        <button type="button" class="gbs-btn-secondary-clean" id="btn-staff-prev" style="display:none;">
                            Back
                        </button>
                        <div style="flex:1;"></div>
                        <button type="button" class="gbs-btn-primary-clean" id="btn-staff-next">
                            <span>Next Step</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                        <button type="button" class="gbs-btn-primary-clean" id="btn-staff-finish" style="display:none;">
                            <span>Create Staff Member</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for step in URL
            const urlParams = new URLSearchParams(window.location.search);
            let currentStep = parseInt(urlParams.get('step')) || 1;

            const totalSteps = 4; // Simplified to 4 steps as per content above

            const btnNext = document.getElementById('btn-staff-next');
            const btnPrev = document.getElementById('btn-staff-prev');
            const btnFinish = document.getElementById('btn-staff-finish');
            const form = document.getElementById('form-add-staff-wizard');

            // Initialize wizard if step > 1
            if (currentStep > 1) {
                setTimeout(updateWizard, 50);
            }

            // --- Validation Functions ---
            const inputs = {
                name: document.getElementById('wiz-staff-name'),
                email: document.getElementById('wiz-staff-email')
            };

            const errors = {
                name: document.getElementById('error-name'),
                email: document.getElementById('error-email')
            };

            function showInputError(input, errorEl, msg) {
                input.classList.add('error');
                errorEl.querySelector('span').innerText = msg;
                errorEl.style.display = 'flex';
                // Shake animation reset
                input.style.animation = 'none';
                input.offsetHeight; /* trigger reflow */
                input.style.animation = 'shake 0.3s';
            }

            function clearInputError(input, errorEl) {
                input.classList.remove('error');
                errorEl.style.display = 'none';
            }

            // Real-time validation
            Object.keys(inputs).forEach(key => {
                if (inputs[key]) {
                    inputs[key].addEventListener('input', () => {
                        if (inputs[key].value.trim() !== '') {
                            clearInputError(inputs[key], errors[key]);
                        }
                    });
                }
            });

            function validateStep(step) {
                let isValid = true;
                if (step === 1) {
                    // Validate Name
                    if (!inputs.name.value.trim()) {
                        showInputError(inputs.name, errors.name, 'Full Name is required');
                        isValid = false;
                    }

                    // Validate Email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!inputs.email.value.trim()) {
                        showInputError(inputs.email, errors.email, 'Email Address is required');
                        isValid = false;
                    } else if (!emailRegex.test(inputs.email.value.trim())) {
                        showInputError(inputs.email, errors.email, 'Please enter a valid email address');
                        isValid = false;
                    }
                }
                return isValid;
            }

            // --- Wizard Navigation ---
            function updateWizard() {
                // Update header steps
                document.querySelectorAll('.gbs-step-h-item').forEach(step => {
                    const stepNum = parseInt(step.dataset.step);
                    if (stepNum < currentStep) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                    } else if (stepNum === currentStep) {
                        step.classList.add('active');
                        step.classList.remove('completed');
                    } else {
                        step.classList.remove('active', 'completed');
                    }
                });

                // Update Progress Lines
                document.querySelectorAll('.gbs-step-h-line').forEach((line, idx) => {
                    // line index is 0 for between step 1 & 2
                    if (idx < currentStep - 1) {
                        line.classList.add('filled');
                    } else {
                        line.classList.remove('filled');
                    }
                });

                // Update panes
                document.querySelectorAll('.gbs-step-pane').forEach(pane => {
                    pane.classList.remove('active');
                    if (pane.id === `step-pane-${currentStep}`) {
                        pane.classList.add('active');
                    }
                });

                // Update buttons
                // Prev Btn
                btnPrev.style.display = currentStep === 1 ? 'none' : 'block';

                // Next/Finish Btn
                if (currentStep === totalSteps) {
                    btnNext.style.display = 'none';
                    btnFinish.style.display = 'inline-flex';
                } else {
                    btnNext.style.display = 'inline-flex';
                    btnFinish.style.display = 'none';
                }
            }

            btnNext.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateWizard();
                    }
                }
            });

            btnPrev.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateWizard();
                }
            });

            btnFinish.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    // Optional: Show loading state
                    btnFinish.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...';
                    form.submit();
                }
            });

            // --- Counter Logic ---
            const inputHours = document.getElementById('wiz-staff-limit-hours');
            document.getElementById('btn-limit-minus').addEventListener('click', () => {
                let v = parseInt(inputHours.value);
                if (v > 1) inputHours.value = v - 1;
            });
            document.getElementById('btn-limit-plus').addEventListener('click', () => {
                let v = parseInt(inputHours.value);
                if (v < 24) inputHours.value = v + 1;
            });

            // --- Timezone Logic ---
            const tzBtn = document.getElementById('wiz-staff-tz-btn');
            const tzDropdown = document.getElementById('wiz-staff-tz-dropdown');
            const tzLabel = document.getElementById('wiz-staff-tz-label');
            const tzInput = document.getElementById('wiz-staff-timezone-value');
            const tzSearch = document.getElementById('wiz-staff-tz-search');

            if (tzBtn) {
                tzBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Toggle hidden class. If hidden is removed, add flex to ensure proper layout (flex-col is already in classes)
                    if (tzDropdown.classList.contains('hidden')) {
                        tzDropdown.classList.remove('hidden');
                        tzDropdown.classList.add('flex');
                    } else {
                        tzDropdown.classList.add('hidden');
                        tzDropdown.classList.remove('flex');
                    }
                });

                tzSearch.addEventListener('click', (e) => e.stopPropagation()); // Prevent closing when clicking search input

                // Filter logic
                const tzItems = document.querySelectorAll('.gbs-tz-item');
                const tzGroups = document.querySelectorAll('.gbs-tz-group');

                tzSearch.addEventListener('input', (e) => {
                    const term = e.target.value.toLowerCase();
                    
                    tzGroups.forEach(group => {
                        let hasVisible = false;
                        const items = group.querySelectorAll('.gbs-tz-item');
                        
                        items.forEach(item => {
                            const name = item.querySelector('span:first-child').textContent.toLowerCase();
                            const region = item.dataset.region || '';
                            
                            if (name.includes(term) || region.includes(term)) {
                                item.style.display = 'flex';
                                hasVisible = true;
                            } else {
                                item.style.display = 'none';
                            }
                        });

                        group.style.display = hasVisible ? 'block' : 'none';
                    });
                });

                // Selection logic
                tzItems.forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.dataset.value;
                        tzLabel.textContent = val;
                        tzInput.value = val;
                        tzDropdown.classList.add('hidden');
                        tzDropdown.classList.remove('flex');
                    });
                });

                // Click outside to close
                document.addEventListener('click', (e) => {
                    if (!tzBtn.contains(e.target) && !tzDropdown.contains(e.target)) {
                        tzDropdown.classList.add('hidden');
                        tzDropdown.classList.remove('flex');
                    }
                });
            }

            // --- Google Calendar Connection ---
            const btnGoogle = document.getElementById('btn-wiz-connect-google');
            if (btnGoogle) {
                btnGoogle.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation(); // Stop global admin.js from interfering

                    // Redirect to the correct API endpoint
                    // We use the gbsAdminData.apiUrl if available, or fallback
                    const baseUrl = (window.gbsAdminData && window.gbsAdminData.apiUrl)
                        ? window.gbsAdminData.apiUrl
                        : '/api/admin/';

                    window.location.href = baseUrl + 'auth/google/connect?context=staff';
                });
            }

            // Check PHP errors on load
            @if($errors->any())
                // If there are any errors, usually they are on step 1 (name/email).
                // The logic ensures we are on Step 1 by default, so they will be visible.
            @endif
        });
    </script>

    <style>
        /* Shake Animation for Errors */
        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }
    </style>
</x-admin-layout>