$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
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

    /*
    ** Date picker
    */

    $('#datePicker').on('change', function(){
        $('#dateForm').submit();
    });

    /*
    ** Cell Items
    */

    // const cellItems = $('.cell-item');
    const modal = $('#cell-popup-modal');
    const modalDate = $('#modalDate');
    const cellContent = $('#cell-content');

    $('#schedule-table').on('click', '.cell-item', function() {
        const date = $(this).data('date');
        const slot= $(this).data('slot');

        // Get the CSRF token from the meta tag

        if (!$(this).hasClass('disabled')) {
            $.ajax({
                url: '/getCellData',
                method: 'get',
                data: {
                    date: date,
                    slot: slot
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);
                    // Display the date and retrieved data in the modal
                    modalDate.text(date);
                    // Update the modal content with the retrieved data
                    cellContent.html(response);
                    // Show the modal
                    modal.fadeIn('fast');
                    modal.css('display', 'flex');

                },
                error: function(response) {
                    console.log(response);
                    // console.log('Error occurred during AJAX request');
                }
            });
        }
    });

    const closeBtn = $('.close');
    $(document).on('click', function(event) {
        if (event.target === modal[0] || event.target === closeBtn[0]) {
            modal.fadeOut('fast');
        }
    });

    /*
    ** Calendar select filters
    */

    $('#calendar-room-select, #calendar-module-select').on('change', function(){
        $('#calendar-filter').submit();
    });
});

