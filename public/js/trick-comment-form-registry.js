class TrickCommentFormRegistry
{
    constructor()
    {
        this.initialize();
    }

    initialize()
    {
        this.registry = {};

        var self = this;

        $('.trick-comment-edit').each(function() {
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
            var trickCommentForm = new TrickCommentForm(id);

            this.registry[id] = trickCommentForm;
        }

        return this;
    }

    has(id)
    {
        return this.registry[id] !== undefined;
    }
}

var trickCommentFormRegistry = new TrickCommentFormRegistry();
