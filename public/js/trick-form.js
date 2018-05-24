class TrickForm
{
    constructor(id)
    {
        this.initialize(id);
    }

    initialize(id)
    {
        $.extend(this, $('#' + id));

        this.id           = id;
        this.bodyTextarea = this.find('.trick-edit-body').first().find('textarea').first();

        this.resizeBodyTextarea();

        var self = this;

        this.bodyTextarea.on('focus', function() {
            self.resizeBodyTextarea();
        });

        this.bodyTextarea.on('input', function() {
            self.resizeBodyTextarea();
        });
    }

    reinitialize()
    {
        this.initialize(this.id);
    }

    reset()
    {
        this.bodyTextarea.val('');
        this.resizeBodyTextarea();
    }

    resizeBodyTextarea()
    {
        this.bodyTextarea.css('height', '');
        this.bodyTextarea.css('height', this.bodyTextarea[0].scrollHeight + 'px');
    }
}
