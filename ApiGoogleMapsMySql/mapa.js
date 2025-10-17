let map;
let markers = [];

// Función de inicialización llamada por la API de Google Maps
window.initMap = function() {
    const initialLocation = { lat: 19.4326, lng: -99.1332 }; 

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: initialLocation,
    });

    // Escuchador para guardar coordenadas al hacer clic
    map.addListener('click', (mapsMouseEvent) => {
        const lat = mapsMouseEvent.latLng.lat();
        const lng = mapsMouseEvent.latLng.lng();
        saveCoordinate(lat, lng);
    });

    // Carga inicial de las coordenadas
    loadCoordinates();
};

/**
 * Guarda una coordenada enviándola al script PHP de backend.
 */
function saveCoordinate(lat, lng) {
    // Apunta al script PHP en el servidor XAMPP
    fetch('save_coordinate.php', { 
        method: 'POST',
        headers: {
            // Usamos FormData para simular un formulario POST tradicional
            // Si usaras JSON, el PHP necesitaría 'php://input'
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `lat=${lat}&lng=${lng}`, // Envío simple de datos como cadena URL
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error de red o en el servidor PHP.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
 
            addMarker({ lat: lat, lng: lng }, data.id);
            appendCoordinateToList(data.id, lat, lng);
        } else {
            alert('Error al guardar: ' + (data.message || 'Error desconocido.'));
        }
    })
    .catch((error) => {
        console.error('Error al guardar la coordenada:', error);
        alert('No se pudo conectar con el servidor.');
    });
}

/**
 * Obtiene todas las coordenadas guardadas del script PHP y las marca.
 */
function loadCoordinates() {
    // Apunta al script PHP para obtener datos
    fetch('get_coordinates.php')
    .then(response => response.json())
    .then(coordinates => {
        clearMarkers();
        const listDiv = document.getElementById('coordenadasGuardadas');
        listDiv.innerHTML = '<h2>Coordenadas Cargadas</h2><ul>';
        
        coordinates.forEach(coord => {
            // Asegura que lat y lng son números
            const position = { lat: parseFloat(coord.lat), lng: parseFloat(coord.lng) };
            addMarker(position, coord.id); 
            
            listDiv.innerHTML += `<li>ID ${coord.id}: ${position.lat}, ${position.lng}</li>`;
        });
        listDiv.innerHTML += '</ul>';
    })
    .catch((error) => {
        console.error('Error al cargar coordenadas:', error);
    });
}

/**
 * Funciones auxiliares para la gestión de marcadores en el mapa.
 */
function addMarker(position, id = 'Nuevo') {
    const marker = new google.maps.Marker({
        position: position,
        map: map,
        title: 'ID: ' + id,
    });
    markers.push(marker); 
}

function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

/**
 * Añade una nueva coordenada guardada a la lista HTML.
 * NOTA: Esto asume que el div #coordenadasGuardadas ya contiene un <ul>.
 */
function appendCoordinateToList(id, lat, lng) {
    const listDiv = document.getElementById('coordenadasGuardadas');
    let ulElement = listDiv.querySelector('ul');
    
    // Si la lista <ul> no existe (porque es el primer elemento), créala.
    // Esto es robusto, pero es mejor asegurar que loadCoordinates la crea.
    if (!ulElement) {
        // Aseguramos la existencia del título y la lista si loadCoordinates aún no se ha ejecutado.
        listDiv.innerHTML = '<h2>Coordenadas Cargadas</h2><ul></ul>';
        ulElement = listDiv.querySelector('ul');
    }
    
    const listItem = document.createElement('li');
    listItem.textContent = `ID ${id}: ${lat}, ${lng}`;
    
    // Inserta la nueva coordenada al principio de la lista
    ulElement.prepend(listItem);
}