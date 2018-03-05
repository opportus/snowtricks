class InfiniteScrollRegistry
{
    constructor()
    {
        this.initialize();
    }

    initialize()
    {
        this.registry = {};

        var self = this;

        $('.infinite-scroll').each(function() {
            self.add($(this).attr('id'));
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
            this.registry[id] = new InfiniteScroll(id);
        }

        return this;
    }

    has(id)
    {
        return this.registry[id] !== undefined;
    }
}

var infiniteScrollRegistry = new InfiniteScrollRegistry();
