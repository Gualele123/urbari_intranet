

// scripts.js
document.addEventListener('DOMContentLoaded', function() {

    // Función para crear una card
    function createCard(empleado, isCumpleaneroDia) {
        
        const card = document.createElement('div');
        card.className = 'card';
        
        if (isCumpleaneroDia) {
            card.classList.add('highlight');
        }
        
        const img = document.createElement('img');
        img.src = `fotos/${empleado.id}.jpg`; // Asumiendo que la foto tiene el mismo ID que el empleado
        card.appendChild(img);
        
        const nombre = document.createElement('h2');
        nombre.textContent = `${empleado.nombre} ${empleado.appaterno} ${empleado.apmaterno}`;
        card.appendChild(nombre);
        
        const area = document.createElement('p');
        area.textContent = `Área: ${empleado.area}`;
        card.appendChild(area);
        
        const semana = document.createElement('p');
        semana.textContent = `Semana del Año: ${empleado.semana_anio}`;
        card.appendChild(semana);
        
        const fechaNacimiento = document.createElement('p');
        const [anio, mes, dia] = empleado.fechaNacimiento.split('-');
        const fechaTexto = `Fecha de Nacimiento: ${dia}/${mes}`;
        const fechaSpan = document.createElement('span');
        fechaSpan.className = 'fecha';

        // Crear fechas para comparación
        const fechaNacimientoDate = new Date();
        fechaNacimientoDate.setMonth(mes - 1);
        fechaNacimientoDate.setDate(dia);
        fechaNacimientoDate.setFullYear(fechaNacimientoDate.getFullYear());

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0); // Asegurarse de que se comparen solo las fechas y no las horas

        // Comparar fechas
        if (fechaNacimientoDate.toDateString() === hoy.toDateString()) {
            fechaSpan.classList.add('hoy');
        } else if (fechaNacimientoDate < hoy) {
            fechaSpan.classList.add('pasado');
        } else {
            fechaSpan.classList.add('proximo');
        }

        fechaSpan.textContent = fechaTexto;
        card.appendChild(fechaSpan);
        
        return card;
    }

    // Mostrar los cumpleañeros del día
    const cumpleanerosDiaContainer = document.getElementById('cumpleaneros-dia');
    if (cumpleanerosDia.length === 0) {
        const mensaje = document.createElement('p');
        mensaje.textContent = "Hoy no hay cumpleañeros";
        cumpleanerosDiaContainer.appendChild(mensaje);
    } else {
        cumpleanerosDia.forEach(empleado => {
            const card = createCard(empleado, true);
            cumpleanerosDiaContainer.appendChild(card);
        });
    }

    // Mostrar los cumpleañeros de la semana y de la próxima semana
    const carouselSemana = document.getElementById('carousel-semana');
    cumpleanerosSemana.forEach(empleado => {
        const card = createCard(empleado, false);
        carouselSemana.appendChild(card);
    });
});
