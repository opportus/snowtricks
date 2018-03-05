var mutationObserver = new MutationObserver(function() {
    
});

mutationObserver.observe($('body')[0], {attributes: true, childList: true});
