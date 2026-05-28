
# Formulaires avancés

## Collection Form

Les "Collection Form" en Symfony sont un moyen d'imbriquer plusieurs type formulaires dans un seul.
Ils sont utilisés principalement dans le cadre de relation ManyToMany.

```php
->add('categories', CollectionType::class, [
    'label' => 'Categories',
    'entry_type' => EntityType::class,
    'entry_options' => [
        'label' => false,
        'class' => Category::class,
        'choice_label' => 'name',
        'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('p')
                ->orderBy('p.name', 'ASC');
        }
    ],
    'allow_add' => true,
    'allow_delete' => true,
])
```

Ici on a un formulaire qui va permettre d'ajouter plusieurs "Category" à l'objet principal (Game).

Il y a différentes clés qui sont utilisées :
- `entry_type` : représente le type de Formulaire qui sera présent dans la collection
- `entry_options` : les options du formulaire enfant
- `allow_add` : autorise l'ajout d'éléments supplémentaires
- `allow_delete` : autorise la suppression d'éléments supplémentaires

`allow_add` est très important car il va ajouter un attribut HTML contenant le prototype du formulaire à ajouter dans la Collection.
Dans l'HTML cela va ajouter un `data-attribute` de nom `data-prototype` : 

```html
<div id="game_categories"
     data-prototype="&lt;div class=&quot;mb-3&quot;&gt;&lt;select id=&quot;game_categories___name__&quot; name=&quot;game[categories][__name__]&quot; class=&quot;form-select&quot;&gt;&lt;option value=&quot;9&quot;&gt;Action&lt;/option&gt;&lt;option value=&quot;10&quot;&gt;Aventure&lt;/option&gt;&lt;option value=&quot;8&quot;&gt;Battle Royale&lt;/option&gt;&lt;option value=&quot;12&quot;&gt;Cartes&lt;/option&gt;&lt;option value=&quot;3&quot;&gt;FPS&lt;/option&gt;&lt;option value=&quot;11&quot;&gt;Hack &amp;#039;n&amp;#039; Slash&lt;/option&gt;&lt;option value=&quot;4&quot;&gt;MMO&lt;/option&gt;&lt;option value=&quot;6&quot;&gt;MOBA&lt;/option&gt;&lt;option value=&quot;7&quot;&gt;Monde ouvert&lt;/option&gt;&lt;option value=&quot;5&quot;&gt;RPG&lt;/option&gt;&lt;option value=&quot;2&quot;&gt;Simulation&lt;/option&gt;&lt;option value=&quot;1&quot;&gt;Stratégie&lt;/option&gt;&lt;/select&gt;&lt;/div&gt;"
     widget-counter="1">
</div>
```

Ce `data-prototype` est très important car il va nous permettre de créer des éléments supplémentaires dans la collection.

Le soucis, c'est que pour cela il faut faire du Javascript pour aller récupérer ce `data-prototype` et l'injecter ensuite dans l'HTML.


### Collection Form - Javascript


Voilà le code Javascript complet, permettant de gérer "facilement" l'ajout de nouveaux champs de formulaire dans la Collection :

```ts
function initCollectionForm(): void {
    const buttonsAddForm: NodeListOf<HTMLButtonElement> = document.querySelectorAll('[data-btn-selector]');
    if (buttonsAddForm) {
        buttonsAddForm.forEach((btnElt) => {
            btnElt.addEventListener('click', () => {
                const dataValueSelector: string = btnElt.getAttribute('data-btn-selector');
                let list: HTMLElement = document.querySelector('[data-list-selector="'+dataValueSelector+'"]');
                let counter: number = list.children.length;
                let newWidget: string = list.getAttribute('data-prototype');
                newWidget = newWidget.replace(/__name__/g, counter.toString());
                newWidget = newWidget.replace('mb-3', 'mb-3 w-100');

                const icon = createIcon(counter);

                counter++;
                list.setAttribute('widget-counter', counter.toString());

                let newDiv = mainDivForm();
                newDiv.innerHTML = newWidget;
                newDiv.insertAdjacentElement('afterbegin', icon)

                list.appendChild(newDiv);
                icon.addEventListener('click', () => {
                    newDiv.innerHTML = '';
                    newDiv.remove();
                });
            });
        });
    }
}

function initButtonCollectionForm() {
    const collectionForm: NodeListOf<HTMLDivElement> = document.querySelectorAll('[data-list-selector]');
    if (collectionForm) {
        for (const form of collectionForm) {
            let i: number = 0;
            const savedChildren: string[] = [];
            for (const child of form.children) {
                child.classList.add('w-100');
                savedChildren.push(child.outerHTML);
            }
            form.innerHTML = '';

            for (const childSaved of savedChildren) {
                const divForm = mainDivForm();
                const icon: HTMLElement = createIcon(i);
                divForm.innerHTML = childSaved;
                divForm.insertAdjacentElement('afterbegin', icon);
                i++;
                form.appendChild(divForm);
                icon.addEventListener('click', () => {
                    divForm.innerHTML = '';
                    divForm.remove();
                });
            }
        }
    }
}

function mainDivForm() {
    let newDiv: HTMLDivElement = document.createElement('div');
    newDiv.classList.add('d-flex');
    return newDiv;
}

function createIcon(counter: number): HTMLElement {
    const icon: HTMLElement = document.createElement('i');
    icon.setAttribute('data-delete-form', counter.toString());
    icon.classList.add('fa');
    icon.classList.add('fa-trash');
    icon.classList.add('icon-click');
    icon.classList.add('me-3');
    icon.classList.add('mt-1');
    icon.classList.add('fa-2x');
    return icon;
}

window.addEventListener('load', () => {
    initCollectionForm();
    initButtonCollectionForm();
});
```

De la manière dont le Javascript est construit il faut ajouter des informations dans le formulaire PHP pour que cela fonctionne :

```php
->add('categories', CollectionType::class, [
    'attr' => [
        'data-list-selector' => 'categories',
    ]
])
->add('addCategory', ButtonType::class, [
    'label' => 'Ajouter une catégorie',
    'attr' => [
        'class' => 'btn btn-primary',
        'data-btn-selector' => 'categories',
    ]
])
```

On ajoute l'attribut `data-list-selector` sur le champ de type `CollectionType` et `data-btn-selector` sur le bouton de type `ButtonType`, il faut bien s'assurer que leurs valeurs respectives soient **identiques**, et hop on a configuré l'automatisation de la Collection, tout en pouvant supprimer des éléments de celle-ci !


## Form imbriqués


Il arrive aussi que l'on ai des "fausses" relation ManyToMany, comme une entité relationnelle entre deux autres, comme les ingrédients d'une recette, il faut sélectionner les ingrédients qui composent la recette, mais aussi préciser les quantités par ingrédient.

Cela fonctionne de la même manière, et rien ne nous empêche de créer un formulaire imbriqué dans un autre formulaire, et donc de créer un formulaire custom dans un autre formulaire de type `CollectionType` !

Exemple :

```php
->add('directives', CollectionType::class, [
    'label' => false,
    'entry_type' => DirectiveType::class,
    'entry_options' => [
        'recipe' => $data,
    ],
    'allow_add' => true,
    'allow_delete' => true,
])
```

On a donc un formulaire `DirectiveType` qui est imbriqué dans un autre formulaire, et il aura ses propres champs, par rapport à l'exemple précédent, le contenu de `entry_options` est différent.

Dans la continuité, on a une clé dans le tableau `entry_options` qui est `recipe`, c'est une option que l'on a défini dans le sous-formulaire.

Afin de déclarer des options complémentaires dans les formulaires on passe par la méthode `configureOptions` :

```php
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'recipe' => null,
        ]);
    }
```

Si l'option `recipe` n'est pas renseignée, par défaut elle sera `null`, on récupère les options via le `$options` du `buildForm` :

```php
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Recipe|null $data */
        $data = $options['recipe'];
```

## Form Event (Symfony)

Il est possible de définir des évènements Symfony sur les formulaires, cela permet de modifier les champs du formulaire, ou d'ajouter des contraintes, des options, etc.

Exemple :

```php
    $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
        $form = $event->getForm(); // Get the form from the FormEvent

        $field = $form->get('thumbnailCover'); // Use the field name declares in the buildForm method
        $fieldLink = $form->get('thumbnailCoverLink');

        if ($field->getData() === null && $fieldLink->getData() === null) {
            $form->addError(new FormError('MET AU MOINS UNE IMAGE'));
        }
    });
```

Dans cet exemple, on récupère les champs `thumbnailCover` et `thumbnailCoverLink` du formulaire, et on vérifie si au moins un des deux est renseigné. Si ce n'est pas le cas, on ajoute une erreur au formulaire.

Il existe plusieurs évènements Symfony, par exemple :

- `FormEvents::PRE_SET_DATA` : Avant que les données ne soient définies dans le formulaire
- `FormEvents::POST_SET_DATA` : Après que les données aient été définies dans le formulaire
- `FormEvents::PRE_SUBMIT` : Avant que les données ne soient soumises
- `FormEvents::SUBMIT` : Après que les données aient été soumises
- `FormEvents::POST_SUBMIT` : Après que les données aient été soumises et que le formulaire ait été validé


## Form "par défaut"


On peut créer des Form avec des comportements par défaut, notamment lorsque l'on répète souvent le code de celui-ci.

Par exemple, on peut imaginer un formulaire qui propose de créer un radio button "Oui" et "Non".

```php
class YesNoType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'expanded' => true,
            'choices' => [
                'Oui' => '1',
                'Non' => '0',
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

}
```

C'est le fait que l'on déclare la méthode `getParent()` qui permet de définir le type de formulaire parent, et donc d'utiliser notre form comme étant un `ChoiceType`, par rapport à notre exemple. 