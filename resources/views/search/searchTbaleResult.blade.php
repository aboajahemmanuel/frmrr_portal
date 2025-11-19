<div class="glass-container">

    @if (Auth::check())
        @if ($isSubscribed || Auth::user()->usertype == 'internal')
            <div class="tab-content">
                <div class="">

                    @if ($Form == 'Basic')
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#home-1" data-toggle="tab">Basic Search</a>
                            </li>
                            <li><a href="#profile-1" data-toggle="tab">Advanced Search</a>
                            </li>

                        </ul>
                    @endif


                    @if ($Form == 'Advanced')
                        <ul class="nav nav-tabs">
                            <li class="">
                                <a href="#home-1" data-toggle="tab">Basic Search</a>
                            </li>
                            <li class="active"><a href="#profile-1" data-toggle="tab">Advanced Search</a>
                            </li>

                        </ul>
                    @endif

                    <div class="tab-content text-muted">
                        @if ($Form == 'Basic')
                            <div class="tab-pane active" id="home-1" role="tabpanel">
                                <nav aria-label="Page navigation example">
                                    <form id="searchFormB1" method="GET" action="{{ route('searchPost') }}">
                                        <div class="search-filters" style="padding-right: 0 !important">
                                            <br>
                                            <div class="sf-title">Select category</div>
                                            <div class="spc-btw">
                                                <div class="cb-gap">
                                                    @foreach ($categories as $category)
                                                        <div class="catgory">
                                                            <input type="checkbox" name="category_id[]"
                                                                id="category_{{ $category->id }}"
                                                                value="{{ $category->id }}"
                                                                @if (in_array($category->id, $selectedCategories ?? [])) checked @endif />
                                                            <label style="margin-bottom: 0px;"
                                                                for="category_{{ $category->id }}">{{ $category->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="search-input">
                                                <div class="w-85">
                                                    <div class="si-title">Search for <span class="starrr" style="color: red;">*</span>
                                                    </div>
                                                    <input class="si-input-box" type="text"
                                                        value="{{ $title }}" name="Key_Words"
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

                                            <input name="Form" value="Basic" hidden>
                                            <div class="btn-flex">
                                                <div class="gradient-buttons">
                                                     <button type="button" onclick="clearFormB1()">
                                                        <div class="gradient-button-content-white">
                                                            <div>Clear Fields</div>
                                                            <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                                alt="Clear Fields" />
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
                    function clearFormB1() {
                        // Get the form element
                        const form = document.getElementById('searchFormB1');

                        if (form) {
                            // Clear all text inputs
                            form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                            
                            // Clear all selects
                            form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                            
                            // Uncheck all checkboxes
                            form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
                            
                            // Reset hidden inputs except the Form value
                        
                        }
                    }
                    </script>
                                </nav>
                            </div>

                        @endif

                        <div class="tab-pane " id="home-1" role="tabpanel">
                            <nav aria-label="Page navigation example">
                                <form id="searchFormB2" method="GET" action="{{ route('searchPost') }}">
                                    <div class="search-filters" style="padding-right: 0 !important">
                                        <br>
                                        <div class="sf-title">Select category</div>
                                        <div class="spc-btw">

                                            <div class="cb-gap">
                                                @foreach ($categories as $category)
                                                    <div class="catgory">
                                                        <input type="checkbox" name="category_id[]"
                                                            id="category_{{ $category->id }}"
                                                            value="{{ $category->id }}"
                                                            @if (in_array($category->id, $selectedCategories ?? [])) checked @endif />
                                                        <label
                                                            style="margin-bottom: 0px;
                                                        for="category_{{ $category->id }}">{{ $category->name }}</label>
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
                                                 <button type="button" onclick="clearFormB2()">
                                                    <div class="gradient-button-content-white">
                                                        <div>Clear Fields</div>
                                                        <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                            alt="Clear Fields" />
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
                    function clearFormB2() {
                        // Get the form element
                        const form = document.getElementById('searchFormB2');

                        if (form) {
                            // Clear all text inputs
                            form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
                            
                            // Clear all selects
                            form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
                            
                            // Uncheck all checkboxes
                            form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
                            
                            // Reset hidden inputs except the Form value
                        
                        }
                    }
                    </script>
                            </nav>
                        </div>




                        @if ($Form == 'Advanced')
                            <div class="tab-pane active" id="profile-1" role="tabpanel">
                                <div class="search-filters">
                                    <br>
                                    <form id="searchForm1" method="GET" action="{{ route('searchPostAdvance') }}">
                                        <div class="sf-title">Select one or more options</div>
                                        <div class="spc-btw">
                                            <div>
                                                <div class="cb-gap">


                                                    @foreach ($categories as $category)
                                                        <div class="catgory">
                                                            <input type="checkbox" name="categories[]"
                                                                id="category_{{ $category->id }}"
                                                                value="{{ $category->id }}"
                                                                @if (in_array($category->id, $selectedCategories ?? [])) checked @endif />
                                                            <label
                                                                style="margin-bottom: 0px;
                                        for="category_{{ $category->id }}">{{ $category->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>


                                            </div>

                                        </div>
                                        <div class="search-input">
                                            <div class="w-33">
                                                <div class="si-title">Search for <span class="starrr" style="color: red;">*</span>
                                                </div>
                                                <input required class="si-input-box" type="text"
                                                    value="{{ $title }}" name="search_Words" id=""
                                                    placeholder="Enter words" />

                                            </div>
                                           
                                            <div class="w-33">
                                                <div class="si-title" style="margin-top: 4px;">Search in</div>
                                                <select class="si-input-box-s" name="searchBy">
                                                    <option></option>

                                                  

                                                        <option value="title"
                                                        @if ($searchBy == 'title') selected @endif>
                                                         Title</option>
                                                    <option value="tags"
                                                        @if ($searchBy == 'tags') selected @endif>
                                                         All Content Keywords</option>

                                                    


                                                </select>

                                            </div>
                                            <div class="w-33">
                                                <div class="si-title" style="margin-top: 4px;">Search Using</div>
                                                <select class="si-input-box-s" name="searchusing">
                                                    <option></option>
                                                    <option value="allwords"
                                                        @if ($searchMethod == 'allwords') selected @endif>
                                                        Exact Phrase</option>
                                                    <option value="anywords"
                                                        @if ($searchMethod == 'anywords') selected @endif>
                                                        Any of the Words</option>
                                                </select>



                                            </div>
                                        </div>


                                        <div class="search-input">
                                            <div class="w-33">
                                                <div class="si-title">Issue Date</div>
                                                <input class="si-input-box" type="date"
                                                    value="{{ $issueDate }}" name="issue_date" />

                                            </div>
                                            <div class="w-33">
                                                <div class="si-title" style="margin-top: 0px;"> Effective Date</div>
                                                <input class="si-input-box" type="date"
                                                    value="{{ $effectiveDate }}" name="effective_date" />


                                            </div>
                                            <div class="w-33">
                                                <div class="si-title" style="margin-top: 0px;">Version Number</div>
                                                <input class="si-input-box" style="margin-top: 3px;"
                                                    value="{{ $versionNumber }}" type="number"
                                                    name="document_version" />



                                            </div>


                                        </div>





                                        <div class="search-input">
                                            {{-- <div class="w-33">
                                                <div class="si-title" style="margin-top: 4px;">Limit Search to</div>
                                                <select class="si-input-box-s" style="margin-top: 3.5px"
                                                    name="year">
                                                    <option></option>
                                                    @foreach ($years as $yearOption)
                                                        <option value="{{ $yearOption->id }}"
                                                            @if (($year_id ?? '') == $yearOption->id) selected @endif>
                                                            {{ $yearOption->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div> --}}
                                            {{-- <div class="w-33">
                                                <div class="si-title">Document Limit</div>
                                                <input class="si-input-box" type="number"
                                                    value="{{ $number }}" name="number" />

                                            </div> --}}
                                            <div class="w-33">
                                                <div class="si-title" style="margin-top: 4px;">Entity</div>
                                                <select class="si-input-box-s" style="margin-top: 3.5px"
                                                    name="entity_id" id="">\
                                                    <option></option>

                                                    @foreach ($entities as $entity)
                                                        <option value="{{ $entity->id }}"
                                                            @if (($entity_id ?? '') == $entity->id) selected @endif>
                                                            {{ $entity->name }}</option>
                                                    @endforeach



                                                    {{-- @foreach ($entities as $entity)
                                                    <option value="{{ $entity->id }}">{{ $entity->name }}
                                                    </option>
                                                @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="w-33">
                                                <div class="si-title" style="margin-top: 4px;">{{$formattedStatuses}}
                                                </div>
                                                <select class="si-input-box-s" style="margin-top: 3.5px"
                                                    name="ceasedRepealed" id="">
                                                    <option></option>
                                                    <option value="Active" {{ trim($ceasedRepealed ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
                                                     @foreach ($statuses as $status)
                                                                <option value="{{ trim($status->name) }}" 
                                                                    {{ trim($status->name) === trim($ceasedRepealed) ? 'selected' : '' }}>
                                                                    {{ trim($status->name) }}
                                                                </option>
                                                            @endforeach


                                                    {{-- <option value="Ceased"
                                                        @if ($ceasedRepealed == 'Ceased') selected @endif>
                                                        Ceased</option>
                                                    <option value="Repealed"
                                                        @if ($ceasedRepealed == 'Repealed') selected @endif>
                                                        Repealed</option>

                                                    <option value="Amended"
                                                        @if ($ceasedRepealed == 'Amended') selected @endif>
                                                        Amended</option>

                                                          <option value="Superseded"
                                                        @if ($ceasedRepealed == 'Superseded') selected @endif>
                                                        Superseded</option> --}}

                                                        



                                                </select>
                                            </div>
                                        </div>
                                        <input name="Form" value="Advanced" hidden>
                                        <div class="btn-flex">
                                            <div class="gradient-buttons">
                                                 <button type="button" onclick="resetSearchForm()">
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
    function resetSearchForm() {
        // Get the form element
        const form = document.getElementById('searchForm1');

        if (form) {
            // Clear all text inputs
            form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

            // Clear all date inputs
            form.querySelectorAll('input[type="date"]').forEach(input => input.value = '');

            // Clear all number inputs
            form.querySelectorAll('input[type="number"]').forEach(input => input.value = '');

            // Uncheck all checkboxes
            form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);

            // Uncheck all radio buttons
            form.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);

            // Reset all select dropdowns
            form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

         
        }
    }
</script>

                            </div>
                        @endif

                        <div class="tab-pane" id="profile-1" role="tabpanel">
                            <div class="search-filters">
                                <br>
                                <form id="searchFormAD1" method="GET" action="{{ route('searchPostAdvance') }}">
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
                                    </style>
                                    <div class="spc-btw">
                                        <div>
                                            <div class="cb-gap">
                                                @foreach ($categories as $category)
                                                    <div class="catgory">
                                                        <input type="checkbox" name="categories[]"
                                                            id="category_{{ $category->id }}"
                                                            value="{{ $category->id }}" />
                                                        <label
                                                            style="margin-bottom: 0px;
                                        for="category_{{ $category->id }}">{{ $category->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>


                                        </div>

                                    </div>
                                    <div class="search-input">
                                        <div class="w-33">
                                            <div class="si-title">Search for <span class="starrr" style="color: red;">*</span></div>
                                            <input class="si-input-box" type="text" name="search_Words"
                                                id="" placeholder="Enter words" />

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Search In</div>
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
                                                <option value="anywords">Any of The Words</option>
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
                                            <div class="si-title" style="margin-top: 0px;">Version number</div>
                                            <input class="si-input-box" style="margin-top: 3px;" type="number"
                                                name="document_version" />



                                        </div>


                                    </div>





                                    <div class="search-input">
                                        {{-- <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Limit Search to</div>
                                            <select class="si-input-box-s" style="margin-top: 3.5px" name="year">
                                                <option></option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->id }}">{{ $year->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div> --}}
                                        {{-- <div class="w-33">
                                            <div class="si-title">Document Limit</div>
                                            <input class="si-input-box" type="text" name="Key_Words"
                                                id="" placeholder="Number" />

                                        </div> --}}
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
                                            <div class="si-title" style="margin-top: 4px;">Ceased/Repealed </div>
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
                                             <button type="button" onclick="clearFormAD1()">
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
    window.SearchTableResult = window.SearchTableResult || {};
    
    SearchTableResult.clearFormAD1 = function() {
        // Get the form element
        const form = document.getElementById('searchFormAD1');

        if (form) {
            // Clear all text inputs
            form.querySelectorAll('input[type="text"]').forEach(input => input.value = '');

            // Clear all date inputs
            form.querySelectorAll('input[type="date"]').forEach(input => input.value = '');

            // Clear all number inputs
            form.querySelectorAll('input[type="number"]').forEach(input => input.value = '');

            // Uncheck all checkboxes
            form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);

            // Uncheck all radio buttons
            form.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);

            // Reset all select dropdowns
            form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            
            // Clear hidden inputs and hide suggestion boxes
            if (document.getElementById('entityHidden')) {
                document.getElementById('entityHidden').value = '';
            }
            if (document.getElementById('statusHidden')) {
                document.getElementById('statusHidden').value = '';
            }
            if (document.getElementById('entitySuggestionBox')) {
                document.getElementById('entitySuggestionBox').style.display = 'none';
            }
            if (document.getElementById('statusSuggestionBox')) {
                document.getElementById('statusSuggestionBox').style.display = 'none';
            }
        }
    }

    // Autocomplete functionality
    SearchTableResult.initAutocomplete = function() {
        // Check if elements exist (only initialize if on the right page)
        if (!document.getElementById('entityInput') || !document.getElementById('statusInput')) {
            return;
        }
        
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
            {name: "Ceased"},
            {name: "Repealed"},
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
                SearchTableResult.showSuggestions(filteredEntities, entitySuggestionBox, entityInput, entityHidden, 'entity', () => {
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
                SearchTableResult.showSuggestions(entities, entitySuggestionBox, entityInput, entityHidden, 'entity', () => {
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
                SearchTableResult.showSuggestions(filteredStatuses, statusSuggestionBox, statusInput, statusHidden, 'status', () => {
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
                SearchTableResult.showSuggestions(statuses, statusSuggestionBox, statusInput, statusHidden, 'status', () => {
                    statusIgnoreBlur = false;
                });
            }
        });
    };
    
    SearchTableResult.showSuggestions = function(suggestions, suggestionBox, inputElement, hiddenElement, type, onSelectCallback) {
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
        SearchTableResult.initAutocomplete();
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
                        <li><a href="{{ route('login') }}">Advanced Search</a>
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
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="category_{{ $category->id }}"
                                                            name="category_id" value="{{ $category->id }}"
                                                            class="custom-control-input" style="font-size: 5px;"
                                                            {{ isset($selectedCategories) && $selectedCategories == $category->id ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="category_{{ $category->id }}"
                                                            style="margin-bottom: 0; color: #1d326d !important; font-size: 12px;">
                                                            {{ $category->name }}
                                                        </label>
                                                    </div>
                                                @endforeach




                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div class="w-85">
                                                <div class="si-title">Search for <span class="starrr" style="color: red;">*</span>
                                                </div>
                                                <input value="{{ $title }}" class="si-input-box"
                                                    type="text" name="Key_Words" placeholder="Enter words"
                                                    required />
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
                    <li><a href="{{ route('login') }}">Advanced Search</a>
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
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="category_{{ $category->id }}"
                                                        name="category_id" value="{{ $category->id }}"
                                                        class="custom-control-input" style="font-size: 5px;"
                                                        {{ isset($selectedCategories) && $selectedCategories == $category->id ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="category_{{ $category->id }}"
                                                        style="margin-bottom: 0; color: #1d326d !important; font-size: 12px;">
                                                        {{ $category->name }}
                                                    </label>
                                                </div>
                                            @endforeach




                                        </div>
                                    </div>
                                    <div class="search-input">
                                        <div class="w-85">
                                            <div class="si-title">Search for <span class="starrr" style="color: red;">*</span>
                                            </div>
                                            <input value="{{ $title }}" class="si-input-box" type="text"
                                                name="Key_Words" placeholder="Enter words" required />
                                        </div>
                                        <div class="w-50" style="display: none">
                                            <div class="si-title">Search In</div>
                                            <select class="si-input-box-s" style="margin-top: 4px;" name="searchBy">

                                                <option value="title">Title</option>
                                                <option value="tags">All Content Keywords</option>
                                            </select>
                                        </div>
                                    </div>

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
                        </nav>
                    </div>


                </div>
            </div>
        </div>
    @endif


</div>

 <script>
    function clearForm() {
        const form = document.getElementById('searchForm');

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
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        // Custom JavaScript to reset additional fields if needed
        console.log("Form reset triggered.");
    });
</script>
