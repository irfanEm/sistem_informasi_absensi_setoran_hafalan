<div class="vh-100 d-flex flex-column justify-content-center align-items-center border px-5 bg-body-tertiary">
    <?php if(isset($model['error'])) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <strong><?= $model['error'] ?></strong>
              <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php } ?>
    <div class="row border p-3 shadow">
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
</div>