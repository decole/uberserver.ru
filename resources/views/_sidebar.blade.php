    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset("img/decole.jpeg")}}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Hello, Decole</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">Основное</li>
                <li class="{{ (request()->is('/')) ? 'active' : '' }}"><a href="/"><i class="fa fa-shirtsinbulk"></i> <span>Main</span></a></li>
                <li class="{{ (request()->is('sensors*')) ? 'active' : '' }}"><a href="/sensors"><i class="fa fa-dashboard"></i> <span>Sensors</span></a></li>
                <li class="{{ (request()->is('watering*')) ? 'active' : '' }}"><a href="/watering"><i class="fa fa-dashboard"></i> <span>Watering</span></a></li>
                <li class="{{ (request()->is('home_swifts*')) ? 'active' : '' }}"><a href="/home_swifts"><i class="fa fa-dashboard"></i> <span>Swifts</span></a></li>
                <li class="{{ (request()->is('chart*')) ? 'active' : '' }}"><a href="/chart"><i class="fa fa-bar-chart"></i> <span>Charts</span></a></li>
                <li><a href="https://blog.uberserver.ru/"><i class="fa fa-link"></i> <span>Blog</span></a></li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
