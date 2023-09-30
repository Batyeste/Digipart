$(document).ready(function () {
    $('#datatable').Tabledit({
        deleteButton: false,
        editButton: false,
        columns: {
            identifier: [0, 'idProduct'],
            editable: [[1, 'reference'], [2, 'description'], [3, 'priceTaxIncl'], [4, 'priceTaxExcl'], [5, 'idLang'], [6, 'quantite']]
        },
        hideIdentifier: true,
        url: 'edit.php'
    });
});