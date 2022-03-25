<?php

    $menu = $this->db->select('*')
                    ->from('menu1')
                    ->order_by('urut', "ASC")
                    ->where([
                        'status' => 't',
                        'root' => 't'
                    ])->get();

?>
<!--
=========================================================
* Argon Dashboard 2 - v2.0.1
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <title><?= $title." - ".$company->company ?></title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?= base_url('/assets/css/nucleo-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('/assets/css/nucleo-svg.css') ?>" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="<?= base_url('/assets/css/nucleo-svg.css') ?>" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= base_url('/assets/css/argon-dashboard.css?v=2.0.1') ?>" rel="stylesheet" />
    <link id="pagestyle" href="<?= base_url('/assets/css/custom.css') ?>" rel="stylesheet" />
</head>

<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
        <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
            <!-- <img src="./assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo"> -->
            <span class="ms-1 font-weight-bold"><?= $company->company ?></span>
        </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <?php foreach($menu->result() as $row){ ?>
                <?php if($row->dropdown == 'f'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($this->uri->segment(1) == $row->url) ? 'active' : '' ?>" href="<?= site_url($row->url) ?>">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="<?= $row->icon ?> text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1"><strong><?= $row->menu ?></strong></span>
                        </a>
                    </li>
                <?php elseif($row->dropdown == 't' && $row->root == 't'): 
                    $dropdownData = $this->db->order_by('urut', "ASC")->get_where('menu1', [
                        'dropdown' => 't',
                        'root' => 'f',
                        'root_id' => $row->id
                    ])->result(); 
                    
                    $menusDropdown = [];
                    foreach($dropdownData as $dd){
                        array_push($menusDropdown, $dd->url);
                    }
                ?>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#applicationsExamples<?= $row->id ?>" class="nav-link <?= (in_array($this->uri->segment(1), $menusDropdown)) ? 'active' : '' ?>" aria-controls="applicationsExamples" role="button" aria-expanded="false">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="<?= $row->icon ?> text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1"><strong><?= $row->menu ?></strong></span>
                        </a>
                        <div class="collapse <?= (in_array($this->uri->segment(1), $menusDropdown)) ? 'show' : '' ?>" id="applicationsExamples<?= $row->id ?>">
                            <ul class="nav ms-2">
                                <?php foreach($dropdownData as $rowD){ ?>
                                    <li class="nav-item ">
                                        <a class="nav-link <?= ($this->uri->segment(1) == $rowD->url) ? 'active' : '' ?>" href="<?= site_url($rowD->url) ?>">
                                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                                <i class="<?= $rowD->icon ?> text-dark text-sm opacity-10"></i>
                                            </div>
                                            <span class="sidenav-normal"><?= $rowD->menu ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>
            <?php } ?>
            <li class="nav-item">
            <a class="nav-link " href="<?= site_url('auth/logout') ?>">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-collection text-danger text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1"><strong>Logout</strong></span>
            </a>
            </li>
        </ul>
        </div>
    </aside>
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm text-white">Pages</li>
                <li class="breadcrumb-item text-sm text-white active" aria-current="page"><?= $title ?></li>
            </ol>
            <h6 class="font-weight-bolder text-white mb-0"><?= $title ?></h6>
            </nav>
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group">
                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                <input type="text" class="form-control" placeholder="Type here...">
                </div>
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                    <i class="fa fa-user me-sm-1"></i>
                    <span class="d-sm-inline d-none">Sign In</span>
                </a>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    </div>
                </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-white p-0">
                    <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                </a>
                </li>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bell cursor-pointer"></i>
                </a>
                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                    <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="d-flex py-1">
                        <div class="my-auto">
                            <img src="./assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">
                            <span class="font-weight-bold">New message</span> from Laur
                            </h6>
                            <p class="text-xs text-secondary mb-0">
                            <i class="fa fa-clock me-1"></i>
                            13 minutes ago
                            </p>
                        </div>
                        </div>
                    </a>
                    </li>
                    <li class="mb-2">
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="d-flex py-1">
                        <div class="my-auto">
                            <img src="./assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">
                            <span class="font-weight-bold">New album</span> by Travis Scott
                            </h6>
                            <p class="text-xs text-secondary mb-0">
                            <i class="fa fa-clock me-1"></i>
                            1 day
                            </p>
                        </div>
                        </div>
                    </a>
                    </li>
                    <li>
                    <a class="dropdown-item border-radius-md" href="javascript:;">
                        <div class="d-flex py-1">
                        <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                            <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>credit-card</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g transform="translate(453.000000, 454.000000)">
                                    <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                    <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                    </g>
                                </g>
                                </g>
                            </g>
                            </svg>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">
                            Payment successfully completed
                            </h6>
                            <p class="text-xs text-secondary mb-0">
                            <i class="fa fa-clock me-1"></i>
                            2 days
                            </p>
                        </div>
                        </div>
                    </a>
                    </li>
                </ul>
                </li>
            </ul>
            </div>
        </div>
        </nav>
        <?php 
            if(@$page)
                $this->load->view('pages/'.@$page);
        ?>
    </main>
    <!--   Core JS Files   -->
    <script src="<?= base_url('/assets/js/core/popper.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/core/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/plugins/smooth-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('/assets/js/plugins/chartjs.min.js') ?>"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
        new Chart(ctx1, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#5e72e4",
            backgroundColor: gradientStroke1,
            borderWidth: 3,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
            legend: {
                display: false,
            }
            },
            interaction: {
            intersect: false,
            mode: 'index',
            },
            scales: {
            y: {
                grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
                },
                ticks: {
                display: true,
                padding: 10,
                color: '#fbfbfb',
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
                }
            },
            x: {
                grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
                },
                ticks: {
                display: true,
                color: '#ccc',
                padding: 20,
                font: {
                    size: 11,
                    family: "Open Sans",
                    style: 'normal',
                    lineHeight: 2
                },
                }
            },
            },
        },
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script src="<?= base_url('/assets/js/argon-dashboard.min.js?v=2.0.1') ?>"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- Datatables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable( {
                ajax: '<?= site_url($this->uri->segment(1) . '/table') ?>',
                lengthChange: false,
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy me-2"></i> Copy',
                        className: 'btn btn-sm btn-primary me-1',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel me-2"></i> Excel',
                        className: 'btn btn-sm btn-primary me-1',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf me-2"></i> PDF',
                        className: 'btn btn-sm btn-primary me-1',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-eye me-2"></i> Column Visibility &nbsp;&nbsp;&nbsp;',
                        className: 'btn btn-sm btn-primary me-1',
                        init: function(api, node, config) {
                            $(node).removeClass('btn-secondary')
                        }
                    },
                ],
                columnDefs: [
                    {
                        "className": "dt-center", 
                        "targets": "_all"
                    }
                ],
                pagingType: 'full_numbers',
                pageLength: 5,
                language: {
                    oPaginate: {
                        sNext: '<i class="fas fa-chevron-right"></i>',
                        sPrevious: '<i class="fas fa-chevron-left"></i>',
                        sFirst: '<i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i>',
                        sLast: '<i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i>'
                    }
                },   
                initComplete: function () {
                    table.buttons().container()
                        .appendTo( $('.col-md-6:eq(0)', table.table().container() ) );
                }
            });
        });
    </script>
</body>

</html>