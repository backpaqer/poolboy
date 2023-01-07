function post(comboID, dayID, timerstart, timerend, commitadd) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "scheduleadd.php");

    var h1 = document.createElement("input");
    h1.setAttribute("type", "hidden");
    h1.setAttribute("name", "comboID");
    h1.setAttribute("value", comboID);

    var h2 = document.createElement("input");
    h2.setAttribute("type", "hidden");
    h2.setAttribute("name", "dayID");
    h2.setAttribute("value", dayID);

    var h3 = document.createElement("input");
    h3.setAttribute("type", "hidden");
    h3.setAttribute("name", "timerstart");
    h3.setAttribute("value", timerstart);

    var h4 = document.createElement("input");
    h4.setAttribute("type", "hidden");
    h4.setAttribute("name", "timerend");
    h4.setAttribute("value", timerend);    

    var h5 = document.createElement("input");
    h5.setAttribute("type", "hidden");
    h5.setAttribute("name", "commitadd");
    h5.setAttribute("value", commitadd);    

    form.appendChild(h1);
    form.appendChild(h2);
    form.appendChild(h3);
    form.appendChild(h4);
    form.appendChild(h5);

    document.body.appendChild(form);
    form.submit();
}