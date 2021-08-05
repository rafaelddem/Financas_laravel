Financas

1. Entidades:

1.1. Pessoa:
Representa o próprio usuário, mas também simula outros usuários, uma vez que estes podem não possuir contas no sistema e/ou o usuário não deseje integrar seus movimentos aos movimentos de outros usuários. Desta forma é possível identificar a qual pessoa os movimentos pertencem.

1.1.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - nome 
        - tipo string, com número máximo de 50 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro (recomenda-se utilizar o nome da pessoa).
    - ativo 
        - tipo boolean;
        - obrigatório;
        - Utilizado para definir um registro como ativo/inativo.

1.2. Carteira:
Similar a entidade pessoa, porém funciona organizando os movimentos para uma mesma pessoa. A ideia é que ele sirva para diferenciar onde o dinheiro se encontra. Por exemplo: Uma entidade pessoa 'Rafael' pode possuir como carteiras 'Banco de Santa Catarina', 'Banco Brasileiro' e 'Dinheiro guardado em casa', e dessa forma será possível saber quais valores se encontram em contas bancárias, quais estão em poupança, e quais estão em mãos.

1.2.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - nome 
        - tipo string, com número máximo de 50 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro.
    - pessoa
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'pessoas'.
    - ativo 
        - tipo boolean;
        - obrigatório;
        - Utilizado para definir um registro como ativo/inativo.

1.3. Forma de Pagamento:
Idealizado para identificar o tipo de pagamento utilizado, dinheiro (cedulas/moedas), débito, crédito ou transferência (para movimentos como emprestimos).

1.3.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - nome 
        - tipo string, com número máximo de 15 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro.
    - ativo 
        - tipo boolean;
        - obrigatório;
        - Utilizado para definir um registro como ativo/inativo.

1.4. Tipo de Movimento:
Idealizado para identificar a razão do movimento, se se trata de um movimento de compra, se é uma cobrança recorrente (energia, luz, aluguel...), etc. Também permite pré-definir a relevância de um movimento, embora o movimento possa possuir uma importância diferente da cadastrada no tipo, a relevância cadastrada no tipo será pré informada no cadastro do movimento.

1.4.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - nome 
        - tipo string, com número máximo de 50 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro.
    - relevancia 
        - tipo char;
        - valor padrão: 0 (número zero);
        - Classifica a importancia do movimento em três níveis:
            - Dispensável: Marca o movimento como algo que poderia ser evitado, um gasto desnecessário (salvo no banco como '0'). Se não informado, este será o valor salvo;
            - Desejável: Marca o movimento como necessário, porém, pode ser evitado caso sejá necessário poupar dinheiro (salvo no banco como '1');
            - Indispensável: Marca o movimento como algo que não pode ser evitado ou adiado, como aluguél e conta de luz (salvo no banco como '2').
    - ativo 
        - tipo boolean;
        - Utilizado para definir um registro como ativo/inativo.

1.5 Movimento:
Utilizado para salvar os registros de movimentos

1.5.1 Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - numeroParcelas
        - tipo integer;
        - valor padrão: 0 (número zero);
        - usado para registrar o número de parcelas em que o movimento foi dividido ;
    - dataMovimento
        - tipo date;
        - obrigatório;
        - utilizado para registrar a data em que o movimento foi feito.
    - tipoMovimento
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'tipo_movimento'.
    - valorInicial
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor inicial do movimento.
    - valorDesconto
        - tipo decimal, com tamanho 6 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do desconto dado ao movimento.
    - valorArredondamento
        - tipo decimal, com tamanho 5 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do arredondamento dado ao movimento.
    - valorFinal
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor final do movimento (antes da tributação). Deve respeitar o cálculo: valorInicial - valorDesconto - valorArredondamento.
    - relevancia
        - tipo char;
        - valor padrão: 0 (número zero);
        - Classifica a importancia do movimento. Inicialmente segue o valor cadastrado no tipo de movimento informado, mas pode ser alterado:
            - Dispensável: Marca o movimento como algo que poderia ser evitado, um gasto desnecessário (salvo no banco como '0'). Se não informado, este será o valor salvo;
            - Desejável: Marca o movimento como necessário, porém, pode ser evitado caso seja necessário poupar dinheiro (salvo no banco como '1');
            - Indispensável: Marca o movimento como algo que não pode ser evitado ou adiado, como aluguél e conta de luz (salvo no banco como '2').
    - descricao
        - tipo string;
        - campo para inserir uma descrição para o movimento

1.6 Parcela:

1.6.1 Atributos:
    - movimento: 
        - tipo integer;
        - chave primária (junto ao atributo 'parcela');
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'movimento'.
    - parcela: 
        - tipo integer;
        - chave primária (junto ao atributo 'movimento');
        - obrigatório.
    - dataVencimento
        - tipo date;
        - obrigatório;
        - utilizado para registrar a data limite para efetuar o pagamento do movimento.
    - dataPagamento
        - tipo date;
        - utilizado para registrar a data em que o movimento foi pago.
    - valorInicial
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor inicial da parcela.
    - valorDesconto
        - tipo decimal, com tamanho 6 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do desconto dado a parcela.
    - valorJuros
        - tipo decimal, com tamanho 5 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do juros aplicado a parcela.
    - valorArredondamento
        - tipo decimal, com tamanho 5 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do arredondamento aplicado a parcela.
    - valorFinal
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor final do movimento. Deve respeitar o cálculo: valorInicial - valorDesconto - valorArredondamento.
    - formaPagamento
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'forma_pagamentos'.
    - carteiraOrigem
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'carteiras';
        - identificará de qual carteira onde o dinheiro será subtraído.
    - carteiraDestino
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'carteiras';
        - identificará em qual carteira onde o dinheiro será somado.

2. Dados pré definidos

2.1. Pessoa:

- Eu

2.2. Carteira:

- Casa

2.3. Forma de Pagamento:

- Dinheiro
- Crédito
- Débito
- Transferencia

2.4. Tipo de Movimento:

- Taxa (para saque de crédito)
- IOF
- INSS



Valores

