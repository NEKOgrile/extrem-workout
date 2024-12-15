document.addEventListener("DOMContentLoaded", function() {
    const openStoryButton = document.getElementById('open-story');
  
    // Écouter le clic sur l'image
    openStoryButton.addEventListener('click', function() {
      openStoryPage();
    });
  
    function openStoryPage() {
      // Rediriger vers la nouvelle page
      window.location.href = 'histoire.html';
    }
  });
  