{{--

Gráfico de barras simples

Como incluir o gráfico na tela:

@include('components.bar-chart', [
    'name' => 'component_name',
    'title' => 'Titulo do gráfico',
    'aspectRatio => 2
    'hasLabel' => 'true',
    'labels' => $data['label'],
    'values' => $data['value'],
])

onde:
- name (obrigatório): Identificador do canvas, atributo "id" de componentes HTML.
- title: Título do gráfico. Se não informado não é apresentado um título ao gráfico.
- aspectRatio: Define a propoção do gráfico em relação ao texto (2 = 2:1, 3 = 3:1, 4 = 4:1 ...). Se não informado assume-se o valor 2
- hasLabel: Define se o gráfico terá Descrição das colunas no topo do gráfico.
- labels: Descrição das colunas.
- values: Valores das colunas.

Estruturas de gráficos suportados:

1 => 
$data = [
    'label' => ['A', 'B', 'C'],
    'value' => [
        [10, -10, 20],
    ],
];

 30 |       |       |       |
 20 |       |       |  |||  |
 10 |  |||  |       |  |||  |
 00 |--|||--|--|||--|--|||--|
-10 |       |  |||  |       |
-20 |       |       |       |
        A       B       C


2 => 
$data = [
    'label' => ['A'],
    'value' => [
        'Z' => [20],
        'Y' => [-10],
    ],
];

      [] Z  [] Y

 30 |           |
 20 |  |||      |
 10 |  |||      |
 00 |--|||-|||--|
-10 |      |||  |
-20 |           |
          A


3 => 
$data = [
    'label' => ['A', 'B', 'C'],
    'value' => [
        'Z' => [30, 20, 30],
        'Y' => [10, 20, 20],
        'X' => [20, 00, 10],
    ],
];

                    [] Z  [] Y  [] X

 40 |               |               |               |
 30 |  |||          |               |               |
 20 |  |||     |||  |  ||| |||      |  |||          |
 10 |  ||| ||| |||  |  ||| |||      |  |||          |
 00 |--|||-|||-|||--|--|||-|||------|--|||-----|||--|
            A               B               C

--}}

<canvas
    id="{{ $name }}"
    data-title='{{$title ?? ""}}'
    data-aspect-ratio='{{$aspectRatio ?? 2}}'
    data-has-label='{{ isset($hasLabel) && $hasLabel }}'
    data-labels='@json($labels)'
    data-values='@json($values)'
></canvas>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('{{$name}}');
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        const title = canvas.dataset.title;
        const hasLabel = canvas.dataset.hasLabel;
        const aspectRatio = canvas.dataset.aspectRatio;

        const labels = JSON.parse(canvas.dataset.labels);
        const values = JSON.parse(canvas.dataset.values);
        const dataSetColors = [
            ['#4ade80', '#16a34a'],
            ['#60a5fa', '#2563eb'],
            ['#f87171', '#b91c1c'],
            ['#facc15', '#ca8a04'],
            ['#a78bfa', '#7c3aed'],
            ['#34d399', '#059669'],
            ['#f472b6', '#be185d'],
            ['#fb923c', '#c2410c'],
            ['#c084fc', '#9333ea'],
            ['#fcd34d', '#b45309']
        ];

        const datasets = Object.entries(values).map(([label, data], index) => {
            const [colorPositive, colorNegative] = dataSetColors[index];

            return {
                label: label,
                data: data,
                backgroundColor: function(context) {
                    return context.raw < 0 ? colorNegative : colorPositive;
                },
                barThickness: 40,
                maxBarThickness: 40,
            }
        });

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: aspectRatio,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    },
                    legend: {
                        display: hasLabel
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: (value) => {
                            return new Intl.NumberFormat('pt-BR', {
                                style: 'currency',
                                currency: 'BRL',
                                minimumFractionDigits: 2
                            }).format(value);
                        },
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        color: '#000'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                            grid: {
                                lineWidth: (ctx) => ctx.tick.value === 0 ? 3 : 1,
                            }
                    }
                },
            },
            plugins: [ChartDataLabels]
        });
    });
</script>