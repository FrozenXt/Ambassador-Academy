@extends('admin::layouts.app')

@section('page_title', 'Edit Counter')

@section('page_actions')
    <a href="{{ route('admin.counters.index') }}" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
@endsection

@section('admin_content')

    <div class="row">

        {{-- LEFT FORM --}}
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i> Edit Counter
                    </h3>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.counters.update', $counter->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            {{-- TITLE --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Title *</label>
                                    <input type="text" name="title" value="{{ old('title', $counter->title) }}"
                                        class="form-control" oninput="updatePreview()">
                                </div>
                            </div>

                            {{-- NUMBER --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Number *</label>
                                    <input type="text" name="number" value="{{ old('number', $counter->number) }}"
                                        class="form-control" oninput="updatePreview()">
                                </div>
                            </div>

                            {{-- PREFIX --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Prefix</label>
                                    <input type="text" name="prefix" value="{{ old('prefix', $counter->prefix) }}"
                                        class="form-control" oninput="updatePreview()">
                                </div>
                            </div>

                            {{-- SUFFIX --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Suffix</label>
                                    <input type="text" name="suffix" value="{{ old('suffix', $counter->suffix) }}"
                                        class="form-control" oninput="updatePreview()">
                                </div>
                            </div>

                            {{-- LIVE PREVIEW --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Live Preview</label>

                                    <div class="p-3 text-center rounded"
                                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">

                                        <iconify-icon id="previewIcon"
                                            icon="{{ old('icon', $counter->icon) ?? 'mdi:counter' }}" width="40"
                                            style="color:white;">
                                        </iconify-icon>

                                        <div id="previewNumber" style="font-size:2rem;font-weight:800;color:white;">
                                            {{ $counter->display_number }}
                                        </div>

                                        <div id="previewTitle" style="color:rgba(255,255,255,.85);font-size:.9rem;">
                                            {{ $counter->title }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ICON INPUT --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Icon (Iconify)</label>

                                    <input type="text" name="icon" id="iconInput"
                                        value="{{ old('icon', $counter->icon) }}" class="form-control"
                                        placeholder="e.g. mdi:account-group" oninput="updatePreview()">

                                    <div class="mt-2">
                                        <iconify-icon id="iconPreviewSmall"
                                            icon="{{ old('icon', $counter->icon) ?? 'mdi:help-circle' }}" width="30"
                                            style="color:#6c757d;">
                                        </iconify-icon>
                                    </div>
                                </div>
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Description</label>
                                    <input type="text" name="description"
                                        value="{{ old('description', $counter->description) }}" class="form-control">
                                </div>
                            </div>

                            {{-- COLOR --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Color</label>
                                    <input type="color" name="color" value="{{ old('color', $counter->color) }}"
                                        class="form-control" oninput="updatePreview()">
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $counter->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $counter->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- ORDER --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Order</label>
                                    <input type="number" name="order" value="{{ old('order', $counter->order) }}"
                                        class="form-control" min="0">
                                </div>
                            </div>

                        </div>

                        <hr>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Counter
                        </button>

                        <a href="{{ route('admin.counters.index') }}" class="btn btn-default ml-2">
                            Cancel
                        </a>

                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR ICON PICKER --}}
        <div class="col-md-4">

            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Icon Picker</h3>
                </div>

                <div class="card-body">

                    @php
                        $icons = [
                            'mdi:account-group' => 'Users',
                            'mdi:briefcase' => 'Projects',
                            'mdi:emoticon-happy' => 'Happy',
                            'mdi:trophy' => 'Awards',
                            'mdi:star' => 'Reviews',
                            'mdi:earth' => 'Global',
                            'mdi:clock' => 'Hours',
                            'mdi:thumb-up' => 'Likes',
                            'mdi:heart' => 'Loves',
                            'mdi:account-cash' => 'Revenue',
                            'mdi:account-group' => 'Users',
                            'mdi:account' => 'Single User',
                            'mdi:account-outline' => 'User Outline',

                            'mdi:briefcase' => 'Projects',
                            'mdi:folder-multiple' => 'Projects Folder',
                            'mdi:clipboard-check' => 'Completed Work',

                            'mdi:emoticon-happy' => 'Happy',
                            'mdi:emoticon-excited' => 'Excited',
                            'mdi:emoticon-outline' => 'Satisfaction',

                            'mdi:trophy' => 'Awards',
                            'mdi:medal' => 'Achievement',
                            'mdi:star' => 'Reviews',
                            'mdi:star-circle' => 'Rating',

                            'mdi:earth' => 'Global',
                            'mdi:earth-arrow-right' => 'Worldwide Reach',
                            'mdi:map-marker-radius' => 'Coverage',

                            'mdi:clock' => 'Hours',
                            'mdi:clock-outline' => 'Time',
                            'mdi:calendar-clock' => 'Experience',

                            'mdi:thumb-up' => 'Likes',
                            'mdi:thumb-up-outline' => 'Approval',

                            'mdi:heart' => 'Love',
                            'mdi:heart-outline' => 'Support',

                            'mdi:account-cash' => 'Revenue',
                            'mdi:cash' => 'Money',
                            'mdi:currency-usd' => 'Income',

                            // --------------------
                            // 🚀 GROWTH / BUSINESS
                            // --------------------
                            'mdi:trending-up' => 'Growth',
                            'mdi:chart-line' => 'Analytics Growth',
                            'mdi:chart-areaspline' => 'Performance',
                            'mdi:finance' => 'Finance Growth',

                            // --------------------
                            // 🎯 TARGET / BULLSEYE
                            // --------------------
                            'mdi:target' => 'Target',
                            'mdi:target-account' => 'Goal Achievement',
                            'mdi:bullseye-arrow' => 'Accuracy',
                            'mdi:crosshairs-gps' => 'Precision',

                            // --------------------
                            // 👥 MULTIPLE USERS
                            // --------------------
                            'mdi:account-multiple' => 'Multiple Users',
                            'mdi:account-supervisor' => 'Team Lead',
                            'mdi:account-tie' => 'Manager',

                            // --------------------
                            // 🏢 BUSINESS / COMPANY
                            // --------------------
                            'mdi:office-building' => 'Company',
                            'mdi:domain' => 'Enterprise',
                            'mdi:storefront' => 'Business',

                            // --------------------
                            // ⚡ SUCCESS / PERFORMANCE
                            // --------------------
                            'mdi:rocket-launch' => 'Fast Growth',
                            'mdi:lightning-bolt' => 'Speed',
                            'mdi:fire' => 'Trending',
                        ];
                    @endphp

                    <div class="d-flex flex-wrap">
                        @foreach ($icons as $icon => $label)
                            <button type="button" class="btn btn-sm btn-outline-secondary m-1"
                                onclick="setIcon('{{ $icon }}')">

                                <iconify-icon icon="{{ $icon }}" width="18"></iconify-icon>
                                <small>{{ $label }}</small>
                            </button>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>

    </div>

@endsection


@section('extra_js')
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

    <script>
        function updatePreview() {
            let prefix = document.querySelector('[name=prefix]').value || '';
            let number = document.querySelector('[name=number]').value || '0';
            let suffix = document.querySelector('[name=suffix]').value || '';
            let title = document.querySelector('[name=title]').value || 'Counter Title';
            let icon = document.getElementById('iconInput').value || 'mdi:help-circle';

            document.getElementById('previewNumber').innerText = prefix + number + suffix;
            document.getElementById('previewTitle').innerText = title;

            document.getElementById('previewIcon').setAttribute('icon', icon);
            document.getElementById('iconPreviewSmall').setAttribute('icon', icon);
        }

        function setIcon(icon) {
            document.getElementById('iconInput').value = icon;
            updatePreview();
        }

        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
    </script>
@endsection
