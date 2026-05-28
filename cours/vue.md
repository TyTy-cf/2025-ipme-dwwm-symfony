
## Vue.js avec Symfony


#### 1. Dépendances


Elles sont à ajouter en tant que "devDependancies" :

```json
        "vue": "^3",
        "vue-loader": "^17.4.2",
        "vue-template-compiler": "^2.7.16"
```


#### 2. Configuration


- Créer le fichier `vue-shims.d.ts` à la racine du projet :

```js
declare module "*.vue" {
    import {createApp} from "vue";
    export default createApp;
}
```


- Si vous utiliser typescript, prévoir de modifier le fichier `tsconfig.json`, ajouter cette clé dans celui-ci :

```json
    "files": [
      "./vue-shims.d.ts"
    ],
```


- Ajouter les lignes suivantes dans le fichier `webpack.config.js` :

```js
    .enableVueLoader()
    .enableTypeScriptLoader((options) =>{
        options.appendTsSuffixTo = [/\.vue$/];
    })
```


### 3. Intégrer un composant Vue


- Il faut monter le composant Vue sur un élément HTML, par exemple admettons ce code HTML (dans un Twig) :


```html
<div id="vue-root-container" data-cart-games="{{ cartJson }}">
</div>
```


- Notre fichier de "mount" pour le composant Vue doit :
  - Récupérer l'élément HTMl où monter le composant Vue (ici "#vue-root-container")
  - Instancier un objet `App` via la méthode `createApp`, elle prend en paramètre :
    - Le nom du composant à monter, ici `RootCartComponent`
    - Ses props sous forme de JSON (props = attributs par défaut)
- On appelle ensuite la méthode `mount` depuis l'objet `vueApp` en passant en paramètre l'élément HTML où le monter

```ts
import RootCartComponent from './vue/RootCartComponent.vue';

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
    element.removeAttribute('data-attr');
}
```


### 4. Exemple de composant Vue basique


```vue
<template>
    <div>
        
    </div>
</template>

<script lang="ts">

import {ICart} from "../interfaces/i-cart";
import {PropType} from "vue";

export default {
    name: 'RootCartComponent',
    components: {},
    props: {
        propsCart: {
            type: Object as PropType<ICart>, // Pour le typage d'objet !
        },
    },
    data() {
        return {
            cart: this.propsCart,
        }
    },
    mounted() {},
    computed: {},
    methods: {},
}

</script>
```
