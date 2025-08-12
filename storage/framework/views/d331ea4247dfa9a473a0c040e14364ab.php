<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="<?php echo e(route('home')); ?>" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="/images/ias.png" style="height:75px" alt="logo"></span>
            <span class="logo-sm"><img src="/images/ias.png" style="height:25px" alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg"><img src="/images/ias.png" style="height:75px" alt="dark logo"></span>
            <span class="logo-sm"><img src="/images/ias.png" style="height:25px" alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ti ti-circle align-middle"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div data-simplebar>

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-title">Main</li>

            <li class="side-nav-item">
                <a href="<?php echo e(route('home')); ?>" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="menu-text"> Dashboard </span>
                    <span class="badge bg-success rounded-pill">5</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSppd" aria-expanded="false" aria-controls="sidebarSppd"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-calendar"></i></span>
                    <span class="menu-text"> Sppd</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSppd">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                             <a href="<?php echo e(route ('third' , ['pages','sppd','index'])); ?>" class="side-nav-link">
                                <span class="menu-text">List SPPD</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="<?php echo e(route ('sppd.create')); ?>" class="side-nav-link">
                                <span class="menu-text">Ajukan SPPD</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="#" class="side-nav-link">
                                <span class="menu-text">Tiket</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="#" class="side-nav-link">
                                <span class="menu-text">Laporan</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDokumen" aria-expanded="false" aria-controls="sidebarDokumen"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-file-invoice"></i></span>
                    <span class="menu-text"> Dokumen</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarDokumen">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="#" class="side-nav-link">
                                <span class="menu-text">E-ticket</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="#" class="side-nav-link">
                                <span class="menu-text">Guarantee Letter</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-calendar"></i></span>
                    <span class="menu-text"> Calendar </span>
                </a>
            </li>

            <!-- Finance -->
            <li class="side-nav-title">Finance</li>
            <li class="side-nav-item">
                <a href="<?php echo e(route ('finance.saldo-mitra')); ?>" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-credit-card"></i></span>
                    <span class="menu-text"> Saldo Mitra </span>
                </a>
            </li>

            <li class="side-nav-title mt-2">
                Settings
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarLayouts" aria-expanded="false" aria-controls="sidebarLayouts"
                    class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-layout"></i></span>
                    <span class="menu-text"> User Management </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="<?php echo e(route('users.index')); ?>" class="side-nav-link">User List</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="<?php echo e(route('roles.index')); ?>" class="side-nav-link">Role</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="<?php echo e(route('divisi.index')); ?>" class="side-nav-link">Departement</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="#" class="side-nav-link">Settings</a>
                        </li>
                    </ul>
                </div>
            </li>

            
        </ul>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->
<?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/layouts/partials/sidenav.blade.php ENDPATH**/ ?>