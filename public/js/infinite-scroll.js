class InfiniteScroll
{
    constructor(id)
    {
        this.initialize(id);
    }

    initialize(id)
    {
        $.extend(this, $('#' + id));

        this.id       = id;
        this.page     = 1;
        this.loading  = false;
        this.complete = false;

        this.loadPageOnScroll();
    }

    reinitialize()
    {
        this.clear();
        this.initialize(this.id);
    }

    clear()
    {
        this.html('');
    }

    isTriggered()
    {
        if (this.hasClass('infinite-scroll-collapsable') && ! this.hasClass('infinite-scroll-collapsed')) {
            return false;
        }

        var docViewTop    = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();

        var elemTop    = this.offset().top;
        var elemBottom = elemTop + this.height();

        if (elemBottom > docViewTop) {
            return true;
        }

        return false;
    }

    loadPageOnScroll()
    {
        var self = this;

        $(document).scroll(function() {
            if (! self.isTriggered()) {
                return;
            }

            self.loadPage();
        });
    }

    loadPage()
    {
        if (this.complete || this.loading) {
            return;
        }

        var self = this;
        var url  = this.data('url') + '&page=' + this.page;

        this.loading = true;
        this.append('<div id="infinite-scroll-loader-' + this.id + '" class="col-12 my-4 text-center"><img class="d-inline" style="height:35px;width:35px;" src="http://vps320850.ovh.net/snowtricks.com/public/img/loader.gif" /></div>');

        $.ajax({
            url: url,
            success: function(response, status) {
                self.find('#infinite-scroll-loader-' + self.id).fadeOut(200, function() {
                    self.find('#infinite-scroll-loader-' + self.id).remove();
                });

                var html = $(response.html);

                html.each(function() {
                    $(this).addClass(self.id + '-page-' + self.page);
                });

                self.find('.' + self.id + '-page-' + self.page).last().nextAll().remove();
                self.find('.' + self.id + '-page-' + self.page).remove();

                html.appendTo(self).hide().fadeIn(500);

                self.loading = false;
                self.page++;
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    self.complete = true;
                }
            },
            complete: function() {
                self.loading = false;
                self.find('#infinite-scroll-loader-' + self.id).remove();

                ajaxActionHandler.initialize();
            }
        });
    }

    refresh(page)
    {
        if (page !== undefined) {
            this.page = page;

        } else if (this.complete === true && this.page > 1) {
            this.page = this.page - 1;
        }

        this.complete = false;

        this.loadPage();
    }
}
