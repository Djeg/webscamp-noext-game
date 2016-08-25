class NewRecipeFormWidget
{
    constructor(element)
    {
        this.rootNode = element;
        this.container = element.find('[data-prototype]');
        this.index = 0;

        this.addNewRecipe = this.addNewRecipe.bind(this);
        this.removeRecipe = this.removeRecipe.bind(this);
    }

    boot()
    {
        this
            .container
            .children()
            .each((i, block) => {
                let index = parseInt($(block).find('[data-index]').data('index'));

                if (this.index <= index) {
                    this.index = index + 1;
                }
            })
        ;

        this
            .rootNode
            .find('[role="add-new-recipe"]')
            .each((index, button) => {
                $(button).unbind('click');
                $(button).bind('click', this.addNewRecipe);
            })
        ;

        this
            .rootNode
            .find('[role="remove-recipe"]')
            .each((index, button) => {
                $(button).unbind('click');
                $(button).bind('click', this.removeRecipe)
            })
        ;

        $('select').material_select();
    }

    addNewRecipe(event)
    {
        let template = this
            .container
            .data('prototype')
            .replace(/__name__label__/g, `Ingredient n°${this.index + 1}`)
            .replace(/__name__/g, this.index)
        ;

        template = $(template);

        template.find('[data-index]').data('index', this.index);

        this.container.append(template);

        this.boot();
    }

    removeRecipe(event)
    {
        $(event.target).parent().parent().remove();

        this.boot();
    }
}

window['new-recipe-form'] = (element) => {
    let widget = new NewRecipeFormWidget(element);

    return widget.boot();
};
