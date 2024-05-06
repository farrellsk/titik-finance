<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
    <li class="{{ in_array(\Request::route()->getName(), ['home']) ? 'active' : '' }} nav-item"><a
            href="{{ route('home') }}"><i class="feather icon-home"></i><span class="menu-title">Dashboard</span></a>
    </li>

    <li
        class="{{ in_array(\Request::route()->getName(), ['kasbank', 'create.kasbank', 'edit.kasbank', 'detailkasbank']) ? 'active' : '' }} nav-item">
        <a href="{{ route('kasbank') }}"><i class="feather icon-credit-card"></i><span class="menu-title">Kas &
                Bank</span></a>
    </li>

    <li class="{{ in_array(\Request::route()->getName(), ['mutasi-view', 'mutasi-tambah']) ? 'active' : '' }} nav-item">
        <a href="{{ route('mutasi-view') }}"><i class="feather icon-git-merge"></i><span class="menu-title">Mutasi
            </span></a>
    </li>

    <li
        class="{{ in_array(\Request::route()->getName(), ['users.index', 'users.create', 'users.edit']) ? 'active' : '' }} nav-item">
        <a href="{{ route('users.index') }}"><i class="feather icon-users"></i><span class="menu-title">Users
            </span></a>
    </li>

    <li class="nav-item">
        <a href="javascript:void(0);" class="dribbble nav-link"><i class="feather icon-dollar-sign"></i><span
                class="menu-title">Transaksi</span></a>
        <ul class="menu-content">
            <li
                class="{{ in_array(\Request::route()->getName(), ['transaksi.index', 'transaksi.create', 'transaksi.edit']) ? 'active' : '' }}">
                <a href="{{ route('transaksi.index') }}"><i class="feather icon-arrow-left"></i><span
                        class="menu-item">Pembelian</span></a>
            </li>
            <li
                class="{{ in_array(\Request::route()->getName(), ['pemasukan.index', 'pemasukan.create', 'pemasukan.edit']) ? 'active' : '' }}">
                <a href="{{ route('pemasukan.index') }}"><i class="feather icon-arrow-right"></i><span
                        class="menu-item">Penjualan</span></a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="javascript:void(0);" class="dribbble nav-link"><i class="feather icon-grid"></i><span
                class="menu-title">Biaya</span></a>
        <ul class="menu-content">
            <li
                class="{{ in_array(\Request::route()->getName(), ['akun-biaya', 'tambah-akunB', 'edit-biaya']) ? 'active' : '' }} nav-item">
                <a href="{{ route('akun-biaya') }}"><i class="feather icon-list"></i><span class="menu-title">Biaya
                    </span></a>
            </li>
            <li
                class="{{ in_array(\Request::route()->getName(), ['kategori-biaya', 'kategori-edit', 'kategori-create']) ? 'active' : '' }} nav-item">
                <a href="{{ route('kategori-biaya') }}"><i class="feather icon-briefcase"></i><span
                        class="menu-title">Kategori Biaya </span></a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="javascript:void(0);" class="dribbble nav-link"><i class="feather icon-package"></i><span
                class="menu-title">Produk</span></a>
        <ul class="menu-content">
            <li
                class="{{ in_array(\Request::route()->getName(), ['produk', 'produk-tambah', 'produk-edit']) ? 'active' : '' }} nav-item">
                <a href="{{ route('produk') }}"><i class="feather icon-menu"></i><span class="menu-title">Produk
                    </span></a>
            </li>
            <li
                class="{{ in_array(\Request::route()->getName(), ['produk-kategori', 'produk-kategori-tambah', 'produk-kategori-edit']) ? 'active' : '' }} nav-item">
                <a href="{{ route('produk-kategori') }}"><i class="feather icon-shopping-bag"></i><span
                        class="menu-title">Produk Kategori</span></a>
            </li>
        </ul>
    </li>

    <li
        class="{{ in_array(\Request::route()->getName(), ['users.kontak', 'kontak-tambah', 'kontak-edit']) ? 'active' : '' }} nav-item">
        <a href="{{ route('users.kontak') }}"><i class="feather icon-phone"></i><span class="menu-title">Kontak
            </span></a>
    </li>

    <li class="{{ in_array(\Request::route()->getName(), ['jurnal']) ? 'active' : '' }} nav-item">
        <a href="{{ route('jurnal') }}"><i class="feather icon-book-open"></i><span
                class="menu-title">Jurnal</span></a>
    </li>

    <li class="{{ in_array(\Request::route()->getName(), ['setting-index']) ? 'active' : '' }} nav-item">
        <a href="{{ route('setting-index') }}"><i class="feather icon-save"></i><span class="menu-title">Setting
            </span></a>
    </li>

    <li class="{{ in_array(\Request::route()->getName(), ['laporan.index']) ? 'active' : '' }} nav-item">
        <a href="{{ route('laporan.index') }}"><i class="feather icon-file"></i><span class="menu-title">Unduh Data
            </span></a>
    </li>

    <li class="{{ in_array(\Request::route()->getName(), ['log']) ? 'active' : '' }} nav-item">
        <a href="{{ route('log') }}"><i class="feather icon-activity"></i><span class="menu-title">Aktivitas
            </span></a>
    </li>

</ul>
