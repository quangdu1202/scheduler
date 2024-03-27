$(document).ready(function() {
    /*
    ** Rooms Management modal
    */

    const addRoomModal = $('#add-room-modal');
    const addRoomAddBtn = $('#add-room-new');
    const addRoomCloseBtn = $('#add-room-close');
    const addRoomSaveBtn = $('#add-room-save');

    addRoomAddBtn.click(function(){
        addRoomModal.fadeIn('fast');
    });

    addRoomSaveBtn.click(function(){
        // Perform save operation here
        // After save operation, hide the modal
        addRoomModal.fadeOut('fast');
    });

    $(document).on('click', function(event) {
        if (event.target === addRoomModal[0] || event.target === addRoomCloseBtn[0]) {
            addRoomModal.fadeOut('fast');
        }
    });

    /*
    ** Room Info
    */
    const roomTable = $('#rooms-table');
    const roomInfoModal = $('#room-info-modal');
    const roomInfoContent = $('#room-info-body');
    const roomInfoCloseBtn = $('#room-info-close');
    const roomInfoSaveBtn = $('#room-info-save');

    roomTable.on('click', '.row-btn-info', function () {
        const roomId = $(this).data('room-id');

        // Get the CSRF token from the meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/getRoomData',
            method: 'POST',
            data: {
                roomId: roomId
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // Update the modal content with the retrieved data
                roomInfoContent.html(response);
                // Show the modal
                roomInfoModal.fadeIn('fast');

            },
            error: function() {
                console.log('Error occurred during AJAX request');
            }
        });
    })

    roomInfoSaveBtn.click(function(){
        // Perform save operation here
        // After save operation, hide the modal
        alert('Room info saved');
        roomInfoModal.fadeOut('fast');
    });

    $(document).on('click', function(event) {
        if (event.target === roomInfoModal[0] || event.target === roomInfoCloseBtn[0]) {
            roomInfoModal.fadeOut('fast');
        }
    });

    /*
    ** Room Edit
    */

    const roomEditModal = $('#edit-room-modal');
    const roomEditContent = $('#edit-room-body');
    const roomEditCloseBtn = $('#edit-room-close');
    const roomEditSaveBtn = $('#edit-room-save');

    roomTable.on('click', '.row-btn-edit', function () {
        const roomId = $(this).data('room-id');

        // Get the CSRF token from the meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/getRoomData',
            method: 'POST',
            data: {
                roomId: roomId
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // Update the modal content with the retrieved data
                roomEditContent.html(response);
                // Show the modal
                roomEditModal.fadeIn('fast');

            },
            error: function() {
                console.log('Error occurred during AJAX request');
            }
        });
    })

    roomEditSaveBtn.click(function(){
        // Perform save operation here
        // After save operation, hide the modal
        alert('Room info saved');
        roomEditModal.fadeOut('fast');
    });

    $(document).on('click', function(event) {
        if (event.target === roomEditModal[0] || event.target === roomEditCloseBtn[0]) {
            roomEditModal.fadeOut('fast');
        }
    });
});

