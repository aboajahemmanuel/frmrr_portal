/**
 * Centralized Table Filter - Reusable filtering system for all tables
 * Provides search, filter, and sort functionality in a centralized way
 */

function initCentralizedTableFilter(tableId, options = {}) {
    console.log('Initializing centralized table filter for table:', tableId);
    console.log('Options:', options);
    
    const table = document.getElementById(tableId);
    if (!table) {
        console.error(`Table with id "${tableId}" not found`);
        return;
    }
    
    console.log('Table found:', table);
    console.log('Table headers:', table.querySelectorAll('thead th').length);
    console.log('Table rows:', table.querySelectorAll('tbody tr').length);
    
    const tbody = table.querySelector('tbody');
    const thead = table.querySelector('thead');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const totalRows = rows.length;
    
    // Initialize filter state
    let currentFilters = {
        alphabet: '',
        year: '',
        search: '',
        entity: '',
        effectiveDateStart: '',
        effectiveDateEnd: '',
        version: '',
        status: '',
        custom: {}
    };
    
    let sortColumn = 0;
    let sortDirection = 'asc';
    
    // Get column indices
    const headers = [];
    thead.querySelectorAll('th').forEach((th, index) => {
        headers.push(th.textContent.trim());
    });
    
    console.log('Table headers found:', headers);
    
    const titleColIndex = 0;
    const yearColIndex = headers.indexOf('Year');
    const entityColIndex = headers.indexOf('Entity');
    // More flexible detection for Effective Date column
    let effectiveDateColIndex = -1;
    for (let i = 0; i < headers.length; i++) {
        if (headers[i].toLowerCase().includes('effective date')) {
            effectiveDateColIndex = i;
            break;
        }
    }
    const versionColIndex = headers.indexOf('Version Number');
    
    // Find status column - look for any header containing 'Status' (case insensitive)
    let statusColIndex = -1;
    for (let i = 0; i < headers.length; i++) {
        if (headers[i].toLowerCase().includes('status')) {
            statusColIndex = i;
            break;
        }
    }
    
    console.log('Status column index found:', statusColIndex);
    
    console.log('Column indices - Title:', titleColIndex, 'Year:', yearColIndex, 'Entity:', entityColIndex, 'Effective Date:', effectiveDateColIndex, 'Version:', versionColIndex, 'Status:', statusColIndex);
    
    // Filter and display rows
    function filterAndDisplayRows() {
        console.log('Current filters:', currentFilters);
        let visibleCount = 0;
        
        rows.forEach((row, rowIndex) => {
            let visible = true;
            const cells = row.querySelectorAll('td');
            
            const titleText = cells[titleColIndex]?.textContent.trim() || '';
            const yearText = cells[yearColIndex >= 0 ? yearColIndex : 2]?.textContent.trim() || '';
            const entityText = entityColIndex >= 0 ? cells[entityColIndex]?.textContent.trim() || '' : '';
            const effectiveDateText = effectiveDateColIndex >= 0 ? cells[effectiveDateColIndex]?.textContent.trim() || '' : '';
            if (rowIndex < 3) {
                console.log(`Row ${rowIndex} effective date text:`, effectiveDateText, 'Column index:', effectiveDateColIndex);
            }
            const versionText = versionColIndex >= 0 ? cells[versionColIndex]?.textContent.trim() || '' : '';
            const statusText = statusColIndex >= 0 ? cells[statusColIndex]?.textContent.trim() || '' : '';
            
            // Log first few rows for debugging
            if (rowIndex < 3) {
                console.log(`Row ${rowIndex} status text:`, statusText);
            }
            
            // Search filter
            if (currentFilters.search) {
                const searchTerm = currentFilters.search.toLowerCase();
                const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                visible = visible && rowText.includes(searchTerm);
            }
            
            // Alphabet filter
            if (visible && currentFilters.alphabet) {
                const firstLetter = titleText.charAt(0).toUpperCase();
                visible = visible && (firstLetter === currentFilters.alphabet);
            }
            
            // Year filter
            if (visible && currentFilters.year) {
                visible = visible && yearText.includes(currentFilters.year);
            }
            
            // Entity filter
            if (visible && currentFilters.entity) {
                visible = visible && entityText.includes(currentFilters.entity);
            }
            
            // Effective Date range filter
            if (visible && (currentFilters.effectiveDateStart || currentFilters.effectiveDateEnd)) {
                console.log('Effective date filter - Start:', currentFilters.effectiveDateStart, 'End:', currentFilters.effectiveDateEnd);
                console.log('Effective date text from cell:', effectiveDateText);
                
                // Convert the effective date text to a proper date format for comparison
                let effectiveDateValue = null;
                
                // Extract date from the text (e.g., "Jan. 1, 2023")
                const dateRegex = /([A-Za-z]{3})\.?\s*(\d{1,2}),?\s*(\d{4})/;
                const dateMatch = effectiveDateText.match(dateRegex);
                
                if (dateMatch) {
                    const monthAbbr = dateMatch[1];
                    const day = dateMatch[2];
                    const year = dateMatch[3];
                    
                    // Create a proper date string
                    const monthMap = {
                        'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04',
                        'May': '05', 'Jun': '06', 'Jul': '07', 'Aug': '08',
                        'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
                    };
                    
                    const monthNum = monthMap[monthAbbr];
                    effectiveDateValue = new Date(`${year}-${monthNum}-${day}`);
                    console.log('Parsed date from regex:', effectiveDateValue);
                } else {
                    // If the format doesn't match, try direct parsing
                    effectiveDateValue = new Date(effectiveDateText);
                    console.log('Direct parsed date:', effectiveDateValue);
                }
                
                // If the date couldn't be parsed, hide the row
                if (isNaN(effectiveDateValue.getTime())) {
                    console.log('Date could not be parsed, hiding row');
                    visible = false;
                } else {
                    // Apply date range filter
                    // Convert to same timezone to avoid date comparison issues
                    const effectiveDateMidnight = new Date(effectiveDateValue.getFullYear(), effectiveDateValue.getMonth(), effectiveDateValue.getDate());
                    
                    if (currentFilters.effectiveDateStart) {
                        // Parse start date (YYYY-MM-DD format from date input)
                        const startDate = new Date(currentFilters.effectiveDateStart);
                        // Set to midnight for comparison
                        const startMidnight = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());
                        console.log('Start filter date:', startMidnight, 'Row date:', effectiveDateMidnight);
                        visible = visible && effectiveDateMidnight >= startMidnight;
                        console.log('After start date check, visible:', visible);
                    }
                    
                    if (currentFilters.effectiveDateEnd) {
                        // Parse end date (YYYY-MM-DD format from date input)
                        const endDate = new Date(currentFilters.effectiveDateEnd);
                        // Set to midnight for comparison
                        const endMidnight = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate());
                        console.log('End filter date:', endMidnight, 'Row date:', effectiveDateMidnight);
                        visible = visible && effectiveDateMidnight <= endMidnight;
                        console.log('After end date check, visible:', visible);
                    }
                }
            }
            
            // Version filter
            if (visible && currentFilters.version) {
                visible = visible && versionText.includes(currentFilters.version);
            }
            
            // Status filter
            if (visible && currentFilters.status) {
                console.log('Applying status filter:', currentFilters.status);
                console.log('Status column index:', statusColIndex);
                
                // Get the status cell content
                const statusCell = cells[statusColIndex];
                const statusText = statusCell ? statusCell.textContent.trim().toLowerCase() : '';
                
                console.log('Status cell content:', statusText);
                
                if (currentFilters.status === 'Active') {
                    // For Active, check if the status text is exactly 'active' (meaning ceased field is null/empty)
                    visible = visible && (statusText === 'active');
                    console.log('Active filter result:', visible);
                } else {
                    // For other statuses, check if the status text contains the selected status
                    const selectedStatus = currentFilters.status.toLowerCase();
                    visible = visible && statusText.includes(selectedStatus);
                    console.log('Status filter result for', selectedStatus, ':', visible);
                }
            }
            
            // Custom filters
            if (visible && options.customFilter) {
                visible = visible && options.customFilter(row, cells, currentFilters);
            }
            
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });
        
        updateSearchInfo(visibleCount);
    }
    
    // Update search info
    function updateSearchInfo(visibleCount) {
        const activeFilters = [];
        
        if (currentFilters.search) {
            activeFilters.push('Search: ' + currentFilters.search);
        }
        if (currentFilters.alphabet) {
            activeFilters.push('Letter: ' + currentFilters.alphabet);
        }
        if (currentFilters.year) {
            activeFilters.push('Year: ' + currentFilters.year);
        }
        if (currentFilters.entity) {
            activeFilters.push('Entity: ' + currentFilters.entity);
        }
        if (currentFilters.effectiveDateStart || currentFilters.effectiveDateEnd) {
            let dateFilterText = 'Effective Date: ';
            if (currentFilters.effectiveDateStart) {
                dateFilterText += 'From ' + currentFilters.effectiveDateStart;
            }
            if (currentFilters.effectiveDateEnd) {
                if (currentFilters.effectiveDateStart) {
                    dateFilterText += ' to ';
                }
                dateFilterText += currentFilters.effectiveDateEnd;
            }
            activeFilters.push(dateFilterText);
        }
        if (currentFilters.version) {
            activeFilters.push('Version: ' + currentFilters.version);
        }
        
        if (currentFilters.status) {
            activeFilters.push('Status: ' + currentFilters.status);
        }
        
        // Add custom filters to info
        if (currentFilters.custom) {
            Object.keys(currentFilters.custom).forEach(key => {
                if (currentFilters.custom[key]) {
                    activeFilters.push(key + ': ' + currentFilters.custom[key]);
                }
            });
        }
        
        const searchInfo = document.getElementById('search-info-' + tableId);
        if (searchInfo) {
            if (activeFilters.length > 0) {
                const infoText = 'Active filters: ' + activeFilters.join(', ') + 
                              ' | Showing ' + visibleCount + ' of ' + totalRows + ' documents';
                searchInfo.textContent = infoText;
                searchInfo.style.display = 'block';
            } else {
                searchInfo.style.display = 'none';
            }
        }
    }
    
    // Sort table
    function sortTable(columnIndex) {
        console.log('Sorting column:', columnIndex, 'Direction:', sortDirection);
        
        const sortedRows = rows.slice().sort((a, b) => {
            const aCells = a.querySelectorAll('td');
            const bCells = b.querySelectorAll('td');
            
            // Handle case where cells might not exist
            if (!aCells[columnIndex] || !bCells[columnIndex]) {
                console.log('Missing cell data for sorting');
                return 0;
            }
            
            const aText = aCells[columnIndex].textContent.trim() || '';
            const bText = bCells[columnIndex].textContent.trim() || '';
            
            console.log('Comparing:', aText, 'with', bText, 'in column', columnIndex);
            
            // Special handling for Year column
            if (columnIndex === yearColIndex && yearColIndex !== -1) {
                const aYear = parseInt(aText, 10);
                const bYear = parseInt(bText, 10);
                
                console.log('Year comparison:', aYear, 'vs', bYear);
                
                if (!isNaN(aYear) && !isNaN(bYear)) {
                    const result = sortDirection === 'asc' ? aYear - bYear : bYear - aYear;
                    console.log('Year sort result:', result);
                    return result;
                }
            }
            
            // Special handling for Version column
            if (columnIndex === versionColIndex && versionColIndex !== -1) {
                // Try to parse as version numbers
                const aParts = aText.split('.').map(Number);
                const bParts = bText.split('.').map(Number);
                
                for (let i = 0; i < Math.max(aParts.length, bParts.length); i++) {
                    const aNum = aParts[i] || 0;
                    const bNum = bParts[i] || 0;
                    
                    if (aNum !== bNum) {
                        const result = sortDirection === 'asc' ? aNum - bNum : bNum - aNum;
                        console.log('Version sort result:', result);
                        return result;
                    }
                }
                
                return 0;
            }
            
            // Special handling for numeric values
            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                const result = sortDirection === 'asc' ? aNum - bNum : bNum - aNum;
                console.log('Numeric sort result:', result);
                return result;
            }
            
            // Default string comparison
            const result = sortDirection === 'asc' ? 
                aText.localeCompare(bText) : 
                bText.localeCompare(aText);
            
            console.log('String sort result:', result);
            return result;
        });
        
        console.log('Sorted rows count:', sortedRows.length);
        sortedRows.forEach(row => tbody.appendChild(row));
        filterAndDisplayRows();
    }
    
    // Attach event listeners
    function attachEventListeners() {
        // Search input
        const searchInput = document.getElementById('table-search-' + tableId);
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                currentFilters.search = e.target.value;
                filterAndDisplayRows();
            });
        }
        
        // Alphabet filter
        const alphabetFilter = document.getElementById('alphabet-filter-' + tableId);
        if (alphabetFilter) {
            alphabetFilter.addEventListener('change', function(e) {
                currentFilters.alphabet = e.target.value;
                filterAndDisplayRows();
            });
        }
        
        // Year filter
        const yearFilter = document.getElementById('year-filter-' + tableId);
        if (yearFilter) {
            yearFilter.addEventListener('change', function(e) {
                currentFilters.year = e.target.value;
                filterAndDisplayRows();
            });
        }
        
        // Entity filter
        const entityFilter = document.getElementById('entity-filter-' + tableId);
        if (entityFilter) {
            entityFilter.addEventListener('change', function(e) {
                currentFilters.entity = e.target.value;
                filterAndDisplayRows();
            });
        }
        
        // Effective Date range filters
        const effectiveDateStartFilter = document.getElementById('effective-date-start-filter-' + tableId);
        const effectiveDateEndFilter = document.getElementById('effective-date-end-filter-' + tableId);
        
        if (effectiveDateStartFilter) {
            effectiveDateStartFilter.addEventListener('change', function(e) {
                currentFilters.effectiveDateStart = e.target.value;
                console.log('Effective date start changed to:', e.target.value);
                filterAndDisplayRows();
            });
        }
        
        if (effectiveDateEndFilter) {
            effectiveDateEndFilter.addEventListener('change', function(e) {
                currentFilters.effectiveDateEnd = e.target.value;
                console.log('Effective date end changed to:', e.target.value);
                filterAndDisplayRows();
            });
        }
        
        // Version filter
        const versionFilter = document.getElementById('version-filter-' + tableId);
        if (versionFilter) {
            versionFilter.addEventListener('change', function(e) {
                currentFilters.version = e.target.value;
                filterAndDisplayRows();
            });
        }
        
        // Status filter
        const statusFilter = document.getElementById('status-filter-' + tableId);
        console.log('Looking for status filter:', 'status-filter-' + tableId);
        console.log('Status filter element found:', statusFilter);
        if (statusFilter) {
            console.log('Attaching event listener to status filter');
            statusFilter.addEventListener('change', function(e) {
                console.log('Status filter changed to:', e.target.value);
                currentFilters.status = e.target.value;
                filterAndDisplayRows();
            });
        } else {
            console.warn('Status filter element not found');
        }
        
        // Clear filters button
        const clearButton = document.getElementById('clear-filters-' + tableId);
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (alphabetFilter) alphabetFilter.value = '';
                if (yearFilter) yearFilter.value = '';
                if (entityFilter) entityFilter.value = '';
                if (effectiveDateStartFilter) {
                    effectiveDateStartFilter.value = '';
                    console.log('Cleared effective date start filter');
                }
                if (effectiveDateEndFilter) {
                    effectiveDateEndFilter.value = '';
                    console.log('Cleared effective date end filter');
                }
                
                // Also explicitly clear the filter values in the state
                currentFilters.effectiveDateStart = '';
                currentFilters.effectiveDateEnd = '';
                if (versionFilter) versionFilter.value = '';
                if (statusFilter) statusFilter.value = '';
                
                currentFilters = {
                    alphabet: '',
                    year: '',
                    search: '',
                    entity: '',
                    effectiveDateStart: '',
                    effectiveDateEnd: '',
                    version: '',
                    status: '',
                    custom: {}
                };
                
                // Explicitly clear the date input values
                if (effectiveDateStartFilter) effectiveDateStartFilter.value = '';
                if (effectiveDateEndFilter) effectiveDateEndFilter.value = '';
                
                filterAndDisplayRows();
            });
        }
        
        // Listen for custom filter changes
        table.addEventListener('customFilterChange', function(e) {
            currentFilters.custom = e.detail;
            filterAndDisplayRows();
        });
        
        // Add sort to headers
        thead.querySelectorAll('th').forEach((th, index) => {
            th.style.cursor = 'pointer';
            th.style.userSelect = 'none';
            
            const sortIndicator = document.createElement('span');
            sortIndicator.innerHTML = ' ↕';
            sortIndicator.style.opacity = '0.3';
            th.appendChild(sortIndicator);
            
            th.addEventListener('click', function() {
                if (sortColumn === index) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    sortColumn = index;
                    sortDirection = 'asc';
                }
                
                thead.querySelectorAll('th span').forEach((span, i) => {
                    if (i === index) {
                        span.innerHTML = sortDirection === 'asc' ? ' ▲' : ' ▼';
                        span.style.opacity = '1';
                    } else {
                        span.innerHTML = ' ↕';
                        span.style.opacity = '0.3';
                    }
                });
                
                sortTable(index);
            });
        });
    }
    
    // Initialize
    attachEventListeners();
    filterAndDisplayRows();
    console.log('Centralized table filter initialized for:', tableId);
    
    // Return public API
    return {
        filterAndDisplayRows: filterAndDisplayRows,
        updateFilters: function(newFilters) {
            Object.assign(currentFilters, newFilters);
            filterAndDisplayRows();
        },
        getFilters: function() {
            return {...currentFilters};
        },
        clearFilters: function() {
            const searchInput = document.getElementById('table-search-' + tableId);
            const alphabetFilter = document.getElementById('alphabet-filter-' + tableId);
            const yearFilter = document.getElementById('year-filter-' + tableId);
            const entityFilter = document.getElementById('entity-filter-' + tableId);
            const effectiveDateStartFilter = document.getElementById('effective-date-start-filter-' + tableId);
            const effectiveDateEndFilter = document.getElementById('effective-date-end-filter-' + tableId);
            const versionFilter = document.getElementById('version-filter-' + tableId);
            const statusFilter = document.getElementById('status-filter-' + tableId);
            
            if (searchInput) searchInput.value = '';
            if (alphabetFilter) alphabetFilter.value = '';
            if (yearFilter) yearFilter.value = '';
            if (entityFilter) entityFilter.value = '';
            if (effectiveDateStartFilter) effectiveDateStartFilter.value = '';
            if (effectiveDateEndFilter) effectiveDateEndFilter.value = '';
            if (versionFilter) versionFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            
            currentFilters = {
                alphabet: '',
                year: '',
                search: '',
                entity: '',
                effectiveDateStart: '',
                effectiveDateEnd: '',
                version: '',
                status: '',
                custom: {}
            };
            
            // Explicitly clear the date input values
            if (effectiveDateStartFilter) effectiveDateStartFilter.value = '';
            if (effectiveDateEndFilter) effectiveDateEndFilter.value = '';
            
            filterAndDisplayRows();
        }
    };
}