<script>
    $(document).ready(function() {
        function displayCurrentTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const dateTimeString = now.toLocaleDateString('en-US', options);
            $('#current-time').text(`${dateTimeString}`);
        }

        function updateNextClassInfo() {
            const classes = @json($classes ?? null);
            const now = new Date();

            let nextClass = null;
            let smallestDiff = Number.MAX_SAFE_INTEGER;

            $.each(classes, function(index, cls) {
                const classTime = new Date(cls.classTime);
                const diffInSeconds = (classTime - now) / 1000;

                if (diffInSeconds > 0 && diffInSeconds < smallestDiff) {
                    smallestDiff = diffInSeconds;
                    nextClass = cls;
                }
            });

            if (nextClass) {
                const classTime = new Date(nextClass.classTime);
                const options = { weekday: 'long', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                const formattedClassTime = classTime.toLocaleDateString('en-US', options);

                const days = Math.floor(smallestDiff / 86400);
                const hours = Math.floor((smallestDiff % 86400) / 3600);
                const minutes = Math.floor((smallestDiff % 3600) / 60);

                let timeToNextClass = '';
                if (days > 0) {
                    timeToNextClass += `${days} days `;
                }
                if (hours > 0) {
                    timeToNextClass += `${hours} hours `;
                }
                timeToNextClass += `${minutes} minutes`;

                $('#next-class-info').html(`
                        Next class:
                        <span class="fw-bold">${nextClass.className}</span>
                        on ${formattedClassTime}
                        in <span class="fw-bold">${timeToNextClass}</span>
                    `);
            } else {
                $('#next-class-info').text('No upcoming classes.');
            }
        }

        function updatePage() {
            displayCurrentTime();
            updateNextClassInfo();
        }

        updatePage(); // Initial update on page load
        setInterval(displayCurrentTime, 1000); // Update every second
        setInterval(updateNextClassInfo, 60000); // Update every minutes
    });
</script>