document.addEventListener("DOMContentLoaded", function () {
    loadInstallmentForm();
    listCards();
});

const category = document.getElementById('category_id');
category.addEventListener('change', function () {
    const selectedOption = category.options[category.selectedIndex];
    document.getElementById('relevance').value = selectedOption.dataset.relevance;
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
        case 'credit':
            document.getElementById('div_card').style.display = 'block';
            break;

        default:
            document.getElementById('div_card').style.display = 'none';
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
