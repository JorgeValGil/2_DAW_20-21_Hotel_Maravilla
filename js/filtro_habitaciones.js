var aviso = document.getElementById('aviso');
Object.keys(document.getElementsByClassName("tipo_habitacion")).forEach(
  (element) => {
    document
      .getElementsByClassName("tipo_habitacion")
      [element].addEventListener("click", (a) => {
        a.preventDefault();
        console.log(a);
        var tipo = document.getElementsByClassName("tipo_habitacion")[element]
          .name;
        var start = document.getElementById("start").value;
        var end = document.getElementById("end").value;
        if (start != "" && end != "") {
          // window.location.href=window.location+"/html/room.php?tipo="+tipo+"&start="+start+"&end="+end;
          window.location.href =
            "/Proxecto/html/room.php?tipo=" +
            tipo +
            "&start=" +
            start +
            "&end=" +
            end;

          // trim e quitar a parte de indexedDB.php
        }else{
          aviso.innerHTML = "Select the check-in date and the check-out date.";
        }
      });
  }
);
