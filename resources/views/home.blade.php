@extends('layouts.hasLogin')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Dashboard</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="booksTotal">*</h3>
                    <p>Total Books</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ url('book') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="authorsTotal">*</h3>
                    <p>Book's Authors</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <a href="{{ url('author') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3 id="publishersTotal">*</h3>
                    <p>Book's Publishers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-industry"></i>
                </div>
                <a href="{{ url('publisher') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 mb-2">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="membersTotal">*</h3>
                    <p>Total Members</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ url('member') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Data Books by Authors</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="authorChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 318px;"
                        width="318" height="250" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-teal">
                    <h3 class="card-title">Data Books by publishers</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="publisherChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 318px;"
                        width="318" height="250" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Members by gender</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="memberChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 318px;"
                        width="318" height="250" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
<script>
    function randColor() {
        return "#" + Math.floor(Math.random()*16777215).toString(16);
    }
    //-- GET DATA --
    $.get("{{ url('/book/api') }}", function (data) {
        books = data;
        $("#booksTotal").text(books.length);
    });
    $.get("{{ url('/publisher/api') }}", function (data) {
        publishers = data.data;
        $("#publishersTotal").text(publishers.length);
        var publisherChart = $('#publisherChart').get(0).getContext('2d');
        publisherLabel = [];
        publisherData = [];
        publisherBackround = [];
        for (const key in publishers) {
            if (Object.hasOwnProperty.call(publishers, key)) {
                const publisher = publishers[key];
                publisherLabel.push(publisher.name);
                publisherData.push(publisher.books);
                publisherBackround.push(randColor())
            }
        }
        new Chart(publisherChart, {
            type: 'doughnut',
            data: {
                labels: publisherLabel,
                datasets: [{
                    data: publisherData,
                    backgroundColor: publisherBackround,
                }]
            },
            
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                }
            }
        })
    });
    $.get("{{ url('/author/api') }}", function (data) {
        authors = data.data;
        $("#authorsTotal").text(authors.length);
        var authorChart = $('#authorChart').get(0).getContext('2d');
        authorLabel = [];
        authorData = [];
        authorBackround = [];
        for (const key in authors) {
            if (Object.hasOwnProperty.call(authors, key)) {
                const author = authors[key];
                authorLabel.push(author.name);
                authorData.push(author.books);
                authorBackround.push(randColor())
            }
        }
        new Chart(authorChart, {
            type: 'doughnut',
            data: {
                labels: authorLabel,
                datasets: [{
                    data: authorData,
                    backgroundColor: authorBackround,
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                }
            }
        })
    });
    $.get("{{ url('/member/api') }}", function (data) {
        members = data.data;
        $("#membersTotal").text(members.length);
        var memberChart = $('#memberChart').get(0).getContext('2d');
        male = 0;
        female = 0;
        for (const key in members) {
            if (Object.hasOwnProperty.call(members, key)) {
                const member = members[key];
                if (member.gender == "L") {
                    male++;
                } else {
                    female++;
                }
            }
        }
        new Chart(memberChart, {
            type: 'doughnut',
            data: {
                labels: ["Male", "Female"],
                datasets: [{
                    data: [male, female],
                    backgroundColor: ["#3792cb", "#f44c8c"],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    position: "bottom"
                }
            }
        })
    });
</script>
@endsection
