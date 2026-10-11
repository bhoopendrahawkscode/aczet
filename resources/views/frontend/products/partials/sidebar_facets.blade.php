{{-- resources/views/frontend/products/partials/sidebar_facets.blade.php --}}
@php
    $isMicro = !empty($isMicroSelected);
@endphp

<!-- 2. SERIES -->
<div class="filter-group {{ request()->has('series') ? '' : 'collapsed' }}" id="facetGroupSeries">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Series
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @forelse($seriesOptions as $sOpt)
            @php
                $sVal = $sOpt->spec_value;
                $isChecked = in_array($sVal, (array) request('series'));
                $isDisabled = !empty($sOpt->is_disabled) || $sOpt->count === 0;
            @endphp
            <label class="filter-item-label {{ $isDisabled ? 'disabled blurred-locked' : '' }}">
                <div class="filter-checkbox-wrap">
                    <input type="checkbox" 
                           value="{{ $sVal }}" 
                           {{ $isChecked ? 'checked' : '' }} 
                           {{ $isDisabled ? 'disabled' : '' }} 
                           onchange="toggleFilterParam('series', this.value, this.checked)">
                    <span class="facet-name-text">{{ $sVal }} ({{ $sOpt->count }})</span>
                </div>
            </label>
        @empty
            <span class="facet-empty-msg">Select a category to view series</span>
        @endforelse
    </div>
</div>

<!-- 3. TYPES -->
<div class="filter-group {{ request()->hasAny(['type', 'types']) ? '' : 'collapsed' }}" id="facetGroupTypes">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Types
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @forelse($typeOptions as $tOpt)
            @php
                $tVal = $tOpt->spec_value;
                $isChecked = in_array($tVal, (array) request('type', request('types')));
                $isDisabled = !empty($tOpt->is_disabled) || !empty($tOpt->is_locked) || $tOpt->count === 0;
            @endphp
            <label class="filter-item-label {{ $isDisabled ? 'disabled blurred-locked' : '' }}">
                <div class="filter-checkbox-wrap">
                    <input type="checkbox" 
                           value="{{ $tVal }}" 
                           {{ $isChecked ? 'checked' : '' }} 
                           {{ $isDisabled ? 'disabled' : '' }} 
                           onchange="toggleFilterParam('type', this.value, this.checked)">
                    <span class="facet-name-text">
                        {{ $tVal }}{{ (!$isDisabled && $tOpt->count > 0) ? ' (' . $tOpt->count . ')' : '' }}
                    </span>
                </div>
            </label>
        @empty
            <span class="facet-empty-msg">Select a category to view types</span>
        @endforelse
    </div>
</div>

<!-- 4. WEIGHING CAPACITY -->
<div class="filter-group {{ request()->hasAny(['capacity', 'capacities']) ? '' : 'collapsed' }}" id="facetGroupCapacity">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Weighing Capacity
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body" id="capacityFilterBody">
        @if(!empty($discreteCapacityOptions) && $discreteCapacityOptions->isNotEmpty())
            @php
                $hasExtra = false;
            @endphp
            @foreach($discreteCapacityOptions as $capOpt)
                @php
                    $capVal = $capOpt->spec_value;
                    $isChecked = in_array($capVal, (array) request('capacity', request('capacities')));
                    $isDisabled = !empty($capOpt->is_disabled) || $capOpt->count === 0;
                    $isExtra = !empty($capOpt->is_extra);
                    if ($isExtra) $hasExtra = true;
                    $displayLabel = $capOpt->display_label ?? ($capVal . ($capOpt->count > 0 ? ' (' . $capOpt->count . ')' : ''));
                @endphp
                <label class="filter-item-label {{ $isDisabled ? 'disabled blurred-locked' : '' }} {{ $isExtra ? 'capacity-extra-item' : '' }}" 
                       style="{{ $isExtra ? 'display:none;' : '' }}">
                    <div class="filter-checkbox-wrap">
                        <input type="checkbox" 
                               value="{{ $capVal }}" 
                               {{ $isChecked ? 'checked' : '' }} 
                               {{ $isDisabled ? 'disabled' : '' }} 
                               onchange="toggleFilterParam('capacity', this.value, this.checked)">
                        <span class="facet-name-text">{{ $displayLabel }}</span>
                    </div>
                </label>
            @endforeach
            @if($hasExtra)
                <div class="show-more-caps-wrap">
                    <button type="button" class="btn-show-more-caps" onclick="toggleExtraCapacities(this)">
                        Show more...
                    </button>
                </div>
            @endif
        @elseif(isset($capacityCounts))
            @php
                $capDefs = [
                    'under_50g' => ['label' => '< 50 g', 'count' => $capacityCounts['under_50g'] ?? 0],
                    '50g_250g' => ['label' => '50 g – 250 g', 'count' => $capacityCounts['50g_250g'] ?? 0],
                    '250g_1kg' => ['label' => '250 g – 1 kg', 'count' => $capacityCounts['250g_1kg'] ?? 0],
                    '1kg_10kg' => ['label' => '1 kg – 10 kg', 'count' => $capacityCounts['1kg_10kg'] ?? 0],
                    'over_10kg' => ['label' => '> 10 kg', 'count' => $capacityCounts['over_10kg'] ?? 0],
                ];
            @endphp
            @foreach($capDefs as $k => $cDef)
                @php
                    $isChecked = in_array($k, (array) request('capacity', request('capacities')));
                    $isDisabled = $cDef['count'] === 0;
                @endphp
                <label class="filter-item-label {{ $isDisabled ? 'disabled blurred-locked' : '' }}">
                    <div class="filter-checkbox-wrap">
                        <input type="checkbox" 
                               value="{{ $k }}" 
                               {{ $isChecked ? 'checked' : '' }} 
                               {{ $isDisabled ? 'disabled' : '' }} 
                               onchange="toggleFilterParam('capacity', this.value, this.checked)">
                        <span class="facet-name-text">{{ $cDef['label'] }} ({{ $cDef['count'] }})</span>
                    </div>
                </label>
            @endforeach
        @endif
    </div>
</div>

<!-- 5. READABILITY -->
<div class="filter-group {{ request()->has('readability') ? '' : 'collapsed' }}" id="facetGroupReadability">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Readability
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @forelse($readabilityOptions as $rOpt)
            @php
                $rVal = $rOpt->spec_value;
                $isChecked = in_array($rVal, (array) request('readability'));
                $isDisabled = !empty($rOpt->is_disabled) || !empty($rOpt->is_locked) || $rOpt->count === 0;
                $displayLabel = $rOpt->display_label ?? ($rVal . (!$isDisabled && $rOpt->count > 0 ? ' (' . $rOpt->count . ')' : ''));
            @endphp
            <label class="filter-item-label {{ $isDisabled ? 'disabled blurred-locked' : '' }}">
                <div class="filter-checkbox-wrap">
                    <input type="checkbox" 
                           value="{{ $rVal }}" 
                           {{ $isChecked ? 'checked' : '' }} 
                           {{ $isDisabled ? 'disabled' : '' }} 
                           onchange="toggleFilterParam('readability', this.value, this.checked)">
                    <span class="facet-name-text">{{ $displayLabel }}</span>
                </div>
            </label>
        @empty
            <span class="facet-empty-msg">Select a category to view readability</span>
        @endforelse
    </div>
</div>

<!-- 6. DISPLAY -->
<div class="filter-group {{ request()->has('display') ? '' : 'collapsed' }}" id="facetGroupDisplay">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Display
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @forelse($displayOptions as $dOpt)
            @php
                $dVal = $dOpt->spec_value;
                $isChecked = in_array($dVal, (array) request('display'));
                $isDisabled = !empty($dOpt->is_disabled) || !empty($dOpt->is_locked) || $dOpt->count === 0;
                $displayLabel = $dOpt->display_label ?? ($dVal . (!$isDisabled && $dOpt->count > 0 ? ' (' . $dOpt->count . ')' : ''));
            @endphp
            <label class="filter-item-label {{ $isDisabled ? 'disabled blurred-locked' : '' }}">
                <div class="filter-checkbox-wrap">
                    <input type="checkbox" 
                           value="{{ $dVal }}" 
                           {{ $isChecked ? 'checked' : '' }} 
                           {{ $isDisabled ? 'disabled' : '' }} 
                           onchange="toggleFilterParam('display', this.value, this.checked)">
                    <span class="facet-name-text">{{ $displayLabel }}</span>
                </div>
            </label>
        @empty
            <span class="facet-empty-msg">Select a category to view displays</span>
        @endforelse
    </div>
</div>

<!-- 7. CLASS -->
<div class="filter-group {{ request()->has('class') ? '' : 'collapsed' }}" id="facetGroupClass">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Class
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @forelse($classOptions as $clOpt)
            @php
                $clVal = $clOpt->spec_value;
                $isChecked = in_array($clVal, (array) request('class'));
                $isDisabled = !empty($clOpt->is_disabled) || !empty($clOpt->is_locked) || $clOpt->count === 0;
                $displayLabel = $clOpt->display_label ?? ($clVal . (!$isDisabled && $clOpt->count > 0 ? ' (' . $clOpt->count . ')' : ''));
            @endphp
            <label class="filter-item-label {{ $isDisabled ? 'disabled blurred-locked' : '' }}">
                <div class="filter-checkbox-wrap">
                    <input type="checkbox" 
                           value="{{ $clVal }}" 
                           {{ $isChecked ? 'checked' : '' }} 
                           {{ $isDisabled ? 'disabled' : '' }} 
                           onchange="toggleFilterParam('class', this.value, this.checked)">
                    <span class="facet-name-text">{{ $displayLabel }}</span>
                </div>
            </label>
        @empty
            <span class="facet-empty-msg">Select a category to view classes</span>
        @endforelse
    </div>
</div>

<!-- 8. CALIBRATION -->
<div class="filter-group {{ request()->has('calibration') ? '' : 'collapsed' }}" id="facetGroupCalibration">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Calibration
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @foreach($calibrationOptions as $calOpt)
            @php
                $isChecked = in_array($calOpt->spec_value, (array) request('calibration'));
            @endphp
            <label class="filter-item-label">
                <div class="filter-checkbox-wrap">
                    <input type="checkbox" 
                           value="{{ $calOpt->spec_value }}" 
                           {{ $isChecked ? 'checked' : '' }} 
                           onchange="toggleFilterParam('calibration', this.value, this.checked)">
                    <span class="facet-name-text">{{ $calOpt->spec_value }} ({{ $calOpt->count }})</span>
                </div>
            </label>
        @endforeach
    </div>
</div>

<!-- 9. WEIGHING SENSOR -->
<div class="filter-group {{ request()->hasAny(['sensor', 'weighing_sensor']) ? '' : 'collapsed' }}" id="facetGroupSensor">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Weighing Sensor
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @if(isset($weighingSensorOptions))
            @forelse($weighingSensorOptions as $wsOpt)
                @php
                    $isChecked = in_array($wsOpt->spec_value, (array) request('sensor', request('weighing_sensor')));
                @endphp
                <label class="filter-item-label">
                    <div class="filter-checkbox-wrap">
                        <input type="checkbox" 
                               value="{{ $wsOpt->spec_value }}" 
                               {{ $isChecked ? 'checked' : '' }} 
                               onchange="toggleFilterParam('sensor', this.value, this.checked)">
                        <span class="facet-name-text">{{ $wsOpt->spec_value }} ({{ $wsOpt->count }})</span>
                    </div>
                </label>
            @empty
                <span class="facet-empty-msg">No sensor options available</span>
            @endforelse
        @endif
    </div>
</div>

<!-- 10. READABILITY MC -->
<div class="filter-group {{ request()->has('readability_mc') ? '' : 'collapsed' }}" id="facetGroupReadabilityMC">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Readability MC
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @if(isset($readabilityMCOptions))
            @forelse($readabilityMCOptions as $mcOpt)
                @php
                    $isChecked = in_array($mcOpt->spec_value, (array) request('readability_mc'));
                @endphp
                <label class="filter-item-label">
                    <div class="filter-checkbox-wrap">
                        <input type="checkbox" 
                               value="{{ $mcOpt->spec_value }}" 
                               {{ $isChecked ? 'checked' : '' }} 
                               onchange="toggleFilterParam('readability_mc', this.value, this.checked)">
                        <span class="facet-name-text">{{ $mcOpt->spec_value }} ({{ $mcOpt->count }})</span>
                    </div>
                </label>
            @empty
                <span class="facet-empty-msg">Available in Moisture Analyzers</span>
            @endforelse
        @endif
    </div>
</div>

<!-- 11. DENSITY RESOLUTION -->
<div class="filter-group {{ request()->has('density_resolution') ? '' : 'collapsed' }}" id="facetGroupDensityResolution">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Density Resolution
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @if(isset($densityResolutionOptions))
            @forelse($densityResolutionOptions as $drOpt)
                @php
                    $isChecked = in_array($drOpt->spec_value, (array) request('density_resolution'));
                @endphp
                <label class="filter-item-label">
                    <div class="filter-checkbox-wrap">
                        <input type="checkbox" 
                               value="{{ $drOpt->spec_value }}" 
                               {{ $isChecked ? 'checked' : '' }} 
                               onchange="toggleFilterParam('density_resolution', this.value, this.checked)">
                        <span class="facet-name-text">{{ $drOpt->spec_value }} ({{ $drOpt->count }})</span>
                    </div>
                </label>
            @empty
                <span class="facet-empty-msg">Available in Density Balances</span>
            @endforelse
        @endif
    </div>
</div>

<!-- 12. OIML CLASS -->
<div class="filter-group {{ request()->has('oiml_class') ? '' : 'collapsed' }}" id="facetGroupOimlClass">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            OIML Class
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @if(isset($oimlClassOptions))
            @forelse($oimlClassOptions as $ocOpt)
                @php
                    $isChecked = in_array($ocOpt->spec_value, (array) request('oiml_class'));
                @endphp
                <label class="filter-item-label">
                    <div class="filter-checkbox-wrap">
                        <input type="checkbox" 
                               value="{{ $ocOpt->spec_value }}" 
                               {{ $isChecked ? 'checked' : '' }} 
                               onchange="toggleFilterParam('oiml_class', this.value, this.checked)">
                        <span class="facet-name-text">{{ $ocOpt->spec_value }} ({{ $ocOpt->count }})</span>
                    </div>
                </label>
            @empty
                <span class="facet-empty-msg">Available in Test Weights</span>
            @endforelse
        @endif
    </div>
</div>

<!-- 13. NOMINAL VALUE -->
<div class="filter-group {{ request()->has('nominal_value') ? '' : 'collapsed' }}" id="facetGroupNominalValue">
    <div class="filter-group-header" onclick="toggleFilterGroup(this)">
        <span class="filter-group-title">
            Nominal Value
        </span>
        <i class="fa-solid fa-chevron-down filter-chevron"></i>
    </div>
    <div class="filter-group-body">
        @if(isset($nominalValueOptions))
            @forelse($nominalValueOptions as $nvOpt)
                @php
                    $isChecked = in_array($nvOpt->spec_value, (array) request('nominal_value'));
                @endphp
                <label class="filter-item-label">
                    <div class="filter-checkbox-wrap">
                        <input type="checkbox" 
                               value="{{ $nvOpt->spec_value }}" 
                               {{ $isChecked ? 'checked' : '' }} 
                               onchange="toggleFilterParam('nominal_value', this.value, this.checked)">
                        <span class="facet-name-text">{{ $nvOpt->spec_value }} ({{ $nvOpt->count }})</span>
                    </div>
                </label>
            @empty
                <span class="facet-empty-msg">Available in Test Weights</span>
            @endforelse
        @endif
    </div>
</div>
