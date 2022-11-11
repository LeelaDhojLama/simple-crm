<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="/images/logo.png" /> </a> <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        </div>
        <ul class="navbar-nav mr-auto">
        </ul>
        <ul>
            <div class="dropdown">
                <img class="btn dropdown-toggle" src="/images/user.png" style="height: 30px;width:30px;padding:0px" alt="dropdown image" data-toggle="dropdown" class="img-responsive">
                <ul class="dropdown-menu">
                    <li><a href="{{ url('/change-password') }}"><i class="fas fa-key" style="padding-right: 8px;"></i> Password</a></li>
                    <li><a href="{{ url('/logout') }}"><i class="fas fa-sign-out" style="padding-right: 8px;"></i>Log Out</a></li>
                </ul>
            </div>
        </ul>
    </div>
</nav>

<div style="height: 60px;"></div>
<div id="loader">
    <div class="bar"></div>
</div>