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
    @if($options['showSearchBar'] ?? false)
    <div class="filter-group">
        <label for="table-search-{{ $tableId }}">Search:</label>
        <input type="text" id="table-search-{{ $tableId }}" class="filter-select" placeholder="Search documents...">
    </div>
    @endif

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
                @php
                    // Ensure we're working with a string value, not an object or integer
                    if (is_object($year)) {
                        // If it's an object, extract the name property and convert to string
                        $yearValue = isset($year->name) ? (string)$year->name : (string)$year;
                    } else {
                        // If it's not an object, convert to string directly
                        $yearValue = (string)$year;
                    }
                @endphp
                <option value="{{ $yearValue }}">{{ $yearValue }}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if($options['showEntityFilter'] ?? false)
    <div class="filter-group">
        <label for="entity-filter-{{ $tableId }}">Entity:</label>
        <select id="entity-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Entities</option>
            @php
                $entities = $records->pluck('entity.name')->unique()->filter()->sort()->values();
            @endphp
            @foreach($entities as $entity)
                @php
                    // Ensure we're working with a string value, not an object
                    if (is_object($entity)) {
                        // If it's an object, convert to string
                        $entityValue = (string)$entity;
                    } else {
                        // If it's not an object, use as is
                        $entityValue = $entity;
                    }
                @endphp
                <option value="{{ $entityValue }}">{{ $entityValue }}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if($options['showEffectiveDateFilter'] ?? true)
    <div class="filter-group">
        <label for="effective-date-filter-{{ $tableId }}">Effective Date:</label>
        <select id="effective-date-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Dates</option>
            @php
                $effectiveDates = $records->pluck('effective_date')->unique()->filter()->sort()->values();
            @endphp
            @foreach($effectiveDates as $date)
                @if($date)
                    @php
                        // Ensure we're working with a string value
                        if (is_object($date)) {
                            // If it's an object, convert to string
                            $dateValue = (string)$date;
                        } else {
                            // If it's not an object, use as is
                            $dateValue = $date;
                        }
                    @endphp
                    <option value="{{ \Carbon\Carbon::parse($dateValue)->format('M. j, Y') }}">{{ \Carbon\Carbon::parse($dateValue)->format('M. j, Y') }}</option>
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
                    @php
                        // Ensure we're working with a string value
                        if (is_object($version)) {
                            // If it's an object, convert to string
                            $versionValue = (string)$version;
                        } else {
                            // If it's not an object, use as is
                            $versionValue = $version;
                        }
                    @endphp
                    <option value="{{ $versionValue }}">{{ $versionValue }}</option>
                @endif
            @endforeach
        </select>
    </div>
    @endif
    
    @if($options['showStatusFilter'] ?? false)
    <div class="filter-group">
        <label for="status-filter-{{ $tableId }}">Status:</label>
        <select id="status-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Statuses</option>
            <option value="Active">Active</option>
            <option value="Ceased">Ceased</option>
            <option value="Repealed">Repealed</option>
            <option value="Amended">Amended</option>
            <option value="Superseded">Superseded</option>
        </select>
    </div>
    @endif
   
    <div class="filter-group">
         <br>
    <br>
    
        <button class="clear-filters-btn" id="clear-filters-{{ $tableId }}">Clear Filters</button>
    </div>
</div>

<div id="search-info-{{ $tableId }}" class="search-info" style="display: none;"></div>