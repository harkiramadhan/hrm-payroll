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
    <style>
        .w-5px{
            width: 5px!important;
        }

        .w-10p{
            width: 10%!important;
        }
    </style>
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        </div>
        <div class="collapse navbar-collapse w-100 h-auto mt-n6" id="sidenav-collapse-main">
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
                        'status' => 't',
                        'dropdown' => 't',
                        'root' => 'f',
                        'root_id' => $row->id
                    ])->result(); 
                ?>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#menus<?= $row->id ?>" class="nav-link <?= ($this->uri->segment(1) == $row->url) ? 'active' : '' ?>" aria-controls="applicationsExamples" role="button" aria-expanded="false">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="<?= $row->icon ?> text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1"><strong><?= $row->menu ?></strong></span>
                        </a>
                        <div class="collapse <?= ($this->uri->segment(1) == $row->url) ? 'show' : '' ?>" id="menus<?= $row->id ?>">
                            <ul class="nav ms-2">
                                <?php foreach($dropdownData as $rowD){ ?>
                                    <li class="nav-item ">
                                        <a class="nav-link <?= ($this->uri->segment(1) == $row->url && $this->uri->segment(2) == $rowD->url) ? 'active' : '' ?>" href="<?= site_url($row->url."/".$rowD->url) ?>">
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

                </div>
                <ul class="navbar-nav  justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                            <i class="fa fa-user me-2"></i>
                            <span class=""><?= $this->session->userdata('username') ?></span>
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
                </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-2">
            <?php if($this->session->userdata('success')): ?>
                <div class="alert bg-success text-white alert-session" role="alert">
                    <strong><?= $this->session->flashdata("success") ?></strong>
                </div>
            <?php endif; ?>

            <?php if($this->session->userdata('error')): ?>
                <div class="alert bg-danger text-white alert-session" role="alert">
                    <strong><?= $this->session->flashdata("error") ?></strong>
                </div>
            <?php endif; ?>
        </div>
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
        var baseUrl = '<?= base_url('') ?>'
        var siteUrl = '<?= site_url('') ?>'
        var nowUrl  = siteUrl + $(location).attr('href').split("/").splice(4, 10).join("/")
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-session').fadeOut('fast')
            }, 2000)

            var table = $('#example').DataTable( {
                ajax: nowUrl + '/table',
                autoWidth: false,
                lengthChange: false,
                scrollX: true,
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
                        "targets": 0
                    }
                ],
                pagingType: 'full_numbers',
                pageLength: 10,
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
            })
        })
    </script>

    <?php 
        if(@$ajax) {
            foreach(@$ajax as $a){
                echo "<script src='".base_url('assets/js/custom/' . $a).".js'></script>";
            }
        }
            
    ?>
</body>

</html>