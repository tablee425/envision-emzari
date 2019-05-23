<!-- #NAVIGATION -->
<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS/SASS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as is -->

            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <!-- <img src="img/avatars/sunny.png" alt="me" class="online" /> -->
                <span>
                    @if(Auth::check()) {{ Auth::user()->activeCompany()->name }} @else Nobody @endif
                </span>
                <i class="fa fa-angle-down"></i>
            </a>

        </span>
    </div>
    <!-- end user info -->

    <!-- NAVIGATION : This navigation is also responsive

    To make this navigation dynamic please make sure to link the node
    (the reference to the nav > ul) after page load. Or the navigation
    will not initialize.
    -->
    <nav>
        <!--
        NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->

        <ul>
            <li class="open"><!-- class="active" -->
                <a href="#" title="Areas"><i class="fa fa-lg fa-fw fa-globe"></i> <span class="menu-item-parent">Areas</span></a>
                <ul style="display: block;">
                    <li>
                        <a href="/areas" title="All Areas"><span class="menu-item-parent">All Areas</span></a>
                    </li>
                    @foreach($areas as $key => $area)
                    <li>
                        <a href="#" title="{{ $area->name }}"><i class="fa fa-database"></i> {{ $area->name }}</a>
                        <ul>
                            <li><a href="{{ action('FieldController@index') }}?area_id={{ $area->id }}">All fields</a></li>
                            @foreach($area->fields as $field)
                            <li>
                                <a href="/locations?field_id={{ $field->id }}" title="{{ $field->name }}"><span class="menu-item-parent">{{ $field->name }}</span></a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <a href="{{ action('PigRunController@index') }}"><i class="fa fa-lg fa-fw fa-plus-square"></i> <span class="menu-item-parent">Enter Pig Runs</span></a>
            </li>
            <li>
                <a href="{{ action('ImportController@getIndex') }}"><i class="fa fa-lg fa-fw fa-upload"></i> <span class="menu-item-parent">Excel Imports</span></a>
            </li>
            <li>
                <a href="{{ action('ReportController@getIndex') }}"><i class="fa fa-lg fa-fw fa-bar-chart"></i> <span class="menu-item-parent">Generate Reports</span></a>
            </li>
            <li>
                <a href="{{ action('ExcelReportController@getIndex') }}"><i class="fa fa-lg fa-fw fa-file-excel-o"></i> <span class="menu-item-parent">Generate Excel Sheets</span></a>
            </li>
            <li>
                <a href="{{ action('DeliveryTicketController@getIndex') }}"><i class="fa fa-lg fa-fw fa-ticket"></i> <span class="menu-item-parent">Delivery Tickets</span></a>
            </li>
            @if(Auth::user()->companies->count() > 1)
            <li>
                <a href="{{ action('AccountController@getSettings') }}"><i class="fa fa-lg fa-fw fa-building"></i> <span class="menu-item-parent">Active Company</span></a>
            </li>
            @endif
            @if(Auth::user()->isAdmin())
            <li>
                <a href="{{ action('AccountController@getIndex') }}"><i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">Client Accounts</span></a>
            </li>
            <li>
            <a href="{{ action('CompanyController@getIndex') }}"><i class="fa fa-lg fa-fw fa-tags"></i> <span class="menu-item-parent">Company Accounts</span></a>
            </li>
            @endif
        </ul>
    </nav>

    <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

</aside>
<!-- END NAVIGATION -->