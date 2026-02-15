<x-admin-layout>
    <div class="wrap p-6">
        <h1 class="text-2xl font-bold mb-4">Availability</h1>
        <div class="gbs-admin-content bg-white p-6 border border-gray-200 rounded-lg">
            <div id="tab-settings">

                <!-- Internal Sub-Tabs for Availability -->
                <div class="gbs-sub-tabs flex space-x-4 border-b border-gray-200 mb-6">
                    <button class="gbs-sub-tab active px-4 py-2 font-medium text-primary border-b-2 border-primary"
                        data-sub="schedules">Schedules</button>
                    <button class="gbs-sub-tab px-4 py-2 font-medium text-gray-500 hover:text-gray-700"
                        data-sub="cal-settings">Calendar Settings</button>
                    <button class="gbs-sub-tab px-4 py-2 font-medium text-gray-500 hover:text-gray-700"
                        data-sub="advanced">Advanced Settings</button>
                </div>

                <!-- 1. Schedules View -->
                <div id="sub-schedules" class="gbs-sub-content">

                    <!-- Header -->
                    <div class="gbs-schedule-header flex justify-between items-center mb-6">
                        <div class="gbs-sched-title-group">
                            <span class="gbs-sched-label text-sm text-gray-500 uppercase tracking-wide">Schedule</span>
                            <div class="gbs-sched-name-row flex items-center gap-2 cursor-pointer relative">
                                <h2 class="gbs-sched-name text-xl font-bold text-gray-800">Working hours (default)</h2>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" class="text-gray-400">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>

                                <!-- Schedule Selector Dropdown -->
                                <div class="gbs-sched-selector-menu gbs-dropdown-menu" id="menu-sched-selector">
                                    <div id="sched-selector-list" class="max-h-60 overflow-y-auto"></div>
                                    <div class="gbs-selector-action border-t border-gray-100 mt-2 pt-2">
                                        <button
                                            class="gbs-btn-text-icon w-full text-left px-4 py-2 text-sm text-primary font-medium hover:bg-gray-50 flex items-center gap-2"
                                            id="btn-new-schedule">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="5" x2="12" y2="19" />
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                            Create schedule
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="gbs-active-meta text-sm text-gray-500 mt-1">Active on: <a href="#"
                                    class="text-primary hover:underline" id="trigger-active-services">Loading...</a>
                            </div>
                        </div>
                        <div class="gbs-sched-actions flex items-center gap-3">
                            <div class="gbs-view-switcher">
                                <button class="gbs-view-btn active" data-view="list">List</button>
                                <button class="gbs-view-btn" data-view="calendar">Calendar</button>
                            </div>
                            <div class="gbs-dropdown-container relative">
                                <button
                                    class="gbs-icon-btn-plain p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100"
                                    id="btn-sched-actions">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>
                                <div class="gbs-sched-menu gbs-dropdown-menu" id="menu-sched-actions">
                                    <!-- Populated via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modals (Hidden) -->
                    <div id="modal-rename-overlay"
                        class="gbs-modal-overlay fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center">
                        <div class="gbs-modal-clean bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
                            <div class="gbs-modal-header flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Rename schedule</h3>
                                <button class="gbs-btn-close text-gray-400 hover:text-gray-600"
                                    id="btn-close-rename"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg></button>
                            </div>
                            <div class="gbs-modal-body mb-6">
                                <label class="gbs-label-small block text-xs font-bold text-gray-500 mb-1">Schedule
                                    name</label>
                                <div class="gbs-input-wrapper relative">
                                    <input type="text" id="input-schedule-name" value="Working hours"
                                        class="w-full border-gray-300 rounded-lg focus:border-primary focus:ring-primary shadow-sm">
                                    <button
                                        class="gbs-input-clear absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                        id="btn-clear-name"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="15" y1="9" x2="9" y2="15" />
                                            <line x1="9" y1="9" x2="15" y2="15" />
                                        </svg></button>
                                </div>
                            </div>
                            <div class="gbs-modal-footer flex justify-end gap-3">
                                <button
                                    class="gbs-btn-secondary-clean px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                                    id="btn-cancel-rename">Cancel</button>
                                <button
                                    class="gbs-btn-primary-clean px-4 py-2 bg-primary text-white rounded-lg hover:bg-orange-600"
                                    id="btn-save-rename">Save</button>
                            </div>
                        </div>
                    </div>

                    <div id="modal-create-schedule-overlay"
                        class="gbs-modal-overlay fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center">
                        <div class="gbs-modal-clean bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
                            <div class="gbs-modal-header flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Create schedule</h3>
                                <button class="gbs-btn-close text-gray-400 hover:text-gray-600"
                                    id="btn-close-create"><svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg></button>
                            </div>
                            <div class="gbs-modal-body mb-6">
                                <label class="gbs-label-small block text-xs font-bold text-gray-500 mb-1">Schedule
                                    name</label>
                                <div class="gbs-input-wrapper">
                                    <input type="text" id="input-new-schedule-name"
                                        placeholder="Working Hours, Exclusive Hours, etc..."
                                        class="w-full border-gray-300 rounded-lg focus:border-primary focus:ring-primary shadow-sm">
                                </div>
                            </div>
                            <div class="gbs-modal-footer flex justify-end gap-3">
                                <button
                                    class="gbs-btn-secondary-clean px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                                    id="btn-cancel-create">Cancel</button>
                                <button
                                    class="gbs-btn-primary-clean px-4 py-2 bg-primary text-white rounded-lg hover:bg-orange-600"
                                    id="btn-create-schedule-confirm">Create</button>
                            </div>
                        </div>
                    </div>

                    <!-- List View Content -->
                    <div id="gbs-view-list" class="gbs-view-section">
                        <div class="gbs-split-layout grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="gbs-layout-col main lg:col-span-2 space-y-6">
                                <div class="gbs-col-header flex items-start gap-4 pb-4 border-b border-gray-100">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" class="text-gray-400 mt-1">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <path d="M22 6l-10 7L2 6" />
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">Weekly hours</h3>
                                        <p class="text-sm text-gray-500">Set when you are typically available for
                                            meetings</p>
                                    </div>
                                </div>

                                <div id="availability-schedule" class="gbs-schedule-list"></div>

                                <div class="gbs-timezone-footer pt-6 border-t border-gray-100 relative">
                                    <button id="gbs-timezone-btn"
                                        class="flex items-center gap-2 text-primary font-medium text-sm hover:underline">
                                        <span id="label-current-timezone"
                                            class="gbs-current-timezone-label">{{ config('app.timezone') }}</span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>

                                    <div id="gbs-timezone-dropdown"
                                        class="gbs-tz-dropdown hidden absolute bottom-full left-0 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50 p-2"
                                        style="max-height: 400px; display: none; flex-direction: column;">
                                        <div class="p-2 flex-shrink-0 bg-white z-10 sticky top-0">
                                            <input type="text" id="inp-timezone-search"
                                                class="w-full border border-primary rounded-md px-3 py-2 text-sm focus:outline-none placeholder-gray-500 text-gray-700"
                                                placeholder="Search...">
                                        </div>
                                        <div id="list-timezones" class="overflow-y-auto flex-grow px-1"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="gbs-layout-col side lg:col-span-1 border-l border-gray-100 pl-8">
                                <div class="gbs-col-header row-between flex justify-between items-start mb-6">
                                    <div class="flex gap-3">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" class="text-gray-400 mt-1">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-800">Date-specific hours</h3>
                                            <p class="text-sm text-gray-500">Adjust hours for specific days</p>
                                        </div>
                                    </div>
                                    <button
                                        class="gbs-btn-pill px-3 py-1 bg-white border border-gray-300 rounded-full text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm"
                                        id="btn-add-override">+ Hours</button>
                                </div>

                                <div id="gbs-overrides-list" class="gbs-empty-state-list space-y-2"></div>
                            </div>
                        </div>

                        <div class="gbs-floating-action mt-8 flex justify-end">
                            <button
                                class="px-6 py-3 bg-primary text-white font-bold rounded-lg shadow-lg hover:bg-orange-600 transition"
                                id="btn-save-settings">Save Changes</button>
                        </div>
                    </div>

                    <!-- Calendar View Content -->
                    <div id="gbs-view-calendar" class="gbs-view-section" style="display:none;">
                        <div class="gbs-cal-nav-row">
                            <div class="gbs-cal-controls">
                                <button class="gbs-icon-btn-plain" id="cal-prev"><svg width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M15 18l-6-6 6-6" />
                                    </svg></button>
                                <span class="gbs-cal-month-label" id="cal-label">January 2026</span>
                                <button class="gbs-icon-btn-plain" id="cal-next"><svg width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 18l6-6-6-6" />
                                    </svg></button>
                            </div>
                        </div>
                        <div class="gbs-calendar-grid" id="gbs-calendar-mount"></div>
                    </div>
                </div>

                <!-- 2. Calendar Settings View -->
                <div id="sub-cal-settings" class="gbs-sub-content hidden">
                    <div class="gbs-cal-settings-intro mb-8">
                        <h2 class="text-xl font-bold text-gray-800">Calendar settings</h2>
                        <p class="text-gray-500">Set which calendars we use to check for busy times</p>
                    </div>

                    <div class="gbs-divider border-t border-gray-200 my-6"></div>

                    <div class="gbs-cal-section">
                        <div id="gbs-connected-calendars-list">
                            @if(isset($connected_accounts) && count($connected_accounts) > 0)
                                <!-- Connected Calendars Loop -->
                            @else
                                <div class="gbs-connect-empty-state flex flex-col items-center justify-center p-12 bg-gray-50 border border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-100 transition"
                                    id="btn-trigger-connect-cal">
                                    <div class="gbs-connect-icon-circle p-4 bg-white rounded-full shadow-sm mb-4">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" class="text-gray-400">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </div>
                                    <h3 class="gbs-connect-empty-title text-lg font-bold text-gray-700">No calendars
                                        connected</h3>
                                    <p class="gbs-connect-empty-desc text-gray-500 text-center max-w-sm mt-2">Connect your
                                        Google Calendar to automatically sync bookings and prevent conflicts.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 3. Advanced Settings View -->
                <div id="sub-advanced" class="gbs-sub-content hidden">
                    <div class="gbs-advanced-intro mb-8">
                        <h2 class="text-xl font-bold text-gray-800">Advanced settings</h2>
                        <p class="text-gray-500">Control availability across all your event types</p>
                    </div>

                    <div class="gbs-divider border-t border-gray-200 my-6"></div>

                    <div class="gbs-advanced-section" id="gbs-meeting-limits-section">
                        <h3 class="font-bold text-gray-800 mb-2">Meeting limits</h3>
                        <p class="text-gray-500 mb-4">Set a maximum number of total meetings.</p>
                        <div id="gbs-limits-header"
                            style="display:none; margin-bottom:8px; font-size:0.75rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.05em;">
                            <span style="display:inline-block; width:80px;">Max</span>
                            <span style="display:inline-block; margin-left:100px;">Frequency</span>
                        </div>
                        <div id="gbs-limits-rows"></div>
                        <button id="btn-add-limit" class="text-primary font-medium hover:underline text-sm">+ Add a
                            meeting limit</button>
                    </div>

                    <div class="gbs-divider border-t border-gray-200 my-8"></div>

                    <div class="gbs-advanced-section">
                        <h3 class="gbs-section-title" style="margin-bottom: 4px;">Holidays</h3>
                        <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 16px;">The system will
                            automatically mark you as unavailable for the selected holidays</p>

                        <div class="gbs-holidays-card"
                            style="border: 1px solid #e5e7eb; border-radius: 10px; width: 100%; max-width: 500px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <!-- Header -->
                            <div
                                style="padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f3f4f6; background: #fafafa; overflow: visible;">
                                <div class="gbs-country-select-wrapper" style="position: relative;">
                                    <label
                                        style="display: block; font-size: 0.65rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Country
                                        for holidays</label>
                                    <button id="btn-country-dropdown"
                                        style="display: flex; align-items: center; gap: 6px; font-weight: 600; color: #1f2937; font-size: 0.9rem; background: transparent; border: none; cursor: pointer; padding: 0;">
                                        <span id="label-selected-country">Other</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" style="color: #9ca3af;">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>
                                    <div id="dropdown-country-list"
                                        style="display: none; position: absolute; top: 100%; left: 0; width: 250px; background: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 999; margin-top: 4px; overflow: hidden;">
                                        <div style="padding: 8px; border-bottom: 1px solid #eee;">
                                            <input type="text" id="input-country-search"
                                                placeholder="Search countries..."
                                                style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                        </div>
                                        <div class="gbs-dropdown-scrollable"
                                            style="max-height: 250px; overflow-y: auto; padding: 4px 0;">
                                            <div class="gbs-country-option" data-val="uk"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                United Kingdom</div>
                                            <div class="gbs-country-option" data-val="us"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                United States</div>
                                            <div class="gbs-country-option" data-val="au"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                Australia</div>
                                            <div class="gbs-country-option" data-val="br"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                Brazil</div>
                                            <div class="gbs-country-option" data-val="ca"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                Canada</div>
                                            <div class="gbs-country-option" data-val="fr"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                France</div>
                                            <div class="gbs-country-option" data-val="de"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                Germany</div>
                                            <div class="gbs-country-option" data-val="mx"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                Mexico</div>
                                            <div class="gbs-country-option" data-val="nl"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                Netherlands</div>
                                            <div class="gbs-country-option" data-val="es"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #333;">
                                                Spain</div>
                                            <div style="border-top: 1px solid #eee; margin: 4px 0;"></div>
                                            <div class="gbs-country-option" data-val="other"
                                                style="padding: 10px 12px; cursor: pointer; font-size: 14px; color: #999;">
                                                Other</div>
                                        </div>
                                    </div>
                                </div>
                                <label class="gbs-toggle"
                                    style="position: relative; display: inline-flex; align-items: center; cursor: pointer;">
                                    <input type="checkbox" id="toggle-holidays"
                                        style="position: absolute; opacity: 0; width: 0; height: 0;">
                                    <span id="main-holiday-slider"
                                        style="display: block; width: 44px; height: 24px; background: #e5e7eb; border-radius: 12px; position: relative; transition: background 0.2s;">
                                        <span id="main-holiday-knob"
                                            style="display: block; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: #fff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: left 0.2s;"></span>
                                    </span>
                                </label>
                            </div>
                            <!-- Holiday List -->
                            <div id="gbs-holidays-content"
                                style="padding: 24px; text-align: center; color: #9ca3af; font-size: 0.85rem;">
                                Select a country to view holidays
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Overlays for Date Overrides -->
    <div id="modal-date-override-overlay"
        class="gbs-modal-overlay fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center">
        <div class="gbs-modal-clean bg-white rounded-xl shadow-2xl p-6 w-[400px]">
            <div class="gbs-modal-header flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Select the date(s)</h3>
                <button class="gbs-btn-close text-gray-400 hover:text-gray-600" id="btn-close-override">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="gbs-modal-body">
                <!-- Mini Calendar -->
                <div class="gbs-mini-cal-header flex justify-between items-center mb-2 px-2">
                    <span id="mini-cal-label" class="font-bold text-gray-700"></span>
                    <div class="gbs-mini-cal-nav flex gap-2">
                        <button id="mini-cal-prev" class="p-1 hover:bg-gray-100 rounded"><svg width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18l-6-6 6-6" />
                            </svg></button>
                        <button id="mini-cal-next" class="p-1 hover:bg-gray-100 rounded"><svg width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6" />
                            </svg></button>
                    </div>
                </div>
                <div class="gbs-mini-cal-days grid grid-cols-7 text-center text-xs font-bold text-gray-400 mb-1">
                    <span>SUN</span><span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span>
                </div>
                <div class="gbs-mini-cal-grid grid grid-cols-7 gap-1 text-sm mb-4" id="mini-cal-grid"></div>

                <!-- Hours Input -->
                <div id="override-settings-wrapper"
                    style="display:none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                    <label class="gbs-label-small block text-xs font-bold text-gray-500 mb-2">What hours are you
                        available?</label>

                    <div id="override-slots-container" class="space-y-2 mb-4"></div>

                    <div class="mt-3" style="display:none;">
                        <button id="btn-add-override-slot" class="gbs-btn-add-period">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add another period
                        </button>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="check-unavailable-all-day"
                                class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700">Unavailable all day</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <label class="gbs-label-small block text-xs font-bold text-gray-500 mb-2">Note / Label</label>
                        <input type="text" id="override-desc"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary"
                            placeholder="e.g. Early Finish">
                    </div>
                </div>
            </div>
            <div class="gbs-modal-footer flex justify-end gap-3 mt-6">
                <button
                    class="gbs-btn-secondary-clean px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm"
                    id="btn-cancel-override">Cancel</button>
                <button
                    class="gbs-btn-primary-clean px-4 py-2 bg-primary text-white rounded-lg hover:bg-orange-600 text-sm"
                    id="btn-apply-override" disabled>Apply</button>
            </div>
        </div>
    </div>
    <!-- Modal Overlays for Weekly Edits (Recurring) -->
    <div id="modal-weekly-edit-overlay"
        class="gbs-modal-overlay fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center">
        <div class="gbs-modal-clean bg-white rounded-xl shadow-2xl p-6 w-[400px]">
            <div class="gbs-modal-header flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800" id="weekly-modal-title">Edit Weekly Availability</h3>
                <button class="gbs-btn-close text-gray-400 hover:text-gray-600" id="btn-close-weekly">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="gbs-modal-body">
                <label class="gbs-label-small block text-xs font-bold text-gray-500 mb-2">What hours are you
                    available?</label>

                <div id="weekly-slots-container" class="space-y-2 mb-4"></div>

                <div class="mt-4 pt-3 border-t border-gray-100">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="check-weekly-unavailable"
                            class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-gray-700">Unavailable all day</span>
                    </label>
                </div>
            </div>
            <div class="gbs-modal-footer flex justify-end gap-3 mt-6">
                <button
                    class="gbs-btn-secondary-clean px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm"
                    id="btn-cancel-weekly">Cancel</button>
                <button
                    class="gbs-btn-primary-clean px-4 py-2 bg-primary text-white rounded-lg hover:bg-orange-600 text-sm"
                    id="btn-apply-weekly">Apply</button>
            </div>
        </div>
    </div>

    <!-- Connect Calendar Modal -->
    <div id="modal-connect-calendar-overlay"
        class="gbs-modal-overlay fixed inset-0 bg-black/50 z-[100] hidden items-center justify-center">
        <div class="gbs-modal-clean bg-white rounded-xl shadow-2xl p-6 w-[400px]">
            <div class="gbs-modal-header flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Connect Calendar</h3>
                <button class="gbs-btn-close text-gray-400 hover:text-gray-600" id="btn-close-connect-cal">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="gbs-modal-body">
                <p class="text-sm text-gray-500 mb-6">Connect your external calendar to sync bookings and prevent
                    double-booking.</p>

                <button id="btn-provider-google"
                    class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition mb-3">
                    <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    <span class="font-medium text-gray-700">Google Calendar</span>
                </button>
                <button
                    class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition opacity-50 cursor-not-allowed"
                    disabled>
                    <span class="font-medium text-gray-500">Outlook Calendar (Coming Soon)</span>
                </button>
            </div>
        </div>
    </div>
</x-admin-layout>