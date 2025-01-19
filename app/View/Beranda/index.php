<nav class="navbar navbar-expand-lg border-bottom border-body shadow-sm">
  <div class="container-fluid">
    <!-- Teks SIASHAF hanya untuk mobile view -->
    <a class="navbar-brand d-lg-none" href="/">SIASHAF</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <!-- Teks Hidden brand tetap tampil pada layar besar -->
      <a class="navbar-brand d-none d-lg-block" href="/">SIASHAF</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/admin/beranda">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Master</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/admin/master/guru">Guru</a></li>
            <li><a class="dropdown-item" href="/admin/master/murid">Santri</a></li>
            <li><a class="dropdown-item" href="/admin/master/hafalan">Hafalan</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/admin/absensi" >Absensi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/admin/hafalan" >Hafalan</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <a class="btn btn-outline-danger" href="/users/logout">Logout</a>
      </form>
    </div>
  </div>
</nav>



<!-- <div class="vh-100 d-flex flex-column justify-content-center align-items-center border px-5 bg-body-tertiary">
    <?php if(isset($model['error'])) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <strong><?= $model['error'] ?></strong>
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php } ?>
    <div class="row border p-3 shadow">
        <a href="/users/logout" class="btn btn-danger">logout</a>
        
    </div>
</div>
<div class="vh-100 d-flex flex-column justify-content-center align-items-center border px-5 bg-body-tertiary">
    <?php if(isset($model['error'])) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <strong><?= $model['error'] ?></strong>
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php } ?>
    <div class="row border p-3 shadow">
        <a href="/users/logout" class="btn btn-danger">logout</a>
    </div>
</div> -->
