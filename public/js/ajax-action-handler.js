class AjaxActionHandler
{
    constructor()
    {
        var self = this;

        var mutationObserver = new MutationObserver(function(mutationsList) {
            for (var mutation of mutationsList) {
                self.initialize();
            }
        });

        if ($('.trick-show-comment-list')[0]) {
            mutationObserver.observe($('.trick-show-comment-list')[0], {childList: true, subtree: true});
        } else if ($('.trick-edit')[0]) {
            mutationObserver.observe($('.trick-edit')[0], {childList: true, subtree: true});
        }

        this.initialize();
    }

    initialize()
    {
        $('.ajax-action').each(function() {
            $(this).off('click');
            $(this).on('click', function() {
                var target = $(this);
                var action = target.data('action');

                ajaxActionHandler[action](target);
            });
        });
    }

    submitTrick(target)
    {
        var trickForm = target.closest('form');
        var formData = new FormData();

        $('input,textarea,select').each(function () {
            var val = null;

            if ('file' === $(this).attr('type')) {
                trickForm.parent().append(this);

                val = this.files[0];
            } else {
                val = $(this).val();
            }

            formData.append($(this).attr('name'), val);

        });

        $.ajax({
            url: target.data('url'),
            processData: false,
            contentType: false,
            method: 'POST',
            dataType: 'json',
            data: formData,
            success: function (response, status, xhr) {
                $(location).attr('href', target.data('redirection'));
            },
            error: function (xhr, status) {
                trickForm.replaceWith(xhr.responseJSON);
                $('#trick-loader').hide();
                $('#trick-loader-message-almost-done').hide();
                $('.progress-bar').css('width', 0);
                $('.progress-bar').attr('aria-valuenow', 0);
            },
            xhr: function () {
                var xhr = new window.XMLHttpRequest();

                if ($('input[type="file"][class="custom-file-input new-file"]').length) {
                    $('#trick-loader').show();

                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;

                            percentComplete = parseInt(percentComplete * 100);

                            $('.progress-bar').css('width', percentComplete+'%');
                            $('.progress-bar').attr('aria-valuenow', percentComplete);
                            $('#trick-loader-message-in-progress').show();

                            if (percentComplete === 100) {
                                $('#trick-loader-message-in-progress').hide();
                                $('#trick-loader-message-almost-done').show();
                            }
                        }
                    }, false);
                }

                return xhr;
            }
        });
    }

    toggleTrickCommentEdit()
    {
        $('.trick-show-comment-list').find('.trick-comment-list-item').each(function() {
            $(this).find('.trick-comment-edit').closest('.row').remove();
            $(this).find('.trick-comment-list-item-body').closest('.row').show();
        });
    }

    editTrickComment(target)
    {
        var commentItem     = target.closest('.trick-comment-list-item');
        var commentItemBody = commentItem.find('.trick-comment-list-item-body').first();

        if (commentItem.has('.trick-comment-edit').length) {
            this.toggleTrickCommentEdit();

        } else {
            this.toggleTrickCommentEdit();

            $.ajax({
                url: target.data('url'),
                dataType: 'json',
                success: function(response, status) {
                    commentItemBody.closest('.row').hide();
                    commentItemBody.closest('.row').after(response);

                    var commentForm = trickCommentFormRegistry.get(commentItem.find('form').first().attr('id'));

                    commentForm.reinitialize();
                    commentForm.bodyTextarea.focus();
                }
            });
        }
    }

    editTrickCommentReply(target)
    {
        var commentItem = target.closest('.trick-comment-list-item');

        if (commentItem.has('.trick-comment-edit').length) {
            this.toggleTrickCommentEdit();

        } else {
            this.toggleTrickCommentEdit();

            $.ajax({
                url: target.data('url'),
                dataType: 'json',
                success: function(response, status) {
                    commentItem.find('.trick-comment-list-item-action').first().after(response);

                    var commentForm = trickCommentFormRegistry.get(commentItem.find('form').first().attr('id'));

                    commentForm.reinitialize();
                    commentForm.bodyTextarea.focus();
                }
            });
        }
    }

    createTrickComment(target)
    {
        var commentForm = trickCommentFormRegistry.get(target.closest('form').attr('id'));
        var commentList = infiniteScrollRegistry.get($('.trick-show-comment-list').attr('id'));

        $.ajax({
            url: commentForm.attr('action'),
            type: 'POST',
            method: 'POST',
            dataType: 'json',
            data: commentForm.serialize(),
            success: function(response, status) {
                commentForm.reset();
                commentList.refresh(1);
            }
        });
    }

    updateTrickComment(target)
    {
        var commentItem = target.closest('.trick-comment-list-item');
        var commentForm = trickCommentFormRegistry.get(target.closest('form').attr('id'));

        $.ajax({
            url: commentForm.attr('action'),
            dataType: 'json',
            method: 'PUT',
            data: commentForm.serialize(),
            success: function(response, status) {
                $.ajax({
                    url: commentItem.data('url'),
                    dataType: 'json',
                    success: function(response, status) {
                        commentItem.replaceWith(response);
                    }
                });
            }
        });
    }

    replyTrickComment(target)
    {
        var commentItem      = target.closest('.trick-comment-list-item');
        var commentReplyForm = trickCommentFormRegistry.get(target.closest('form').attr('id'));

        if (commentItem.has('.trick-comment-list-item-children-list').length) {
            var commentChildrenList = infiniteScrollRegistry.get(commentItem.find('.trick-comment-list-item-children-list').first().attr('id'));

        } else {
            var commentChildrenList = infiniteScrollRegistry.get(commentItem.parents('.trick-comment-list-item').first().find('.trick-comment-list-item-children-list').first().attr('id'));
        }

        $.ajax({
            url: commentReplyForm.attr('action'),
            dataType: 'json',
            method: 'POST',
            data: commentReplyForm.serialize(),
            success: function(response, status) {
                commentReplyForm.closest('.trick-comment-edit').closest('.row').remove();
                commentChildrenList.closest('.trick-comment-list-item').find('.trick-comment-list-item-action-list-children').first().css('display', 'inline');
                commentChildrenList.refresh();
            }
        });
    }

    listTrickCommentChildren(target)
    {
        var commentItem         = target.closest('.trick-comment-list-item');
        var commentChildrenList = infiniteScrollRegistry.get(commentItem.find('.trick-comment-list-item-children-list').attr('id'));

        if (commentItem.has('.trick-comment-edit').length) {
            this.toggleTrickCommentEdit();
        }

        if (commentChildrenList.hasClass('infinite-scroll-collapsed')) {
            commentChildrenList.removeClass('infinite-scroll-collapsed');
            commentChildrenList.reinitialize();

        } else {
            commentChildrenList.addClass('infinite-scroll-collapsed');
            commentChildrenList.loadPage();
            this.initialize();
        }
    }

    deleteTrickComment(target)
    {
        $.ajax({
            url: target.data('url'),
            data: {'trick_comment_delete[_token]': target.data('token'), '_method': 'DELETE'},
            method: 'POST',
            dataType: 'json',
            success: function(response, status) {
                if (target.closest('.trick-comment-list-item-children-list').children().length === 1) {
                    target.closest('.trick-comment-list-item-children-list').closest('.trick-comment-list-item').find('.trick-comment-list-item-action-list-children').first().css('display', 'none');
                }

                target.closest('.trick-comment-list-item').hide('slow', function() {
                    target.closest('.trick-comment-list-item').remove();
                });
            }
        });
    }

    deleteTrick(target)
    {
        $('#trick-delete-modal').modal();
        $('#trick-delete-modal .btn-danger').on('click', function () {
            $.ajax({
                url: target.data('url'),
                data: {'trick_delete[_token]': target.data('token'), '_method': 'DELETE'},
                method: 'POST',
                dataType: 'html',
                success: function(response, status) {
                    if (target.data('redirection')) {
                        $(location).attr('href', target.data('redirection'))
                        return;
                    }

                    target.closest('.trick-list-item').hide('slow', function() {
                        target.closest('.trick-list-item').remove();
                    });

                    $('#trick-delete-modal').modal('hide');
                }
            });
        });
    }

    editEmbedTrickAttachment(target)
    {
        var trickForm = trickFormRegistry.get(target.closest('.trick-edit-content').find('form').attr('id'));
        trickForm.getEmbedTrickAttachmentFieldset();
    }

    editUploadTrickAttachment(target)
    {
        var trickForm = trickFormRegistry.get(target.closest('.trick-edit-content').find('form').attr('id'));
        trickForm.getUploadTrickAttachmentFieldset();
    }

    addTrickAttachment(target)
    {
        var trickForm = trickFormRegistry.get(target.closest('.trick-edit-content').find('form').attr('id'));
        trickForm.addTrickAttachment();
    }

    removeTrickAttachment(target)
    {
        var trickForm = trickFormRegistry.get(target.closest('.trick-edit-content').find('form').attr('id'));
        trickForm.removeTrickAttachment(target.closest('.trick-attachment-list-item').attr('id'));
    }
}

var ajaxActionHandler = new AjaxActionHandler();
