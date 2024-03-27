<div class="d-flex">
    <button class="toggle-btn" type="button">
        <i class="lni lni-grid-alt"></i>
    </button>
    <div class="sidebar-logo">
        <a href="#">Scheduler</a>
    </div>
</div>
<ul class="sidebar-nav">
    <li class="sidebar-item">
        <a href="{{route('calendar')}}" class="sidebar-link">
            <i class="lni lni-calendar"></i>
            <span>Calendar</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="{{route('rooms')}}" class="sidebar-link">
            <i class="lni lni-apartment"></i>
            <span>Rooms Management</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
           data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
            <i class="lni lni-protection"></i>
            <span>Component Points</span>
        </a>
        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item">
                <a href="{{route('mark-by-module')}}" class="sidebar-link">By module class</a>
            </li>
            <li class="sidebar-item">
                <a href="{{route('mark-by-practice')}}" class="sidebar-link">By practice class</a>
            </li>
        </ul>
    </li>
    <li class="sidebar-item">
        <a href="{{route('modules.index')}}" class="sidebar-link">
            <i class="lni lni-book"></i>
            <span>Modules Management</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
           data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
            <i class="lni lni-layout"></i>
            <span>Multi Level</span>
        </a>
        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                   data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
                    Two Links
                </a>
                <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">Link 1</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">Link 2</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="sidebar-item">
        <a href="#" class="sidebar-link">
            <i class="lni lni-popup"></i>
            <span>Notification</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="#" class="sidebar-link">
            <i class="lni lni-cog"></i>
            <span>Setting</span>
        </a>
    </li>
</ul>
<div class="sidebar-footer">
    <a href="#" class="sidebar-link">
        <i class="lni lni-exit"></i>
        <span>Logout</span>
    </a>
</div>
