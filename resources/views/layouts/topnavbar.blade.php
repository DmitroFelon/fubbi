<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        @auth
        <ul class="nav navbar-top-links navbar-right">
            <li id="message-notifications-content" class="dropdown">
                @include('partials.navbar-elements.navbar-messages')
            </li>
            <li id="alert-notifications-content" class="dropdown">
                @include('partials.navbar-elements.navbar-alerts')
            </li>
            <li>
                <a onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="#">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </li>
        </ul>
        @endauth
    </nav>
</div>
