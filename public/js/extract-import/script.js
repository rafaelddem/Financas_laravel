document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".money").forEach(formatMoney);
    
    document.querySelectorAll('select[id^="transaction["][id$="][source_wallet_id]"]').forEach(select => {
        loadInstallmentForm(select.dataset.key);
    });
});

function getPaymentType(transactionId) {
    const payment_method = document.getElementById('transaction[' + transactionId + '][payment_method_id]');
    const selectedOption = payment_method.options[payment_method.selectedIndex];
    return selectedOption.dataset.type;
}

document.getElementById('module_id').addEventListener('change', function () {
    const disabled = this.value == 0;

    document.getElementById('submit_button').disabled = disabled;
    document.getElementById('extract_file').disabled = disabled;
});

document.addEventListener("change", function (e) {
    if (e.target.matches("select[id^='transaction'][id$='[payment_method_id]']")) {
        loadInstallmentForm(e.target.dataset.key);
        listCards(e.target.dataset.key);
    }
    if (e.target.matches("select[id^='transaction'][id$='[source_wallet_id]']")) {
        listCards(e.target.dataset.key);
    }
});

function loadInstallmentForm(transactionId) {
    switch (getPaymentType(transactionId)) {
        case 'debit':
            listCards(transactionId);
            document.getElementById('div_card[' + transactionId + ']').style.display = 'block';
            break;

        case 'credit':
            listCards(transactionId);
            document.getElementById('div_card[' + transactionId + ']').style.display = 'block';
            break;

        default:
            document.getElementById('div_card[' + transactionId + ']').style.display = 'none';
            break;
    }

    const div_card = document.getElementById('div_card[' + transactionId + ']');
    div_card.querySelectorAll("input, select, textarea").forEach(
        input => input.disabled = getComputedStyle(div_card).display === "none"
    );
}

function listCards(transactionId) {
    const source_wallet = document.getElementById('transaction[' + transactionId + '][source_wallet_id]');
    const selectedWallet = source_wallet.options[source_wallet.selectedIndex];
    const ownerValue = selectedWallet.dataset.owner;
    const walletValue = selectedWallet.dataset.wallet;

    switch (getPaymentType(transactionId)) {
        case 'debit':
            showCards(transactionId, `${window.location.origin}/dono/${ownerValue}/carteira/${walletValue}/cartao/debito`);
            break;

        case 'credit':
            showCards(transactionId, `${window.location.origin}/dono/${ownerValue}/carteira/${walletValue}/cartao/credito`);
            break;
    }
}

function showCards(transactionId, path) {
    fetch(path)
        .then(res => {
            if (!res.ok) {
                throw new Error(`Erro HTTP: ${res.status}`);
            }
            return res.json();
        })
        .then(cards => {
            const card_select = document.getElementById("card_id[" + transactionId + "]");
            card_select.innerHTML = '';
            if (cards.length > 0) {
                cards.forEach(card => {
                    const option = document.createElement("option");
                    option.value = card.id;
                    option.text = card.name;
                    option.selected = document.getElementById("card_id[" + transactionId + "]").dataset.selected == card.id;
                    card_select.appendChild(option);
                });
            } else {
                card_select.innerHTML = '<option value="">Essa Carteira não possui Cartões</option>';
            }
        })
        .catch(error => {
            document.getElementById("card_id[" + transactionId + "]").innerHTML = '<option value="">Não foi possível carregar os Cartões</option>';
        });
}