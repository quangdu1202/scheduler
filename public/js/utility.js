/*
** Full page spinner
*/
function showOverlay() {
    const overlay = $('#loadingOverlay');
    overlay.addClass('visible').css('display', 'block');
}

function hideOverlay() {
    const overlay = $('#loadingOverlay');
    overlay.removeClass('visible');
    // Ensure display:none is applied after the transition
    setTimeout(() => overlay.css('display', 'none'), 300);
}
/*
** End Full page spinner
*/