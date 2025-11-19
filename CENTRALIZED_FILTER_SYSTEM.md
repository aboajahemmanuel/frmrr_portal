# Centralized Filter System Documentation

## Overview
This document explains how to use the new centralized filter system for tables in the FMRR application. The system provides a consistent, reusable way to add filtering capabilities to any table.

## Files Included
1. `resources/views/components/filters/table-filters.blade.php` - Reusable filter component
2. `resources/views/components/regulations/table.blade.php` - Updated table component with filter support
3. `public/assets/js/centralized-table-filter.js` - JavaScript library for filter functionality

## How to Use

### 1. Include the JavaScript file
Add this to your page header:
```html
<script src="{{ asset('public/assets/js/centralized-table-filter.js') }}"></script>
```

### 2. Use the table component with filters
Include the table component with filter options:
```php
@include('components.regulations.table', [
    'records' => $records, 
    'isSubscribed' => $isSubscribed,
    'showFilters' => true,
    'filterOptions' => [
        'showAlphabetFilter' => true,
        'showYearFilter' => true,
        'showEntityFilter' => false,
        'showEffectiveDateFilter' => true,
        'showVersionFilter' => true,
        'years' => $years
    ]
])
```

### 3. Initialize the filter system
Add this JavaScript to initialize the filters:
```javascript
$(document).ready(function() {
    var years = @json($years);
    
    // Initialize centralized table filter
    window.tableFilter = initCentralizedTableFilter('example', {
        years: years
    });
});
```

## Filter Options
The filter system supports the following options:
- `showAlphabetFilter` - Enable/disable alphabet filter (default: true)
- `showYearFilter` - Enable/disable year filter (default: true)
- `showEntityFilter` - Enable/disable entity filter (default: true)
- `showEffectiveDateFilter` - Enable/disable effective date filter (default: false)
- `showVersionFilter` - Enable/disable version filter (default: false)
- `years` - Array of years for the year filter

## JavaScript API
The `initCentralizedTableFilter` function returns an object with the following methods:
- `filterAndDisplayRows()` - Manually trigger filtering
- `updateFilters(newFilters)` - Update filter values
- `getFilters()` - Get current filter values
- `clearFilters()` - Clear all filters

## Benefits
1. **Consistency** - All tables use the same filter UI and behavior
2. **Maintainability** - Changes to filter logic only need to be made in one place
3. **Flexibility** - Easy to enable/disable specific filters per page
4. **Performance** - Optimized filtering algorithm
5. **Extensibility** - Easy to add new filter types

## Migration from Old System
To migrate from the old filter system:
1. Remove old filter HTML and JavaScript
2. Add the table component with `showFilters => true`
3. Initialize the centralized filter system
4. Update any custom filter logic to use the new API