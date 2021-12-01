<!-- Main Sidebar -->
<div id="sidebar">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <div>
                <a href="/dashboard">
                    <img class="logo_admin" src="{{ asset('img/landing/logo.png') }}" alt="avatar" style="padding: 20px">
                </a>
            </div>
            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                @admin
                <li>
                    <a href="{{ route('dashboard.index') }}"><i class="fa fa-bar-chart-o"></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Users</a>
                </li>
                <li>
                    <a href="{{ route('admin.webhooks.index') }}"><i class="fa fa-desktop"></i> Webhooks</a>
                </li>
                <li>
                    <a href="{{ route('admin.template.index') }}"><i class="fa fa-list"></i> Templates</a>
                </li>
                <li>
                    <a href="{{ route('webhooks.index') }}"><i class="fa fa-laptop"></i> My Webhooks</a>
                </li>
                <li>
                    <a href="{{ route('bots.index') }}"><i class="fa fa-reddit"></i> My Bots</a>
                </li>
                <li>
                    <a href="{{ route('admin.history.index') }}"><i class="fa fa-history"></i> Payload histories</a>
                </li>
                <li>
                    <a href="{{ route('templates.index') }}"><i class="fa fa-share-alt"></i> My Templates</a>
                </li>
                @else
                <li>
                   <a href="{{ route('dashboard.index') }}"><i class="fa fa-bar-chart-o"></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('webhooks.index') }}"><i class="fa fa-desktop"></i> Webhooks</a>
                </li>
                <li>
                    <a href="{{ route('bots.index') }}"><i class="fa fa-reddit"></i> Bots</a>
                </li>
                <li>
                    <a href="{{ route('history.index') }}"><i class="fa fa-history"></i> Payload histories</a>
                </li>
                <li>
                    <a href="{{ route('templates.index') }}"><i class="fa fa-share-alt"></i> Templates</a>
                </li>
                @endadmin
            </ul>
            <!-- END Sidebar Navigation -->
        </div>
        <!-- END Sidebar Content -->
    </div>
    <!-- END Wrapper for scrolling functionality -->
</div>
<!-- END Main Sidebar -->
