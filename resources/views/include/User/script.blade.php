<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('admin-sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.admin-main');

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('closed');
            mainContent.classList.toggle('full-width');
        });
    });
</script>
