function initAddGameButton(): void {
    const buttonAdd: HTMLButtonElement = document.querySelector('[data-add-to-cart]');
    if (buttonAdd) {
        const url: string = buttonAdd.getAttribute('data-add-to-cart');
        buttonAdd.addEventListener('click', () => {
            fetch(url)
                .then((response: Response)=> {
                    if (response.ok) return response.json();
                })
                .then((data: {qty: number}) => {
                    const cartQty: HTMLDivElement = document.querySelector('div.cart-quantity');
                    if (cartQty) {
                        cartQty.classList.add('animation');
                        cartQty.innerHTML = data.qty + '';
                        setTimeout(() => {
                            cartQty.classList.remove('animation');
                        }, 2000);
                    }
                });
        });
        buttonAdd.removeAttribute('data-add-to-cart');
    }
}

window.addEventListener('load', () => {
    initAddGameButton();
});
