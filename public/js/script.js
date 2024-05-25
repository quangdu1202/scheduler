$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    /*
    ** Sidebar
    */

    const sidebar = $('#sidebar');
    const toggleBtn = $('.toggle-btn');

    toggleBtn.click(function() {
        sidebar.toggleClass('pinned');
    });

    sidebar.hover(function () {
        if (!sidebar.hasClass('pinned')) {
            $(this).addClass('expand');
        }
    }, function () {
        if (sidebar.hasClass('expand') && !sidebar.hasClass('pinned')) {
            $(this).removeClass('expand');
        }
    });
});

