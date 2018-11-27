$("#submit").click(function () {
    if (!document.getElementById("village").checked && !document.getElementById("small-city").checked
        && !document.getElementById("medium-size-city").checked && !document.getElementById("big-city").checked) {
        document.getElementById("village").required = true;
    }
});