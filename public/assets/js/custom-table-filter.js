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
        effectiveDate: '',
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

    const titleColIndex = 0;
    const statusColIndex = headers.indexOf('Status');
    const yearColIndex = headers.indexOf('Year');
    const entityColIndex = headers.indexOf('Entity');
    const effectiveDateColIndex = headers.indexOf('Effective Date');
    const versionColIndex = headers.indexOf('Version Number');

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

        // Effective Date filter
        if (effectiveDateColIndex !== -1 && options.showEffectiveDateFilter !== false) {
            // Collect unique effective dates
            const effectiveDates = new Set();
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const effectiveDateText = cells[effectiveDateColIndex]?.textContent.trim();
                if (effectiveDateText) effectiveDates.add(effectiveDateText);
            });

            const sortedDates = Array.from(effectiveDates).sort();

            filterHtml += '<div class="filter-group">';
            filterHtml += '<label for="effective-date-filter-' + tableId + '">Effective Date:</label>';
            filterHtml += '<select id="effective-date-filter-' + tableId + '" class="filter-select">';
            filterHtml += '<option value="">All Dates</option>';
            sortedDates.forEach(date => {
                filterHtml += `<option value="${date}">${date}</option>`;
            });
            filterHtml += '</select>';
            filterHtml += '</div>';
        }

        // Version filter
        if (versionColIndex !== -1 && options.showVersionFilter !== false) {
            // Collect unique versions
            const versions = new Set();
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const versionText = cells[versionColIndex]?.textContent.trim();
                if (versionText) versions.add(versionText);
            });

            // Sort versions properly
            const sortedVersions = Array.from(versions).sort((a, b) => {
                // Try to parse as version numbers
                const aParts = a.split('.').map(Number);
                const bParts = b.split('.').map(Number);

                for (let i = 0; i < Math.max(aParts.length, bParts.length); i++) {
                    const aNum = aParts[i] || 0;
                    const bNum = bParts[i] || 0;

                    if (aNum !== bNum) {
                        return aNum - bNum;
                    }
                }

                return 0;
            });

            filterHtml += '<div class="filter-group">';
            filterHtml += '<label for="version-filter-' + tableId + '">Version:</label>';
            filterHtml += '<select id="version-filter-' + tableId + '" class="filter-select">';
            filterHtml += '<option value="">All Versions</option>';
            sortedVersions.forEach(version => {
                filterHtml += `<option value="${version}">${version}</option>`;
            });
            filterHtml += '</select>';
            filterHtml += '</div>';
        }

        // Status filter
        if (statusColIndex !== -1 && options.showStatusFilter !== false) {
            filterHtml += '<div class="filter-group">';
            filterHtml += '<label for="status-filter-' + tableId + '">Status:</label>';
            filterHtml += '<select id="status-filter-' + tableId + '" class="filter-select">';
            filterHtml += '<option value="">All Statuses</option>';
            filterHtml += '<option value="Active">Active</option>';
            filterHtml += '<option value="Ceased">Ceased</option>';
            filterHtml += '<option value="Repealed">Repealed</option>';
            filterHtml += '<option value="Amended">Amended</option>';
            filterHtml += '<option value="Superseded">Superseded</option>';
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
            const statusText = statusColIndex >= 0 ? cells[statusColIndex]?.textContent.trim() || '' : '';
            const yearText = cells[yearColIndex >= 0 ? yearColIndex : 2]?.textContent.trim() || '';
            const entityText = entityColIndex >= 0 ? cells[entityColIndex]?.textContent.trim() || '' : '';
            const effectiveDateText = effectiveDateColIndex >= 0 ? cells[effectiveDateColIndex]?.textContent.trim() || '' : '';
            const versionText = versionColIndex >= 0 ? cells[versionColIndex]?.textContent.trim() || '' : '';

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

            // Effective Date filter
            if (visible && currentFilters.effectiveDate) {
                visible = visible && effectiveDateText.includes(currentFilters.effectiveDate);
            }

            // Version filter
            if (visible && currentFilters.version) {
                visible = visible && versionText.includes(currentFilters.version);
            }

            // Status filter
            if (visible && currentFilters.status) {
                if (currentFilters.status === 'Active') {
                    // Active = the status badge says 'Active' (no ceased value)
                    visible = visible && statusText === 'Active';
                } else if (currentFilters.status === 'Empty Space') {
                    visible = visible && statusText === '';
                } else {
                    // For Ceased/Repealed/Amended/Superseded, check if status text contains the keyword
                    visible = visible && statusText.toLowerCase().includes(currentFilters.status.toLowerCase());
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
        if (currentFilters.effectiveDate) {
            activeFilters.push('Effective Date: ' + currentFilters.effectiveDate);
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

            // Special handling for Year column (index 4)
            if (columnIndex === 4) {
                const aYear = parseInt(aText, 10);
                const bYear = parseInt(bText, 10);

                console.log('Year comparison:', aYear, 'vs', bYear);

                if (!isNaN(aYear) && !isNaN(bYear)) {
                    const result = sortDirection === 'asc' ? aYear - bYear : bYear - aYear;
                    console.log('Year sort result:', result);
                    return result;
                }
            }

            // Special handling for Version column (index 1)
            if (columnIndex === 1) {
                // Try to parse as version numbers
                const aVersion = parseFloat(aText);
                const bVersion = parseFloat(bText);

                if (!isNaN(aVersion) && !isNaN(bVersion)) {
                    const result = sortDirection === 'asc' ? aVersion - bVersion : bVersion - aVersion;
                    console.log('Version sort result:', result);
                    return result;
                }
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

    // Event listeners
    const searchInput = document.getElementById('table-search-' + tableId);
    if (searchInput) {
        searchInput.addEventListener('keyup', function (e) {
            currentFilters.search = e.target.value;
            filterAndDisplayRows();
        });
    }

    const alphabetFilter = document.getElementById('alphabet-filter-' + tableId);
    if (alphabetFilter) {
        alphabetFilter.addEventListener('change', function (e) {
            currentFilters.alphabet = e.target.value;
            filterAndDisplayRows();
        });
    }

    const yearFilter = document.getElementById('year-filter-' + tableId);
    if (yearFilter) {
        yearFilter.addEventListener('change', function (e) {
            currentFilters.year = e.target.value;
            filterAndDisplayRows();
        });
    }

    const entityFilter = document.getElementById('entity-filter-' + tableId);
    if (entityFilter) {
        entityFilter.addEventListener('change', function (e) {
            currentFilters.entity = e.target.value;
            filterAndDisplayRows();
        });
    }

    // Effective Date filter
    const effectiveDateFilter = document.getElementById('effective-date-filter-' + tableId);
    if (effectiveDateFilter) {
        effectiveDateFilter.addEventListener('change', function (e) {
            currentFilters.effectiveDate = e.target.value;
            filterAndDisplayRows();
        });
    }

    // Version filter
    const versionFilter = document.getElementById('version-filter-' + tableId);
    if (versionFilter) {
        versionFilter.addEventListener('change', function (e) {
            currentFilters.version = e.target.value;
            filterAndDisplayRows();
        });
    }

    // Status filter
    const statusFilter = document.getElementById('status-filter-' + tableId);
    if (statusFilter) {
        statusFilter.addEventListener('change', function (e) {
            currentFilters.status = e.target.value;
            filterAndDisplayRows();
        });
    }

    const clearButton = document.getElementById('clear-filters-' + tableId);
    if (clearButton) {
        clearButton.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (alphabetFilter) alphabetFilter.value = '';
            if (yearFilter) yearFilter.value = '';
            if (entityFilter) entityFilter.value = '';
            if (effectiveDateFilter) effectiveDateFilter.value = '';
            if (versionFilter) versionFilter.value = '';
            if (statusFilter) statusFilter.value = '';

            currentFilters = {
                alphabet: '',
                year: '',
                search: '',
                entity: '',
                effectiveDate: '',
                version: '',
                status: '',
                custom: {}
            };

            filterAndDisplayRows();
        });
    }

    // Listen for custom filter changes
    table.addEventListener('customFilterChange', function (e) {
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

        th.addEventListener('click', function () {
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