function post(relayID, relayState) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "settingrelays.php");

    var h1 = document.createElement("input");
    h1.setAttribute("type", "hidden");
    h1.setAttribute("name", "relayID");
    h1.setAttribute("value", relayID);

    var h2 = document.createElement("input");
    h2.setAttribute("type", "hidden");
    h2.setAttribute("name", "relayState");
    h2.setAttribute("value", relayState);

    form.appendChild(h1);
    form.appendChild(h2);

    document.body.appendChild(form);
    form.submit();
}