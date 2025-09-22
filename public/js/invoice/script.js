document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('.popupConfirmation');

    document.querySelectorAll('.open-confirm').forEach(button => {
        button.addEventListener('click', () => {
            modal.style.display = 'flex';
            modal.querySelector('h4').textContent = button.dataset.subtitle;
            document.getElementById('popup-id').value = button.dataset.id;
            document.getElementById('popup-filter-start-date').value = document.getElementById('start_date').value;
            document.getElementById('popup-filter-end-date').value = document.getElementById('end_date').value;
            document.getElementById('popup-filter-wallet').value = document.getElementById('wallet_id').value;
            document.getElementById('popup-filter-card').value = document.getElementById('card_id').value;
        });
    });
});

function closePopup() {
    document.querySelector('.popupConfirmation').style.display = 'none';
}

document.getElementById('wallet_id').addEventListener('change', function () {
    const source_wallet = document.getElementById('wallet_id');
    const selectedWallet = source_wallet.options[source_wallet.selectedIndex];
    const ownerValue = selectedWallet.dataset.owner;
    const walletValue = selectedWallet.dataset.wallet;

    if (selectedWallet.value == 0) {
        const option = document.createElement("option");
        option.value = 0;
        option.text = "Todos os Cartões";

        card_select = document.getElementById("card_id");
        card_select.innerHTML = '';
        card_select.appendChild(option);
    } else {
        fetch(`${window.location.origin}/dono/${ownerValue}/carteira/${walletValue}/cartao/credito`)
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
                }
            })
            .catch(error => {
                document.getElementById("card_id").innerHTML = '<option value="">Não foi possível carregar os Cartões</option>';
            });
    }
});
