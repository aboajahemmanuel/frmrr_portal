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
                                                <option value="allwords">All of the Words</option>
                                                <option value="anywords">Any of the Words</option>
                                                {{-- <option value="exactwords">The Exact Phrase</option> --}}
                                                <option value="woutwords">Without the Words</option>
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
                                            <div class="si-title" style="margin-top: 4px;">{{{$formattedStatuses}}}
                                            </div>
                                            <select class="si-input-box-s" style="margin-top: 3.5px"
                                                name="ceasedRepealed" id="">
                                                <option></option>
                                               @foreach ($statuses as $status)
                                                                            <option value="{{ $status->name }}">
                                                                                {{ $status->name }}</option>

                                                                            
                                                                        @endforeach




                                            </select>
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
    function clearForm() {
        const form = document.getElementById('searchFormADFD');

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
