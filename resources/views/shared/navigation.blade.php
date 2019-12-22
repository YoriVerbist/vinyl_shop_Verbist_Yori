<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">The Vinyl Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact-us">Contact</a>
                </li>
            </ul>
            {{--  Admin navigation  --}}
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#!" data-toggle="dropdown">Admin</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="/admin/records">Records</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
