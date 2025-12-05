{{--

Gráfico de barras simples

Como incluir o gráfico na tela:

@include('components.bar-chart', [
    'name' => 'component_name',
    'title' => 'Titulo do gráfico',
    'aspectRatio => 2
    'hasLabel' => 'true',
    'labels' => $data['labels'],
    'values' => $data['values'],
])

onde:
- name (obrigatório): Identificador do canvas, atributo "id" de componentes HTML.
- title: Título do gráfico. Se não informado não é apresentado um título ao gráfico.
- aspectRatio: Define a propoção do gráfico em relação ao texto (2 = 2:1, 3 = 3:1, 4 = 4:1 ...). Se não informado assume-se o valor 2
- hasLabel: Define se o gráfico terá Descrição das colunas no topo do gráfico.
- labels: Descrição das colunas.
- values: Valores das colunas.

Estruturas de gráficos suportados:

$data = [
    'labels' => ['2023', '2024', '2025'],
    'values' => [
        'Nome identificação #1' => [10, 10, 0],
        'Nome identificação #2' => [5, 10, 20],
        'Nome identificação #3' => [10, 0, 15],
        'Nome identificação #4' => [0, 10, 18],
    ],
];

A chave 'labels' conterá os títulos de cada coluna (como o período relativo aos valores)
A chave 'values' conterá os valores que compõem uma coluna. No exemplo anterior, a primeira coluna teria três divisões (Valores zerados são ignorados) e teria altura 25 (soma dos valores)

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
        const dataSetColors = ['#4ade80', '#60a5fa', '#f87171', '#facc15', '#a78bfa', '#34d399', '#f472b6', '#fb923c', '#c084fc', '#fcd34d'];

        const datasets = Object.entries(values).map(([label, data], index) => {
            const colorPositive = dataSetColors[index % 10];
            console.log(label, data, index);

            return {
                label: label,
                data: data,
                backgroundColor: colorPositive,
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
                plugins: {
                    title: {
                        display: true,
                        text: title
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        min:0
                    }
                }
            }
        });
    });
</script>