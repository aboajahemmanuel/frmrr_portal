<style>
  .autocomplete-box {
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    display: none;
    position: absolute;
    background: white;
    z-index: 1000;
    width: 100%;
}

.autocomplete-item {
    padding: 8px;
    cursor: pointer;
}

.autocomplete-item:hover {
    background: #f0f0f0;
}

</style>


<script> 
  const entities = @json($entities->map(fn($e) => ['id' => $e->id, 'name' => $e->name]));

const entityInput = document.getElementById("entityInput");
const entityHidden = document.getElementById("entityHidden");
const entityBox = document.getElementById("entitySuggestionBox");

entityInput.addEventListener("input", function () {
    const value = this.value.toLowerCase();

    if (value.length < 2) {
        entityBox.style.display = "none";
        return;
    }

    const results = entities.filter(e => e.name.toLowerCase().includes(value));

    let html = "";
    results.forEach(e => {
        html += `<div class="autocomplete-item" data-id="${e.id}" data-name="${e.name}">
                    ${e.name}
                 </div>`;
    });

    entityBox.innerHTML = html;
    entityBox.style.display = results.length ? "block" : "none";
});

// choose a suggestion
entityBox.addEventListener("click", function (e) {
    if (e.target.classList.contains("autocomplete-item")) {
        const name = e.target.getAttribute("data-name");
        const id = e.target.getAttribute("data-id");

        entityInput.value = name;
        entityHidden.value = id;

        entityBox.style.display = "none";
    }
});
</script>



<div class="glass-container">

    @if (Auth::check())
        @if ($isSubscribed || Auth::user()->usertype == 'internal')
            <div class="tab-content">
                <div class="">

                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#home-1" data-toggle="tab">Basic Search</a>
                        </li>
                        <li><a href="#profile-1" data-toggle="tab">Advanced Search</a>
                        </li>

                    </ul>

                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="home-1" role="tabpanel">
                            <nav aria-label="Page navigation example">
                                <form id="searchForm" method="GET" action="{{ route('searchPost') }}">
    <div class="search-filters" style="padding-right: 0 !important">
        <br>
        <div class="sf-title">Select category</div>
        <div class="spc-btw">
            <div class="cb-gap">
                @foreach ($categories as $category)
                    <div class="catgory">
                        <input type="checkbox" name="category_id[]"
                            id="category_{{ $category->id }}"
                            value="{{ $category->id }}" />
                        <label for="category_{{ $category->id }}" style="margin-bottom: 0px;">
                            {{ $category->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="search-input">
            <div class="w-85">
                <div class="si-title">Search for <span class="starrr" style="color: red;">*</span></div>
                <input class="si-input-box" type="text" name="Key_Words"
                    placeholder="Enter words" required />
            </div>
            <div class="w-50" style="display: none">
                <div class="si-title">Search In</div>
                <select class="si-input-box-s" style="margin-top: 4px;" name="searchBy">
                    <option value="title">Title</option>
                    <option value="tags">All Content Keywords</option>
                </select>
            </div>
        </div>

        <input name="Form" value="Basic" hidden>

        <div class="btn-flex">
            <div class="gradient-buttons">
                <button type="button" onclick="clearForm()">
                    <div class="gradient-button-content-white">
                        <div>Clear Fields</div>
                        <img src="{{ asset('public/users/assets/Close.svg') }}" alt="Clear Fields" />
                    </div>
                </button>
            </div>
            <div class="gradient-buttons">
                <button type="submit">
                    <div class="gradient-button-content">
                        <div>Search</div>
                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Search" />
                    </div>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    function clearForm() {
        const form = document.getElementById('searchForm');

        // Clear all text inputs
        form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

        // Uncheck all checkboxes
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);

        // Reset all selects
        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Optional: reset hidden input if needed
        // form.querySelectorAll('input[type="hidden"]').forEach(hidden => hidden.value = '');
    }
</script>

                            </nav>
                        </div>
                        <div class="tab-pane" id="profile-1" role="tabpanel">
                            <div class="search-filters">
                                <br>
                                 <form id="searchFormADFD" method="GET" action="{{ route('searchPostAdvance') }}">
                                    <div class="sf-title">Select one or more options</div>
                                    <!-- Add custom CSS for autocomplete -->
                                    <style>
                                        .si-input-box-s {
                                            width: calc(100% - 30px);
                                            padding: 8px 12px;
                                            border: 1px solid #e5e9f2;
                                            border-radius: 4px;
                                            font-size: 14px;
                                            height: 38px;
                                            box-sizing: border-box;
                                            float: left;
                                        }
                                        
                                        .autocomplete-wrapper {
                                            position: relative;
                                            width: 100%;
                                        }
                                        
                                        .dropdown-btn {
                                            width: 30px;
                                            height: 38px;
                                            border: 1px solid #e5e9f2;
                                            border-left: none;
                                            border-radius: 0 4px 4px 0;
                                            background: #f8f9fa;
                                            cursor: pointer;
                                            float: right;
                                            box-sizing: border-box;
                                        }
                                        
                                        .dropdown-btn:hover {
                                            background: #e9ecef;
                                        }
                                        
                                        .autocomplete-box {
                                            position: absolute;
                                            background: white;
                                            border: 1px solid #e5e9f2;
                                            border-radius: 4px;
                                            max-height: 200px;
                                            overflow-y: auto;
                                            z-index: 1000;
                                            width: 100%;
                                            display: none;
                                            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                                            top: 38px;
                                            left: 0;
                                        }
                                        
                                        .autocomplete-item {
                                            padding: 10px 12px;
                                            cursor: pointer;
                                            border-bottom: 1px solid #f0f0f0;
                                        }
                                        
                                        
                                        .autocomplete-item:hover {
                                            background-color: #f5f6fa;
                                        }
                                        
                                        .autocomplete-item:last-child {
                                            border-bottom: none;
                                        }
                                        
                                        .highlight {
                                            background-color: #6576ff;
                                            color: white;
                                        }
                                        
                                        .clear-btn {
                                            position: absolute;
                                            right: 35px;
                                            top: 50%;
                                            transform: translateY(-50%);
                                            background: none;
                                            border: none;
                                            cursor: pointer;
                                            font-size: 16px;
                                            color: #999;
                                            display: none;
                                        }
                                    </style>
                                    <div class="search-input">
                                        <div class="w-33">
                                            <div class="si-title">Search for <span class="starrr" style="color: red;">*</span></div>
                                            <input required class="si-input-box" type="text" name="search_Words"
                                                id="" placeholder="Enter words" />

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Search in</div>
                                            <select class="si-input-box-s" name="searchBy">
                                                <option></option>
                                                <option value="title">Title</option>
                                                <option value="tags">All Content Keywords</option>
                                            </select>

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Search Using</div>
                                            <select class="si-input-box-s" name="searchusing" id="">
                                                <option></option>
                                                <option value="allwords">Exact Phrase</option>
                                                <option value="anywords">Any of the Words</option>
                                            </select>






                                        </div>
                                    </div>


                                    <div class="search-input">
                                        <div class="w-33">
                                            <div class="si-title">Issue Date</div>
                                            <input class="si-input-box" type="date" name="issue_date" />

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 0px;"> Effective Date</div>
                                            <input class="si-input-box" type="date" name="effective_date" />


                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 0px;">Version Number</div>
                                            <input class="si-input-box" style="margin-top: 3px;" type="number"
                                                name="document_version" />



                                        </div>


                                    </div>





                                    <div class="search-input">
                                       
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Entity</div>
                                            <div class="autocomplete-wrapper">
                                                <input type="text" id="entityInput" class="si-input-box-s" placeholder="Type to search entities..." autocomplete="off">
                                                <input type="hidden" name="entity_id" id="entityHidden">
                                                <div id="entitySuggestionBox" class="autocomplete-box"></div>
                                                <button type="button" class="dropdown-btn" id="entityDropdownBtn">▼</button>
                                            </div>
                                        </div>

                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">{{{$formattedStatuses}}}</div>
                                            <div class="autocomplete-wrapper">
                                                <input type="text" id="statusInput" class="si-input-box-s" placeholder="Type to search statuses..." autocomplete="off">
                                                <input type="hidden" name="ceasedRepealed" id="statusHidden">
                                                <div id="statusSuggestionBox" class="autocomplete-box"></div>
                                                <button type="button" class="dropdown-btn" id="statusDropdownBtn">▼</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input name="Form" value="Advanced" hidden>
                                    <div class="btn-flex">
                                        <div class="gradient-buttons">
                                            <button type="button" onclick="clearForm()">
                                                <div class="gradient-button-content-white">
                                                    <div>Clear Fields</div>
                                                    <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                        alt="Right Arrow" />
                                                </div>
                                            </button>
                                        </div>
                                        <div class="gradient-buttons">
                                            <button type="submit">
                                                <div class="gradient-button-content">
                                                    <div>Search</div>
                                                    <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                        alt="Search" />
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                            </div>

                            </form>

                            <script>
    // Namespace our functions to avoid conflicts
    window.SearchTable = window.SearchTable || {};
    
    SearchTable.clearForm = function() {
        const form = document.getElementById('searchFormADFD');

        // Clear all text inputs
        form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

        // Uncheck all checkboxes
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);

        // Reset all selects
        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        
        // Clear hidden inputs and hide suggestion boxes
        document.getElementById('entityHidden').value = '';
        document.getElementById('statusHidden').value = '';
        document.getElementById('entitySuggestionBox').style.display = 'none';
        document.getElementById('statusSuggestionBox').style.display = 'none';
    }

    // Autocomplete functionality
    SearchTable.initAutocomplete = function() {
        // Entity autocomplete
        const entityInput = document.getElementById('entityInput');
        const entityHidden = document.getElementById('entityHidden');
        const entitySuggestionBox = document.getElementById('entitySuggestionBox');
        const entityDropdownBtn = document.getElementById('entityDropdownBtn');
        
        // Status autocomplete
        const statusInput = document.getElementById('statusInput');
        const statusHidden = document.getElementById('statusHidden');
        const statusSuggestionBox = document.getElementById('statusSuggestionBox');
        const statusDropdownBtn = document.getElementById('statusDropdownBtn');
        
        // Create entity data array from Blade template
        const entities = [
            @foreach ($entities as $entity)
                {id: "{{ $entity->id }}", name: "{{ $entity->name }}"},
            @endforeach
        ];
        
        // Create status data array
        const statuses = [
            {name: "Active"},
            @foreach ($statuses as $status)
                {name: "{{ $status->name }}"},
            @endforeach
        ];
        
        // Variables to handle blur properly
        let entityIgnoreBlur = false;
        let statusIgnoreBlur = false;
        
        // Entity autocomplete functions
        entityInput.addEventListener('input', function() {
            const inputValue = this.value.toLowerCase();
            if (inputValue.length >= 2) {
                const filteredEntities = entities.filter(entity => 
                    entity.name.toLowerCase().includes(inputValue)
                );
                SearchTable.showSuggestions(filteredEntities, entitySuggestionBox, entityInput, entityHidden, 'entity', () => {
                    entityIgnoreBlur = false;
                });
            } else {
                entitySuggestionBox.style.display = 'none';
            }
        });
        
        entityInput.addEventListener('blur', function() {
            // Only hide if not clicking on a suggestion
            if (!entityIgnoreBlur) {
                setTimeout(() => {
                    entitySuggestionBox.style.display = 'none';
                }, 150);
            }
        });
        
        // Entity dropdown button
        entityDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (entitySuggestionBox.style.display === 'block') {
                entitySuggestionBox.style.display = 'none';
            } else {
                SearchTable.showSuggestions(entities, entitySuggestionBox, entityInput, entityHidden, 'entity', () => {
                    entityIgnoreBlur = false;
                });
            }
        });
        
        // Status autocomplete functions
        statusInput.addEventListener('input', function() {
            const inputValue = this.value.toLowerCase();
            if (inputValue.length >= 2) {
                const filteredStatuses = statuses.filter(status => 
                    status.name.toLowerCase().includes(inputValue)
                );
                SearchTable.showSuggestions(filteredStatuses, statusSuggestionBox, statusInput, statusHidden, 'status', () => {
                    statusIgnoreBlur = false;
                });
            } else {
                statusSuggestionBox.style.display = 'none';
            }
        });
        
        statusInput.addEventListener('blur', function() {
            // Only hide if not clicking on a suggestion
            if (!statusIgnoreBlur) {
                setTimeout(() => {
                    statusSuggestionBox.style.display = 'none';
                }, 150);
            }
        });
        
        // Status dropdown button
        statusDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (statusSuggestionBox.style.display === 'block') {
                statusSuggestionBox.style.display = 'none';
            } else {
                SearchTable.showSuggestions(statuses, statusSuggestionBox, statusInput, statusHidden, 'status', () => {
                    statusIgnoreBlur = false;
                });
            }
        });
    };
    
    SearchTable.showSuggestions = function(suggestions, suggestionBox, inputElement, hiddenElement, type, onSelectCallback) {
        if (suggestions.length === 0) {
            suggestionBox.style.display = 'none';
            return;
        }
        
        suggestionBox.innerHTML = '';
        const inputValue = inputElement.value.toLowerCase();
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            const name = type === 'entity' ? suggestion.name : suggestion.name;
            const id = type === 'entity' ? suggestion.id : suggestion.name;
            
            // Highlight matching text only when filtering
            if (inputValue.length >= 2) {
                const regex = new RegExp(`(${inputValue})`, 'gi');
                const highlightedName = name.replace(regex, '<strong>$1</strong>');
                item.innerHTML = highlightedName;
            } else {
                item.textContent = name;
            }
            
            // Add click event with proper closure
            item.addEventListener('mousedown', (function(name, id) {
                return function(e) {
                    // Prevent blur event from firing before click
                    e.preventDefault();
                    inputElement.value = name;
                    hiddenElement.value = id;
                    suggestionBox.style.display = 'none';
                    if (onSelectCallback) onSelectCallback();
                };
            })(name, id));
            
            suggestionBox.appendChild(item);
        });
        
        suggestionBox.style.display = 'block';
    };

    // Initialize when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        SearchTable.initAutocomplete();
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        const entitySuggestionBox = document.getElementById('entitySuggestionBox');
        const statusSuggestionBox = document.getElementById('statusSuggestionBox');
        
        if (entitySuggestionBox && !e.target.closest('#entityInput, #entitySuggestionBox, #entityDropdownBtn')) {
            entitySuggestionBox.style.display = 'none';
        }
        
        if (statusSuggestionBox && !e.target.closest('#statusInput, #statusSuggestionBox, #statusDropdownBtn')) {
            statusSuggestionBox.style.display = 'none';
        }
    });
</script>

                        </div>


                    </div>
                </div>
            </div>
        @endif
    @endif

    @if (Auth::check())
        @if (!$isSubscribed && Auth::user()->usertype != 'internal')
            <div class="tab-content">
                <div class="">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#home-1" data-toggle="tab">Basic Search</a>
                        </li>
                        <li><a href="{{ route('subscribe') }}">Advanced Search</a>
                        </li>

                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="home-1" role="tabpanel">
                            <nav aria-label="Page navigation example">
                                 <form id="searchFormUser" method="GET" action="{{ route('searchPost') }}">
                                    <div class="search-filters" style="padding-right: 0 !important">
                                        <br>
                                        <div class="sf-title">Select category</div>
                                        <div class="spc-btw">

                                            <div class="cb-gap">
                                                @foreach ($categories as $category)
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" style="font-size:5px"
                                                            id="category_{{ $category->id }}" name="category_id"
                                                            value="{{ $category->id }}" class="custom-control-input">
                                                        <label class="custom-control-label"
                                                            for="category_{{ $category->id }}"
                                                            style="margin-bottom: 0px; color: #1d326d !important; font-size:12px">{{ $category->name }}</label>
                                                    </div>
                                                @endforeach



                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div class="w-85">
                                                <div class="si-title">Search for <span class="starrr" style="color: red;">*</span>
                                                </div>
                                                <input class="si-input-box" type="text" name="Key_Words"
                                                    placeholder="Enter words" required />
                                            </div>
                                            <div class="w-50" style="display: none">
                                                <div class="si-title">Search In</div>
                                                <select class="si-input-box-s" style="margin-top: 4px;"
                                                    name="searchBy">

                                                    <option value="title">Title</option>
                                                    <option value="tags">All Content Keywords</option>
                                                </select>
                                            </div>
                                        </div>

                                        <input name="Form" hidden value="Basic">
                                        <div class="btn-flex">
                                            <div class="gradient-buttons">
                                                <button type="button" onclick="clearFormUser()">
                                                    <div class="gradient-button-content-white">
                                                        <div>Clear Fields</div>
                                                        <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                            alt="Right Arrow" />
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="gradient-buttons">
                                                <button type="submit">
                                                    <div class="gradient-button-content">
                                                        <div>Search</div>
                                                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                            alt="Search" />
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                 <script>
    function clearFormUser() {
        const form = document.getElementById('searchFormUser');

        // Clear text inputs
        form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

        // Uncheck all radio buttons
        form.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);

        // Uncheck all checkbox 
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => radio.checked = false);

        // Reset select dropdowns
        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    }
</script>
                            </nav>
                        </div>


                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="tab-content">
            <div class="">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#home-1" data-toggle="tab">Basic Search</a>
                    </li>
                    <li><a href="{{ route('subscribe') }}">Advanced Search</a>
                    </li>

                </ul>
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="home-1" role="tabpanel">
                        <nav aria-label="Page navigation example">
                             <form id="searchFormg1" method="GET" action="{{ route('searchPost') }}">
                                <div class="search-filters" style="padding-right: 0 !important">
                                    <br>
                                    <div class="sf-title">Select category</div>
                                    <div class="spc-btw">

                                        <div class="cb-gap">
                                            @foreach ($categories as $category)
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" style="font-size:5px"
                                                        id="category_{{ $category->id }}" name="category_id"
                                                        value="{{ $category->id }}" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="category_{{ $category->id }}"
                                                        style="margin-bottom: 0px; color: #1d326d !important; font-size:12px">{{ $category->name }}</label>
                                                </div>
                                            @endforeach



                                        </div>
                                    </div>
                                    <div class="search-input">
                                        <div class="w-85">
                                            <div class="si-title">Search for <span class="starrr" style="color: red;">*</span>
                                            </div>
                                            <input class="si-input-box" type="text" name="Key_Words"
                                                placeholder="Enter words" required />
                                        </div>
                                        <div class="w-50" style="display: none">
                                            <div class="si-title">Search In</div>
                                            <select class="si-input-box-s" style="margin-top: 4px;" name="searchBy">

                                                <option value="title">Title</option>
                                                <option value="tags">All Content Keywords</option>
                                            </select>
                                        </div>
                                    </div>
                                  
                                    <input name="Form" value="Basic" hidden>
                                    <div class="btn-flex">
                                        <div class="gradient-buttons">
                                            <button type="button" onclick="clearFormG1()">
                                                <div class="gradient-button-content-white">
                                                    <div>Clear Fields</div>
                                                    <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                        alt="Right Arrow" />
                                                </div>
                                            </button>


                                        </div>
                                        <div class="gradient-buttons">
                                            <button type="submit">
                                                <div class="gradient-button-content">
                                                    <div>Search</div>
                                                    <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                        alt="Search" />
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </nav>
                    </div>


                </div>
            </div>
        </div>
       

 <script>


function clearFormG1() {
        const form = document.getElementById('searchFormg1');

        // Clear text inputs
        form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

        // Uncheck all radio buttons
        form.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);

        // Uncheck all checkbox 
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => radio.checked = false);

        // Reset select dropdowns
        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    }
</script>


 <script>
    function clearForm() {
        const form = document.getElementById('searchFormad');

        // Clear text inputs
        form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

        // Uncheck all radio buttons
        form.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);

        // Uncheck all checkbox 
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => radio.checked = false);

        // Reset select dropdowns
        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    }



  

</script>
    @endif


</div>
