<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add To Home</title>
    <link rel="manifest" href="/manifest.json">
</head>
<body>
<button href="javascript:void(0);" type="button" class="btn  save-card-button"
   title="Export to vCard"><i class="fa fa-save"></i> Add To Home Screen</button>
<script>
    if ('serviceWorker' in navigator) {
        console.log("Will the service worker register?");
        navigator.serviceWorker.register('service-worker.js')
            .then(function(reg){
                console.log("Yes, it did.");
            }).catch(function(err) {
            console.log("No it didn't. This happened:", err)
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        let deferredPrompt;
        const saveBtn = document.querySelector('.save-card-button');
        // saveBtn.style.display = 'none';

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI to notify the user they can add to home screen
            saveBtn.style.display = 'block';

            saveBtn.addEventListener('click', (e) => {
                // hide our user interface that shows our A2HS button
                saveBtn.style.display = 'none';
                // Show the prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt');
                    } else {
                        console.log('User dismissed the A2HS prompt');
                    }
                    deferredPrompt = null;
                });
            });
        });
    });

</script>
</body>
</html>
