<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= site_url(); ?>">WordPress Guide</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?= site_url(); ?>">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url("about"); ?>">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://github.com/drthomas21/WordPress_Tutorial" target="_blank">GitHub Repo</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="<?= site_url(); ?>">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="s">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>
