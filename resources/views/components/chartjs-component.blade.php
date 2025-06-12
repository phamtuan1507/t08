<div {{ $attributes }}>
    <canvas id="{{ $chart->id }}" width="{{ $chart->width }}" height="{{ $chart->height }}"></canvas>
    <script>
        const ctx = document.getElementById('{{ $chart->id }}').getContext('2d');
        new Chart(ctx, {
            type: '{{ $chart->type }}',
            data: {
                labels: {{ json_encode($chart->labels) }},
                datasets: {{ json_encode($chart->datasets) }}
            },
            options: {{ json_encode($chart->options) }}
        });
    </script>
</div>
