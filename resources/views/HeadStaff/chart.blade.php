@extends('layout.layout')

@section('content')
<div class="container">
    <div id="chart" class="d-flex justify-content-center align-items-center text-center" style="max-width: 50rem;"></div>
</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
  chart: {
    type: 'bar'
  },
  plotOptions: {
    bar: {
      horizontal: false
    }
  },
  series: [{
    data: [{
      x: 'Report',
      y: {{$report}}
    }, {
      x: 'Response',
      y: {{$response}}
    }]
  }],
  colors: ['#FF5733', '#33FF57']
}

var chart = new ApexCharts(document.querySelector("#chart"), options);

chart.render();
</script>
@endsection