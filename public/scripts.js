document.getElementById('registrationForm').addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    try {
        const response = await fetch('/3GID/hackaton/php/controllers/inscription.php', {
            method: 'POST',
            body: formData,
        });
        const result = await response.json();
        if (result.success) {
            alert(result.message);
            event.target.reset(); // Réinitialiser le formulaire
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Erreur lors de la requête :', error); // Log the error
        alert('Une erreur est survenue. Veuillez réessayer.');
    }
});