// Improved Filter System - Copy this JavaScript to replace old filtering code

// CSS Styles (add to <style> section)
const filterCSS = `
        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
            clear: both;
            width: 100%;
        }
        
        .dataTables_wrapper .dataTables_filter {
            float: none !important;
            text-align: left;
            margin-bottom: 15px;
        }
        
        .dataTables_wrapper .dataTables_length {
            float: none !important;
            margin-bottom: 10px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            min-width: 120px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .clear-filters-btn {
            padding: 8px 16px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            align-self: flex-end;
        }

        .clear-filters-btn:hover {
            background-color: #5a6268;
        }

        .search-info {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 14px;
            color: #495057;
        }
`;

// JavaScript Code (replace existing DataTable initialization)
const filterJS = `
    $(document).ready(function() {
        var table = $('#example').DataTable({
            columnDefs: [
                {
                    targets: 0, // Title column
                    render: function (data, type, row) {
                        if (type === 'filter' || type === 'sort') {
                            return $('<div>').html(data).text(); // Strips HTML for filtering/sorting
                        }
                        return data; // Keep HTML for display
                    }
                }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'asc']], // Default sort by title
            responsive: true,
            language: {
                search: "Search documents:",
                lengthMenu: "Show _MENU_ documents per page",
                info: "Showing _START_ to _END_ of _TOTAL_ documents",
                infoFiltered: "(filtered from _MAX_ total documents)"
            }
        });

        // Detect table structure and get column indices
        var headers = [];
        $('#example thead th').each(function(index) {
            headers.push($(this).text().trim());
        });
        
        var titleColIndex = 0; // Title is always first
        var yearColIndex = headers.indexOf('Year');

        // Create enhanced filter container
        var filterHtml = '<div class="filter-container">';
        
        // Alphabet filter dropdown
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="alphabet-filter">Filter by First Letter:</label>';
        filterHtml += '<select id="alphabet-filter" class="filter-select">';
        filterHtml += '<option value="">All Letters</option>';
        var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        alphabet.forEach(function (letter) {
            filterHtml += '<option value="' + letter + '">' + letter + '</option>';
        });
        filterHtml += '</select>';
        filterHtml += '</div>';

        // Year filter dropdown
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="year-filter">Filter by Year:</label>';
        filterHtml += '<select id="year-filter" class="filter-select">';
        filterHtml += '<option value="">All Years</option>';
        years.forEach(function (year) {
            filterHtml += '<option value="' + year + '">' + year + '</option>';
        });
        filterHtml += '</select>';
        filterHtml += '</div>';

        // Clear filters button
        filterHtml += '<button class="clear-filters-btn" id="clear-filters">Clear All Filters</button>';
        filterHtml += '</div>';

        // Add search info container
        filterHtml += '<div id="search-info" class="search-info" style="display: none;"></div>';

        $('#example_wrapper').prepend(filterHtml);

        // Custom search function for first letter and year filtering
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var selectedLetter = $('#alphabet-filter').val();
                var selectedYear = $('#year-filter').val();
                
                // If no filters are selected, show all rows
                if (!selectedLetter && !selectedYear) {
                    return true;
                }
                
                var match = true;
                
                // Check alphabet filter
                if (selectedLetter) {
                    var titleText = $('<div>').html(data[titleColIndex]).text().trim();
                    var firstLetter = titleText.charAt(0).toUpperCase();
                    if (firstLetter !== selectedLetter.toUpperCase()) {
                        match = false;
                    }
                }
                
                // Check year filter - find year column dynamically
                if (match && selectedYear) {
                    var currentYearColIndex = yearColIndex;
                    // For tables without explicit year column, try common indices
                    if (currentYearColIndex === -1) {
                        currentYearColIndex = 3; // Common year column position
                    }
                    
                    var yearText = $('<div>').html(data[currentYearColIndex]).text().trim();
                    if (yearText !== selectedYear) {
                        match = false;
                    }
                }
                
                return match;
            }
        );

        // Alphabet filter functionality
        $('#alphabet-filter').on('change', function () {
            table.draw();
            updateSearchInfo();
        });

        // Year filter functionality
        $('#year-filter').on('change', function () {
            table.draw();
            updateSearchInfo();
        });

        // Clear all filters
        $('#clear-filters').on('click', function () {
            $('#alphabet-filter').val('');
            $('#year-filter').val('');
            table.draw();
            $('#search-info').hide();
        });

        // Update search info
        function updateSearchInfo() {
            var info = table.page.info();
            var activeFilters = [];
            
            if ($('#alphabet-filter').val()) {
                activeFilters.push('Letter: ' + $('#alphabet-filter').val());
            }
            if ($('#year-filter').val()) {
                activeFilters.push('Year: ' + $('#year-filter').val());
            }
            
            if (activeFilters.length > 0) {
                var infoText = 'Active filters: ' + activeFilters.join(', ') + 
                              ' | Showing ' + info.recordsDisplay + ' of ' + info.recordsTotal + ' documents';
                $('#search-info').text(infoText).show();
            } else {
                $('#search-info').hide();
            }
        }

        // Update info on table draw
        table.on('draw', function () {
            updateSearchInfo();
        });

        // Enhanced search functionality
        $('.dataTables_filter input').attr('placeholder', 'Search by title, entity, or any field...');
    });
`;
