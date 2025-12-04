{{--

Gráfico de pizza simples

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
- labels: Descrição das fatias.
- values: Valores das fatias.

Estruturas de gráficos suportados:

1 => 
$data = [
    'label' => ['A', 'B', 'C'],
    'value' => [10, 15, 20],
];

--}}

<canvas
    id="{{ $name }}"
    data-title='{{$title ?? ""}}'
    data-labels='@json($labels)'
    data-values='@json($values)'
></canvas>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('{{$name}}');
        const labels = JSON.parse(canvas.dataset.labels);
        const values = JSON.parse(canvas.dataset.values);
        const title = canvas.dataset.title;
        const dataSetColors = ['#4ade80', '#60a5fa', '#f87171', '#facc15', '#a78bfa', '#34d399', '#f472b6', '#fb923c', '#c084fc', '#fcd34d'];

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: dataSetColors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>