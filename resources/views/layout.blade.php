<!DOCTYPE html>
<html>
<head>
    <title>Scrapping - SEACE</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body{
            margin: unset;
            background: unset;
            height: unset;
        }
       img {
            display: unset;
            -webkit-user-select: unset;
            margin: unset;
            background-color: unset;
            transition: unset;
        } 
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="#"><img src="https://co.licitaciones.info/img/logos/licitaciones-colombia-original.png" alt="" Width="150"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link active" href="/data">Scrapping</a>
      <a class="nav-item nav-link" href="/index">Concursos</a>
    </div>
  </div>
</nav>
<div class="custom-container">
    <div class="card" style="margin-top: 20px;">
        <div class="card-body">
        @yield('content')
        </div>
    </div>
</div>

</body>
<script
  src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
  crossorigin="anonymous"></script>
<style>
  p {
      margin-bottom: 0.5rem;
  }
  .card {
    margin-top: 15px;
  }

  .table tr th {
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }

  .sorting {
      background-color: #d4d4d4;
  }

  .asc:after {
      content: " ↑";
  }

  .desc:after {
      content: " ↓";
  }
</style>
<script>
    var fecha_inicio_publicacion = document.getElementById("fecha_inicio_publicacion")
    if(fecha_inicio_publicacion){
      fecha_inicio_publicacion.valueAsDate = new Date();
    }
    var fecha_final_publicacion = document.getElementById("fecha_final_publicacion")
    if(fecha_final_publicacion){
      fecha_final_publicacion.valueAsDate = new Date();
    }
</script>
<script>
  $(document).ready(function () {
    $("#busquedaInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $("th").click(function () {
    var table = $(this).parents("table").eq(0);
    var rows = table
        .find("tr:gt(0)")
        .toArray()
        .sort(comparer($(this).index()));
    this.asc = !this.asc;
    if (!this.asc) {
        rows = rows.reverse();
    }
    for (var i = 0; i < rows.length; i++) {
        table.append(rows[i]);
    }
    setIcon($(this), this.asc);
});

function comparer(index) {
    return function (a, b) {
        var valA = getCellValue(a, index),
            valB = getCellValue(b, index);
        return $.isNumeric(valA) && $.isNumeric(valB)
            ? valA - valB
            : valA.localeCompare(valB);
    };
}

function getCellValue(row, index) {
    return $(row).children("td").eq(index).html();
}

function setIcon(element, asc) {
    $("th").each(function (index) {
        $(this).removeClass("sorting");
        $(this).removeClass("asc");
        $(this).removeClass("desc");
    });
    element.addClass("sorting");
    if (asc) element.addClass("asc");
    else element.addClass("desc");
}

});
</script>
</html>