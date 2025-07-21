@extends('layout')

@section('page_content')
        <section class="presentation">
            <h1>Bem-vindo ao Sistema Financeiro</h1>
            <p>Este sistema ajuda a gerenciar seus dados financeiros de forma eficiente e segura.</p>
        </section>

        <section class="cards-container">
            <div class="card">
                <h2 class="card-title">Total Receitas</h2>
                <p class="card-value">R$ 120.000,00</p>
            </div>
            <div class="card">
                <h2 class="card-title">Total Despesas</h2>
                <p class="card-value">R$ 45.500,00</p>
            </div>
            <div class="card">
                <h2 class="card-title">Lucro Líquido</h2>
                <p class="card-value">R$ 74.500,00</p>
            </div>
        </section>

        <section class="presentation">
            <h2 class="card-title">Preencha o formulário</h2>
            <form action="#" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>

                <label for="idade">Idade:</label>
                <input type="number" id="idade" name="idade" min="1" max="120" required>

                <label>
                    <input type="checkbox" name="newsletter">
                    Quero receber novidades por e-mail
                </label>

                <label>Gênero:</label>
                <label><input type="radio" name="genero" value="masculino"> Masculino</label>
                <label><input type="radio" name="genero" value="feminino"> Feminino</label>
                <label><input type="radio" name="genero" value="outro"> Outro</label>

                <label for="pais">País:</label>
                <select id="pais" name="pais">
                    <option value="brasil">Brasil</option>
                    <option value="portugal">Portugal</option>
                    <option value="eua">Estados Unidos</option>
                    <option value="outro">Outro</option>
                </select>

                <button type="submit">Enviar</button>
            </form>
        </section>

        <section class="presentation">
            <h2 class="card-title">Lista de Valores</h2>
            <table>
                <tr>
                    <td>Valor A1</td>
                    <td>
                        <button>Editar</button>
                        <button>Excluir</button>
                        <button>Ver</button>
                    </td>
                </tr>
                <tr>
                    <td>Valor B2</td>
                    <td>
                        <button>Editar</button>
                        <button>Excluir</button>
                        <button>Ver</button>
                    </td>
                </tr>
                <tr>
                    <td>Valor C3
                        <span>
                            <button>Editar</button>
                            <button>Excluir</button>
                            <button>Ver</button>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Valor D4</td>
                    <td>
                        <button>Editar</button>
                        <button>Excluir</button>
                        <button>Ver</button>
                    </td>
                </tr>
            </table>
        </section>
@endsection