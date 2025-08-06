window.addEventListener('exibirModal', (event) => {
    Swal.fire({
        title: event.detail[0],
        icon: event.detail[1],
        text: event.detail[2],
        position: event.detail[3],
        timer: 2000,
        showConfirmButton: false,
    })
});