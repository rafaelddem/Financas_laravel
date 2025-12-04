{{--

Gráfico de linhas simples

Como incluir o gráfico na tela:

@include('components.pie-chart', [
    'name' => 'component_name',
    'title' => 'Titulo do gráfico',
    'labels' => $data['labels'],
    'values' => $data['values']
])

onde:
- name (obrigatório): Identificador do canvas, atributo "id" de componentes HTML.
- title: Título do gráfico. Se não informado não é apresentado um título ao gráfico.
- labels: Descrição das linhas.
- values: Valores das linhas.

Estruturas de gráficos suportados:

$data = [
    'label' => ['A', 'B', 'C'],
    'value' => [
        'Nome da Linha' => [10, 15, 20],
    ]
];

--}}

<canvas
    id="{{ $name }}"
    data-title='{{$title ?? ""}}'
    data-aspect-ratio='{{$aspectRatio ?? 2}}'
    data-labels='@json($labels)'
    data-values='@json($values)'
></canvas>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('{{$name}}');
        const title = canvas.dataset.title;
        const aspectRatio = canvas.dataset.aspectRatio;
        const labels = JSON.parse(canvas.dataset.labels);
        const values = JSON.parse(canvas.dataset.values);
        const dataSetColors = [
            ['#4ade80', 'rgba(74, 222, 128, 0.2)', 'rgba(22, 163, 74, 0.2)'],
            ['#60a5fa', 'rgba(96, 165, 250, 0.2)', 'rgba(37, 99, 235, 0.2)'],
            ['#f87171', 'rgba(248, 113, 113, 0.2)', 'rgba(185, 28, 28, 0.2)'],
            ['#facc15', 'rgba(250, 204, 21, 0.2)', 'rgba(202, 138, 4, 0.2)'],
            ['#a78bfa', 'rgba(167, 139, 250, 0.2)', 'rgba(124, 58, 237, 0.2)'],
            ['#34d399', 'rgba(52, 211, 153, 0.2)', 'rgba(5, 150, 105, 0.2)'],
            ['#f472b6', 'rgba(244, 114, 182, 0.2)', 'rgba(190, 24, 93, 0.2)'],
            ['#fb923c', 'rgba(251, 146, 60, 0.2)', 'rgba(194, 65, 12, 0.2)'],
            ['#c084fc', 'rgba(192, 132, 252, 0.2)', 'rgba(147, 51, 234, 0.2)'],
            ['#fcd34d', 'rgba(252, 211, 77, 0.2)', 'rgba(180, 83, 9, 0.2)']
        ];

        const datasets = Object.entries(values).map(([label, data], index) => {
            const [colorBorder, colorPositive, colorNegative] = dataSetColors[index];

            return {
                label: label,
                data: data,
                borderColor: colorBorder,
                backgroundColor: function(context) {
                    return context.raw < 0 ? colorNegative : colorPositive;
                },
                fill: true,
                tension: 0.3
            }
        });

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 4,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>