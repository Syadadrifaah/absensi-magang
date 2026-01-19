@extends('layouts.index')

@section('judul','Dashboard')

@section('content')

    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
        <h3 class="fw-bold mb-3">Dashboard</h3>
        {{-- <h6 class="op-7 mb-2">Sistem Absensi Pegawai</h6> --}}
        </div>
        <div class="ms-md-auto py-2 py-md-0">
        {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
        <a href="#" class="btn btn-primary btn-round">Add Customer</a> --}}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
            <div class="row align-items-center">
                <div class="col-icon">
                <div
                    class="icon-big text-center icon-primary bubble-shadow-small"
                >
                    <i class="fas fa-users"></i>
                </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                    <p class="card-category">Hadir</p>
                    <h4 class="card-title">1,294</h4>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
            <div class="row align-items-center">
                <div class="col-icon">
                <div
                    class="icon-big text-center icon-info bubble-shadow-small"
                >
                    <i class="fas fa-user-check"></i>
                </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                    <p class="card-category">Tidak Hadir</p>
                    <h4 class="card-title">1303</h4>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
            <div class="row align-items-center">
                <div class="col-icon">
                <div
                    class="icon-big text-center icon-success bubble-shadow-small"
                >
                    <i class="fas fa-luggage-cart"></i>
                </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                    <p class="card-category">Izin</p>
                    <h4 class="card-title">$ 1,345</h4>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
            <div class="row align-items-center">
                <div class="col-icon">
                <div
                    class="icon-big text-center icon-secondary bubble-shadow-small"
                >
                    <i class="far fa-check-circle"></i>
                </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                    <p class="card-category">Pegawai</p>
                    <h4 class="card-title">576</h4>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
            
    <div class="row">
      <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Pie Chart</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas
                        id="pieChart"
                        style="width: 50%; height: 50%"
                        ></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Bar Chart</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

     

<script>
    var pieChart = document.getElementById("pieChart").getContext("2d");
    var myPieChart = new Chart(pieChart, {
        type: "pie",
        data: {
            labels: ["New Visitors", "Subscribers", "Active Users"],
            datasets: [{
                data: [50, 35, 15],
                backgroundColor: ["#1d7af3", "#f3545d", "#fdaf4b"],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        color: "rgb(154,154,154)",
                        font: { size: 11 },
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });

    var barChart = document.getElementById("barChart").getContext("2d");
var myBarChart = new Chart(barChart, {
    type: "bar",
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
        datasets: [{
            label: "Sales",
            backgroundColor: "rgb(23, 125, 255)",
            borderColor: "rgb(23, 125, 255)",
            data: [3,2,9,5,4,6,4,6,7,8,7,4],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: true,
                position: "top"
            },
            tooltip: {
                enabled: true
            }
        }
    }
});
</script>


@endsection