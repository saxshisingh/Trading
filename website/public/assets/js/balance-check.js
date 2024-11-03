document.addEventListener('DOMContentLoaded', function () {
    function checkBalanceAndSubmit() {
        var url = "{{route('trigger.close.all')}}";
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin',
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.trigger) {
                submitCloseAllForm();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    checkBalanceAndSubmit();
});
