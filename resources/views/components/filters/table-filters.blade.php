@php
    // Expected variables:
    // $records: Collection of records to filter
    // $tableId: ID of the table to filter
    // $options: Array of options for filter configuration
    // $showAlphabetFilter: bool (default: true)
    // $showYearFilter: bool (default: true)
    // $showEntityFilter: bool (default: true)
    // $showEffectiveDateFilter: bool (default: false)
    // $showVersionFilter: bool (default: false)
    // $years: array of years for year filter (optional)
@endphp

<div class="filter-container">
    @if($options['showAlphabetFilter'] ?? true)
    <div class="filter-group">
        <label for="alphabet-filter-{{ $tableId }}">First Letter :</label>
        <select id="alphabet-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Letters</option>
            @foreach(range('A', 'Z') as $letter)
                <option value="{{ $letter }}">{{ $letter }}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if(($options['showYearFilter'] ?? true) && !empty($options['years']))
    <div class="filter-group">
        <label for="year-filter-{{ $tableId }}">Year:</label>
        <select id="year-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Years</option>
            @foreach($options['years'] as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if($options['showEntityFilter'] ?? true)
    <div class="filter-group">
        <label for="entity-filter-{{ $tableId }}">Entity:</label>
        <select id="entity-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Entities</option>
            @php
                $entities = $records->pluck('entity.name')->unique()->filter()->sort()->values();
            @endphp
            @foreach($entities as $entity)
                <option value="{{ $entity }}">{{ $entity }}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if($options['showEffectiveDateFilter'] ?? false)
    <div class="filter-group">
        <label for="effective-date-filter-{{ $tableId }}">Effective Date:</label>
        <select id="effective-date-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Dates</option>
            @php
                $effectiveDates = $records->pluck('effective_date')->unique()->filter()->sort()->values();
            @endphp
            @foreach($effectiveDates as $date)
                @if($date)
                    <option value="{{ \Carbon\Carbon::parse($date)->format('M. j, Y') }}">{{ \Carbon\Carbon::parse($date)->format('M. j, Y') }}</option>
                @endif
            @endforeach
        </select>
    </div>
    @endif

    @if($options['showVersionFilter'] ?? false)
    <div class="filter-group">
        <label for="version-filter-{{ $tableId }}">Version:</label>
        <select id="version-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Versions</option>
            @php
                $versions = $records->pluck('document_version')->unique()->filter()->sort(function($a, $b) {
                    return version_compare($a, $b);
                })->values();
            @endphp
            @foreach($versions as $version)
                @if($version !== null && $version !== '')
                    <option value="{{ $version }}">{{ $version }}</option>
                @endif
            @endforeach
        </select>
    </div>
    @endif

    <div class="filter-group">
        <button class="clear-filters-btn" id="clear-filters-{{ $tableId }}">Clear Filters</button>
    </div>
</div>

<div id="search-info-{{ $tableId }}" class="search-info" style="display: none;"></div>