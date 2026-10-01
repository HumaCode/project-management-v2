@props([
    'name',
    'id' => null,
    'value' => '',
    'placeholder' => 'Pilih tanggal...',
    'required' => false,
    'class' => '',
])

@php
    $elementId = $id ?? ($name . '_' . Str::random(6));
@endphp

<div class="custom-datepicker-wrap position-relative">
    <input 
        type="text" 
        name="{{ $name }}" 
        id="{{ $elementId }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}" 
        {{ $required ? 'required' : '' }} 
        {{ $attributes->merge(['class' => 'fmi custom-datepicker-input ' . $class]) }}
        readonly 
        style="padding-left: 42px !important; cursor: pointer;"
    />
    <i class="bi bi-calendar-event datepicker-icon"></i>
</div>

@once
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            .custom-datepicker-wrap {
                width: 100%;
            }
            .custom-datepicker-wrap .custom-datepicker-input,
            .custom-datepicker-wrap input.flatpickr-input {
                padding-left: 42px !important;
                cursor: pointer !important;
            }
            .custom-datepicker-wrap .datepicker-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--cyan, #0284c7);
                font-size: 16px;
                pointer-events: none;
                z-index: 5;
                transition: color 0.2s ease;
            }
            .custom-datepicker-wrap .custom-datepicker-input:focus ~ .datepicker-icon,
            .custom-datepicker-wrap input.flatpickr-input:focus ~ .datepicker-icon {
                color: #00e5a0;
            }
            
            /* ════ Modern Custom Styling for Flatpickr ════ */
            .flatpickr-calendar {
                border-radius: 14px !important;
                font-family: var(--font, 'Outfit', sans-serif) !important;
                padding: 8px !important;
                z-index: 99999 !important;
                transition: all 0.2s ease !important;
            }

            /* Dark Mode Styling */
            html:not([data-theme="light"]) .flatpickr-calendar {
                background: #050e1d !important;
                border: 1px solid rgba(0, 200, 255, 0.25) !important;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6) !important;
                color: #e2eaf4 !important;
            }
            html:not([data-theme="light"]) .flatpickr-calendar.arrowTop::before,
            html:not([data-theme="light"]) .flatpickr-calendar.arrowTop::after {
                border-bottom-color: #050e1d !important;
            }
            html:not([data-theme="light"]) .flatpickr-calendar.arrowBottom::before,
            html:not([data-theme="light"]) .flatpickr-calendar.arrowBottom::after {
                border-top-color: #050e1d !important;
            }
            html:not([data-theme="light"]) .flatpickr-months {
                background: transparent !important;
            }
            html:not([data-theme="light"]) .flatpickr-months .flatpickr-month,
            html:not([data-theme="light"]) .flatpickr-months .flatpickr-prev-month,
            html:not([data-theme="light"]) .flatpickr-months .flatpickr-next-month {
                color: #e2eaf4 !important;
                fill: #e2eaf4 !important;
            }
            html:not([data-theme="light"]) .flatpickr-current-month .flatpickr-monthDropdown-months,
            html:not([data-theme="light"]) .flatpickr-current-month input.cur-year {
                color: #e2eaf4 !important;
                background: #050e1d !important;
                font-weight: 700 !important;
            }
            html:not([data-theme="light"]) span.flatpickr-weekday {
                color: #00c8ff !important;
                font-weight: 600 !important;
            }
            html:not([data-theme="light"]) .flatpickr-day {
                color: #e2eaf4 !important;
                border-radius: 8px !important;
            }
            html:not([data-theme="light"]) .flatpickr-day.prevMonthDay,
            html:not([data-theme="light"]) .flatpickr-day.nextMonthDay {
                color: #475569 !important;
            }
            html:not([data-theme="light"]) .flatpickr-day:hover,
            html:not([data-theme="light"]) .flatpickr-day:focus {
                background: rgba(0, 200, 255, 0.15) !important;
                color: #00c8ff !important;
            }
            html:not([data-theme="light"]) .flatpickr-day.selected,
            html:not([data-theme="light"]) .flatpickr-day.selected:hover {
                background: linear-gradient(135deg, #0072c6, #00c8ff) !important;
                color: #ffffff !important;
                box-shadow: 0 4px 12px rgba(0, 200, 255, 0.4) !important;
            }
            html:not([data-theme="light"]) .flatpickr-day.today {
                border-color: #00c8ff !important;
                color: #00c8ff !important;
            }

            /* ════ Common Selected Day Styling (Must be at the bottom to override everything) ════ */
            .flatpickr-day.selected,
            .flatpickr-day.startRange,
            .flatpickr-day.endRange,
            .flatpickr-day.selected.inRange,
            .flatpickr-day.startRange.inRange,
            .flatpickr-day.endRange.inRange,
            .flatpickr-day.selected:focus,
            .flatpickr-day.startRange:focus,
            .flatpickr-day.endRange:focus,
            .flatpickr-day.selected:hover,
            .flatpickr-day.startRange:hover,
            .flatpickr-day.endRange:hover,
            .flatpickr-day.selected.prevMonthDay,
            .flatpickr-day.selected.nextMonthDay,
            .flatpickr-day.selected.today,
            html[data-theme="light"] .flatpickr-day.selected,
            html[data-theme="light"] .flatpickr-day.selected.today,
            html[data-theme="light"] .flatpickr-day.selected:hover,
            html[data-theme="light"] .flatpickr-day.selected:focus {
                background: #0284c7 !important;
                background: linear-gradient(135deg, #0284c7, #00c8ff) !important;
                border-color: transparent !important;
                color: #ffffff !important;
                font-weight: 700 !important;
                box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4) !important;
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
        <script>
            function initCustomDatePicker(selector = '.custom-datepicker-input') {
                if (typeof flatpickr === 'undefined') return;

                flatpickr(selector, {
                    locale: 'id',
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd F Y',
                    allowInput: false,
                    disableMobile: "true",
                    static: false,
                });
            }

            $(function() {
                initCustomDatePicker();

                // Re-initialize for dynamically loaded modals
                $(document).on('shown.bs.modal', '.modal', function() {
                    initCustomDatePicker($(this).find('.custom-datepicker-input'));
                });
            });
        </script>
    @endpush
@endonce
