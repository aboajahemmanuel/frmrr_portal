<!DOCTYPE html>
<html>
<head>
    <title>Centralized Filter System Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('public/assets/js/centralized-table-filter.js') }}"></script>
    <style>
        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
            clear: both;
            width: 100%;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
        }

        .clear-filters-btn {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            align-self: flex-end;
            width: 100%;
            margin-top: 10px;
        }

        .search-info {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 14px;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Centralized Filter System Test</h1>
        
        <div id="filter-container"></div>
        
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Version Number</th>
                    <th>Year</th>
                    <th>Effective Date</th>
                    <th>Entity</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Document A</td>
                    <td>Regulations</td>
                    <td>1.2.3</td>
                    <td>2023</td>
                    <td>Jan 1, 2023</td>
                    <td>Entity A</td>
                </tr>
                <tr>
                    <td>Document B</td>
                    <td>Guidelines</td>
                    <td>2.0.0</td>
                    <td>2022</td>
                    <td>Feb 15, 2022</td>
                    <td>Entity B</td>
                </tr>
                <tr>
                    <td>Document C</td>
                    <td>Standards</td>
                    <td>1.5.0</td>
                    <td>2023</td>
                    <td>Mar 10, 2023</td>
                    <td>Entity A</td>
                </tr>
                <tr>
                    <td>Document D</td>
                    <td>Regulations</td>
                    <td>1.0.0</td>
                    <td>2021</td>
                    <td>Dec 5, 2021</td>
                    <td>Entity C</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Create filter HTML dynamically for testing
            var filterHtml = `
                <div class="filter-container">
                    <div class="filter-group">
                        <label for="alphabet-filter-example">First Letter:</label>
                        <select id="alphabet-filter-example" class="filter-select">
                            <option value="">All Letters</option>
                            ${Array.from('ABCDEFGHIJKLMNOPQRSTUVWXYZ').map(letter => 
                                `<option value="${letter}">${letter}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="year-filter-example">Year:</label>
                        <select id="year-filter-example" class="filter-select">
                            <option value="">All Years</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="entity-filter-example">Entity:</label>
                        <select id="entity-filter-example" class="filter-select">
                            <option value="">All Entities</option>
                            <option value="Entity A">Entity A</option>
                            <option value="Entity B">Entity B</option>
                            <option value="Entity C">Entity C</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="effective-date-filter-example">Effective Date:</label>
                        <select id="effective-date-filter-example" class="filter-select">
                            <option value="">All Dates</option>
                            <option value="Jan 1, 2023">Jan 1, 2023</option>
                            <option value="Feb 15, 2022">Feb 15, 2022</option>
                            <option value="Mar 10, 2023">Mar 10, 2023</option>
                            <option value="Dec 5, 2021">Dec 5, 2021</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="version-filter-example">Version:</label>
                        <select id="version-filter-example" class="filter-select">
                            <option value="">All Versions</option>
                            <option value="1.0.0">1.0.0</option>
                            <option value="1.2.3">1.2.3</option>
                            <option value="1.5.0">1.5.0</option>
                            <option value="2.0.0">2.0.0</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button class="clear-filters-btn" id="clear-filters-example">Clear Filters</button>
                    </div>
                </div>
                <div id="search-info-example" class="search-info" style="display: none;"></div>
            `;
            
            $('#filter-container').html(filterHtml);
            
            // Initialize centralized table filter
            window.tableFilter = initCentralizedTableFilter('example', {
                years: [2021, 2022, 2023]
            });
            
            console.log('Centralized filter system initialized');
        });
    </script>
</body>
</html>