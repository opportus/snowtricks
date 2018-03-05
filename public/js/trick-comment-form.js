class TrickCommentForm
{
    constructor(id)
    {
        this.initialize(id);
    }

    initialize(id)
    {
        $.extend(this, $('#' + id));

        this.id           = id;
        this.bodyTextarea = this.find('.trick-comment-edit-body').first().find('textarea').first();
        this.submitButton = this.find('.trick-comment-edit-submit').first().find('button').first();

        this.resizeBodyTextarea();
        this.toggleSubmitButton();

        var self = this;

        this.bodyTextarea.on('focus', function() {
            self.resizeBodyTextarea();
        });

        this.bodyTextarea.on('input', function() {
            self.resizeBodyTextarea();
            self.toggleSubmitButton();
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
        this.toggleSubmitButton();
    }

    resizeBodyTextarea()
    {
        this.bodyTextarea.css('height', '');
        this.bodyTextarea.css('height', this.bodyTextarea[0].scrollHeight + 'px');
    }

    toggleSubmitButton()
    {
        if (this.bodyTextarea.val() === undefined || this.bodyTextarea.val() === '') {
            this.submitButton.attr('disabled', 'disabled');

        } else {
            this.submitButton.removeAttr('disabled');
        }
    }
}
