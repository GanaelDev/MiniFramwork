/**
 * Fonction pour effectuer une requête HTTP avec la méthode et la route spécifiées.
 * @param {string} method - La méthode HTTP (GET, POST, PUT, DELETE, etc.)
 * @param {string} route - La route ou l'URL de l'API
 * @param {Object} [data] - Les données à envoyer dans le corps de la requête (pour POST, PUT)
 * @returns {Promise<Object>} - La réponse de la requête en JSON
 */
async function httpRequest(method, route, data = null) {
    const options = {
        method: method.toUpperCase(), // Convertit la méthode en majuscules
        headers: {
            'Content-Type': 'application/json'
        }
    };

    // Ajout des données dans le corps de la requête si nécessaire
    if (data && (method === 'POST' || method === 'PUT')) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(route, options);

        // Vérifie si la réponse est correcte
        if (!response.ok) {
            throw new Error(`Erreur: ${response.status} ${response.statusText}`);
        }

        // Retourne la réponse en JSON
        return await response.json();
    } catch (error) {
        console.error('Erreur de requête:', error);
        throw error;
    }
}

/* EXEMPLE sera sénéralisé post projet
<button onclick="fetchData()">Obtenir les données</button>
<button onclick="createResource()">Créer une ressource</button>
<button onclick="updateResource(123)">Mettre à jour la ressource</button>
<button onclick="deleteResource(123)">Supprimer la ressource</button>


async function fetchData() {
    try {
        const data = await httpRequest('GET', '/api/resource');
        console.log('Données obtenues:', data);
    } catch (error) {
        console.error('Erreur lors de la récupération des données:', error);
    }
}

async function createResource() {
    const newData = { name: 'Nouvelle Ressource' };
    try {
        const data = await httpRequest('POST', '/api/resource', newData);
        console.log('Ressource créée:', data);
    } catch (error) {
        console.error('Erreur lors de la création de la ressource:', error);
    }
}

async function updateResource(id) {
    const updatedData = { name: 'Ressource Mise à Jour' };
    try {
        const data = await httpRequest('PUT', `/api/resource/${id}`, updatedData);
        console.log('Ressource mise à jour:', data);
    } catch (error) {
        console.error('Erreur lors de la mise à jour:', error);
    }
}

*/