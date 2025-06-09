<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="#" class="brand-link">
            <!--begin::Brand Image-->
            
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <i class="fa fa-fire fa-2x " style="color: red;"></i>
            <span class="brand-text  fw-bold  fs-4">SIGAP-IO</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                  
                    <x-layouts.admin.menu-items :url="'admin.beranda'" icon="fas fa-home" class="nav-link" :active="request()->is('admin/beranda')"
                        label="Beranda" />
                </li>
                <li class="nav-item">
                  
                    <x-layouts.admin.menu-items icon="fas fa-mobile" :url="'admin.perangkat'" class="nav-link" :active="request()->is('admin/perangkat')"
                        label="Perangkat" />
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Data Petugas
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <x-layouts.admin.menu-items :url="'admin.petugas'" class="nav-link" :active="request()->is('admin/petugas')"
                                label="Petugas" />
                            <x-layouts.admin.menu-items :url="'admin.jabatan'" class="nav-link" :active="request()->is('admin/jabatan')"
                                label="Jabatan" />
                        </li>
                    </ul>
                </li>
               
                <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon bi bi-activity"></i>
                        <p>
                          Log Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <x-layouts.admin.menu-items :url="'admin.log-perangkat'" class="nav-link" :active="request()->is('admin/perangkat')"
                                label="Log Perangkat" />
                            <x-layouts.admin.menu-items :url="'admin.log-perangkat'" class="nav-link" :active="request()->is('admin/log-perangkat')"
                                label="Log Petugas" />
                            <x-layouts.admin.menu-items :url="'admin.petugas'" class="nav-link" :active="request()->is('admin/log-perangkat')"
                                label="Log User" />
                        </li>
                    </ul>
                </li>


            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
