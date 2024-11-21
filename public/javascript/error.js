// Fonction pour obtenir la valeur d'un paramètre GET
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Récupération du paramètre 'error'
const error = getQueryParam('error');

// Affichage de l'erreur si elle existe
if (error) {
    const errorMessage = decodeURIComponent(error.replace(/\+/g, ' ')); // Décodage du message d'erreur
    var e =  document.getElementById('error-message')
    if(e)
    {
        e.innerText = errorMessage;
        e.style.display = 'flex'; // Afficher l'élément si caché par défaut
    }
 
}