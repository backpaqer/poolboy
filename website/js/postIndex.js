function post(comboID) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "index.php");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "comboID");
    hiddenField.setAttribute("value", comboID);

    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}