<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="#" class="app-brand-link">
        <img src="{{asset('assets/img/branding/bear-logo-dswd.png')}}" class="app-brand-logo w-px-30 h-auto me-2 " alt="logo" />
            <span class="app-brand-text menu-text fw-bold">DTRMS
              <br />
              <span class="fs-tiny fw-medium">DTR Management System</span>
            </span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto ">
        <i class="bx bx-chevron-left bx-sm align-middle sidebar-menu-toggle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      {{-- <li class="menu-header small">
        <span class="menu-header-text" data-i18n="Apps & Pages">Entry</span>
      </li> --}}
      <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
        <a href="/" class="menu-link">
          <i class="menu-icon tf-icons bx bx-time-five"></i>
          <div class="text-truncate" data-i18n="Page 1">Add Today's Time Entry</div>
        </a>
      </li>

      <li class="menu-item {{ request()->is('time_sheet') ? 'active' : '' }}">
        <a href="/time_sheet" class="menu-link">
          <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
          <div class="text-truncate" data-i18n="Page 1">View Time Sheet</div>
        </a>
      </li>
    </ul>
  </aside>
