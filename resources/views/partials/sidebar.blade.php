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
        <a href="{{route('teacher.register-classes')}}" class="sidebar-link">
            <i class="lni lni-grid"></i>
            <span>Register Classes</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="{{route('modules.index')}}" class="sidebar-link">
            <i class="lni lni-book"></i>
            <span>Modules</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="{{route('module-classes.index')}}" class="sidebar-link">
            <i class="lni lni-library"></i>
            <span>Module Classes</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="{{route('practice-classes.index')}}" class="sidebar-link">
            <i class="lni lni-microsoft"></i>
            <span>Practice Classes</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="{{route('practice-rooms.index')}}" class="sidebar-link">
            <i class="lni lni-apartment"></i>
            <span>Practice Rooms</span>
        </a>
    </li>
    {{--<li class="sidebar-item">
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
    </li>--}}
</ul>
<div class="sidebar-footer">
    <a href="#" class="sidebar-link">
        <i class="lni lni-exit"></i>
        <span>Logout</span>
    </a>
</div>
