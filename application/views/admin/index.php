<!DOCTYPE html>

<html lang="en">



<head>
    <meta name="robots" content="noindex,nofollow" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>VnEsports Admin</title>
    <link rel="stylesheet" href="/assets/css/admin/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="/assets/css/admin/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/css/admin/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="/images/favicon.png" />



</head>

<script src="/assets/js/jquery.min.js"></script>

<script src="/assets/js/jquery.validate.min.js"></script>

<script src="/assets/css/admin/vendors/js/vendor.bundle.base.js"></script>

<body>
    <style>
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
                <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
                    <a class="navbar-brand brand-logo" href="/"><img src="/images/logo.png" alt="logo" /></a>
                    <a class="navbar-brand brand-logo-mini" href="/"><img src="/images/logo.png" alt="logo" /></a>
                    <button class="navbar-toggler navbar-toggler align-self-center btn_menu" type="button" data-toggle="minimize">
                        <span class="typcn typcn-th-menu"></span>
                    </button>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="typcn typcn-th-menu"></span>
                </button>
            </div>
        </nav>
        <nav class="navbar-breadcrumb col-xl-12 col-12 d-flex flex-row p-0">

        </nav>
        <div class="container-fluid page-body-wrapper">
            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">
                            <i class="typcn typcn-device-desktop menu-icon"></i>
                            <span class="menu-title">Trang chủ</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/member">
                            <i class="typcn typcn-film menu-icon"></i>
                            <span class="menu-title">Thành viên</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="/admin/his_card/" aria-expanded="false" aria-controls="ui-basics">
                            <i class="typcn typcn-document-text menu-icon"></i>
                            <span class="menu-title">Giao dịch</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php
                    if (isset($content)) {
                        $this->load->view($content);
                    }
                    ?>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2020 <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrapdash</a>. All rights reserved.</span>
                                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted">Free <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank">Bootstrap dashboard</a> templates from Bootstrapdash.com</span>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
    </div>




    <!-- base:js -->
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="/assets/js/admin/off-canvas.js"></script>
    <script src="/assets/js/admin/hoverable-collapse.js"></script>
    <script src="/assets/js/admin/template.js"></script>
    <script src="/assets/js/admin/settings.js"></script>
    <script src="/assets/js/admin/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="/assets/js/admin/dashboard.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>


    <!-- End custom js for this page-->
    <script src="/assets/js/sweetalert.min.js"></script>
    <script src="/assets/js/jquery.validate.min.js"></script>