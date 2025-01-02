<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Calendar</title>
  
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/calendar.css">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" rel="stylesheet" />
    
    <!-- FullCalendar JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Updated jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>

    <!-- Timepicker CSS and JS -->
    <link href="css/jquery.timepicker.min.css" rel="stylesheet" />
    <script src="js/jquery.timepicker.min.js"></script>

</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <!-- Calendar Container -->
    <div id="calendar"></div>

    <script>

    $(document).ready(function() {
    var isModalOpen = false; // Track if a modal is open

    // Function to remove all modals when clicking outside any modal
    $(document).on('click', function(event) {
        // Only close modals if they are open and the click is outside of them
        if (!$(event.target).closest('#eventModal').length && $('#eventModal').length) {
            $('#eventModal').fadeOut(300, function() {
                $(this).remove(); // Remove the modal after fade-out
            });
            isModalOpen = false; // Mark the modal as closed
        }
    });

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: 'fetch_events.php', // Use fetch_events.php to load events
        eventRender: function(event, element) {
            console.log(event); // Log event data for debugging
        },
        dayClick: function(date, jsEvent, view) {
            // Prevent opening modal if one is already open
            if (isModalOpen) {
                return; // Exit function if a modal is already open
            }

            // Stop event propagation to prevent the click from immediately closing the modal
            jsEvent.stopPropagation();

            // Delay the modal creation to ensure the click event is fully processed
            setTimeout(function() {
                var modal = `
                    <div id="eventModal" style="position: fixed; top: 30%; left: 30%; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.5); z-index: 9999;">
                        <h3>Create Event</h3>
                        <label for="eventTitle">Title:</label>
                        <input type="text" id="eventTitle" style="display: block; margin-bottom: 10px; width: 100%;">

                        <label for="startTime">Start Time:</label>
                        <input type="text" id="startTime" class="timepicker" style="display: block; margin-bottom: 10px; width: 100%">

                        <label for="endTime">End Time:</label>
                        <input type="text" id="endTime" class="timepicker" style="display: block; margin-bottom: 10px; width: 100%">

                        <button id="saveEvent" style="margin-right: 10px;">Save</button>
                        <button id="cancelEvent">Cancel</button>
                    </div>
                `;
                $('body').append(modal);  // Append modal to the body directly

                // Set the flag to true, indicating the modal is open
                isModalOpen = true;

                // Initialize Timepicker for inputs
                $('.timepicker').timepicker({
                    timeFormat: 'HH:mm',
                    interval: 15,
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true
                });

                // Save Button Click Event
                $('#saveEvent').on('click', function() {
                    var eventTitle = $('#eventTitle').val();
                    var startTime = $('#startTime').val();
                    var endTime = $('#endTime').val();

                    if (eventTitle && startTime && endTime) {
                        $.ajax({
                            url: 'add_event.php',
                            method: 'POST',
                            data: {
                                title: eventTitle,
                                start: date.format('YYYY-MM-DD') + ' ' + startTime,
                                end: date.format('YYYY-MM-DD') + ' ' + endTime
                            },
                            success: function(response) {
                                console.log(response); // Debugging
                                $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
                                $('#eventModal').remove(); // Remove modal
                                isModalOpen = false; // Mark modal as closed
                            }
                        });
                    } else {
                        alert('Please fill out all fields.');
                    }
                });

                // Cancel Button Click Event (with fade-out)
                $('#cancelEvent').on('click', function() {
                    $('#eventModal').fadeOut(300, function() { // Fades out over 300ms
                        $(this).remove(); // Removes the modal after fade-out
                        isModalOpen = false; // Mark modal as closed
                    });
                });
            }, 10); // Slight delay to ensure event processing
        },

        // Edit Existing Event
        eventClick: function(event, jsEvent, view) {
            // Prevent opening modal if one is already open
            if (isModalOpen) {
                return; // Exit function if a modal is already open
            }

            // Prevent event from propagating to the document click handler
            jsEvent.stopPropagation();

            var modal = `
                <div id="eventModal" style="position: fixed; top: 30%; left: 30%; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.5); z-index: 9999;">
                    <h3>Edit Event</h3>
                    <label for="eventTitle">Title:</label>
                    <input type="text" id="eventTitle" value="${event.title}" style="display: block; margin-bottom: 10px; width: 100%;"> 

                    <label for="startTime">Start Time:</label>
                    <input type="text" id="startTime" class="timepicker" value="${moment(event.start).format('HH:mm')}" style="display: block; margin-bottom: 10px; width: 100%">

                    <label for="endTime">End Time:</label>
                    <input type="text" id="endTime" class="timepicker" value="${moment(event.end).format('HH:mm')}" style="display: block; margin-bottom: 10px; width: 100%">

                    <button id="saveEvent" style="margin-right: 10px;">Save</button>
                    <button id="deleteEvent" style="background-color: red; color: white;">Delete Appointment</button>
                    <button id="cancelEvent">Cancel</button>
                </div>

            `;
            $('body').append(modal); // Append modal to the body directly

            // Set the flag to true, indicating the modal is open
            isModalOpen = true;

            // Initialize Timepicker for inputs
            $('.timepicker').timepicker({
                timeFormat: 'HH:mm',
                interval: 15,
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            // Save Button Click Event
            $('#saveEvent').on('click', function() {
                var eventTitle = $('#eventTitle').val();
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();

                if (eventTitle && startTime && endTime) {
                    $.ajax({
                        url: 'edit_event.php', // Add event editing script
                        method: 'POST',
                        data: {
                            id: event.id,
                            title: eventTitle,
                            start: event.start.format('YYYY-MM-DD') + ' ' + startTime,
                            end: event.end.format('YYYY-MM-DD') + ' ' + endTime
                        },
                        success: function(response) {
                            console.log(response); // Debugging
                            $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
                            $('#eventModal').remove(); // Remove modal
                            isModalOpen = false; // Mark modal as closed
                        }
                    });
                } else {
                    alert('Please fill out all fields.');
                }
            });

            // Delete Button Click Event
            $('#deleteEvent').on('click', function() {
                $.ajax({
                    url: 'delete_event.php', // Add event deletion script
                    method: 'POST',
                    data: {
                        eventId: event.id  // Ensure the correct 'eventId' is being passed
                    },
                    success: function(response) {
                        console.log("Raw response from server:", response); // Debugging
                        if (response == 'success') {
                            $('#calendar').fullCalendar('refetchEvents'); // Refresh calendar
                            $('#eventModal').fadeOut(300, function() { // Fade-out the modal
                                $(this).remove(); // Remove the modal
                                isModalOpen = false; // Mark modal as closed
                            });
                        } else {
                            alert('Error deleting event');
                        }
                    }
                });
            });

            // Cancel Button Click Event (with fade-out)
            $('#cancelEvent').on('click', function() {
                $('#eventModal').fadeOut(300, function() {
                    $(this).remove(); // Remove the modal after fade-out
                    isModalOpen = false; // Mark modal as closed
                });
            });
        }
    });
});
</script>


</body>
</html>
