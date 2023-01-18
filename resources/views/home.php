@title->(Home Page)
@layout->(main2)

    @styles
        <!-- You can add stylesheets or others links on every view and deliver to head. -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endstyles

    @content
        <div class="text-center mt-4 mb-4">
            <h1>Arco Framework</h1>
            <?php if (isGuest()): ?>
            <h2 class="h4">Welcome guest</h2>
            <?php else: ?>
            <h2 class="h4">Welcome <?php echo auth()->name ?></h2>
            <p>You are logged in!</p>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">About the Framework</h4>
                        <p class="card-text">
                            This framework is inspired by Laravel and in no way wants to compete against Laravel.
                        </p>
                        <p class="card-text">
                            Arco Framework has been created within aim educational aim following the Mastermind course below:
                        </p>
                        <a href="https://www.mastermind.ac/courses/crea-tu-propio-framework-desde-cero" target="_blank" class="btn btn-outline btn-primary">
                            Create your own Web Framework with PHP - <b>Mastermind.ac</b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Do you have any suggestions?</h4>
                        <p class="card-text">Any new ideas are welcome. Challenges are fun and I would be happy to expand the framework's capabilities.</p>
                        <p class="card-text">Follow the project on GitHub.</p>
                        <a href="https://github.com/aldearco/arco-framework" target="_blank" class="btn btn-primary">
                            <i class="fa-brands fa-github"></i>
                            Arco Framework
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endcontent

    @scripts
        <!-- You can add scripts on every view and deliver to bottom. -->
    @endscripts

    <p>This will not be rendered because is outside @content tags.</p>