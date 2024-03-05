<?php
use App\Models\Utils;
?> 
<div class="card  mb-4 mb-md-5 border-0">
    <!--begin::Header-->
    <div class="d-flex justify-content-between px-3 pt-2 px-md-4 border-bottom">
        <h4 style="line-height: 1; margrin: 0; " class="fs-22 fw-800">
            Persons with Disabilities by Districts
        </h4>
    </div>
    <div class="card-body py-2 py-md-3">
        <canvas id="by-districts" style="width: 100%;"></canvas>
    </div>
</div>


<script>
    $(function() {
        var config = {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: @json($data),
                    backgroundColor: [
                        '#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#00FFFF', '#FF00FF', '#C0C0C0', '#808080', '#800000', '#008000', 
'#000080', '#FFA500', '#FF4500', '#FF6347', '#FF8C00', '#FFD700', '#7FFF00', '#40E0D0', '#FF1493', '#00BFFF', 
'#7B68EE', '#228B22', '#8A2BE2', '#ADFF2F', '#6B8E23', '#9932CC', '#A52A2A', '#5F9EA0', '#D2691E', '#CD5C5C', 
'#8B008B', '#FF00FF', '#2E8B57', '#FF69B4', '#00FF7F', '#2F4F4F', '#FFA07A', '#87CEEB', '#D2B48C', '#9370DB', 
'#008080', '#FF6347', '#F08080', '#6A5ACD', '#FA8072', '#4169E1', '#FF4500', '#7FFFD4', '#FF8C00', '#00FA9A', 
'#FF00FF', '#BDB76B', '#DA70D6', '#E9967A', '#ADFF2F', '#9370DB', '#FF69B4', '#00BFFF', '#F4A460', '#98FB98', 
'#FFD700', '#FAEBD7', '#9932CC', '#7FFF00', '#FA8072', '#1E90FF', '#FF6347', '#FF4500', '#00FF00', '#FF7F50', 
'#87CEFA', '#B8860B', '#8FBC8F', '#FF69B4', '#FFDAB9', '#FF8C00', '#CD853F', '#FF1493', '#00BFFF', '#8B0000', 
'#4682B4', '#FF69B4', '#32CD32', '#FF8C00', '#00BFFF', '#FF4500', '#F0E68C', '#7CFC00', '#FF7F50', '#ADFF2F', 
'#00FFFF', '#FF1493', '#FF4500', '#7CFC00', '#FFA500', '#2E8B57', '#FF6347', '#FF00FF', '#00BFFF', '#D2691E', 
'#8B0000', '#FF8C00', '#FF1493', '#32CD32', '#FF4500', '#00BFFF', '#7CFC00', '#8A2BE2', '#F4A460', '#FF69B4', 
'#2E8B57', '#FF4500', '#FFD700', '#00BFFF', '#FF8C00', '#00FF00', '#FF69B4', '#FF1493', '#7CFC00', '#D2691E', 
'#FF6347', '#FFFF00', '#00BFFF', '#FF00FF', '#B8860B', '#FF8C00', '#2E8B57', '#FF4500', '#8B0000', '#00BFFF', 
'#FFA500', '#FF69B4', '#FF1493', '#D2691E', '#FF6347', '#9932CC', '#FFFF00', '#00BFFF', '#FF00FF', '#B8860B'

                    ],
                    label: 'Dataset 1'
                }],
                labels: @json($labels),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'left',
                    display: true, 
                },
                title: {
                    display: false,
                    text: 'Persons with Disabilities by Categories'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        };

        var ctx = document.getElementById('by-districts').getContext('2d');
        new Chart(ctx, config);
    });
</script>
