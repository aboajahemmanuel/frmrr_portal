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

                                                    {{-- <option value="title">
                                                        Title</option>
                                                    <option value="tags">
                                                        All Content Keywords</option> --}}

                                                        

                                                        <option value="title"
                                                        @if ($searchBy == 'title') selected @endif>
                                                         Title</option>
                                                    <option value="tags"
                                                        @if ($searchBy == 'tags') selected @endif>
                                                         All Content Keywords</option>

                                                    


                                                    {{-- <option value="title">Title</option>
                                                <option value="tags">All Content Keywords</option> --}}
                                                </select>

                                            </div>
                                            <div class="w-33">
                                                <div class="si-title" style="margin-top: 4px;">Search Using</div>
                                                <select class="si-input-box-s" name="searchusing">
                                                    <option></option>
                                                    <option value="allwords"
                                                        @if ($searchMethod == 'allwords') selected @endif>
                                                        All of the Words</option>
                                                    <option value="anywords"
                                                        @if ($searchMethod == 'anywords') selected @endif>
                                                        Any of the Words</option>
                                                    {{-- <option value="exactwords"
                                                        @if ($searchMethod == 'exactwords') selected @endif>The Exact
                                                        Phrase
                                                    </option> --}}
                                                    <option value="woutwords"
                                                        @if ($searchMethod == 'woutwords') selected @endif>Without the
                                                        Words
                                                    </option>
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
                                                <option value="allwords">All of The Words</option>
                                                <option value="anywords">Any of The Words</option>
                                                {{-- <option value="exactwords">The Exact Phrase</option> --}}
                                                <option value="woutwords">Without The Words</option>
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
                                            <select class="si-input-box-s" style="margin-top: 3.5px" name="entity_id"
                                                id="">\
                                                <option></option>
                                                @foreach ($entities as $entity)
                                                    <option value="{{ $entity->id }}">{{ $entity->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Ceased/Repealed </div>
                                            <select class="si-input-box-s" style="margin-top: 3.5px"
                                                name="ceasedRepealed" id="">
                                                <option></option>
                                                {{-- <option value="Ceased" @if (($ceasedRepealed ?? 'Ceased') === 'Ceased') selected @endif>
                                                Ceased</option>
                                            <option value="tags" @if (($ceasedRepealed ?? 'Repealed') === 'Repealed') selected @endif>
                                                Repealed</option> --}}



                                                <option value="Ceased">Ceased</option>
                                                <option value="Repealed">Repealed</option>


                                            </select>
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
    function clearFormAD1() {
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

           
        }
    }
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
