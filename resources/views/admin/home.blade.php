@extends("admin.layouts.layout") 
@section("title","Dashboard") 
@section("breadcumps")
  <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
<li class="active">Dashboard</li>
@endsection
 
@section("content")
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{ $adultCount }}</h3>

        <p>Adult Membership</p>
      </div>
      <div class="icon">
        <i class="ion ion-android-contact"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->

  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{ $childCount }}</h3>

        <p>Child Membership</p>
      </div>
      <div class="icon">
        <i class="ion ion-android-contact"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->

  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3><sup style="font-size: 20px">$</sup>{{ $totalPrice }}</h3>

        <p>Due Collected</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
      <div class="inner">
        <h3><sup style="font-size: 20px">$</sup>{{ $lastPrice }}</h3>

        <p>Last Month Collected</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->

<div class="row">
  <div class="col-md-12">
    <!-- BAR CHART -->
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Monthly Membership</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div id="legend-month-member" class="legend">
          <div class="legend-container">
              <div class="legend-item" style="">
                <div class="legend-item-color" style="background-color: #00c0ef;"></div>
                <span>Adult</span>
              </div>
              <div class="legend-item" style="">
                <div class="legend-item-color" style="background-color: #00a65a;"></div>
                <span>Child</span>
              </div>
          </div>
        </div>
        <div class="chart">
          <canvas id="month-members" style="height:230px"></canvas>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <!-- BAR CHART -->
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Monthly Revenues</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div id="legend-month-member" class="legend">
          <div class="legend-container">
            @if(auth()->user()->role_id == 1)
              <div class="legend-item" style="">
                <div class="legend-item-color" style="background-color: #00c0ef;"></div>
                <span>By User</span>
              </div>
            @endif
            
            <div class="legend-item" style="">
              <div class="legend-item-color" style="background-color: #00a65a;"></div>
              <span>By Admin</span>
            </div>
          </div>
        </div>
        <div class="chart">
          <canvas id="month-revenues" style="height:230px"></canvas>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

@endsection
 
@section("js")
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('public/adminTemplate/dist/js/pages/dashboard.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('public/adminTemplate/dist/js/demo.js') }}"></script>
  <script src="{{ asset('public/adminTemplate/bower_components/chart.js/Chart.js')}}"></script>

  <script>
    $(function() {
      var role = {!! auth()->user()->role_id !!};
      var monthlyMembers = {
        labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
          {
            fillColor           : '#00c0ef',
            strokeColor         : '#00c0ef',
            data                : {!! json_encode($mAdultMembers) !!}
          },
          {
            fillColor           : '#00a65a',
            strokeColor         : '#00a65a',
            data                : {!! json_encode($mChildMembers) !!}
          }
        ]
      };

      var revenueData = [
        {
          fillColor           : '#00a65a',
          strokeColor         : '#00a65a',
          data                : {!! json_encode($adminRevenues) !!}
        }
      ];

      if (role == 1) {
        revenueData.push({
            fillColor           : '#00c0ef',
            strokeColor         : '#00c0ef',
            data                : {!! json_encode($userRevenues) !!}
          });
      }

      var monthlyRevenues = {
        labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: revenueData
      };

      var barChartCanvas                   = $('#month-members').get(0).getContext('2d');
      var barChart                         = new Chart(barChartCanvas);

      barChart.Bar(monthlyMembers, {});

      var barChartCanvas1                   = $('#month-revenues').get(0).getContext('2d');
      var barChart1                         = new Chart(barChartCanvas1);

      barChart1.Bar(monthlyRevenues, {});
    });
  </script>
@endsection