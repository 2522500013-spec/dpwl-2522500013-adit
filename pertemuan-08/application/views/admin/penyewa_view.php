<?php
require APPPATH . '/views/templates/header.php';
require APPPATH . '/views/templates/sidebar.php';

$penyewa = $penyewa ?? [];
$item = $item ?? [];
$mode = $mode ?? null;
$isForm = in_array($mode, ['tambah', 'ubah'], true);
$edit = $mode === 'ubah';
$error = $error ?? null;
$flash = $flash ?? null;
$token = $token ?? '';
$esc = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$action = $edit
  ? site_url('admin/penyewa/ubah/' . rawurlencode((string) ($item['user_plg'] ?? '')))
  : site_url('admin/penyewa/tambah');
?>
<style>
  /* Semua aturan dibatasi untuk halaman Manajemen Penyewa. */
  .tenant-page {
    background: #032552;
    min-height: calc(100vh - 50px);
  }

  .tenant-page .content {
    padding-top: 8px;
  }

  .tenant-page .tenant-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
    padding: 8px 0 14px;
  }

  .tenant-page .tenant-heading h1 {
    margin: 0;
    font-size: 26px;
    font-weight: 700;
    color: #0051f4;
    letter-spacing: -.4px;
  }

  .tenant-page .tenant-heading h1 small {
    display: block;
    margin-top: 7px;
    font-size: 13px;
    color: #010104d0;
  }

  .tenant-page .tenant-card {
    border: 0;
    border-top: 4px solid #000000;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 22px rgba(30, 55, 90, .08);
  }

  .tenant-page .tenant-card .box-body {
    padding: 18px;
  }

  .tenant-page .tenant-card .box-footer {
    background: #6881b3;
    border-top: 1px solid #edf1f6;
    padding: 13px 18px;
  }

  .tenant-page .tenant-card>.box-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 18px;
    background: #fff;
  }

  .tenant-page .tenant-card .box-title {
    font-size: 17px;
    font-weight: 700;
    color: #243b5a;
  }

  .tenant-page .tenant-tools {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
  }

  .tenant-page .tenant-search {
    width: 250px;
    max-width: 100%;
    height: 36px;
    padding: 7px 12px;
    border: 1px solid #dce3ed;
    border-radius: 6px;
    outline: none;
    transition: .2s;
  }

  .tenant-page .tenant-search:focus {
    border-color: #2878d0;
    box-shadow: 0 0 0 3px rgba(40, 120, 208, .12);
  }

  .tenant-page .btn-primary {
    background: #2878d0;
    border-color: #2878d0;
    border-radius: 6px;
    font-weight: 600;
    padding: 8px 13px;
  }

  .tenant-page .tenant-table {
    border: 0;
  }

  .tenant-page .tenant-table>thead>tr>th {
    border-bottom: 1px solid #e4eaf2;
    border-top: 0;
    padding: 13px 10px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .35px;
  }

  .tenant-page .tenant-table>tbody>tr>td {
    border-color: #edf1f6;
    padding: 13px 10px;
  }

  .tenant-page .tenant-table tbody tr:hover {
    background: #f7faff;
  }

  .tenant-page .tenant-table {
    margin-bottom: 0;
  }

  .tenant-page .tenant-table thead th {
    background: #f5f8fc;
    color: #60728a;
    white-space: nowrap;
    vertical-align: middle;
  }

  .tenant-page .tenant-table tbody td {
    vertical-align: middle;
  }

  .tenant-page .tenant-table td.address-cell {
    min-width: 220px;
    max-width: 330px;
    white-space: normal;
  }

  .tenant-page .tenant-actions {
    white-space: nowrap;
  }

  .tenant-page .document-link {
    color: #2878d0;
    font-weight: 600;
    white-space: nowrap;
  }

  .tenant-page .tenant-actions .btn {
    border-radius: 5px;
    padding: 5px 8px;
  }

  .tenant-page .tenant-count {
    display: inline-block;
    margin-left: 7px;
    padding: 3px 8px;
    border-radius: 20px;
    background: #eaf3ff;
    color: #2878d0;
    font-size: 11px;
    font-weight: 700;
    vertical-align: 2px;
  }

  .tenant-page .tenant-intro {
    color: #718198;
    font-size: 13px;
    margin-top: 6px;
  }

  .tenant-page .tenant-form-card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 22px rgba(30, 55, 90, .08);
  }

  .tenant-page .tenant-form-card .box-header {
    padding: 16px 18px;
  }

  .tenant-page .tenant-form-card .box-body {
    padding: 20px;
  }

  .tenant-page .tenant-form-card .box-footer {
    padding: 15px 20px;
    background: #fbfcfe;
  }

  .tenant-page .tenant-form-card label {
    color: #344761;
    font-weight: 600;
  }

  .tenant-page .tenant-form-card .form-control {
    height: 38px;
    border: 1px solid #dce3ed;
    border-radius: 6px;
    box-shadow: none;
  }

  .tenant-page .tenant-form-card textarea.form-control {
    height: auto;
    min-height: 90px;
  }

  .tenant-page .document-empty {
    color: #9aa4aa;
    font-style: italic;
  }

  .tenant-page .tenant-form-card {
    border-top: 3px solid #3c8dbc;
  }

  .tenant-page .tenant-form-card .form-control {
    border-radius: 3px;
  }

  @media (max-width:767px) {
    .tenant-page .tenant-heading h1 {
      font-size: 22px;
    }

    .tenant-page .tenant-search {
      width: 100%;
    }

    .tenant-page .tenant-tools {
      width: 100%;
    }
  }
</style>
<div class="content-wrapper tenant-page">
  <section class="content-header">
    <div class="tenant-heading">
      <div>
        <h1><i class="fa fa-users" style="color:#2878d0;margin-right:8px"></i>Manajemen Penyewa</h1>
        <div class="tenant-intro">Kelola informasi, identitas, dan dokumen penyewa dalam satu halaman.</div>
      </div>
      <ol class="breadcrumb">
        <li><a href="<?= $esc(site_url('admin')) ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Manajemen Penyewa</li>
      </ol>
    </div>
  </section>

  <section class="content">
    <?php if (!empty($flash)): ?>
      <div class="alert <?= ($flash['type'] ?? '') === 'success' ? 'alert-success' : 'alert-danger' ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">&times;</button>
        <?= $esc($flash['message'] ?? '') ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">&times;</button>
        <?= $esc($error) ?>
      </div>
    <?php endif; ?>

    <div class="box tenant-card">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list-ul text-blue"></i> Daftar Data Penyewa <span class="tenant-count"><?= count($penyewa) ?> DATA</span></h3>
        <div class="tenant-tools">
          <input type="search" id="tenantSearch" class="tenant-search" placeholder="Cari nama, username, NIK..." aria-label="Cari data penyewa">
          <a class="btn btn-primary btn-sm" href="<?= $esc(site_url('admin/penyewa/tambah')) ?>">
            <i class="fa fa-plus"></i> Tambah Penyewa
          </a>
        </div>
      </div>
      <div class="box-body table-responsive">
        <table class="table table-bordered table-striped tenant-table" id="tenantTable">
          <thead>
            <tr>
              <th style="width:45px">No.</th>
              <th>Username</th>
              <th>NIK</th>
              <th>Nama Lengkap</th>
              <th>No. HP</th>
              <th>Alamat</th>
              <th>Foto KTP</th>
              <th>Foto SIM A</th>
              <th style="width:135px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($penyewa)): ?>
              <tr>
                <td colspan="9" class="text-center text-muted">Belum ada data penyewa.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($penyewa as $i => $p): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= $esc($p['user_plg'] ?? '') ?></td>
                  <td><?= $esc($p['nik'] ?? '') ?></td>
                  <td><strong style="color:#263b57"><?= $esc($p['nm_lngkp'] ?? '') ?></strong></td>
                  <td><?= $esc($p['no_hp'] ?? '') ?></td>
                  <td class="address-cell"><?= $esc($p['almt_lngkp'] ?? '') ?></td>
                  <?php foreach (['ft_ktp' => 'KTP', 'ft_sim_a' => 'SIM A'] as $field => $label): ?>
                    <td>
                      <?php if (!empty($p[$field])): ?>
                        <a class="document-link" target="_blank" rel="noopener" href="<?= $esc(base_url('uploads/penyewa/' . rawurlencode($p[$field]))) ?>">
                          <i class="fa fa-file-image-o"></i> Lihat <?= $label ?>
                        </a>
                      <?php else: ?>
                        <span class="document-empty">Belum ada</span>
                      <?php endif; ?>
                    </td>
                  <?php endforeach; ?>
                  <td class="tenant-actions">
                    <a class="btn btn-warning btn-xs" href="<?= $esc(site_url('admin/penyewa/ubah/' . rawurlencode((string) ($p['user_plg'] ?? '')))) ?>">
                      <i class="fa fa-edit"></i> Ubah
                    </a>
                    <form method="post" action="<?= $esc(site_url('admin/penyewa/hapus/' . rawurlencode((string) ($p['user_plg'] ?? '')))) ?>" style="display:inline" onsubmit="return confirm('Hapus data penyewa ini?');">
                      <input type="hidden" name="_token" value="<?= $esc($token) ?>">
                      <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash"></i> Hapus</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer text-muted">
        <i class="fa fa-info-circle"></i> Gunakan kolom pencarian untuk menemukan data penyewa dengan cepat.
      </div>
    </div>

    <?php if ($isForm): ?>
      <div class="box box-primary tenant-form-card">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> <?= $edit ? 'Ubah' : 'Tambah' ?> Data Penyewa</h3>
        </div>
        <form method="post" enctype="multipart/form-data" action="<?= $esc($action) ?>">
          <input type="hidden" name="_token" value="<?= $esc($token) ?>">
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group"><label>Username</label><input class="form-control" name="user_plg" maxlength="50" required value="<?= $esc($item['user_plg'] ?? '') ?>" <?= $edit ? 'readonly' : '' ?>></div>
              </div>
              <div class="col-md-6">
                <div class="form-group"><label>NIK (16 digit)</label><input class="form-control" name="nik" maxlength="16" pattern="[0-9]{16}" required value="<?= $esc($item['nik'] ?? '') ?>"></div>
              </div>
              <div class="col-md-6">
                <div class="form-group"><label>Nama Lengkap</label><input class="form-control" name="nm_lngkp" maxlength="100" required value="<?= $esc($item['nm_lngkp'] ?? '') ?>"></div>
              </div>
              <div class="col-md-6">
                <div class="form-group"><label>Nomor HP</label><input class="form-control" name="no_hp" maxlength="15" required value="<?= $esc($item['no_hp'] ?? '') ?>"></div>
              </div>
              <div class="col-md-12">
                <div class="form-group"><label>Alamat Lengkap</label><textarea class="form-control" name="almt_lngkp" maxlength="255" required><?= $esc($item['almt_lngkp'] ?? '') ?></textarea></div>
              </div>
              <div class="col-md-6">
                <div class="form-group"><label>Password <?= $edit ? '(kosongkan jika tidak diubah)' : '' ?></label><input class="form-control" type="password" name="password" minlength="8" maxlength="72" <?= $edit ? '' : 'required' ?> autocomplete="new-password"></div>
              </div>
              <div class="col-md-6">
                <div class="form-group"><label>Foto KTP (JPG/PNG/WEBP, maks. 3 MB)</label><input type="file" name="ft_ktp" accept="image/jpeg,image/png,image/webp"><?php if (!empty($item['ft_ktp'])): ?><p class="help-block">Foto KTP saat ini: <?= $esc($item['ft_ktp']) ?></p><?php endif; ?></div>
              </div>
              <div class="col-md-6">
                <div class="form-group"><label>Foto SIM A (JPG/PNG/WEBP, maks. 3 MB)</label><input type="file" name="ft_sim_a" accept="image/jpeg,image/png,image/webp"><?php if (!empty($item['ft_sim_a'])): ?><p class="help-block">Foto SIM A saat ini: <?= $esc($item['ft_sim_a']) ?></p><?php endif; ?></div>
              </div>
            </div>
            <p class="text-muted"><i class="fa fa-lock"></i> Foto lama tetap digunakan jika tidak memilih file baru. Password disimpan dalam bentuk hash.</p>
          </div>
          <div class="box-footer">
            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
            <a class="btn btn-default" href="<?= $esc(site_url('admin/penyewa')) ?>">Batal</a>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </section>
</div>
<script>
  (function() {
    var search = document.getElementById('tenantSearch');
    var table = document.getElementById('tenantTable');
    if (!search || !table) return;
    search.addEventListener('input', function() {
      var keyword = this.value.toLocaleLowerCase();
      var rows = table.querySelectorAll('tbody tr');
      rows.forEach(function(row) {
        row.style.display = row.textContent.toLocaleLowerCase().indexOf(keyword) !== -1 ? '' : 'none';
      });
    });
  })();
</script>
<?php require APPPATH . '/views/templates/footer.php'; ?>