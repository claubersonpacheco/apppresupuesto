import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

document.addEventListener('livewire:init', function () {

    Livewire.on('clipboard:copied', ( text ) => {

        navigator.clipboard.writeText(text)
            .then(() => {
                alert('Texto copiado para a área de transferência!');
            })
            .catch(err => {
                console.error('Erro ao copiar o texto:', err);
            });
    });
});
