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

    $(document).ready(function() {
        const modal = new bootstrap.Modal(document.getElementById('cell-popup-modal'));

        scheduleTable.on('click', '.cell-item', function() {
            const date = $(this).data('date');
            const slot = $(this).data('slot');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                    success: function(practiceClass) {
                        if (typeof practiceClass === 'string' || practiceClass instanceof String) {
                            practiceClass = JSON.parse(practiceClass);
                        }

                        // Update the modal content with the retrieved data
                        $('#class-id').text(practiceClass.id || '');
                        $('#class-name').text(practiceClass.practice_class_name || '');
                        $('#class-schedule-date').text(practiceClass.schedule_date || '');
                        $('#class-session').text(practiceClass.session || '');
                        $('#class-practice-room-id').text(practiceClass.practice_room_id || '');
                        $('#class-teacher-id').text(practiceClass.teacher_id || '');
                        $('#class-module-id').text(practiceClass.module_id || '');

                        // Show the modal with Bootstrap's method
                        modal.show();
                    },
                    error: function(response) {
                        console.error('Error occurred during AJAX request:', response);
                    }
                });
            } else {
                console.log('Empty Cell Clicked');
                $(this).find('.cell-class-register').removeClass('visually-hidden');
                $(this).addClass('active');
            }
        });
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

    /*Submit Register*/

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
        const recurring = recurringSelect.val();

        submitBtn.closest('.cell-register-fieldset').attr('disabled', 'disabled');
        submitBtn.addClass('button-loading');
        console.log('Submit Register');

        $.ajax({
            url: '/registerSchedule',
            method: 'post',
            data: {
                date: date,
                slot: slot,
                recurring: recurring
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

    $('#calendar-room-select, #calendar-class-select').on('change', function(){
        $('#calendar-filter').submit();
    });

    /*
    ** End Calendar select filters
    */
});

