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
        <a href="{{route('teacher.manage-classes')}}" class="sidebar-link">
            <i class="lni lni-grid"></i>
            <span>Manage Classes</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="{{route('student.register-classes')}}" class="sidebar-link">
            <i class="lni lni-grid"></i>
            <span>(S) Register Classes</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a href="{{route('student.manage-classes')}}" class="sidebar-link">
            <i class="lni lni-grid"></i>
            <span>(S) Manage Classes</span>
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
</ul>
<div class="sidebar-footer">
    <a href="{{route('logout')}}" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="lni lni-exit"></i>
        <span>Logout</span>
    </a>
    <form id="logout-form" action="{{route('logout')}}" method="post">
        @csrf
    </form>
</div>
