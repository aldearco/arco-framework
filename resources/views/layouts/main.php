<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?php echo csrf_token() ?>">
  <title>#title - <?php echo config("app.name") ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.3/zephyr/bootstrap.min.css" integrity="sha512-dcTg+pv6j02FTyko5ua8nsnARs/l4u43vmnbeVgkFWB5wdLgfUq4CEotFWOlTE4XK7FfVriWj7BrpqET/a+SJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
  #styles
</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-xl">
      <a class="navbar-brand" href="<?= route('home') ?>">
        <img src="<?= asset('img/arco-logo-white.svg') ?>" alt="Arco Framework" class="navbar-brand p-0 me-2" width="30" height="30">
        <span class="fw-bold">Arco</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse mt-2 mt-sm-0" id="navbar">
        <ul class="navbar-nav me-auto mb-2 mb-sm-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= route('home') ?>">
              <i class="fa-solid fa-home me-2"></i>
              Home
            </a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <?php if (isGuest()): ?>
              <i class="fa-solid fa-key me-2"></i>
              Access
              <?php else: ?>
              <i class="fa-solid fa-user me-2"></i>
              <?= auth()->name ?>
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <?php if (isGuest()): ?>
              <li>
                <a class="dropdown-item" href="<?= route('login') ?>">
                  <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>
                  Login
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="<?= route('register') ?>">
                  <i class="fa-regular fa-pen-to-square me-2"></i>
                  Register
                </a>
              </li>
              <?php else: ?>
              <li>
                <a class="dropdown-item" href="<?= route('logout') ?>">
                  <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                  Logout
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container">
    #content
  </main>
  #scripts
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" integrity="sha512-i9cEfJwUwViEPFKdC1enz4ZRGBj8YQo6QByFTF92YXHi7waCqyexvRD75S5NVTsSiTv7rKWqG9Y5eFxmRsOn0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>