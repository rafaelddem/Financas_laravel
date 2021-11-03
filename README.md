Financas

1. Entidades:

1.1. Proprietário (Owner):
Representa o próprio usuário, mas também simula outros usuários, desta forma é possível identificar a qual pessoa os movimentos pertencem.

1.1.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - name 
        - tipo string, com número máximo de 50 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro (recomenda-se utilizar o nome da pessoa).
    - active 
        - tipo boolean;
        - obrigatório;
        - Utilizado para definir um registro como ativo/inativo.

1.2. Carteira (Wallet):
Similar a entidade Proprietário, porém, funciona organizando os valores do mesmo, como que em carteiras, a ideia é que ele sirva para identifiacar onde o dinheiro se encontra. Por exemplo: Uma entidade Proprietário 'Rafael' pode possuir como Carteira 'Banco de Santa Catarina', 'Banco Brasileiro' e 'Dinheiro guardado em casa', e assim saber quais valores se encontram em contas bancárias, quais estão em poupança, e quais estão em mãos.

1.2.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - name 
        - tipo string, com número máximo de 50 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro.
    - owner
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'pessoas'.
    - main_wallet 
        - tipo boolean;
        - obrigatório;
        - Utilizado para definir um registro como carteira principal (a pessoa a qual pertence).
    - active 
        - tipo boolean;
        - obrigatório;
        - Utilizado para definir um registro como ativo/inativo.

1.2.2 Particularidades:
    - Quando criado, ou alterado, se a carteira for marcada como inativa, a campo "principal" deve ser salvo como falso, mesmo que o cliente tenha definido como verdadeiro

1.3. Forma de Pagamento (Payment Mathod):
Idealizado para identificar o tipo de pagamento utilizado, se em dinheiro (cedulas/moedas), débito, crédito ou transferência (para movimentos como emprestimos).

1.3.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - name 
        - tipo string, com número máximo de 15 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro.
    - active 
        - tipo boolean;
        - obrigatório;
        - Utilizado para definir um registro como ativo/inativo.

1.4. Tipo de Movimento (Movement Type):
Idealizado para identificar a razão do movimento, se se trata de um movimento de compra, se é uma cobrança recorrente (energia, luz, aluguel...), etc. Também permite pré-definir a relevância de um movimento.

1.4.1. Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - name 
        - tipo string, com número máximo de 50 caracteres;
        - obrigatório;
        - Identificador literal do registro, diferente do atributo 'id' este serve apenas para melhor descrever o registro.
    - relevance 
        - tipo char;
        - valor padrão: 0 (número zero);
        - Classifica a importancia do movimento em três níveis:
            - Dispensável: Marca o movimento como algo que poderia ser evitado, um gasto desnecessário (salvo no banco como '0'). Se não informado, este será o valor salvo;
            - Desejável: Marca o movimento como necessário, porém, pode ser evitado caso sejá necessário poupar dinheiro (salvo no banco como '1');
            - Indispensável: Marca o movimento como algo que não pode ser evitado ou adiado, como aluguél e conta de luz (salvo no banco como '2').
    - active 
        - tipo boolean;
        - Utilizado para definir um registro como ativo/inativo.

1.4.2. Particularidades:
A relevância informada no tipo de movimento, por padrão também será utilizada no movimento. Entretanto, é possível informar uma relevância diferente no movimento.

1.5 Movimento (movement):
Utilizado para salvar os registros de movimentos

1.5.1 Atributos:
    - id
        - tipo integer;
        - chave primária;
        - possui auto_increment;
        - obrigatório.
    - title
        - tipo string;
        - obrigatório;
        - usado como uma breve descricao do movimento. Se não for informado, será utilizado o valor do campo 'nome' do Tipo de Movimento informado;
    - installments
        - tipo integer;
        - valor padrão: 0 (número zero);
        - usado para registrar o número de parcelas em que o movimento foi dividido ;
    - movement_date
        - tipo date;
        - obrigatório;
        - utilizado para registrar a data em que o movimento foi feito.
    - movement_type
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'tipo_movimento'.
    - gross_value
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor inicial do movimento.
    - descount_value
        - tipo decimal, com tamanho 6 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do desconto dado ao movimento.
    - rounding_value
        - tipo decimal, com tamanho 5 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do arredondamento dado ao movimento.
    - net_value
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor final do movimento (antes da tributação). Deve respeitar o cálculo: valorInicial - valorDesconto - valorArredondamento.
    - relevance
        - tipo char;
        - valor padrão: 0 (número zero);
        - Classifica a importancia do movimento. Inicialmente segue o valor cadastrado no tipo de movimento informado, mas pode ser alterado:
            - Dispensável: Marca o movimento como algo que poderia ser evitado, um gasto desnecessário (salvo no banco como '0'). Se não informado, este será o valor salvo;
            - Desejável: Marca o movimento como necessário, porém, pode ser evitado caso seja necessário poupar dinheiro (salvo no banco como '1');
            - Indispensável: Marca o movimento como algo que não pode ser evitado ou adiado, como aluguél e conta de luz (salvo no banco como '2').
    - description
        - tipo string;
        - campo para inserir uma descrição para o movimento

1.6 Parcela (installment):

1.6.1 Atributos:
    - movement: 
        - tipo integer;
        - chave primária (junto ao atributo 'parcela');
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'movimento'.
    - installment_number: 
        - tipo integer;
        - chave primária (junto ao atributo 'movimento');
        - obrigatório.
    - duo_date:
        - tipo date;
        - obrigatório;
        - utilizado para registrar a data limite para efetuar o pagamento do movimento.
    - payment_date:
        - tipo date;
        - utilizado para registrar a data em que o movimento foi pago.
    - gross_value:
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor inicial da parcela.
    - descount_value:
        - tipo decimal, com tamanho 6 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do desconto dado a parcela.
    - interest_value:
        - tipo decimal, com tamanho 5 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do juros aplicado a parcela.
    - rounding_value:
        - tipo decimal, com tamanho 5 e duas casas decimais;
        - valor padrão: 0.0 (número zero, com casas decimais);
        - utilizado para registrar o valor do arredondamento aplicado a parcela.
    - net_value:
        - tipo decimal, com tamanho 8 e duas casas decimais;
        - obrigatório;
        - utilizado para registrar o valor final do movimento. Deve respeitar o cálculo: valorInicial - valorDesconto - valorArredondamento.
    - payment_method:
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'forma_pagamentos'.
    - source_wallet:
        - tipo integer;
        - obrigatório;
        - chave estrangeira com o atributo 'id' da tabela 'carteiras';
        - identificará de qual carteira onde o dinheiro será subtraído.
    - destination_wallet:
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

