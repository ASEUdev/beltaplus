<?php
if ($_SESSION["perfil"] == "Especial") {
  echo '<script>
    window.location = "inicio";
  </script>';
  return;
}
$xml = ControladorVentasTemp::ctrDescargarXML('ventas_proceso');
if ($xml) {
  rename($_GET["xml"] . ".xml", "xml/" . $_GET["xml"] . ".xml");
  echo '<a class="btn btn-block btn-success abrirXML" archivo="xml/' . $_GET["xml"] . '.xml" href="ventas-temp">Se ha creado correctamente el archivo XML <span class="fa fa-times pull-right"></span></a>';
}
?>
<div class="content-wrapper">
  <section class="content-header">

    <h1>
      Administrar ventas en proceso
    </h1 <ol class="breadcrumb">

  </section>
  <section class="content">
    <div class="box">
      <div class="box-header with-border">

        <button type="button" class="btn btn-default pull-right" id="daterange-btn">

          <span>
            <i class="fa fa-calendar"></i>
            <?php
            if (isset($_GET["fechaInicial"])) {
              echo $_GET["fechaInicial"] . " - " . $_GET["fechaFinal"];
            } else {

              echo 'Rango de fecha';
            }
            ?>
          </span>

          <i class="fa fa-caret-down"></i>
        </button>
      </div>
      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas" width="100%">

          <thead>

            <tr>
              <th style="width:10px">#</th>
              <!-- <th>Código factura</th> -->
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Forma de pago</th>
              <th>Neto</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>

          </thead>
          <tbody>
            <?php
            if (isset($_GET["fechaInicial"])) {
              $fechaInicial = $_GET["fechaInicial"];
              $fechaFinal = $_GET["fechaFinal"];
            } else {
              $fechaInicial = null;
              $fechaFinal = null;
            }
            $respuesta = ControladorVentasTemp::ctrRangoFechasVentas($fechaInicial, $fechaFinal, 'ventas_proceso');
            foreach ($respuesta as $key => $value) {

              echo '<tr>
                  <td>' . ($key + 1) . '</td>';

              $itemCliente = "id";
              $valorCliente = $value["id_cliente"];
              $respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);
              echo '<td>' . $respuestaCliente["nombre"] . '</td>';

              $itemUsuario = "id";
              $valorUsuario = $value["id_vendedor"];
              $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);
              /*<a class="btn btn-success" href="index.php?ruta=ventas&xml='.$value["codigo"].'">xml</a>*/
              echo '<td>' . $respuestaUsuario["nombre"] . '</td>
                  <td>' . $value["metodo_pago"] . '</td>
                  <td>$ ' . number_format($value["neto"], 2) . '</td>
                  <td>$ ' . number_format($value["total"], 2) . '</td>
                  <td>' . $value["fecha_venta"] . '</td>
                  <td>
                    <div class="btn-group">
                    ';
              if ($_SESSION["perfil"] == "Administrador") {
                echo '<button name="accionVentaProceso" value="pagado" class="btn btn-warning btnPagarVentaProccess" accion= "pagado" idVenta  ="' . $value["id"] . '"><i class="fa fa-pencil"></i></button>
                      <button name="accionVentaProceso" value="cancelado" class="btn btn-danger btnEliminarVentaProcess" devolucion="true" idVenta="' . $value["id"] . '"><i class="fa fa-times"></i></button>';
              }
              echo '</div>
                  </td>
                </tr>';
            }
            ?>

          </tbody>
        </table>
        <?php


        $eliminarTransito = new ControladorTransito();
        $eliminarTransito->ctrEliminarProductosTransito();
        $confirmarVenta = new ControladorVentasTemp();
        $confirmarVenta->ctrEliminarVenta(true, 'ventas_proceso');




        ?>

      </div>
    </div>
  </section>
</div>