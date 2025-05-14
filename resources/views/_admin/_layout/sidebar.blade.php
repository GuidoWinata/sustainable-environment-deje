@php
    $page = Request::segment(2);
@endphp

<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
    <ul id="sidebarnav">
        <li class="nav-small-cap mb-3 mt-4" style="color: #adadad">
            <span class="hide-menu ms-1">MENU APLIKASI</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ $page == '/' ? 'active' : '' }}" href="{{ base_url('') }}" aria-expanded="false"
                navigate>
                @include('_admin._layout.icons.dashboard')
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link has-arrow {{ in_array($page, ['news', 'news-category']) ? 'active' : '' }}"
                href="#" data-toggle="collapse"
                aria-expanded="{{ in_array($page, ['news']) ? 'true' : 'false' }}">
                @include('_admin._layout.icons.news')
                <span class="hide-menu">Data Berita</span>
            </a>
            <ul class="collapse with-bullets show mm-collapse {{ in_array($page, ['news', 'category']) ? 'show mm-collapse mm-show' : '' }}">
                <li class="py-2 nav-item">
                    <a href="{{ url('admin/news') }}" navigate class="{{ $page == 'news' ? 'active' : '' }}">
                        <p class="mb-0">Berita</p>
                    </a>
                </li>
                <li class="py-2 nav-item {{ $page == 'news-category' ? 'active' : '' }}">
                    <a href="{{ url('admin/category') }}" navigate class="{{ $page == 'category' ? 'active' : '' }}">
                        <p class="mb-0">Kategori Berita</p>
                    </a>
                </li>
            </ul>
        </li>        
        <li class="sidebar-item">
            <a class="sidebar-link {{ $page == 'user' ? 'active' : '' }}" href="{{ base_url('user') }}"
                aria-expanded="false" navigate>
                @include('_admin._layout.icons.users')
                <span class="hide-menu">Pengguna Aplikasi</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link {{ $page == 'setting' ? 'active' : '' }}"
                href="{{ base_url('setting/general') }}" aria-expanded="false" navigate>
                @include('_admin._layout.icons.setting')
                <span class="hide-menu">Pengaturan</span>
            </a>
        </li>
        
        <span class="sidebar-divider lg my-4"></span>
        <li class="sidebar-item">
            <div class="d-grid gap-2">
                <a class="btn btn-outline-danger text-start rounded-3" href="{{ base_url('auth/logout') }}"
                    aria-expanded="false" onclick="return confirm('Apakah kamu yakin?')">
                    <span class="hide-menu"><b>Keluar Aplikasi</b></span>
                </a>
            </div>
        </li>
    </ul>
</nav>