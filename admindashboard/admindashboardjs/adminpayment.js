function showContent(contentId) {
    // Hide all content divs
    var contents = document.querySelectorAll('.content');
    contents.forEach(function(content) {
        content.classList.remove('active');
    });

    // Show the selected content
    var selectedContent = document.getElementById(contentId);
    selectedContent.classList.add('active');
}