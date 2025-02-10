function handleImageSelection(input, maxFiles) {
    const files = input.files;
    const mainImageSelect = document.getElementById('mainImageSelect');
    const mainImageSelector = document.getElementById('mainImageSelector');
    const selectedFilesDiv = document.getElementById('selectedFiles');
    
    // Limpiar selecciones previas
    mainImageSelect.innerHTML = '';
    selectedFilesDiv.innerHTML = '';
    
    // Validar número de archivos
    if (files.length > maxFiles) {
        alert(`You can only upload up to ${maxFiles} images`);
        input.value = '';
        mainImageSelector.classList.add('d-none');
        return;
    }

    // Validar archivos y mostrar lista
    let fileList = '<ul class="list-unstyled">';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Validar tipo de archivo
        if (!file.type.match('image.*')) {
            alert('Please upload only image files (JPEG, PNG, GIF, SVG)');
            input.value = '';
            mainImageSelector.classList.add('d-none');
            return;
        }
        
        // Validar tamaño
        if (file.size > 2 * 1024 * 1024) {
            alert('Image size should not exceed 2MB');
            input.value = '';
            mainImageSelector.classList.add('d-none');
            return;
        }

        // Añadir a la lista de archivos
        fileList += `<li>
            <i class="fas fa-file-image text-primary me-2"></i>
            ${file.name} 
            <small class="text-muted">(${(file.size / 1024).toFixed(2)} KB)</small>
        </li>`;

        // Añadir opción al selector de imagen principal
        const option = document.createElement('option');
        option.value = i;
        option.text = `Image ${i + 1} - ${file.name}`;
        mainImageSelect.appendChild(option);
    }
    
    fileList += '</ul>';
    
    // Mostrar lista de archivos y selector de imagen principal
    if (files.length > 0) {
        selectedFilesDiv.innerHTML = fileList;
        mainImageSelector.classList.remove('d-none');
    } else {
        mainImageSelector.classList.add('d-none');
    }
}