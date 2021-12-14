export default function() {
    const { lockScreen, showAlert } = require('../utils/global');
    const { haveMetaMask, noMetaMask, checkChainId, getMetaMaskAccounts, makeUserSign, getFromSign, changeNetwork, getActiveWallet } = require('../utils/metamask');
    const passphrase = `Secure Login ${site_url}`;
    const getOrCreateSecret = async (wallet, passphrase) => {
        let secret = localStorage.getItem("Signed|" + wallet);
        if (!secret) {
            secret = await makeUserSign(wallet, passphrase);
            localStorage.setItem("Signed|" + wallet, secret);
        }
        return secret;
    };
    const authWithMetaMask = async function (wallet, secret) {
        const activeWallet = await getActiveWallet();
        if (activeWallet.toLowerCase() !== wallet) {
            lockScreen(false);
            showAlert(__('Error'), __('Invalid wallet address. Please reload the page and try again.'), 'error');
            return;
        }

        axios.post('auth', { wallet, secret }).then((res) => {
            const { redirect, success, title, message } = res.data;

            // showAlert(title, message, success ? 'success' : 'error');
            if (redirect) {
                setTimeout(() => { window.location.href = redirect; }, 1000);
            }
        }).catch((err) => {
            lockScreen(false);
            const { title, message } = err.response.data;
            showAlert(title, message, 'error');
        });
    };

    $('.user-auth').on('click', async function (e) {
        e.preventDefault();
        // Verify is user have MetaMask installed
        if (!haveMetaMask()) {
            noMetaMask();
            return;
        }
        try {
            lockScreen(true);
            await checkChainId(); // Somente usado para testar se o usuário está conectado a rede correta
            const wallets = await getMetaMaskAccounts(); // Retorna um array com os endereços de cada carteira
			const useWallet = wallets[0].toLowerCase(); // Usa a primeira carteira do array
            const secret = await getOrCreateSecret(useWallet, passphrase); // Gera ou recupera a chave privada do usuário
            const wallet = await getFromSign(secret, passphrase);
            authWithMetaMask(wallet.toLowerCase(), secret);
        } catch (err) {
            if (err.message == 'InvalidChainId') {
                showAlert(__('Error'), __('Invalid chain ID. Please change your network in MetaMask and try again.'), 'error');
            } else if (err.message == 'InvalidMetaMaskAccounts' || err instanceof TypeError) {
                showAlert(__('Error'), __('Failed to get your wallet address. Please try again.'), 'error');
            } else if (err.message == 'InvalidSignature') {
                //showAlert(__("Alert"), __("Your signature is invalid. Please try again"), 'error');
                localStorage.clear();
                $('.user-auth').trigger('click');
            } else if (err.message == 'FailedToGetWallet') {
                showAlert(__("Alert"), __("Fail to get your wallet address"), 'error');
            }
        } finally {
            lockScreen(false);
        }
    });
}
