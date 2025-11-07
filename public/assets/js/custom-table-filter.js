/**
 * Custom Table Filter - Replaces DataTables with vanilla JavaScript
 * Provides search, filter, and sort functionality
 */

function initCustomTableFilter(tableId, options = {}) {
    const table = document.getElementById(tableId);
    if (!table) {
        console.error(`Table with id "${tableId}" not found`);
        return;
    }
    
    const tbody = table.querySelector('tbody');
    const thead = table.querySelector('thead');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const totalRows = rows.length;
    
    let currentFilters = {
        alphabet: '',
        year: '',
        search: '',
        entity: '',
        custom: {}
    };
    
    let sortColumn = 0;
    let sortDirection = 'asc';
    
    // Get column indices
    const headers = [];
    thead.querySelectorAll('th').forEach((th, index) => {
        headers.push(th.textContent.trim());
    });
    
    const titleColIndex = 0;
    const yearColIndex = headers.indexOf('Year');
    const entityColIndex = headers.indexOf('Entity');
    
    // Build filter HTML
    function buildFilterHtml() {
        let filterHtml = '<div class="filter-container">';
        
        // Search input
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="table-search-' + tableId + '">Search:</label>';
        filterHtml += '<input type="text" id="table-search-' + tableId + '" class="filter-select" placeholder="Search..." style="min-width: 200px;">';
        filterHtml += '</div>';
        
        // Alphabet filter
        if (options.showAlphabetFilter !== false) {
            filterHtml += '<div class="filter-group">';
            filterHtml += '<label for="alphabet-filter-' + tableId + '">First Letter:</label>';
            filterHtml += '<select id="alphabet-filter-' + tableId + '" class="filter-select">';
            filterHtml += '<option value="">All Letters</option>';
            const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
            alphabet.forEach(letter => {
                filterHtml += `<option value="${letter}">${letter}</option>`;
            });
            filterHtml += '</select>';
            filterHtml += '</div>';
        }
        
        // Year filter
        if (options.years && options.years.length > 0) {
            filterHtml += '<div class="filter-group">';
            filterHtml += '<label for="year-filter-' + tableId + '">Year:</label>';
            filterHtml += '<select id="year-filter-' + tableId + '" class="filter-select">';
            filterHtml += '<option value="">All Years</option>';
            options.years.forEach(year => {
                filterHtml += `<option value="${year}">${year}</option>`;
            });
            filterHtml += '</select>';
            filterHtml += '</div>';
        }
        
        // Entity filter
        if (entityColIndex !== -1 && options.showEntityFilter !== false) {
            filterHtml += '<div class="filter-group">';
            filterHtml += '<label for="entity-filter-' + tableId + '">Entity:</label>';
            filterHtml += '<select id="entity-filter-' + tableId + '" class="filter-select">';
            filterHtml += '<option value="">All Entities</option>';
            filterHtml += '</select>';
            filterHtml += '</div>';
        }
        
        // Clear button
        filterHtml += '<button class="clear-filters-btn" id="clear-filters-' + tableId + '">Clear Filters</button>';
        filterHtml += '</div>';
        
        // Search info
        filterHtml += '<div id="search-info-' + tableId + '" class="search-info" style="display: none;"></div>';
        
        return filterHtml;
    }
    
    // Insert filters before table
    const filterContainer = document.createElement('div');
    filterContainer.innerHTML = buildFilterHtml();
    table.parentNode.insertBefore(filterContainer, table);
    
    // Populate entity filter if exists
    if (entityColIndex !== -1 && options.showEntityFilter !== false) {
        const entities = new Set();
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const entityText = cells[entityColIndex]?.textContent.trim();
            if (entityText) entities.add(entityText);
        });
        
        const entitySelect = document.getElementById('entity-filter-' + tableId);
        Array.from(entities).sort().forEach(entity => {
            const option = document.createElement('option');
            option.value = entity;
            option.textContent = entity;
            entitySelect.appendChild(option);
        });
    }
    
    // Filter and display rows
    function filterAndDisplayRows() {
        let visibleCount = 0;
        
        rows.forEach(row => {
            let visible = true;
            const cells = row.querySelectorAll('td');
            
            const titleText = cells[titleColIndex]?.textContent.trim() || '';
            const yearText = cells[yearColIndex >= 0 ? yearColIndex : 2]?.textContent.trim() || '';
            const entityText = entityColIndex >= 0 ? cells[entityColIndex]?.textContent.trim() || '' : '';
            
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
        
        const searchInfo = document.getElementById('search-info-' + tableId);
        if (activeFilters.length > 0) {
            const infoText = 'Active filters: ' + activeFilters.join(', ') + 
                          ' | Showing ' + visibleCount + ' of ' + totalRows + ' documents';
            searchInfo.textContent = infoText;
            searchInfo.style.display = 'block';
        } else {
            searchInfo.style.display = 'none';
        }
    }
    
    // Sort table
    function sortTable(columnIndex) {
        const sortedRows = rows.slice().sort((a, b) => {
            const aText = a.querySelectorAll('td')[columnIndex]?.textContent.trim() || '';
            const bText = b.querySelectorAll('td')[columnIndex]?.textContent.trim() || '';
            
            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return sortDirection === 'asc' ? aNum - bNum : bNum - aNum;
            }
            
            if (sortDirection === 'asc') {
                return aText.localeCompare(bText);
            } else {
                return bText.localeCompare(aText);
            }
        });
        
        sortedRows.forEach(row => tbody.appendChild(row));
        filterAndDisplayRows();
    }
    
    // Event listeners
    const searchInput = document.getElementById('table-search-' + tableId);
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            currentFilters.search = e.target.value;
            filterAndDisplayRows();
        });
    }
    
    const alphabetFilter = document.getElementById('alphabet-filter-' + tableId);
    if (alphabetFilter) {
        alphabetFilter.addEventListener('change', function(e) {
            currentFilters.alphabet = e.target.value;
            filterAndDisplayRows();
        });
    }
    
    const yearFilter = document.getElementById('year-filter-' + tableId);
    if (yearFilter) {
        yearFilter.addEventListener('change', function(e) {
            currentFilters.year = e.target.value;
            filterAndDisplayRows();
        });
    }
    
    const entityFilter = document.getElementById('entity-filter-' + tableId);
    if (entityFilter) {
        entityFilter.addEventListener('change', function(e) {
            currentFilters.entity = e.target.value;
            filterAndDisplayRows();
        });
    }
    
    const clearButton = document.getElementById('clear-filters-' + tableId);
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (alphabetFilter) alphabetFilter.value = '';
            if (yearFilter) yearFilter.value = '';
            if (entityFilter) entityFilter.value = '';
            
            currentFilters = {
                alphabet: '',
                year: '',
                search: '',
                entity: '',
                custom: {}
            };
            
            filterAndDisplayRows();
        });
    }
    
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
    
    // Initialize
    filterAndDisplayRows();
    console.log('Custom table filter initialized for:', tableId);
}
