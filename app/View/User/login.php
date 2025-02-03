<!-- lama -->
<!-- <div class="vh-100 d-flex flex-column justify-content-center align-items-center border px-5 bg-body-tertiary">
    <?php //if(isset($model['error'])) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <strong><?//$model['error'] ?></strong>
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php //} ?> -->
    <!-- <div class="row border p-3 shadow">
        <h2 class="my-3">Login User</h2>
        <form action="/users/login" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="masukan email / no. telp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="masukan password">
            </div>
            <div class="mb-3">
                <button class="form-control btn btn-success" type="submit">Login</button>
            </div>
        </form>
    </div>
</div> -->

<!-- baru -->
<style>
    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .login-card {
      width: 100%;
      max-width: 400px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      background: #fff;
    }
    .login-card .card-header {
      background: #fff;
      border-bottom: none;
      border-radius: 15px 15px 0 0;
      padding: 1.5rem;
      text-align: center;
    }
    .login-card .card-body {
      padding: 1.5rem;
    }
    .login-card .form-control {
      border-radius: 10px;
      padding: 10px 15px;
    }
    .login-card .btn-primary {
      background: linear-gradient(135deg, #667eea, #764ba2);
      border: none;
      border-radius: 10px;
      padding: 10px;
      font-weight: 600;
      width: 100%;
    }
    .login-card .btn-primary:hover {
      opacity: 0.9;
    }
    .login-card .text-muted {
      text-align: center;
      margin-top: 1rem;
    }
    .login-card .text-muted a {
      color: #667eea;
      text-decoration: none;
    }
  </style>
<!-- Card Login -->
<div class="card login-card">
    <!-- pesan error jika ada -->
    <?php if(isset($model['error'])) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <strong><?= $model['error'] ?></strong>
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php } ?>

    <div class="card-header">
      <h3 class="fw-bold">Login Admin</h3>
      <p class="text-muted">Silakan masuk untuk mengakses dashboard</p>
    </div>
    <div class="card-body">
      <form action="/users/login" method="post">
        <!-- Input Email -->
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="username" name="username" class="form-control" id="username" placeholder="Masukkan username" required>
        </div>

        <!-- Input Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
        </div>

        <!-- Tombol Login -->
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>

        <!-- Link Register -->
        <div class="text-muted mt-3">
          Belum punya akun? <a href="/users/register">Daftar di sini</a>
        </div>
      </form>
    </div>
  </div>