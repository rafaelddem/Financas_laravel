document.addEventListener("DOMContentLoaded", function () {
    loadInstallmentForm();
    listCards();
});

const transaction_type = document.getElementById('transaction_type_id');
transaction_type.addEventListener('change', function () {
    const selectedOption = transaction_type.options[transaction_type.selectedIndex];
    document.getElementById('relevance').value = selectedOption.dataset.relevance;
});

document.getElementById('transaction_date').addEventListener('change', function () {
    document.getElementById('processing_date').value = document.getElementById('transaction_date').value;
});

function getPaymentType() {
    const payment_method = document.getElementById('payment_method_id');
    const selectedOption = payment_method.options[payment_method.selectedIndex];
    return selectedOption.dataset.type;
}

document.getElementById('payment_method_id').addEventListener('change', function () {
    loadInstallmentForm();
    listCards();
});

function loadInstallmentForm() {
    switch (getPaymentType()) {
        case 'debit':
            document.getElementById('div_card').style.display = 'block';
            document.getElementById('div_installments').style.display = 'none';
            addInstallmentFields(0);
            break;

        case 'credit':
            document.getElementById('div_card').style.display = 'block';
            document.getElementById('div_installments').style.display = 'block';
            addInstallmentFields(document.getElementById('installments').value);
            break;

        default:
            document.getElementById('div_card').style.display = 'none';
            document.getElementById('div_installments').style.display = 'none';
            addInstallmentFields(0);
            break;
    }

    const div_card = document.getElementById("div_card");
    div_card.querySelectorAll("input, select, textarea").forEach(
        input => input.disabled = getComputedStyle(div_card).display === "none"
    );
}

document.getElementById('source_wallet_id').addEventListener('change', function () {
    listCards();
});

function listCards() {
    const source_wallet = document.getElementById('source_wallet_id');
    const selectedWallet = source_wallet.options[source_wallet.selectedIndex];
    const ownerValue = selectedWallet.dataset.owner;
    const walletValue = selectedWallet.dataset.wallet;

    switch (getPaymentType()) {
        case 'debit':
            showCards(`${window.location.origin}/dono/${ownerValue}/carteira/${walletValue}/cartao/debito`);
            break;

        case 'credit':
            showCards(`${window.location.origin}/dono/${ownerValue}/carteira/${walletValue}/cartao/credito`);
            break;
    }
}

function showCards(path) {
    fetch(path)
        .then(res => {
            if (!res.ok) {
                throw new Error(`Erro HTTP: ${res.status}`);
            }
            return res.json();
        })
        .then(cards => {
            const card_select = document.getElementById("card_id");
            card_select.innerHTML = '';
            if (cards.length > 0) {
                cards.forEach(card => {
                    const option = document.createElement("option");
                    option.value = card.id;
                    option.text = card.name;
                    card_select.appendChild(option);
                });
            } else {
                card_select.innerHTML = '<option value="">Essa Carteira não possui Cartões</option>';
            }
        })
        .catch(error => {
            document.getElementById("card_id").innerHTML = '<option value="">Não foi possível carregar os Cartões</option>';
        });
}

document.getElementById('installments').addEventListener('change', function () {
    addInstallmentFields(this.value);
});

function addInstallmentFields(installments) {
    const container = document.getElementById('installmentFields');
    container.innerHTML = '';
    const transaction_date = document.getElementById('transaction_date').value;

    for (let i = 0; i < installments; i++) {
        const installmentData = document.getElementById('transction_credit_template').firstElementChild.cloneNode(true);
        installmentData.querySelector("#installmentData").textContent = "Parcela #" + (i + 1);
        installmentData.querySelectorAll('input').forEach(input => {
            const field = input.getAttribute('data-name');
            if (!field) return;

            input.setAttribute('name', `installments[${i}][${field}]`);
            input.setAttribute('id', `installments[${i}][${field}]`);

            if (field === 'installment_date') {
                input.setAttribute('value', addMonths(transaction_date, i).toISOString().slice(0, 10));
            }
        });
        container.appendChild(installmentData);
    }

    distributeNetValueAcrossInstallments();
}

document.addEventListener("keydown", function (e) {
    if (e.target.classList.contains("money") && e.key === "-") {
        const input = e.target;
        const raw = input.value.replace(/[^\d,-]/g, "");

        if (raw === "" || raw === "0,00" || raw === "00") {
            e.preventDefault();
            input.value = "-0,00";

            setTimeout(() => {
                input.setSelectionRange(input.value.length, input.value.length);
            }, 0);
        }
    }
});

document.addEventListener("input", function (e) {
    if (e.target.classList.contains("money")) {
        let value = e.target.value.replace(/[^\d-]/g, '');
        value = (parseInt(value, 10) / 100).toFixed(2);
        value = value.replace(".", ",");
        e.target.value = formatMoney(value);

        const installmentContainer = e.target.closest(".flex-container");
        if (installmentContainer) {
            recalculateNetValue(installmentContainer);
            if (e.target.name === "gross_value" || e.target.name === "discount_value" || e.target.name === "interest_value" || e.target.name === "rounding_value") {
                distributeNetValueAcrossInstallments();
            }
        }
    }
});

function recalculateNetValue(container) {
    const gross = parseMoney(container.querySelector('[data-name="gross_value"]')?.value);
    const discount = parseMoney(container.querySelector('[data-name="discount_value"]')?.value);
    const interest = parseMoney(container.querySelector('[data-name="interest_value"]')?.value);
    const rounding = parseMoney(container.querySelector('[data-name="rounding_value"]')?.value);

    const net = gross - discount + interest + rounding;

    const netInput = container.querySelector('[data-name="net_value"]');
    if (netInput) {
        netInput.value = formatMoney(net);
    }
}

function distributeNetValueAcrossInstallments() {
    const netValueInput = document.querySelector('[name="net_value"]');
    const netValue = parseMoney(netValueInput?.value);

    const installmentContainers = document.querySelectorAll('#installmentFields .flex-container');
    const numInstallments = installmentContainers.length;

    if (numInstallments === 0 || netValue === 0) return;

    const baseValue = Math.floor((netValue / numInstallments) * 100) / 100;
    let remainder = netValue - (baseValue * numInstallments);

    installmentContainers.forEach((container, index) => {
        let value = baseValue;
        if (remainder > 0.009) {
            value += 0.01;
            remainder -= 0.01;
        }

        const grossInput = container.querySelector('[data-name="gross_value"]');
        if (grossInput) {
            grossInput.value = formatMoney(value);
        }

        recalculateNetValue(container);
    });
}
