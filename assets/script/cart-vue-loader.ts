import {App, createApp} from "vue";
import RootCartComponent from './vue/RootCartComponent.vue';

window.addEventListener('load', () => {
    initCartLoader();
});

function initCartLoader(): void {
    const element: HTMLDivElement = document.querySelector('#vue-root-container');
    if (element) {
        const data: string = element.getAttribute('data-cart-games');
        const vueApp: App<Element> = createApp(
            RootCartComponent,
            {
                propsCart: JSON.parse(data),
            }
        );
        vueApp.mount(element);
        element.removeAttribute('data-cart-games');
    }
}
