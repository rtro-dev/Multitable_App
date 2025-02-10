function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        
        fetch(`/laraveles/Multitable_App/multitableApp/public/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar la tarjeta de imagen del DOM
                const imageCard = document.querySelector(`div[data-image-id="${imageId}"]`);
                if (imageCard) {
                    imageCard.remove();
                }
                // Mostrar mensaje de éxito
                alert('Image deleted successfully');
            } else {
                // Mostrar mensaje de error
                alert(data.error || 'Error deleting image');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting image');
        });
    }
}