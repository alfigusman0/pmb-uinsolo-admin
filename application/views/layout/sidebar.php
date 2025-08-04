<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?= base_url('dashboard') ?>" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">PMB</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item <?= ($this->uri->segment(1) == 'dashboard') ? 'active' : '' ?>">
            <a href="<?= base_url('dashboard') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        <?php if ($this->jwt->ids_level == 32) : ?>
            <!-- Mandiri -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri') ? 'active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div data-i18n="Mandiri">Mandiri</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'penilaian') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/penilaian') ?>" class="menu-link">
                            <div data-i18n="Penilaian">Penilaian</div>
                        </a>
                    </li>
                </ul>
            </li>
        <?php else : ?>
            <!-- Daftar Ulang -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'daftar') ? 'active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div data-i18n="Daftar Ulang">Daftar Ulang</div>
                </a>
                <ul class="menu-sub">
                    <!-- Export -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'export') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Export">Export</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'kelas') ? 'active' : '' ?>">
                                <a href="<?= base_url('daftar/export/kelas') ?>" class="menu-link">
                                    <div data-i18n="Kelas">Kelas</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'kelulusan') ? 'active' : '' ?>">
                                <a href="<?= base_url('daftar/export/kelulusan') ?>" class="menu-link">
                                    <div data-i18n="Kelulusan">Kelulusan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'mahasiswa') ? 'active' : '' ?>">
                                <a href="<?= base_url('daftar/export/mahasiswa') ?>" class="menu-link">
                                    <div data-i18n="Mahasiswa">Mahasiswa</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'ukuran-baju') ? 'active' : '' ?>">
                                <a href="<?= base_url('daftar/export/ukuran-baju') ?>" class="menu-link">
                                    <div data-i18n="Ukuran Baju">Ukuran Baju</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Import -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'import') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Import">Import</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'kelulusan') ? 'active' : '' ?>">
                                <a href="<?= base_url('daftar/import/kelulusan') ?>" class="menu-link">
                                    <div data-i18n="Kelulusan">Kelulusan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'mandiri') ? 'active' : '' ?>">
                                <a href="<?= base_url('daftar/import/mandiri') ?>" class="menu-link">
                                    <div data-i18n="Mandiri">Mandiri</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'mahasiswa') ? 'active' : '' ?>">
                        <a href="<?= base_url('daftar/mahasiswa') ?>" class="menu-link">
                            <div data-i18n="Mahasiswa">Mahasiswa</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'pembayaran') ? 'active' : '' ?>">
                        <a href="<?= base_url('daftar/pembayaran') ?>" class="menu-link">
                            <div data-i18n="Pembayaran">Pembayaran</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'statistik') ? 'active' : '' ?>">
                        <a href="<?= base_url('daftar/statistik') ?>" class="menu-link">
                            <div data-i18n="Statistik">Statistik</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Mandiri -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri') ? 'active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div data-i18n="Mandiri">Mandiri</div>
                </a>
                <ul class="menu-sub">
                    <!-- Export -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'export') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Export">Export</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'abhp') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/abhp') ?>" class="menu-link">
                                    <div data-i18n="ABHP">ABHP</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'berita-acara') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/berita-acara') ?>" class="menu-link">
                                    <div data-i18n="Berita Acara">Berita Acara</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'akademik') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/akademik') ?>" class="menu-link">
                                    <div data-i18n="Akademik">Akademik</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'formulir') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/formulir') ?>" class="menu-link">
                                    <div data-i18n="Formulir">Formulir</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'pembayaran') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/pembayaran') ?>" class="menu-link">
                                    <div data-i18n="Pembayaran">Pembayaran</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'kelulusan') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/kelulusan') ?>" class="menu-link">
                                    <div data-i18n="Kelulusan">Kelulusan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'jadwal') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/jadwal') ?>" class="menu-link">
                                    <div data-i18n="Jadwal">Jadwal</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'pilihan') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/pilihan') ?>" class="menu-link">
                                    <div data-i18n="Pilihan">Pilihan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'kebutuhan-khusus') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/kebutuhan-khusus') ?>" class="menu-link">
                                    <div data-i18n="Kebutuhan Khusus">Kebutuhan Khusus</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'sanggah') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/sanggah') ?>" class="menu-link">
                                    <div data-i18n="Sanggah">Sanggah</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'wilayah3t') ? 'active' : '' ?>">
                                <a href="<?= base_url('mandiri/export/wilayah3t') ?>" class="menu-link">
                                    <div data-i18n="Wilayah 3T">Wilayah 3T</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'mahasiswa') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/mahasiswa') ?>" class="menu-link">
                            <div data-i18n="Mahasiswa">Mahasiswa</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'kelulusan') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/kelulusan') ?>" class="menu-link">
                            <div data-i18n="Kelulusan">Kelulusan</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'penilaian') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/penilaian') ?>" class="menu-link">
                            <div data-i18n="Penilaian">Penilaian</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'pembayaran') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/pembayaran') ?>" class="menu-link">
                            <div data-i18n="Pembayaran">Pembayaran</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'sanggah') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/sanggah') ?>" class="menu-link">
                            <div data-i18n="Sanggah">Sanggah</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'statistik') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/statistik') ?>" class="menu-link">
                            <div data-i18n="Statistik">Statistik</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(1) == 'mandiri' && $this->uri->segment(2) == 'setting') ? 'active' : '' ?>">
                        <a href="<?= base_url('mandiri/setting') ?>" class="menu-link">
                            <div data-i18n="Setting">Setting</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- UKT -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'ukt') ? 'active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="UKT">UKT</div>
                </a>
                <ul class="menu-sub">
                    <!-- Histori -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'histori') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Histori">Histori</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'bobot-nilai') ? 'active' : '' ?>">
                                <a href="<?= base_url('ukt/histori/bobot-nilai') ?>" class="menu-link">
                                    <div data-i18n="Bobot Nilai UKT">Bobot Nilai UKT</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'bobot-range') ? 'active' : '' ?>">
                                <a href="<?= base_url('ukt/histori/bobot-range') ?>" class="menu-link">
                                    <div data-i18n="Bobot Range UKT">Bobot Range UKT</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Import -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'import') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Import">Import</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'penetapan') ? 'active' : '' ?>">
                                <a href="<?= base_url('ukt/import/penetapan') ?>" class="menu-link">
                                    <div data-i18n="Penetapan">Penetapan</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Penetapan -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'penetapan') ? 'active' : '' ?>">
                        <a href="<?= base_url('ukt/penetapan') ?>" class="menu-link">
                            <div data-i18n="Penetapan">Penetapan</div>
                        </a>
                    </li>
                    <!-- Rekap Jurusan -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'rekap-jurusan') ? 'active' : '' ?>">
                        <a href="<?= base_url('ukt/rekap-jurusan') ?>" class="menu-link">
                            <div data-i18n="Rekap Jurusan">Rekap Jurusan</div>
                        </a>
                    </li>
                    <!-- Rekap Nilai -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'rekap-nilai') ? 'active' : '' ?>">
                        <a href="<?= base_url('ukt/rekap-nilai') ?>" class="menu-link">
                            <div data-i18n="Rekap Nilai">Rekap Nilai</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Kelulusan -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'kelulusan') ? 'active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div data-i18n="Kelulusan">Kelulusan</div>
                </a>
                <ul class="menu-sub">
                    <!-- Export -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'export') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Export">Export</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'kelulusan') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/export/kelulusan') ?>" class="menu-link">
                                    <div data-i18n="Kelulusan">Kelulusan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'biodata') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/export/biodata') ?>" class="menu-link">
                                    <div data-i18n="Biodata">Biodata</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'pilihan') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/export/pilihan') ?>" class="menu-link">
                                    <div data-i18n="Pilihan">Pilihan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'nilai') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/export/nilai') ?>" class="menu-link">
                                    <div data-i18n="Nilai">Nilai</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'sk') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/export/sk') ?>" class="menu-link">
                                    <div data-i18n="SK">SK</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'sk_sanggah') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/export/sk_sanggah') ?>" class="menu-link">
                                    <div data-i18n="SK Sanggah">SK Sanggah</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Import -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'import') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Import">Import</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'afirmasi') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/import/afirmasi') ?>" class="menu-link">
                                    <div data-i18n="Afirmasi">Afirmasi</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'bebas') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/import/bebas') ?>" class="menu-link">
                                    <div data-i18n="Bebas">Bebas</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'cek-kelulusan') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/import/cek-kelulusan') ?>" class="menu-link">
                                    <div data-i18n="Cek Kelulusan">Cek Kelulusan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'cek-sanggah') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/import/cek-sanggah') ?>" class="menu-link">
                                    <div data-i18n="Cek Sanggah">Cek Sanggah</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'kelulusan') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/import/kelulusan') ?>" class="menu-link">
                                    <div data-i18n="Kelulusan">Kelulusan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'nilai') ? 'active' : '' ?>">
                                <a href="<?= base_url('kelulusan/import/nilai') ?>" class="menu-link">
                                    <div data-i18n="Nilai">Nilai</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'lulus') ? 'active' : '' ?>">
                        <a href="<?= base_url('kelulusan/lulus') ?>" class="menu-link">
                            <div data-i18n="Generate Kelulusan">Generate Kelulusan</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'tidak-lulus') ? 'active' : '' ?>">
                        <a href="<?= base_url('kelulusan/tidak-lulus') ?>" class="menu-link">
                            <div data-i18n="Generate Tidak Lulus">Generate Tidak Lulus</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'reset') ? 'active' : '' ?>">
                        <a href="<?= base_url('kelulusan/reset') ?>" class="menu-link">
                            <div data-i18n="Reset Kelulusan">Reset Kelulusan</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'cbt') ? 'active' : '' ?>">
                        <a href="<?= base_url('kelulusan/cbt') ?>" class="menu-link">
                            <div data-i18n="CBT">Tarik Nilai CBT</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Notifikasi -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'notifikasi') ? 'active' : '' ?>">
                <a href="<?= base_url('notifikasi') ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-message"></i>
                    <div data-i18n="Notifikasi">Notifikasi</div>
                </a>
            </li>
            <!-- Akun -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'akun') ? 'active' : '' ?>">
                <a href="<?= base_url('akun/user') ?>" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Akun">Akun</div>
                </a>
            </li>
            <!-- Settings -->
            <li class="menu-item <?= ($this->uri->segment(1) == 'setting') ? 'active open' : '' ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Settings">Settings</div>
                </a>
                <ul class="menu-sub">
                    <!-- Import -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'import') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Import">Import</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'ukt') ? 'active' : '' ?>">
                                <a href="<?= base_url('setting/import/ukt') ?>" class="menu-link">
                                    <div data-i18n="Kategori UKT">Kategori UKT</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Export -->
                    <li class="menu-item <?= ($this->uri->segment(2) == 'export') ? 'active open' : '' ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Export">Export</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?= ($this->uri->segment(3) == 'users') ? 'active' : '' ?>">
                                <a href="<?= base_url('setting/export/users') ?>" class="menu-link">
                                    <div data-i18n="Users">Users</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'jurusan') ? 'active' : '' ?>">
                                <a href="<?= base_url('setting/export/jurusan') ?>" class="menu-link">
                                    <div data-i18n="Jurusan">Jurusan</div>
                                </a>
                            </li>
                            <li class="menu-item <?= ($this->uri->segment(3) == 'daya-tampung') ? 'active' : '' ?>">
                                <a href="<?= base_url('setting/export/daya-tampung') ?>" class="menu-link">
                                    <div data-i18n="Daya Tampung">Daya Tampung</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'bobot-jurusan') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/bobot-jurusan') ?>" class="menu-link">
                            <div data-i18n="Bobot Jurusan">Bobot Jurusan</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'bobot-nilai') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/bobot-nilai') ?>" class="menu-link">
                            <div data-i18n="Bobot Nilai UKT">Bobot Nilai UKT</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'bobot-range') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/bobot-range') ?>" class="menu-link">
                            <div data-i18n="Bobot Range UKT">Bobot Range UKT</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'daya-tampung') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/daya-tampung') ?>" class="menu-link">
                            <div data-i18n="Daya Tampung">Daya Tampung</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'jadwal') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/jadwal') ?>" class="menu-link">
                            <div data-i18n="Jadwal">Jadwal</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'jalur-masuk') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/jalur-masuk') ?>" class="menu-link">
                            <div data-i18n="Jalur Masuk">Jalur Masuk</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'program') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/program') ?>" class="menu-link">
                            <div data-i18n="Program">Program</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'sanggah') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/sanggah') ?>" class="menu-link">
                            <div data-i18n="Sanggah">Sanggah</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'slider') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/slider') ?>" class="menu-link">
                            <div data-i18n="Slider">Slider</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'sub-daya-tampung') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/sub-daya-tampung') ?>" class="menu-link">
                            <div data-i18n="Sub Daya Tampung">Sub Daya Tampung</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'tipe-file') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/tipe-file') ?>" class="menu-link">
                            <div data-i18n="Tipe File">Tipe File</div>
                        </a>
                    </li>
                    <li class="menu-item <?= ($this->uri->segment(2) == 'tipe-ujian') ? 'active' : '' ?>">
                        <a href="<?= base_url('setting/tipe-ujian') ?>" class="menu-link">
                            <div data-i18n="Tipe Ujian">Tipe Ujian</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Cronejob -->
            <?php if ($this->jwt->ids_level == 1) : ?>
                <li class="menu-item <?= ($this->uri->segment(2) == 'cronejob') ? 'active open' : '' ?>">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class='menu-icon tf-icons bx bxs-timer'></i>
                        <div data-i18n="Cronejob">Cronejob</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj1') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj1') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Update Tipe Ujian">Update Tipe Ujian</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj2') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj2') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Expired Pembayaran">Expired Pembayaran</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj3') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj3/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Daya Tampung & Kuota">Daya Tampung & Kuota</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj4') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj4') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Perhitungan Bobot Sekolah">Perhitungan Bobot Sekolah</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj5') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj5') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Akumulasi Nilai">Akumulasi Nilai</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj6') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj6/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Passing Grade - Sarjana">Passing Grade - Sarjana</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj7') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj7/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="G.U. NIM">G.U. NIM</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj8') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj8/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="G.U. Kelas">G.U. Kelas</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj9') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj9/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="G.C. Sub Daya Tampung">G.C. Sub Daya Tampung</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj11') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj11/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Update Data Double Pembayaran">Update Data Double Pembayaran</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj12') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj12/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Update Tanggal Pembayaran Kelulusan">Update Tanggal Pembayaran Kelulusan</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj13') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj13/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="G.S.Berdasarkan Tipe Ujian">G.S.Berdasarkan Tipe Ujian</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj14') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj14/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="U.Tanggal Pembayaran Kelulusan">U.Tanggal Pembayaran Kelulusan</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj15') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj15/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Kirim Email">Kirim Email</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj16') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj16/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="G.C. Bobot Jurusan">G.C. Bobot Jurusan</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj17') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj17/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="G.C. Kelulusan">G.C. Kelulusan</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj19') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj19/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="Kirim Whatsapp">Kirim Whatsapp</div>
                            </a>
                        </li>
                        <li class="menu-item <?= ($this->uri->segment(3) == 'cj21') ? 'active' : '' ?>">
                            <a href="<?= base_url('cronejob/cj21/') ?>" class="menu-link" target="_blank">
                                <div data-i18n="G.U. Nilai Min & Max">G.U. Nilai Min & Max</div>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</aside>