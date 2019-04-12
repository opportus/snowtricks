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

    getEmbedTrickAttachmentFieldset() {
        this.getTrickAttachmentFieldset('embed');
    }

    getUploadTrickAttachmentFieldset() {
        this.getTrickAttachmentFieldset('upload');
    }

    getTrickAttachmentFieldset(type)
    {
        var attachmentFieldset = this.find('#trick_edit_attachments').attr('data-prototype'),
            attachmentList = this.find('.trick-attachment-list'),
            attachmentCounter = attachmentList.children().length,
            attachmentModal = this.find('#trick-attachment-modal');

        attachmentFieldset = $(attachmentFieldset.replace(/__name__/g, attachmentCounter));

        attachmentFieldset.find('.form-group')[2].remove();

        switch (type) {
            case 'embed':
                attachmentFieldset.find('.form-group')[1].remove();
                break;
            case 'upload':
                attachmentFieldset.find('.form-group')[0].remove();
                break;
        }

        attachmentModal.find('.modal-body').empty();
        attachmentModal.find('.modal-body').append(attachmentFieldset);
        attachmentModal.attr('data-attachment-counter', attachmentCounter);
        attachmentModal.find('#trick-attachment-modal-embed-error').addClass('d-none');
        attachmentModal.find('#trick-attachment-modal-upload-error').addClass('d-none');

        attachmentModal.find('input').focus();
    }

    addTrickAttachment()
    {
        var attachmentModal = this.find('#trick-attachment-modal'),
            attachmentCounter = attachmentModal.attr('data-attachment-counter'),
            attachmentId = 'trick-attachment-'+attachmentCounter,
            upload = attachmentModal.find('.custom-file input'),
            embed = attachmentModal.find('[id$=embed]'),
            src = upload.length ? URL.createObjectURL(upload[0].files[0]) : $(embed[0].value).attr('src'),
            active = this.find('.trick-attachment-list').children().length ? '' : 'active',
            attachmentTemplate = null,
            fieldset = attachmentModal.find('.modal-body').find('fieldset');

        if (upload.length) {
            switch (upload[0].files[0].type) {
                case 'image/gif':
                case 'image/jpeg':
                case 'image/png':
                case 'image/webp':
                    attachmentTemplate = '<img src="'+src+'" class="trick-list-item-attachment embed-responsive-item img-fluid mx-auto d-block w-100" />';
                    break;
                case 'video/mpeg':
                case 'video/avi':
                    attachmentTemplate = '<iframe class="trick-list-item-attachment embed-responsive-item" frameborder="0" src="'+src+'" allowfullscreen></iframe>';
                    break;
                default:
                    this.find('#trick-attachment-modal-upload-error').removeClass('d-none');
                    return;
            }
        } else if (embed.length) {
            var embedTag = $(embed[0].value);

            if (embedTag[0] === undefined || embedTag[0].tagName !== 'IFRAME' || src.length < 1 || (src.indexOf('https://www.youtube.com/embed/') !== 0 && src.indexOf('https://www.dailymotion.com/embed/video/') !== 0)) {
                this.find('#trick-attachment-modal-embed-error').removeClass('d-none');
                return;
            }

            attachmentTemplate = '<iframe class="trick-list-item-attachment embed-responsive-item" frameborder="0" src="'+src+'" allowfullscreen></iframe>';
        } else {
            return;
        }

        attachmentTemplate =
            '<li id="'+attachmentId+'" class="trick-attachment-list-item carousel-item col-md-4 '+active+'" data-attachment-counter="'+attachmentCounter+'">'+
                '<div class="embed-responsive embed-responsive-16by9 z-depth-1">'+
                    attachmentTemplate+
                '</div>'+
                '<ul class="trick-attachment-list-action-list list-inline position-absolute p-2" style="right:30px;top:10px;">'+
                    '<li class="list-inline-item">'+
                        '<i class="trick-attachment-list-action-list-remove-item fas fa-trash-alt ajax-action" data-action="removeTrickAttachment" style="background:rgba(0,0,0,0.5);border-radius:100px;color:#ffffff;padding:15px;cursor:pointer;"'+
                            'style="cursor:pointer;">'+
                        '</i>'+
                    '</li>'+
                '</ul>'+
            '</div>'
        ;

        this.find('.trick-attachment-list').append(attachmentTemplate).show('slow');
        this.find('#trick-attachment-modal').modal('toggle');

        console.debug(fieldset.find('input'));

        if (fieldset.find('input').attr('type') == 'file') {
        } else {
            fieldset.find('input').val(src)
        }

        this.find('#trick_edit_attachments').append(fieldset);
        this.find('#trick-attachment-carousel').removeClass('d-none');

        if (this.find('.trick-attachment-list').children().length > 3) {
            this.find('#trick-attachment-carousel').addClass('carousel');
            this.find('.trick-attachment-carousel-control').removeClass('d-none');
        }
    }

    removeTrickAttachment(attachmentId)
    {
        var attachmentCounter = this.find('#'+attachmentId).attr('data-attachment-counter');

        this.find('#'+attachmentId).remove();
        this.find('#trick_edit_attachments_'+attachmentCounter).parent('fieldset').remove();

        var attachmentList = this.find('.trick-attachment-list');

        if (attachmentList.children().length == 0) {
            this.find('#trick-attachment-carousel').addClass('d-none');
        }

        attachmentList.children(':first').addClass('active');

        if (this.find('.trick-attachment-list').children().length <= 3) {
            this.find('#trick-attachment-carousel').removeClass('carousel');
            this.find('.trick-attachment-carousel-control').addClass('d-none');
        }
    }
}
