Documentation

--- Frontend ---

class attribute ('add_class', 'edit_class', 'add_grade', etc.) in each element that has card class is used as selector to get the name and value to be sent through form data and AJAX.

Each td must has class attribute to be selected when starting to edit item to get the innerText. The class attribute name must be the same as globalItemKeys to be matched with globalItemKey when iterating throught globalItemKey.

formData key format is 'add_{key}', 'edit_{key}', and 'delete_{key}'. key is database field.

'script' tag order: source '(ajax, form, modal).js', define custom global object property, source 'frontend.js', define custom renderRow() for list table row structure.

Table row tr tag's 'class' attribute value in list should be the same to get all of item inputs value by a 'class' attribute value for setting the input value based on value in table list.

Each input's tag 'class' attribute value in edit modal should be 'edit_{item}' for getting the 'name' and 'value' attribute value to be sent through AJAX.

Edit: Hidden 'tr' dataset.value -> hidden input value -> handleXMLHttpResponse: set the value in table

--- Backend ---

init/index.php - initiation of database connection.

Model file statement of checking order: read, create, update, delete

$_POST key format is 'add_{key}', 'edit_{key}', and 'delete_{key}'. key is database field.

paramValues array in model file must be in order.