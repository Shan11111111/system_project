<!-- filepath: c:\xampp\htdocs\analysis_project\includes\footer.php -->
</div>
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    window.onclick = function (event) {
        const dropdown = document.getElementById('dropdownMenu');
        if (!event.target.matches('.profile img')) {
            dropdown.style.display = 'none';
        }
    }
</script>
</body>
</html>