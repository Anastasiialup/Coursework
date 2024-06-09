document.getElementById('apply-filters').addEventListener('click', function() {
    var categoryFilter = document.getElementById('category-filter').value;
    var tableRows = document.querySelectorAll('#records-table tbody tr');

    tableRows.forEach(function(row) {
        var categoryId = row.getAttribute('data-category-id');

        if (categoryFilter === '' || categoryId === categoryFilter) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
});
