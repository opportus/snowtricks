class TrickFormRegistry
{
    constructor()
    {
        this.initialize();
    }

    initialize()
    {
        this.registry = {};

        var self = this;

        $('.trick-edit').each(function() {
            self.add($(this).find('form').first().attr('id'));
        });
    }

    get(id)
    {
        if (! this.has(id)) {
            this.add(id);
        }

        return this.registry[id];
    }

    add(id)
    {
        if (! this.has(id)) {
            var trickForm = new TrickForm(id);

            this.registry[id] = trickForm;
        }

        return this;
    }

    has(id)
    {
        return this.registry[id] !== undefined;
    }
}

var trickFormRegistry = new TrickFormRegistry();
