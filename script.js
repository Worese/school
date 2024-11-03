document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); 
        const formData = new FormData(form);
        fetch('process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('result').innerHTML = `<p style="color:red;">${data.error}</p>`;
                return;
            }
            let output = `
                <p>Twoje liczby: ${data.userNumbers.join(', ')}</p>
                <p>Wylosowane liczby: ${data.randomNumbers.join(', ')}</p>
                <p>Trafiłeś ${data.numberOfMatches} liczb(y): ${data.matches.join(', ')}</p>
                <p>Twoja wygrana: ${data.prize}</p>
            `;
            document.getElementById('result').innerHTML = output;
        })
        .catch(error => console.error('Błąd:', error));
        
    });
});
