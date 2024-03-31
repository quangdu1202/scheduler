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
    /*
    ** End Sidebar
    */

    /*
    ** Date picker
    */

    $('#datePicker').on('change', function(){
        $('#dateForm').submit();
    });

    /*
    ** Cell Items
    */

    const modal = $('#cell-popup-modal');
    const modalDate = $('#modalDate');
    const cellContent = $('#cell-content');
    const scheduleTable = $('#schedule-table');

    // Info modal popup
    const closeBtn = $('.close');
    $(document).on('click', function(event) {
        if (event.target === modal[0] || event.target === closeBtn[0]) {
            modal.fadeOut('fast');
        }
    });

    scheduleTable.on('click', '.cell-item', function() {
        const date = $(this).data('date');
        const slot= $(this).data('slot');

        // Get the CSRF token from the meta tag

        if ($(this).hasClass('registered')) {
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
        }else{
            console.log('Empty Cell Clicked');
            $(this).find('.cell-class-register').removeClass('visually-hidden');
            $(this).addClass('active');
        }
    });

    /*
    ** End Cell Items
    */

    /*
    ** Register form inside cell
    */
    scheduleTable.on('click', '.cell-class-register', function (event) {
        event.stopPropagation();
    });

    scheduleTable.on('change', '.recurring-select', function () {
        $(this).css('color', 'initial');
    });

    scheduleTable.on('click', '.action-cancel-register', function (event) {
        event.preventDefault();
        console.log('Cancel Register');
        $(this).closest('.cell-class-register').addClass('visually-hidden');
        $(this).closest('.cell-item').removeClass('active');
    });

    scheduleTable.on('click', '.action-submit-register', function (event) {
        event.preventDefault();
        const recurringSelect = $(this).closest('form').find('select[name="recurring"]');

        if (recurringSelect.val() === '-1') {
            recurringSelect.css('color', 'red');
            return;
        }

        const overlay = $('<div class="overlay"></div>');
        overlay.addClass('active');
        $('body').append(overlay);
        const submitBtn = $(this);
        const cell = submitBtn.closest('.cell-item');

        const date = cell.data('date');
        const slot= cell.data('slot');
        const recurring = null;

        submitBtn.closest('.cell-register-fieldset').attr('disabled', 'disabled');
        submitBtn.addClass('button-loading');
        console.log('Submit Register');

        $.ajax({
            url: '/registerSchedule',
            method: 'post',
            data: {
                date: date,
                slot: slot,
                recurring: recurringSelect.val()
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // console.log(response);
                if (response.status === 'success') {
                    console.log(response);
                    submitBtn.text('Success!');
                    setTimeout(function() {
                        submitBtn.removeClass('button-loading');
                    }, 3000);

                    setTimeout(function() {
                        window.location.reload();
                    }, 5000);
                }
            },
            error: function(response) {
                console.log(response);
                submitBtn.text('Error!');
                setTimeout(function() {
                    submitBtn.removeClass('button-loading');
                }, 3000);
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            }
        });
    });

    /*
    ** End Register form inside cell
    */

    /*
    ** Calendar select filters
    */

    $('#calendar-room-select, #calendar-module-select').on('change', function(){
        $('#calendar-filter').submit();
    });

    /*
    ** End Calendar select filters
    */
});

