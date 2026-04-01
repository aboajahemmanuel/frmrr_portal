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
    // $showMarketProductFilter: bool (default: false)
    // $years: array of years for year filter (optional)
@endphp

<style>
    .filter-container .filter-group.date-range-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .filter-container .date-range-container {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    
    .filter-container .date-range-container input[type="date"] {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
        font-size: 14px;
        min-width: 120px;
        flex: 1;
    }
    
    .filter-container .date-range-container span {
        white-space: nowrap;
        flex-shrink: 0;
    }
</style>

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
    <div class="filter-group date-range-group">
        <label for="effective-date-start-filter-{{ $tableId }}">Effective Date Range:</label>
        <div class="date-range-container">
            <input type="date" id="effective-date-start-filter-{{ $tableId }}" class="filter-select" placeholder="Start date">
            <span>to</span>
            <input type="date" id="effective-date-end-filter-{{ $tableId }}" class="filter-select" placeholder="End date">
        </div>
    </div>
    @endif

    @if($options['showMarketProductFilter'] ?? true)
    <div class="filter-group">
        <label for="market-product-filter-{{ $tableId }}">Market Product:</label>
        <select id="market-product-filter-{{ $tableId }}" class="filter-select">
            <option value="">All Market Products</option>
            @php
                if (!isset($options['marketProducts'])) {
                    $marketProducts = \App\Models\MarketProductTag::orderBy('name')->pluck('name');
                } else {
                    $marketProducts = $options['marketProducts'];
                }
            @endphp
            @foreach($marketProducts as $marketProduct)
                @php
                    if (is_object($marketProduct)) {
                        $marketProductValue = isset($marketProduct->name) ? (string)$marketProduct->name : (string)$marketProduct;
                    } else {
                        $marketProductValue = (string)$marketProduct;
                    }
                @endphp
                <option value="{{ $marketProductValue }}">{{ $marketProductValue }}</option>
            @endforeach
        </select>
    </div>
    @endif

    
    @if($options['showStatusFilter'] ?? true)
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